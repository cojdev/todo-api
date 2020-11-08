<?php

use App\Controller\TaskController;
use App\Model\TaskModel;
use \Slim\App as App;
// get tasks
$app->group('/task', function (App $app) {
  $app->get('[/]', TaskController::class . ':index');
  $app->get('/{id}', TaskController::class . ':single');
  $app->post('[/]', TaskController::class . ':add');
  $app->post('/import[/]', TaskController::class . ':import');
  $app->patch('/{id}', TaskController::class . ':edit');
  $app->delete('/{id}', TaskController::class . ':delete');
});