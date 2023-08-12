<!DOCTYPE html>
<html>
<head>
	<title>Laporan Absensi</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<style type="text/css">
		table tr td,
		table tr th{
			font-size: 9pt;
		}
	</style>
</head>
<body>
	<center>
		<h1>Laporan Absensi</h1>
	</center>

	<table class='table table-bordered'>
		<thead>
			<tr>
				<th>ID</th>
				<th>Nama Pegawai</th>
				<th>Tanggal</th>
				<th>Jam</th>
				<th>Jenis Absen</th>
			</tr>
		</thead>
		<tbody>
			@foreach($attendanceDetails as $item)
				<tr>
					<td>{{$item->id}}</td>
					<td>{{$item->user->name}}</td>
					<td>{{$item->created_at->format('Y-m-d')}}</td>
					<td>{{$item->created_at->format('H:i:s')}}</td>
					@if ($item->detail[0]['type'] == "in" || $item->detail[0]['type'] == "out")
						<td>Hadir</td>
					@else
						@if ($item->detail[0]['type'] == "sick")
							<td>Sakit/Izin</td>
						@else
							<td>Alpha</td>
						@endif
					@endif
				</tr>
			@endforeach
		</tbody>
	</table>
 
</body>
</html>