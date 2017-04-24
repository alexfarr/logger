<?php
/**
 * Created by PhpStorm.
 * User: alexfarr
 * Date: 24/04/2017
 * Time: 15:11
 */

namespace App\Controller;


class iotLoggerDataOptions {

  public $range_start;
  public $range_end;

  /**
   * @param $start
   * @param $end
   */
  public function setRange($start, $end) {
    $this->range_start  = $start;
    $this->range_end = $end;
  }

  /**
   * @return mixed
   */
  public function getStart(){
    return $this->range_start;
  }

  /**
   * @return mixed
   */
  public function getEnd(){
    return $this->range_end;
  }
}