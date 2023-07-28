<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ImageStorage;
use Carbon\Carbon;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AttendanceController extends Controller
{
    use ImageStorage;

    /**
     * Store presence status
     * @param Request $request
     * @return JsonResponse|void
     * @throws InvalidFormatException
     * @throws BindingResolutionException
     */
    public function store(Request $request)
    {
        $request->validate([
            'long' => ['required'],
            'lat' => ['required'],
            'address' => ['required'],
            'type' => ['in:in,out,sick', 'required'],
            'photo' => ['required']
        ]);
    
        $photo = $request->file('photo');
        $attendanceType = $request->type;
        $userAttendanceToday = $request->user()
            ->attendances()
            ->whereDate('created_at', Carbon::today())
            ->first();
    
        if ($attendanceType == 'in') {
            if (!$userAttendanceToday) {
                $attendance = $request
                    ->user()
                    ->attendances()
                    ->create(['status' => false]);
    
                $attendance->detail()->create([
                    'type' => 'in',
                    'long' => $request->long,
                    'lat' => $request->lat,
                    'photo' => $this->uploadImage($photo, $request->user()->name, 'attendance'),
                    'address' => $request->address
                ]);
    
                return response()->json(['message' => 'Success'], Response::HTTP_CREATED);
            }
    
            return response()->json(['message' => 'User has been checked in'], Response::HTTP_OK);
        }
    
        if ($attendanceType == 'sick') {
            if (!$userAttendanceToday) {
                $attendance = $request
                    ->user()
                    ->attendances()
                    ->create(['status' => false]);
        
                $attendance->detail()->create([
                    'type' => 'in',
                    'long' => $request->long,
                    'lat' => $request->lat,
                    'photo' => $this->uploadImage($photo, $request->user()->name, 'attendance'),
                    'address' => $request->address
                ]);
        
                $attendance->detail()->create([
                    'type' => 'sick',
                    'long' => $request->long,
                    'lat' => $request->lat,
                    'photo' => $this->uploadImage($photo, $request->user()->name, 'attendance'),
                    'address' => $request->address
                ]);
        
                $attendance->detail()->create([
                    'type' => 'out',
                    'long' => $request->long,
                    'lat' => $request->lat,
                    'photo' => $this->uploadImage($photo, $request->user()->name, 'attendance'),
                    'address' => $request->address
                ]);
        
                $userAttendanceToday = $request->user()
                    ->attendances()
                    ->whereDate('created_at', Carbon::today())
                    ->first();
        
                $userAttendanceToday->update(['status' => true]);
        
                return response()->json(['message' => 'Success'], Response::HTTP_CREATED);
            }
        }
    
        if ($attendanceType == 'out') {
            if ($userAttendanceToday) {
                if ($userAttendanceToday->status) {
                    return response()->json(['message' => 'User has been checked out'], Response::HTTP_OK);
                }
    
                $userAttendanceToday->update(['status' => true]);
    
                $userAttendanceToday->detail()->create([
                    'type' => 'out',
                    'long' => $request->long,
                    'lat' => $request->lat,
                    'photo' => $this->uploadImage($photo, $request->user()->name, 'attendance'),
                    'address' => $request->address
                ]);
    
                return response()->json(['message' => 'Success'], Response::HTTP_CREATED);
            }
    
            return response()->json(['message' => 'Please do check in first'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function history(Request $request)
    {
        $request->validate(
            [
                'from' => ['required'],
                'to' => ['required'],
            ]
        );

        $history = $request->user()->attendances()->with('detail')
            ->whereBetween(
                DB::raw('DATE(created_at)'),
                [
                    $request->from, $request->to
                ]
            )->get();

        return response()->json(
            [
                'message' => "list of presences by user",
                'data' => $history,
            ],
            Response::HTTP_OK
        );
    }
}