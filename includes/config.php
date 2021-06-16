<?php

if ($_SERVER['SERVER_NAME'] == "localhost") {
  define("BASE_URL", "http://" . $_SERVER['SERVER_NAME'] . '/zastita/');
}
