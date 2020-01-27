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
}