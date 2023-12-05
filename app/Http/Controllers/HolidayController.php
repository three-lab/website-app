<?php

namespace App\Http\Controllers;

use App\Http\Requests\HolidayFormRequest;
use App\Repos\HolidayRepo;
use App\Http\Requests\HolidayRequest;
use App\Http\Resources\HolidayResource;
use App\Models\Holiday;

class HolidayController
{
    private Holiday $holiday;

    public function __construct()
    {
        $this->holiday = new Holiday;
    }

    public function index()
    {
        return view('holiday.index');
    }

    public function show($id)
    {
        $holiday = $this->holiday->findOrFail($id);

        return response()->json(
            HolidayResource::make($holiday),
            200
        );
    }

    public function store(HolidayFormRequest $request)
    {
        $this->holiday->insert($request->validated());

        return response()->json([
            'message' => 'Berhasil mengupdate hari libur',
        ], 200);
    }

    public function update(HolidayFormRequest $request, $id)
    {
        $holiday = $this->holiday->findOrFail($id);
        $holiday->update($request->validated());

        return response()->json([
            'message' => 'Berhasil mengupdate hari libur',
        ], 200);
    }

    public function destroy($id)
    {
        $holiday = $this->holiday->findOrFail($id);
        $holiday->delete();

        return response()->json([
            'message' => 'Berhasil menghapus hari libur',
        ], 200);
    }

    public function json(HolidayRequest $request)
    {
        $holidays = (new HolidayRepo)->getByMonth($request->month, $request->year);
        return response()->json($holidays, 200);
    }
}
