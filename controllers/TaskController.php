<?php

namespace App\Controller;

use App\Core\Controller;
use App\Model\TaskModel;

class TaskController extends Controller {
  public function index($request, $response, $args) {
    $model = new TaskModel($this->container->db);
    $params = $request->getQueryParams();

    $ret = $model->getAll($params);

    if (!$ret['success']) {
      $ret = [
        "code" => $ret['code'],
        "message" => $ret['message'],
      ];
    }

    return $response->withJson($ret, $ret['code'] ?: 200);
  }

  public function getSingle($request, $response, $args) {
    $model = new TaskModel($this->db);
    $ret = $model->get($args['id']);

    // get all tasks
    if (!$ret['success']) {
      $ret = [
        "code" => $ret['code'],
        "message" => $ret['message'],
      ];
    }

    return $response->withJson($ret, $ret['code'] ?: 200);
  }

  public function add($request, $response, $args) {
    $body = $request->getParsedBody();
    
    // convert to sql datetime
    $body['due'] = strtotime($body['due']);
    $body['due'] = date('Y-m-d H:i:s', $body['due']);

    $model = new TaskModel($this->db);
    $ret = $model->add($body);

    return $response->withJson($ret, $ret['code'] ?: 200);
  }
}