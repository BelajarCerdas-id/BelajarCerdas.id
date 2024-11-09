<x-layout></x-layout>
<form action="{{ route('users.store') }}" method="POST">
    <div class="">
        <label for="nama_lengkap">Nama</label><br>
        <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ @old('nama_lengkap') }}">
        @error('nama_lengkap')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="">
        <label for="sekolah">Sekolah</label><br>
        <input type="text" name="sekolah" id="sekolah" value="{{ @old('sekolah') }}">
    </div>
    <div class="">
        <label for="fase">Fase</label><br>
        <input type="text" name="fase" id="fase" value="{{ @old('fase') }}">
    </div>
    <div class="">
        <label for="kelas">Kelas</label><br>
        <input type="text" name="kelas" id="kelas" value="{{ @old('kelas') }}">
    </div>
    <div class="">
        <label for="email">Email</label><br>
        <input type="text" name="email" id="email" value="{{ @old('email') }}">
    </div>
    <div class="">
        <label for="password">password</label><br>
        <input type="text" name="password" id="password" value="{{ @old('password') }}">
    </div>
    <div class="">
        <label for="no_hp">No_hp</label><br>
        <input type="tel" name="no_hp" id="no_hp" value="{{ @old('no_hp') }}">
    </div>
    <div class="">
        <label for="status">Status</label><br>
        <input type="text" name="status" id="status" value="{{ @old('status') }}">
    </div>
    <button type="submit" class="btn btn-success">Kirim Data</button>
</form>
