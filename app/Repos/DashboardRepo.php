<?php

namespace App\Repos;

use App\Models\Attendance;
use PDO;

class DashboardRepo
{
    private Attendance $attendance;

    public function __construct()
    {
        $this->attendance = new Attendance;
    }

    public function getChartData()
    {
        $date = date('Y-m-d');
        $conn = $this->attendance->conn();

        $presentSt =

        dd($data);
    }

    private function composeChartQuery(PDO $conn, string $date, string $status)
    {
        return $conn->prepare("SELECT MONTH(date), COUNT(*) FROM attendances WHERE `date` >= DATE_SUB({$date}, INTERVAL 5 MONTH) AND `status` = 'present' GROUP BY MONTH({$date})");
    }
}
