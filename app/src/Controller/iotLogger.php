<?php

namespace App\Controller;

class iotLogger {

  private $args = [];
  private $db;

  function __construct(\PDO $db) {
    $this->db = $db;
  }

  function homepage() {
    $record               = new iotLoggerData($this->db);
    //$this->args['result'] = $record->fetchAll();
    $this->args['chart']  = '';
    $charts               = [];
    $chart    = new iotLoggerChart($this->db);
    /*$sensors              = $record->getSensors();
    foreach ($sensors as $sensor) {
      $charts[] = $chart->Chart($sensor['sensor']);
    }
    $this->args['chart'] = implode(' ', $charts);*/
    $options = new iotLoggerDataOptions();
    $startTime = new \DateTime('last monday');
    $endTime = new \DateTime('next sunday');
    $options->setRange($startTime->getTimestamp(), $endTime->getTimestamp());
    $this->args['charts'] = $chart->AllCharts($options);


    $temp_stats = $record->getLatest();
    $this->args['stats'] = $temp_stats;

    return $this->args;
  }

  function save($data) {
    $record = new iotLoggerData($this->db);
    $record->save($data);
  }

}
