<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH');
header('Access-Control-Allow-Headers: *');
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

echo $method;