<?php

namespace App\Controller;

class iotLogger{

  /**
   * @var \PDO
   */
  private $db;
  private $data;
  private $insert_statement = "INSERT INTO logger(sensor, data, value, time) VALUES(:sensor, :data, :value, :time)";

  function __construct(\PDO $db){
    $this->db = $db;
  }

  function save($data){
    $this->data = $data;
    $this->data['time'] = time();
    //Check we have the correct data available
//print($this->data);
    if(!empty($this->data['sensor']) && !empty($this->data['data']) && !empty($this->data['value'])) {
      $query = $this->db->prepare($this->insert_statement);
      $query->execute($this->data);

    }
  }

  function fetchAll(){
    $this->data =  $this->db->query('SELECT * FROM logger')->fetchAll();
    return $this->data;
  }
}
