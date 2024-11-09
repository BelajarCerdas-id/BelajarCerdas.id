<x-layout></x-layout>
<form action="{{ route('users.update', $cari->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="">
        <label for="name">Nama</label><br>
        <input type="text" name="name" id="name" value="{{ $cari->name }}">
        @error('name')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="">
        <label for="email">Sekolah</label><br>
        <input type="text" name="email" id="email" value="{{ $cari->email }}">
        @error('email')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="">
        <label for="password">Fase</label><br>
        <input type="text" name="password" id="password" value="{{ $cari->password }}">
        @error('password')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <button type="submit" class="btn btn-success">Kirim Data</button>
</form>
