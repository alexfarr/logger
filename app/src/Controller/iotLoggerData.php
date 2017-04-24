<?php

namespace App\Controller;

class iotLoggerData{

  /**
   * @var \PDO
   */
  private $db;
  private $data;
  private $insert_statement = "INSERT INTO logger(sensor, data, value, time) VALUES(:sensor, :data, :value, :time)";

  function __construct(\PDO $db){
    $this->db = $db;
  }

  function save($data, iotLoggerDataOptions $options = NULL){
    $this->data = $data;
    $this->data['time'] = time();
    //Check we have the correct data available

    if(!empty($this->data['sensor']) && !empty($this->data['data']) && !empty($this->data['value'])) {
      $query = $this->db->prepare($this->insert_statement);
      $query->execute($this->data);

    }
  }

  function fetchAll(iotLoggerDataOptions $options = NULL){
    $query = 'SELECT * FROM logger';
    $where = [];

    if($options->getStart() && $options->getEnd()){
      $where[] = " time > {$options->getStart()} && time < {$options->getEnd()}";
    }

    if(count($where)){
      $query .= ' WHERE '. implode(' AND ', $where);
    }


    $this->data =  $this->db->query($query)->fetchAll();

    return $this->data;
  }

  function fetchSensor($sensor, iotLoggerDataOptions $options = NULL){
    $query = $this->db->prepare("SELECT * FROM logger WHERE sensor = :sensor");
    $query->execute(['sensor' => $sensor]);
    $this->data = $query->fetchAll();
    return $this->data;
  }

  function getSensors(iotLoggerDataOptions $options = NULL){
    return $this->db->query("SELECT DISTINCT sensor FROM logger")->fetchAll();
  }
}
