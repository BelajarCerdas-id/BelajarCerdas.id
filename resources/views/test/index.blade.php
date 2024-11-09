<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
</head>

<body>
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
    <h1>User List</h1>

    <a href="{{ route('test.create') }}">Tambah Data</a>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->nama }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if ($user->trashed())
                            <span style="color: red;">Deleted</span>
                        @else
                            <span style="color: green;">Active</span>
                        @endif
                    </td>
                    <td>
                        @if ($user->trashed())
                            <form action="{{ route('test.restore', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit">Restore</button>
                            </form>
                        @else
                            <form action="{{ route('test.destroy', $user->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table border="1">
        <label for="">RESTORE DATA</label>
        <tr>
            <th>nama</th>
            <th>email</th>
        </tr>

        @foreach ($tests as $item)
            <tr>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->email }}</td>
            </tr>
        @endforeach
    </table>

    {{-- @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif --}}

    {{-- <a href="{{ route('test.create') }}">Add User</a> --}}

    {{-- <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->nama }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        {{-- <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                        <form action="{{ route('users.restore', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit">Restore</button>
                        </form> --}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table> --}}
</body>

</html>
