<?php

use App\Controller\TaskController;
use App\Model\TaskModel;
use \Slim\App as App;
// get tasks
$app->group('/task', function (App $app) {
  
  $app->get('[/]', TaskController::class . ':index');

  // get single task
  $app->get('/{id}', TaskController::class . ':getSingle');

  // add task
  $app->post('[/]', TaskController::class . ':add');

  $app->post('/import[/]', function ($request, $response, $args) {
    $body = $request->getParsedBody();
    
    $model = new TaskModel($this->db);
    $ret = $model->import($body);
    
    return $response->withJson($ret, $ret['code'] ?: 200);
  });
  
  // edit task
  $app->patch('/{id}', function ($request, $response, $args) {
    $body = $request->getParsedBody();
    // die(print_r($body, true));

    // convert to sql datetime
    if ($body['due']) {
      $body['due'] = strtotime($body['due']);
      $body['due'] = date('Y-m-d H:i:s', $body['due']);
    }

    if ($body['completed'] && $body['completed'] !== null) {
      $body['completed'] = strtotime($body['completed']);
      $body['completed'] = date('Y-m-d H:i:s', $body['completed']);
    }
    // die(print_r($body, true));

    $model = new TaskModel($this->db);
    $ret = $model->edit($args['id'], $body);

    return $response->withJson($ret, $ret['code'] ?: 200);
  });

  // delete task
  $app->delete('/{id}', function ($request, $response, $args) {
    $model = new TaskModel($this->db);
    $ret = $model->delete($args['id']);

    return $response->withJson($ret, $ret['code'] ?: 200);
  });

});