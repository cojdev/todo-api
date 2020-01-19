<?php

abstract class Model {
  private $db;

  /**
   * __construct
   *
   * @param  mixed $connection
   *
   * @return void
   */
  function __construct($connection) {
    $this->db = $connection;
  }

  /**
   * getAll
   *
   * @param  mixed $params
   *
   * @return void
   */
  function getAll($params) {
    try {
      // parameters
      // NOTE: this is a significant security hole
      $limit = isset($params['limit']) ? ' LIMIT ' . $params['limit'] : '';
      $sort = isset($params['sort']) ? ' ORDER BY Task.due ' . $params['sort'] : ' ORDER BY Task.due ASC';
      
      // where clauses
      $whereClauses = [];
      isset($params['date']) ? $whereClauses[] = 'due="'.$params['date'].'"' : null;
      isset($params['starred']) ? $whereClauses[] = 'starred="'.$params['starred'].'"' : null;
      isset($params['completed']) ? $whereClauses[] = ((int)$params['completed'] === 0 ? 'completed IS NULL' : 'completed IS NOT NULL') : null;
      $whereSql = count($whereClauses) ? ' WHERE ' . implode('', $whereClauses) : '';

      $query = $this->db->query("SELECT * FROM Task$whereSql$sort$limit");
      $result = $query->fetchAll(PDO::FETCH_ASSOC);

      if ($result) {
        return [
          'success' => true,
          'count' => count($result),
          'data' => $result,
          'params' => $params,
        ];
      }

      return [
        'success' => false,
        "code" => 404,
        'message' => "No tasks found"
      ];
    } catch (PDOException $e) {
      die($e->getMessage());
    } catch (Exception $e) {
      die($e->getMessage());
    }
  }

  /**
   * get
   *
   * @param  mixed $id
   *
   * @return void
   */
  function get($id) {
    try {
      $query = $this->db->prepare("SELECT * FROM Task WHERE id=?");
      $query->execute([$id]);
      $result = $query->rowCount() ? $query->fetch(PDO::FETCH_ASSOC) : false;
  
      if ($result) {
        return [
          'success' => true,
          'data' => $result,
        ];
      }

      return [
        'success' => false,
        "code" => 404,
        'message' => "Task not found"
      ];
    } catch (PDOException $e) {
      return [
        'success' => false,
        "code" => 500,
        'message' => $e->getMessage()
      ];
    } catch (Exception $e) {
      return [
        'success' => false,
        "code" => 500,
        'message' => $e->getMessage()
      ];
    }
  }
}
