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
          