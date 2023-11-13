<?php

namespace App\Repos;

use App\Models\Holiday;
use GuzzleHttp\Client;
use PDO;
use System\Support\Facades\FileSystem;

class HolidayRepo
{
    private string $holidayEndpoint = 'https://api-harilibur.vercel.app/api';
    private string $configPath;

    private Client $httpClient;
    private Holiday $holiday;

    public function __construct()
    {
        $this->httpClient = new Client([
            'base_uri' => $this->holidayEndpoint,
            'timeout' => 2.0,
        ]);

        $this->configPath = base_path('config/holidays.json');
        $this->holiday = new Holiday;
    }

    public function getByMonth(int $month, int $year): array
    {
        $this->syncByMonth($month, $year);

        $conn = $this->holiday->conn();
        $stmt = $conn->prepare("SELECT * FROM holidays WHERE
            YEAR(date_start) = :year AND MONTH(date_start) = :month");

        $stmt->execute([
            'year' => $year,
            'month' => $month,
        ]);

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function syncByMonth(int $month, int $year): void
    {
        $loadedMonths = FileSystem::get($this->configPath, true) ?? [];

        if(!in_array("$year-$month", $loadedMonths))
            $this->loadFromApi($month, $year);
    }

    private function loadFromApi(int $month, int $year): void
    {
        $loadedMonths = FileSystem::get($this->configPath, true) ?? [];
        $response = $this->httpClient->get("?month=$month&year=$year");

        $holidays = json_decode($response->getBody()->getContents());
        $holidays = array_filter($holidays, fn($holiday) => $holiday->is_national_holiday);
        $holidays = $this->groupHoliday($holidays);

        array_map(fn($holiday) => $this->holiday->insert($holiday), $holidays);
        array_unshift($loadedMonths, "$year-$month");

        FileSystem::put($this->configPath, json_encode($loadedMonths));
    }

    private function groupHoliday(array $holidays): array
    {
        $groupedHoliday = [];
        $parsedHolidays = [];

        foreach($holidays as $holiday)
            $groupedHoliday[$holiday->holiday_name][] = $holiday->holiday_date;

        foreach($groupedHoliday as $holidayName => $holidayDates)
            $parsedHolidays[] = [
                'information' => $holidayName,
                'date_start' => min($holidayDates),
                'date_end' => max($holidayDates),
                'type' => 'regular',
            ];

        return $parsedHolidays;
    }
}
