<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\AttendanceDetail;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use PDF;

class AttendanceController extends Controller
{
    /**
     * Construct
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'is_admin']);
    }

    public function index(Request $request)
    {
 
        if ($request->ajax()) {
            $data = Attendance::with(['user', 'detail']);

            return DataTables::eloquent($data)
                ->addColumn('action', function ($data) {
                    return view('layouts._action', [
                        'model' => '',
                        'edit_url' => '',
                        'show_url' => route('attendance.show', $data->id),
                        'delete_url' => '',
                    ]);
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->toJson();
        }

        $nama_user =  User::all();
        $page = "home";  
        
        $filter = [
            "pegawai" => "",
            "tahun" => "",
            "bulan" => "",
            "tanggal" => "",
            "status" => ""
        ];
        Carbon::setLocale('id');
        $kemarin = Carbon::now()->toDateString();

        # CEK APAKAH KEMARIN HADIR APA TIDAK
        $attendancesYesterday = Attendance::whereDate('created_at' , $kemarin)->get();

        $list_user = User::all();

        // dd($list_user);
        
        foreach ($list_user as $user) {
            $userHasAttendance = $attendancesYesterday->contains(function ($attendance) use ($user) {
                return $attendance->user_id === $user->id;
            });

            if (!$userHasAttendance) {
                $ketidakhadiran = new Attendance();
                $ketidakhadiran->user_id = $user->id;
                $ketidakhadiran->status = 0;
                $ketidakhadiran->created_at = now();
                $ketidakhadiran->updated_at = now();
                $ketidakhadiran->save();
                
                $ketidakhadiran_detail = new AttendanceDetail();
                $ketidakhadiran_detail->attendance_id = $ketidakhadiran->id;
                $ketidakhadiran_detail->long = 0;
                $ketidakhadiran_detail->lat = 0;
                $ketidakhadiran_detail->address = "";
                $ketidakhadiran_detail->photo = "";
                $ketidakhadiran_detail->type = "alpha";
                $ketidakhadiran_detail->created_at = now();
                $ketidakhadiran_detail->updated_at = now();
                $ketidakhadiran_detail->save();
            } 
            // dd(!$userHasAttendance);
        }		

        return view('pages.attendance.index', compact('nama_user', 'page', 'filter'));
    }

    public function show($id)
    {
        $attendance = Attendance::with(['user', 'detail'])->findOrFail($id);
        return view('pages.attendance.show', compact('attendance'));
    }

    public function filter(Request $request)
    {
        $filter = [
            "pegawai" => "",
            "tahun" => "",
            "bulan" => "",
            "tanggal" => "",
            "status" => ""
        ];
        $query = Attendance::query()->with(['user', 'detail']);
        if(!is_null($request->input('pegawai'))){
            $filter['pegawai'] = $request->input('pegawai');
            $query->where('user_id', $request->input('pegawai'));
        }
        if(!is_null($request->input('tahun'))){
            $filter['tahun'] = $request->input('tahun');
            $year = $request->input('tahun');
            $query->whereYear('created_at', $year);
        }
        if(!is_null($request->input('bulan'))){
            $filter['bulan'] = $request->input('bulan');
            $month = $request->input('bulan');
            $query->whereMonth('created_at', $month);
        }
        if(!is_null($request->input('tanggal'))){
            $filter['tanggal'] = $request->input('tanggal');
            $date = $request->input('tanggal');
            $query->whereDate('created_at', $date);
        }
        if(!is_null($request->input('status'))){
            $filter['status'] = $request->input('status');
            $type = $request->input('status');
            if ($type == "in") {
                $query->whereHas('detail', function($query) use ($type){
                    $query->where('type', $type)
                        ->orWhere('type', 'out');
                });
            }else{
                $query->whereHas('detail', function($query) use ($type){
                    $query->where('type', $type);
                });
            }
            
            // $query->where('attendance_details.type', $request->input('status'));
        }

        $filterData = $query->get();

        $dataShow = $query->first();

        # Set tanggal indonesia
        if (!is_null($dataShow)) {
            Carbon::setLocale('id');
            $bulan = $dataShow->created_at->format('m');
            $bulan = Carbon::createFromDate(null, $bulan, null);
            $bulan = $bulan->formatLocalized('%B');
        }else{
            $bulan = "bulan";
        }
        $nama_user =  User::all();  

        $page = "filter";


        return view('pages.attendance.index', compact('nama_user', 'filterData', 'page', 'dataShow', 'bulan', 'filter'));

    }

    public function generatePDF(Request $request)
    {
        // $attendanceDetail = AttendanceDetail::all();
        // $attendance = Attendance::all();
        // $users = User::all();

        // $pdf = PDF::loadview('pages.attendance.attendance_details',['attendanceDetails'=>$attendanceDetail]);

        // return $pdf->download('attendance_details.pdf');
        // $attendanceDetails = AttendanceDetail::all();
        // $users = User::all();

        // foreach ($attendanceDetails as $detail) {
        //     $user = $users->where('id', $detail->attendance->user_id)->first();
        //     $detail->user_name = $user->name;
        // }

        $query = Attendance::query()->with(['user', 'detail']);
        if(!is_null($request->input('pegawai'))){
            $filter['pegawai'] = $request->input('pegawai');
            $query->where('user_id', $request->input('pegawai'));
        }
        if(!is_null($request->input('tahun'))){
            $filter['tahun'] = $request->input('tahun');
            $year = $request->input('tahun');
            $query->whereYear('created_at', $year);
        }
        if(!is_null($request->input('bulan'))){
            $filter['bulan'] = $request->input('bulan');
            $month = $request->input('bulan');
            $query->whereMonth('created_at', $month);
        }
        if(!is_null($request->input('tanggal'))){
            $filter['tanggal'] = $request->input('tanggal');
            $date = $request->input('tanggal');
            $query->whereDate('created_at', $date);
        }
        if(!is_null($request->input('status'))){
            $filter['status'] = $request->input('status');
            $type = $request->input('status');
            if ($type == "in") {
                $query->whereHas('detail', function($query) use ($type){
                    $query->where('type', $type)
                        ->orWhere('type', 'out');
                });
            }else{
                $query->whereHas('detail', function($query) use ($type){
                    $query->where('type', $type);
                });
            }
            
            // $query->where('attendance_details.type', $request->input('status'));
        }

        $filterData = $query->get();

        // dd($filterData);


        $pdf = PDF::loadView('pages.attendance.attendance_details', ['attendanceDetails' => $filterData]);
        return $pdf->download('attendance_details.pdf');
    }
}
