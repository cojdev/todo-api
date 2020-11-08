<?php

namespace App\Core;

abstract class Controller implements ControllerInterface {
  protected $container;
  protected $db;

  function __construct ($container) {
    $this->container = $container;
    $this->db = $this->container->get('db');
  }
}

interface ControllerInterface {
  public function index($request, $response, $args);
}