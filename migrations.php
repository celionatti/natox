<?php

use NatoxCore\Application;
use NatoxCore\helpers\H;

require_once __DIR__ . '/vendor/autoload.php';
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = new Application(__DIR__);

$app->db->applyMigrations();