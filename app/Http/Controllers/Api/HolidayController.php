<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\HolidayRequest;
use App\Repos\HolidayRepo;
use App\Traits\ApiResponser;

class HolidayController
{
    use ApiResponser;

    private HolidayRepo $holidayRepo;

    public function __construct()
    {
        $this->holidayRepo = new HolidayRepo;
    }

    public function index(HolidayRequest $request)
    {
        $holidays = $this->holidayRepo->getByMonth($request->month, $request->year);
        return $this->success(data: $holidays);
    }
}
