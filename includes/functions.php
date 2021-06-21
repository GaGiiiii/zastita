<?php
function clean($input) {
  $input = trim($input);
  $input = str_replace('"', "", $input);
  $input = str_replace("'", "", $input);
  $input = htmlspecialchars($input); // Mora ispod str_replace jer htmlspecialchars pretvara " u &nesto; i onda ga str_replace ne nadje
  // $input = mysqli_real_escape_string(Database::getInstance()->getConnection(), $input);

  return $input;
}

function isLettersOnly($text) {
  if (preg_match('/^[a-zA-ZšđčćžŠĐČĆŽ]+$/', $text)) {
    return true;
  }

  return false;
}

function isAdress($text) {
  if (preg_match('/^[a-zA-ZšđčćžŠĐČĆŽ]+[0-9]+$/', $text)) {
    return true;
  }

  return false;
}

function isPhoneNumber($text) {
  if (preg_match('/^[0-9-]+$/', $text)) {
    return true;
  }

  return false;
}
// URADI VALIDACIJU VREMENA ( ([0-1][0-9] | [2][0-3]):[0-5][0-9])

function isVreme($text) {
  if (preg_match('/^([0-1][0-9]|[2][0-3]):[0-5][0-9]+$/', $text)) {
    return true;
  }

  return false;
}

function printFormatedFlashMessage($sessionName) {
  if (isset($_SESSION[$sessionName])) {
    echo $_SESSION[$sessionName];
    unset($_SESSION[$sessionName]);
  }
}

function printFlashMessage($sessionName) {
  if (isset($_SESSION[$sessionName])) {
    echo "<div class='container-fluid text-uppercase success-message-custom'>
            <div class='row'>
                <div class='col-md-10 col-sm-10 offset-sm-1 offset-md-1 p-0 mt-5'>
                    <div class='alert alert-success alert-dismissible fade show' role='alert'>
                      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                      <strong>" . $_SESSION[$sessionName] . " <i class='fas fa-check'></i></strong>
                    </div>
                </div>
            </div>
          </div>";
    unset($_SESSION[$sessionName]);
  }
}

// CART ==================================================================================================================

function inCart($product) {
  return in_array($product, $_SESSION['cart']);
}

function totalPriceInCart() {
  $price = 0;
  for ($i = 0; $i < sizeof($_SESSION['cart']); $i++) {
    $price += $_SESSION['cart'][$i]['product.price'];
  }

  return $price;
}

$token = getToken()['access_token'];

function getToken() {
  $clientID = "AQf3HNR-XCsd8q_DvLFWInNNZUYiu6-6AZrjgZ9fC4CFXa7mRe8jgze87PD7M7VzU5yOd2CVNo_Ci0Pd";
  $secret = "EI4_bG_MB43Pv6OPN4sPr0q_JpEYcR1yBAsXxzs8vXe4g72tpPrM6S_996geOEOd6R_TSLh7wXfd8UOV";
  $url = "https://api-m.sandbox.paypal.com/v1/oauth2/token";
  $data = "grant_type=client_credentials";
  $headers = [
    "Accept: application/json",
    "Accept-Language: en_US",
  ];

  $curl = curl_init();

  curl_setopt($curl, CURLOPT_POST, 1);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
  curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  curl_setopt($curl, CURLOPT_USERPWD, "$clientID:$secret");
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

  $result = json_decode(curl_exec($curl), true);
  curl_close($curl);

  return $result;
}

function generateOrder($token, $price) {
  $url = "https://api-m.sandbox.paypal.com/v2/checkout/orders";
  $data = [
    "intent" => "CAPTURE",
    "purchase_units" => [
      [
        "amount" => [
          "currency_code" => "EUR",
          "value" => $price,
        ]
      ]
    ]
  ];
  $data = json_encode($data);
  $headers = [
    "Accept: application/json",
    "Content-Type:application/json",
    "Accept-Language: en_US",
    "Authorization: Bearer " . $token,
  ];

  $curl = curl_init();

  curl_setopt($curl, CURLOPT_POST, 1);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

  $result = json_decode(curl_exec($curl), true);
  curl_close($curl);

  return $result;
}
