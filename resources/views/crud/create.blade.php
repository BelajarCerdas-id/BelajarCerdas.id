<x-layout></x-layout>
<form action="{{ route('crud.store') }}" method="POST">
    @csrf
    <div class="">
        <label for="nama_lengkap">Nama</label><br>
        <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ @old('nama_lengkap') }}">
        @error('nama_lengkap')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="">
        <label for="sekolah">Sekolah</label><br>
        <input type="text" name="sekolah" id="sekolah" value="{{ @old('password') }}">
        @error('sekolah')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="">
        <label for="fase">Fase</label><br>
        <input type="text" name="fase" id="fase" value="{{ @old('fase') }}">
        @error('fase')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="">
        <label for="kelas">kelas</label><br>
        <input type="text" name="kelas" id="kelas" value="{{ @old('kelas') }}">
        @error('kelas')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="">
        <label for="email">email</label><br>
        <input type="text" name="email" id="email" value="{{ @old('email') }}">
        @error('email')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="">
        <label for="password">password</label><br>
        <input type="password" name="password" id="email" value="{{ @old('password') }}">
        @error('password')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="">
        <label for="no_hp">no_hp</label><br>
        <input type="text" name="no_hp" id="no_hp" value="{{ @old('no_hp') }}">
        @error('no_hp')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="">
        <label for="status">status</label><br>
        <input type="text" name="status" id="status" value="{{ @old('status') }}">
        @error('status')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <button type="submit" class="btn btn-success">Kirim Data</button>
</form>
