<?php

namespace App\Http\Controllers;

use App\Repos\HolidayRepo;
use App\Http\Requests\HolidayRequest;

class HolidayController
{
    public function index()
    {
        return view('holiday.index');
    }

    public function json(HolidayRequest $request)
    {
        $holidays = (new HolidayRepo)->getByMonth($request->month, $request->year);
        return response()->json($holidays, 200);
    }
}