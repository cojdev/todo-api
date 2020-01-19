<?php

class TaskModel extends Model {
  /**
   * add
   *
   * @param  mixed $data
   * @param  mixed $import
   *
   * @return void
   */
  function add($data, $import = false) {
    try {
      // validation
      if ($data['description'] === '') {
        throw new Exception("No description provided.", 69);
      }

      if (
        (strtotime($data['due']) < strtotime(date('Y-m-d h:i:s')))
        && $import === false
      ) {
        throw new Exception("Date due date cannot be before now.", 69);
        
      }

      $query = $this->db->prepare(
        'INSERT INTO
          Task (
            description,
            starred,
            due,
            completed,
            created
          )
        VALUES
          (?,?,?,?,NOW())'
      );

      $result = $query->execute([
        $data['description'],
        $data['starred'] || 0,
        $data['due'],
        $data['completed'] ?: null,
      ]);

      $id = $this->db->query('SELECT LAST_INSERT_ID()')->fetch();

      if ($result) {
        return [
          'success' => true,
          'message' => 'Task added',
          'url' => '/task/'.$id[0],
          'requestBody' => $data,
          'id' => $id[0],
        ];
      }

      return [
        'success' => false,
        "code" => 400,
        'message' => "Bad Request",
        'requestBody' => $data,
      ];
    } catch (PDOException $e) {
      return [
        'success' => false,
        "code" => 500,
        'message' => $e->getMessage(),
        'requestBody' => $data,
      ];
    } catch (Exception $e) {
      if ($e->getCode() === 69) {
        return [
          'success' => false,
          "code" => 400,
          'message' => 'Validation Error: ' . $e->getMessage(),
          'requestBody' => $data,
        ];
      }
      return [
        'success' => false,
        "code" => 500,
        'message' => $e->getMessage(),
        'requestBody' => $data,
      ];
    }
  }

  /**
   * edit
   *
   * @param  mixed $id
   * @param  mixed $data
   *
   * @return void
   */
  function edit($id, $data) {
    // die(print_r($data, true));
    try {
      $result = $this->db->prepare(
        "UPDATE
          Task
        SET
          description=?,
          completed=?,
          starred=?,
          due=?,
          modified=NOW()
        WHERE
          id=?"
      );
      
      $result->execute([
        $data['description'],
        $data['completed'],
        $data['starred'],
        $data['due'],
        $id,
      ]);

      if ($result->rowCount()) {
        return [
          'success' => true,
          'message' => 'Task modifed',
          'id' => $id,
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
        'code' => 500,
        'message' => $e->getMessage()
      ];
    } catch (Exception $e) {
      return [
        'success' => false,
        'code' => 500,
        'message' => $e->getMessage()
      ];
    }
  }

  /**
   * delete
   *
   * @param  mixed $id
   *
   * @return void
   */
  function delete($id) {
    try {
      $result = $this->db->prepare("DELETE FROM Task WHERE id=?");
      $result->execute([$id]);

      if ($result->rowCount()) {
        return [
          'success' => true,
          'message' => 'Task deleted',
          'id' => $id,
        ];
      }

      return [
        'success' => false,
        'code' => 400,
        'message' => 'Unknown Error. No tasks deleted',
      ];
    } catch (PDOException $e) {
      return [
        'success' => false,
        "code" => 500,
        'message' => $e->getMessage(),
      ];
    } catch (Exception $e) {
      return [
        'success' => false,
        "code" => 500,
        'message' => $e->getMessage(),
      ];
    }
  }

  /**
   * import
   *
   * @param  mixed $data
   *
   * @return void
   */
  function import($data) {
    try {
      $imported = 0;

      if (is_array($data) === false) {
        throw new Exception('Invalid format submitted', 400);
      }

      foreach ($data as $d) {
        $call = $this->add($d);
        if ($call['success'] === false) {
          throw new Exception($call['message'], $call['code']);
        }
        $imported++;
      }

      return [
        'success' => true,
        'message' => 'Import complete',
        'count' => $imported,
      ];
    } catch (Exception $e) {
      return [
        'success' => false,
        "code" => $e->getCode(),
        'message' => $e->getMessage(),
        'count' => $imported,
      ];
    }
  }
}
