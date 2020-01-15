<?php

use \Slim\App as App;

require_once 'vendor/autoload.php';

session_start();

// Environment Variables in .env file
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

// Configuration
$config = [
  'db' => [
    'host'   => getenv('DB_HOST'),
    'user'   => getenv('DB_USER'),
    'pass'   => getenv('DB_PASS'),
    'dbname' => getenv('DB_NAME'),
  ],
  'displayErrorDetails' => true,
];

$app = new App(['settings' => $config]);

$container = $app->getContainer();
$container['db'] = function ($c) {
  $db = $c['settings']['db'];
  try {
    $pdo = new PDO(
      'mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'],
      $db['user'],
      $db['pass'],
      []
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
    echo 'Caught PDOException: ';
    die($e->getMessage());
  }

  return $pdo;
};

$app->options('/{routes:.+}', function ($request, $response, $args) {
  return $response;
});

// Request Headers
$app->add(function ($req, $res, $next) {
  $response = $next($req, $res);
  return $response
          ->withHeader('Access-Control-Allow-Origin', '*')
          ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
          ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

// Example route
$app->get('/', function ($request, $response, $args) {
  $data = [
    'foo' => 'bar',
    'fizz' => 'buzz',
    'args' => $args,
  ];

  return $response->withJson($data);
});

// import routes
require './routes/task.php';

// 404
$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function($req, $res) {
  return $res->withJson([
    'code' => 404,
    'message' => 'Not Found',
  ], 404);
});


// run application
$app->run();
