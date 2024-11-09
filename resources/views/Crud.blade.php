<x-layout></x-layout>
<div class="overflow-x-auto">
    <table class="table">
        <!-- head -->
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Sekolah</th>
                <th>Fase</th>
                <th>Kelas</th>
                <th>Email</th>
                <th>Password</th>
                <th>No.Hp</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data->nama_lengkap }}</td>
                    <td>{{ $data->sekolah }}</td>
                    <td>{{ $data->fase }}</td>
                    <td>{{ $data->kelas }}</td>
                    <td>{{ $data->email }}</td>
                    <td>{{ $data->password }}</td>
                    <td>{{ $data->no_hp }}</td>
                    <td>{{ $data->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<a href="{{ route('users.create') }}">TAMBAH DATA</a>
