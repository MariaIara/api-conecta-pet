<?php

const BASE_PATH = __DIR__ . "/../../";

// Autoload
require_once BASE_PATH . 'vendor/autoload.php';

// Constants
require_once BASE_PATH . 'app/config/constants.php';

// Helpers
require_once BASE_PATH . 'app/Core/helpers/functions.php';

// CORS Headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
