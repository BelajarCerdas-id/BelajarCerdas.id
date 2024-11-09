<x-layout></x-layout>
<form action="{{ route('crud.update', $view->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="flex mb-4 gap-2">
        <div class="flex flex-col w-full">
            <label class="mb-2" for="nama_lengkap">Nama</label>
            <input type="text"
                class="w-full border-2 rounded-lg outline-cyan-400 {{ $errors->has('nama_lengkap') ? 'border-rose-300' : 'border-gray-300' }}"
                name="nama_lengkap" id="nama_lengkap" value="{{ $view->nama_lengkap }}">
            @error('nama_lengkap')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div class="flex flex-col w-full">
            <label class="mb-2" for="email">email</label>
            <input type="text"
                class="w-full border-2 rounded-lg outline-cyan-400 {{ $errors->has('email') ? 'border-rose-300' : 'border-gray-300' }}"
                name="email" id="email" value="{{ $view->email }}">
            @error('email')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="flex mb-4 gap-2">
        <div class="flex flex-col w-full">
            <label class="mb-2" for="sekolah">Sekolah</label>
            <input type="text"
                class="border-2 rounded-lg outline-cyan-400 {{ $errors->has('sekolah') ? 'border-rose-300' : 'border-gray-300' }}"
                name="sekolah" id="sekolah" value="{{ $view->sekolah }}">
            @error('sekolah')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div class="flex flex-col w-full">
            <label class="mb-2" for="password">password</label>
            <input type="text"
                class="border-2 rounded-lg outline-cyan-400 {{ $errors->has('password') ? 'border-rose-300' : 'border-gray-300' }}"
                name="password" id="email" value="{{ $view->password }}">
            @error('password')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="flex mb-4 gap-2">
        <div class="flex flex-col w-full">
            <label class="mb-2" for="fase">Fase</label>
            <input type="text"
                class="border-2 rounded-lg outline-cyan-400 {{ $errors->has('fase') ? 'border-rose-300' : 'border-gray-300' }}"
                name="fase" id="fase" value="{{ $view->fase }}">
            @error('fase')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div class="flex flex-col w-full">
            <label class="mb-2" for="no_hp">no_hp</label>
            <input type="text"
                class="border-2 rounded-lg outline-cyan-400 {{ $errors->has('no_hp') ? 'border-rose-300' : 'border-gray-300' }}"
                name="no_hp" id="no_hp" value="{{ $view->no_hp }}">
            @error('no_hp')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="flex mb-4 gap-2">
        <div class="flex flex-col w-full">
            <label class="mb-2" for="kelas">kelas</label>
            <input type="text"
                class="border-2 rounded-lg outline-cyan-400 {{ $errors->has('kelas') ? 'border-rose-300' : 'border-gray-300' }}"
                name="kelas" id="kelas" value="{{ $view->kelas }}">
            @error('kelas')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div class="flex flex-col w-full">
            <label class="mb-2" for="status">status</label>
            <input type="text"
                class="border-2 rounded-lg outline-cyan-400 {{ $errors->has('status') ? 'border-rose-300' : 'border-gray-300' }}"
                name="status" id="status" value="{{ $view->status }}">
            @error('status')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <button type="submit" class="btn btn-success">Kirim Data</button>
</form>
