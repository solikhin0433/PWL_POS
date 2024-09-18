<!DOCTYPE html>
<html>
     <head>
     <title>Data User</title>
     </head>
     <body>
          <h1>Data User</h1>
          <table border="1" cellpadding="2" cellspacing="0">
    <tr>
        {{-- <td>Jumlah Pengguna</td> --}}
        <td>ID</td>
        <td>Username</td>
        <td>Nama</td>
        <th>ID Level Pengguna</th>
    </tr>
    {{-- @foreach ($data as $d) --}}
    <tr>
        {{-- <td>{{ $data}}</td> --}}
        <td>{{ $data->id }}</td>
        <td>{{ $data->username }}</td>
        <td>{{ $data->nama }}</td>
        <td>{{ $data->level_id }}</td>
    </tr>
    {{-- @endforeach --}}
          </table>
     </body>
</html>