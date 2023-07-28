<?php
 
namespace App\Http\Controllers;
 
use App\Attendance;
use App\Charts\AttendanceChart;
use App\User;
use Illuminate\Http\Request;
 
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
 
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $chart = new AttendanceChart();
        $chart->labels(['Today']);
        $chart->dataset('In', 'bar', [Attendance::countAttendance(false)])
            ->backgroundColor('#3490DC');
        $chart->dataset('Out', 'bar', [Attendance::countAttendance(true)])
            ->backgroundColor('#E3342F');
        $chart->dataset('Total User', 'line', [User::where('is_admin', false)->count()])
            ->color('#38C172')->fill(false);
        return view('home', compact('chart'));
    }
}