<x-layout></x-layout>
<form action="{{ route('users.store') }}" method="POST">
    @csrf
    <div class="">
        <label for="name">Nama</label><br>
        <input type="text" name="name" id="name" value="{{ @old('name') }}">
        @error('name')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="">
        <label for="email">Sekolah</label><br>
        <input type="text" name="email" id="email" value="{{ @old('email') }}">
        @error('email')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="">
        <label for="password">Fase</label><br>
        <input type="text" name="password" id="password" value="{{ @old('password') }}">
        @error('password')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <button type="submit" class="btn btn-success">Kirim Data</button>
</form>
