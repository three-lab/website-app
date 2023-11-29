<?php

namespace App\Repos;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Employee;
use App\Models\Subject;
use PDO;
use PDOStatement;

class DashboardRepo
{
    private Attendance $attendance;

    public function __construct()
    {
        $this->attendance = new Attendance;
    }

    public function getCountData()
    {
        return (object) [
            'employees' => count((new Employee)->all()),
            'subjects' => count((new Subject)->all()),
            'classrooms' => count((new Classroom)->all()),
        ];
    }

    public function getChartData()
    {
        $date = date('Y-m-d');
        $conn = $this->attendance->conn();
        $stmt = $conn->prepare("SELECT MONTH(`date`) AS month, COUNT(*) AS total, status FROM attendances WHERE `date` >= DATE_SUB(:date, INTERVAL 5 MONTH) GROUP BY MONTH(`date`), `status`");

        $stmt->execute(compact('date'));
        $results = $stmt->fetchAll(PDO::FETCH_OBJ);

        $months = array_unique(array_column($results, 'month'));
        $series = [
            ['name' => 'Hadir', 'data' => $this->genSeriesData($results, $months, 'present')],
            ['name' => 'Terlambat', 'data' => $this->genSeriesData($results, $months, 'late')],
            ['name' => 'Absen', 'data' => $this->genSeriesData($results, $months, 'absence')],
            ['name' => 'Izin', 'data' => $this->genSeriesData($results, $months, 'excused')],
        ];

        return [
            'months' => array_values(array_map(fn($month) => months()[$month], $months)),
            'series' => $series,
        ];
    }

    private function genSeriesData($results, $months, $status)
    {
        $data = [];
        $filtered = array_filter($results, function ($result) use ($status) {
            return $result->status == $status;
        });

        foreach ($months as $month) {
            $found = false;

            foreach($filtered as $result)
                if ($result->month == $month) {
                    $found = true;
                    $data[] = $result->total;
                    break;
                }

            if(!$found) $data[] = 0;
        }

        return $data;
    }
}
