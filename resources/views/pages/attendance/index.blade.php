@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Absen Bulanan</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Absen Bulanan</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<section class="content">
    <div class="container-fluid">
        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <section class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h1 class="font-weight-bold d-flex justify-content-center align-items-center">
                            DATA ABSEN PEGAWAI
                        </h1>
                        @if ($page == "home")
                            <h4 class="font-weight-bold d-flex justify-content-center align-items-center">nama - pekerjaan</h4>
                            <h5 class="font-weight-bold d-flex justify-content-center align-items-center"> (bulan - tanggal) </h5>
                        @else
                            @if (is_null($dataShow))
                                <h4 class="font-weight-bold d-flex justify-content-center align-items-center">nama - pekerjaan</h4>
                                <h5 class="font-weight-bold d-flex justify-content-center align-items-center"> (bulan - tanggal) </h5>
                            @else
                                <h4 class="font-weight-bold d-flex justify-content-center align-items-center">{{$dataShow->user->name}} - Guru</h4>
                            <h5 class="font-weight-bold d-flex justify-content-center align-items-center"> ({{$bulan}} - {{$dataShow->created_at->format('Y')}}) </h5>
                            @endif
                            
                        @endif
                        
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                    <form action="{{route('filter')}}" method="post">
                        @csrf
                        <div class="row d-flex justify-content-around">
                            <div class="col-md-3">
                                <select name="pegawai" id="pegawai" class="form-control">
                                <option value="">- PILIH PEGAWAI -</option>
                                @foreach ($nama_user as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="tahun" id="tahun" class="form-control">
                                    <option value="">- PILIH TAHUN -</option>
                                    <option value="2017">2017</option>
                                    <option value="2018">2018</option>
                                    <option value="2019">2019</option>
                                    <option value="2020">2020</option>
                                    <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="bulan" id="bulan" class="form-control" required >
                                <option value="">- PILIH BULAN -</option>
                                <option value="1">Januari</option>
                                <option value="2">Februari</option>
                                <option value="3">Maret</option>
                                <option value="4">April</option>
                                <option value="5">Mei</option>
                                <option value="6">Juni</option>
                                <option value="7">Juli</option>
                                <option value="8">Agustus</option>
                                <option value="9">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                                </select>
                            </div>

                            <script>
                                function handleInvalidSelect() {
                                    var select = document.getElementById('bulan');
                                    if (select.value === '') {
                                        select.setCustomValidity('Harap Pilih Bulan');
                                    } else {
                                        select.setCustomValidity('');
                                    }
                                }
                                    document.getElementById('bulan').addEventListener('change', function() {
                                    this.setCustomValidity('');
                                    });
                            </script>
                            
                        </div>
                        
                        <div class="mt-3 row d-flex justify-content-between align-items-center ml-5" >
                            <div class="col-md-3">
                                <input type="date" name="tanggal" class="form-control">
                            </div>
    
                            <div class="col-md-3" style="margin-left: 30px;">
                                <select name="status" id="status" class="form-control">
                                <option value="">- PILIH STATUS -</option>
                                <option value="in">Hadir</option>
                                <option value="sick">Sakit/Izin</option>
                                <option value="alpha">Alpha</option>
                                </select>
                            </div>
 
                            <div class="col-md-4 d-flex justify-content-center align-items-center">
                                <button type="submit" class="btn btn-primary" style="background-color: blue;"><i class="ion ion-search mr-1"></i> Search</button>
                            
                                
                            </div>

                            
                        </div>

                        
                    </form>

                    <div class="col-md-4" style="margin-left: 1070px; margin-top: -38px;">
                        <form action="{{ route('generate.pdf') }}" method="get">
                            @csrf
                            @if ($filter['pegawai'] != "")
                                <input type="text" name="pegawai" value="{{$filter['pegawai']}}" hidden>
                            @endif
                            @if ($filter['tahun'] != "")
                                <input type="text" name="tahun" value="{{$filter['tahun']}}" hidden>
                            @endif
                            @if ($filter['bulan'] != "")
                                <input type="text" name="bulan" value="{{$filter['bulan']}}" hidden>
                            @endif
                            @if ($filter['tanggal'] != "")
                                <input type="text" name="tanggal" value="{{$filter['tanggal']}}" hidden>
                            @endif
                            @if ($filter['status'] != "")
                                <input type="text" name="status" value="{{$filter['status']}}" hidden>
                            @endif
                            <button type="submit" class="btn btn-danger"><i class="ion ion-printer mr-1"></i> Cetak</button>
                        </form>
                    </div>
                    
                    <br><br>
                    <table class="table" id="datatable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Pegawai</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Jenis Absen</th>
            {{-- <th>Status</th> --}}
            <th>Detail</th>
        </tr>
    </thead>


@if ($page == "filter")
    

    <tbody>
        

            @foreach ($filterData as $item)
            <tr>
                <td>{{$item->id}}</td>
                <td>{{$item->user->name}}</td>
                <td>{{$item->created_at->format('Y-m-d')}}</td>
                <td>{{$item->created_at->format('H:i:s')}}</td>
                @if ($item->detail[0]['type'] == "in" || $item->detail[0]['type'] == "out")
                    <td>Hadir</td>
                    <td><a href="{{ route('attendance.show', $item->id) }}" class="btn btn-sm btn-secondary">Show</a></td>
                @else
                    @if ($item->detail[0]['type'] == "sick")
                        <td>Sakit/Izin</td>
                        <td><a href="{{ route('attendance.show', $item->id) }}" class="btn btn-sm btn-secondary">Show</a></td>
                    @else
                        <td>Alpha</td>
                        <td></td>
                    @endif
                @endif
                {{-- @if ($item->status == 0)
                    <td>Check In</td>
                @else
                    <td>Check Out</td>
                @endif --}}
                
                
                </tr
            @endforeach
       >
    </tbody>
    @endif
</table>



                    </div>
                </div>
                <!-- /.card -->
            </section>
            <!-- /.Left col -->
        </div>
        <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
</section>

@endsection

@if ($page == "home")

@push('scripts')
<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: '{{ url("attendance") }}',
            columns: [
                {data: 'DT_RowIndex', name: 'id'},
                {data: 'user.name', name: 'user.name'},
                {data: function(row) {
                    let date = new Date(row.created_at);
                    return date.toLocaleDateString();
                }, name: 'created_at'},
                {data: function(row) {
                    let date = new Date(row.created_at);
                    return date.toLocaleTimeString();
                }, name: 'created_at'},
                {data: function(row) {
                    let data = "";
                    if (row.detail[0]['type'] == "in" || row.detail[0]['type'] == "out") {
                        data = "Hadir";
                    }else if(row.detail[0]['type'] == "sick"){
                        data = "Sakit";
                    }else{
                        data = "Alpha";
                    }
                    return data;
                }, name: 'type'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });
    });
</script>
@endpush

@endif