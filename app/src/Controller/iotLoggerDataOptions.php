<?php
/**
 * Created by PhpStorm.
 * User: alexfarr
 * Date: 24/04/2017
 * Time: 15:11
 */

namespace App\Controller;


class iotLoggerDataOptions {

  private $range_start = NULL;
  public $range_end = NULL;
  public $sensor = NULL;

  /**
   * @param int $start
   * @param int $end
   */
  public function setRange($start, $end) {
    $this->range_start  = $start;
    $this->range_end = $end;
  }

  /**
   * @return int
   */
  public function getStart(){
    return $this->range_start;
  }

  /**
   * @return int
   */
  public function getEnd(){
    return $this->range_end;
  }

  /**
   * @param string $sensor
   */
  public function setSensor($sensor){
    $this->sensor = $sensor;
  }

  /**
   * @return string
   */
  public function getSensor(){
    return $this->sensor;
  }
}