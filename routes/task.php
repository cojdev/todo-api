<?php
use \Slim\App as App;

$app->group('/task', function (App $app) {
  // get tasks
  $app->get('[/]', function ($request, $response, $args) {
    $model = new Task($this->db);
    $params = $request->getQueryParams();

    $ret = $model->getAll($params);

    if (!$ret['success']) {
      $ret = [
        "code" => $ret['code'],
        "message" => $ret['message'],
      ];
    }

    return $response->withJson($ret, $ret['code'] ?: 200);
  });

  // get single task
  $app->get('/{id}', function ($request, $response, $args) {
    $model = new Task($this->db);
    $ret = $model->get($args['id']);

    // get all tasks
    if (!$ret['success']) {
      $ret = [
        "code" => $ret['code'],
        "message" => $ret['message'],
      ];
    }

    return $response->withJson($ret, $ret['code'] ?: 200);
  });

  // add task
  $app->post('[/]', function ($request, $response, $args) {
    $body = $request->getParsedBody();
    
    // convert to sql datetime
    $body['due'] = strtotime($body['due']);
    $body['due'] = date('Y-m-d H:i:s', $body['due']);

    $model = new Task($this->db);
    $ret = $model->add($body);

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

    $model = new Task($this->db);
    $ret = $model->edit($args['id'], $body);

    return $response->withJson($ret, $ret['code'] ?: 200);
  });

  // delete task
  $app->delete('/{id}', function ($request, $response, $args) {
    $model = new Task($this->db);
    $ret = $model->delete($args['id']);

    return $response->withJson($ret, $ret['code'] ?: 200);
  });

  $app->post('/import[/]', function ($request, $response, $args) {
    $body = $request->getParsedBody();
    
    $model = new Task($this->db);
    $ret = $model->import($body);
    
    return $response->withJson($ret, $ret['code'] ?: 200);
  });
});