<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <form action="{{ route('test.store') }}" method="POST">
        @csrf
        <input type="text" name="nama" class="{{ $errors->has('nama') ? 'border-red-500' : '' }}">
        @error('nama')
            <span class="text-red-500">{{ $message }}</span>
        @enderror
        <input type="text" name="email">
        @error('email')
            <span class="text-red-500">{{ $message }}</span>
        @enderror
        <button>Kirim</button>
    </form>
</body>

</html>
