<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\TimeEntry;
use Illuminate\Http\Request;
use App\Radius;
class GetTimeController extends Controller
{
    public function getTime()
    {
        $timeEntry = TimeEntry::latest()->first();
        $radius = Radius::latest()->first();

        if ($timeEntry) {
            return response()->json([
                'clock_in' => $timeEntry->clock_in,
                'clock_out' => $timeEntry->clock_out,
                'clock_in_dispensation' => $timeEntry->clock_in_dispensation, // Include clock_in_dispensation in the response
                'latitude' => $radius->lat,
                'longitude' => $radius->long,
                'radius' => $radius->radius

            ]);
        }

        return response()->json([
            'clock_in' => null,
            'clock_out' => null,
            'clock_in_dispensation' => null, // Include clock_in_dispensation with null value in the response
            'latitude' => null,
            'longitude' => null,
            'radius' => null
        ]);
    }
}