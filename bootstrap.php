<?php
declare(strict_types=1);

use Illuminate\Database\Capsule\Manager;
use Symfony\Component\Dotenv\Dotenv;

error_reporting(E_ALL);
ini_set('display_errors', 'on');

require __DIR__ . '/vendor/autoload.php';

(new Dotenv(true))->load(__DIR__ . '/.env');

$config = require __DIR__ . '/config.php';

$capsule = new Manager();
$capsule->addConnection($config['db'][getenv('DB_DRIVER')]);
$capsule->setAsGlobal();
$capsule->bootEloquent();
