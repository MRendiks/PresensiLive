<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\TimeEntry;
use App\Radius;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SetTimeController extends Controller
{
    public function index()
    {
        $latestTimeEntry = TimeEntry::latest()->first();
        $latitude = null;
        $longitude = null;
        $radius = null;

        if ($latestTimeEntry) {
            $radius = Radius::latest()->first();
            $latitude = $radius ? $radius->lat : null;
            $longitude = $radius ? $radius->long : null;
            $radius = $radius ? $radius->radius : null;
        }

        return view('pages.user.set-time', [
            'time' => $latestTimeEntry,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'radius' => $radius
        ]);
    }

    public function saveTime(Request $request)
    {
        // Get the clock-in, clock-out, and clock-in-dispensation values from the input
        $clockIn = $request->input('clock_in');
        $clockOut = $request->input('clock_out');
        $clockInDispensation = $request->input('clock_in_dispensation');

        // Get the latitude, longitude, and radius values from the input
        $longitude = $request->input('longitude');
        $latitude = $request->input('latitude');
        $radiusValue = $request->input('radius');

        // Log the latitude, longitude, and radius values
        $radius = new Radius();
        $radius->long = $longitude;
        $radius->lat = $latitude;
        $radius->radius = $radiusValue;
        $radius->save();

        // Create a new TimeEntry model instance
        $timeEntry = new TimeEntry();
        $timeEntry->clock_in = $clockIn;
        $timeEntry->clock_out = $clockOut;
        $timeEntry->clock_in_dispensation = $clockInDispensation; // Set the value for clock_in_dispensation
        $timeEntry->save();

        // Set a success flash message in the session
        Session::flash('success', 'Time saved successfully');

        // Return success response
        return response()->json([
            'clock_in' => $clockIn,
            'clock_out' => $clockOut,
            'clock_in_dispensation' => $clockInDispensation, // Include clock_in_dispensation in the response
            'latitude' => $latitude,
            'longitude' => $longitude,
            'radius' => $radiusValue
        ]);
    }
}