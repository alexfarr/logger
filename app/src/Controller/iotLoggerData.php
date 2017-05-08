<?php

namespace App\Controller;

use PDO;

class iotLoggerData{

  /**
   * @var PDO
   */
  private $db;
  private $data;
  private $insert_statement = "INSERT INTO logger(sensor, data, value, time) VALUES(:sensor, :data, :value, :time)";

  function __construct(PDO $db){
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
    $query = 'SELECT s.title, l.* FROM logger l JOIN sensors s ON l.sensor = s.id';
    $where = [];

    if(isset($options) && $options->getStart() && $options->getEnd()){
      $where[] = " time > {$options->getStart()} && time < {$options->getEnd()}";
    }

    if(count($where)){
      $query .= ' WHERE '. implode(' AND ', $where);
    }


    $this->data =  $this->db->query($query)->fetchAll();

    return $this->data;
  }

  function fetchSensor($sensor, iotLoggerDataOptions $options = NULL){
    $query = $this->db->prepare("SELECT s.title, l.* FROM logger l JOIN sensors s ON l.sensor = s.id WHERE sensor = :sensor");
    $query->execute(['sensor' => $sensor]);
    $this->data = $query->fetchAll();
    return $this->data;
  }

  function getSensors(){
    $query = $this->db->query("SELECT l.sensor, s.title FROM logger l JOIN sensors s ON l.sensor = s.id GROUP BY l.sensor");
    $result = $query->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_GROUP);
    return $result;
  }

  function getLatest($sensor = NULL){
    if($sensor){
      $sensors = [$sensor => $sensor];
    } else {
      $sensors = $this->getSensors();
    }
    $results = [];
    foreach(array_keys($sensors) as $sensor){
      $sql = "SELECT s.title, l.value, l.time FROM logger l JOIN sensors s ON l.sensor = s.id WHERE sensor = '{$sensor}' ORDER BY time DESC LIMIT 1 ";
      $query = $this->db->query($sql);
      $results[] = $query->fetchAll()[0];
    }
    return $results;
  }
}
