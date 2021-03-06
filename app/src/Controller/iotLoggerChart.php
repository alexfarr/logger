<?php

namespace App\Controller;

use Khill\Lavacharts\DataTables\Formats\DateFormat;
use Khill\Lavacharts\Lavacharts;

class iotLoggerChart
{

    private $charter;
    private $data;
    private $db;
    private $loggerData;

    public function __construct($db)
    {
        $this->db = $db;
        $this->charter = new Lavacharts();
    }

    public function Chart($sensor)
    {
        $this->loggerData = new iotLoggerData($this->db);
        $this->data = $this->loggerData->fetchSensor($sensor);

        $dt = $this->charter->DataTable();
        $dt->addDateColumn('Date');
        $dt->addNumberColumn('temperature');
        foreach ($this->data as $row) {
            if (!empty($row['time'])) {
                $date = new \DateTime();
                $date->setTimestamp($row['time']);
                $date_str = $date->format('Y-m-d H:i:s');
                $dt->addRow([$date_str, $row['value']]);
            }
        }


        $chart = $this->charter->LineChart('Temperature-' . $sensor, $dt);

        return $this->charter->render('LineChart', 'Temperature-' . $sensor, 'chart-' . $sensor);

    }

    public function AllCharts(iotLoggerDataOptions $options = NULL)
    {
        $data = [];
        $this->loggerData = new iotLoggerData($this->db);
        $this->data = $this->loggerData->fetchAll($options);
        $sensors_found = [];
        $sensors = $this->loggerData->getSensors();
        $dt = $this->charter->DataTable();
        $date_format = new DateFormat([
            'pattern' => "dd MMM H:mm",
        ]);

        $dt->addDateColumn('Date', $date_format);
        foreach ($this->data as $row) {
            if (!empty($row['time'])) {

                $date = new \DateTime();
                $date->setTimestamp($row['time']);
                $data[$this->roundTime($row['time'])][$row['sensor']][] = ($row['data'] != 'lux') ? $row['value'] : $row['value'] / 1000;
                //$dt->addRow([$date_str, $row['value']]);
                $sensors_found[$row['sensor']] = $row['title'];
            }
        }

        ksort($sensors_found);
        foreach ($sensors_found as $sensor_id => $sensor_title) {
            $dt->addNumberColumn($sensor_title);
        }

        foreach ($data as $time => $time_sensor_data) {

            $date = new \DateTime();
            $date->setTimestamp($time);
            $date_str = $date->format('Y-m-d H:i:s');

            $row = [
                $date_str
            ];

            $sensor_ids = array_keys($sensors);
            sort($sensor_ids);
            $row += array_fill_keys($sensor_ids, NULL);


            ksort($time_sensor_data);

            // The sensor data might be an array of multiple values, take average
            foreach ($time_sensor_data as $id => $sensor_data) {
                $count = count($sensor_data);
                $total = 0;
                foreach ($sensor_data as $value) {
                    $total += $value;
                }
                $row[$id] = $total / $count;

            }
            $dt->addRow($row);

        }

        $chart = $this->charter->LineChart('Temperature', $dt, ['height' => 600]);


        return $this->charter->render('LineChart', 'Temperature', 'temp-chart');

    }

    /**
     * @param int $time
     * @param int $precision
     *
     * @return int mixed
     */
    function roundTime($time, $precision = 30)
    {

        $s = ($precision * 60);
        $remainder = $time % $s;

        if ($remainder > 0) {
            $time = $time + $s - $remainder;
        }

        return $time;
    }
}

