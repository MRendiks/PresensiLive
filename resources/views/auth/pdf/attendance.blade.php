<!DOCTYPE html>
<html>
<head>
    <title>Attendance PDF</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <h2>Attendance List</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Status</th>
                <th>Check In Time</th>
                <th>Check Out Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td>{{ $row->id }}</td>
                <td>{{ $row->user->name }}</td>
                <td>{{ $row->status ? 'Check Out' : 'Check In' }}</td>
                <td>{{ $row->created_at }}</td>
                <td>{{ $row->updated_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>