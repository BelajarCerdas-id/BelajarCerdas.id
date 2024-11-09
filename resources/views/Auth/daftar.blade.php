<x-script></x-script>
<form action="/daftar" method="POST">
    @csrf
    <div class="flex justify-center items-center min-h-screen max-w-full">
        <div class="max-w-auto h-[550px] flex border-[1px] border-gray-200 rounded-xl overflow-hidden">
            <div class="w-2/4">
                <img src="/image/image-auth.png" alt="" class="w-full h-full">
            </div>
            <div class="w-2/4">
                <header class="flex justify-center text-slate-600 font-bold my-4">SELAMAT DATANG</header>
                <div class="flex mx-4 gap-4">
                    <input type="hidden" name="kode">
                    <div class="flex flex-col w-full mb-4">
                        <label for="nama_lengkap" class="mb-2 text-sm">Nama Lengkap<sup
                                class="text-red-500 pl-1">&#42;</sup></label>
                        <input type="text"
                            class="w-full bg-gray-100 outline-none rounded-xl text-xs p-2 {{ $errors->has('nama_lengkap') ? 'border-[1px] border-red-500' : 'border-gray-300' }}"
                            name="nama_lengkap" placeholder="Masukkan Nama" value="{{ @old('nama_lengkap') }}">
                        @error('nama_lengkap')
                            <span class="text-red-500 font-bold text-xs pt-2">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex flex-col w-full mb-4">
                        <label for="email" class="mb-2 text-sm">Email<sup
                                class="text-red-500 pl-1">&#42;</sup></label>
                        <input type="text"
                            class="w-full bg-gray-100 outline-none rounded-xl text-xs p-2 {{ $errors->has('email') ? 'border-[1px] border-red-500' : 'border-gray-300' }}"
                            name="email" placeholder="Masukkan Email" value="{{ @old('email') }}">
                        @error('email')
                            <span class="text-red-500 font-bold text-xs pt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="flex mx-4 gap-4">
                    <div class="flex flex-col w-full mb-4">
                        <label for="sekolah" class="mb-2 text-sm">Sekolah<sup
                                class="text-red-500 pl-1">&#42;</sup></label>
                        <input type="text"
                            class="w-full bg-gray-100 outline-none rounded-xl text-xs p-2 {{ $errors->has('sekolah') ? 'border-[1px] border-red-500' : 'border-gray-300' }}"
                            name="sekolah" placeholder="Masukkan Sekolah" value="{{ @old('sekolah') }}">
                        @error('sekolah')
                            <span class="text-red-500 font-bold text-xs pt-2">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex flex-col w-full mb-4">
                        <label for="password" class="mb-2 text-sm">Password<sup
                                class="text-red-500 pl-1">&#42;</sup></label>
                        <input type="password"
                            class="w-full bg-gray-100 outline-none rounded-xl text-xs p-2 {{ $errors->has('password') ? 'border-[1px] border-red-500' : 'border-gray-300' }}"
                            name="password" placeholder="Masukkan Password" value="{{ @old('password') }}">
                        @error('password')
                            <span class="text-red-500 font-bold text-xs pt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="flex mx-4 gap-4">
                    <div class="flex flex-col w-full mb-4">
                        <label class="mb-2 text-sm">Fase<sup class="text-red-500 pl-1">&#42;</sup></label>
                        <select name="fase" id="id_fase"
                            class="form-select w-full bg-gray-100 outline-none rounded-xl text-xs p-2 cursor-pointer {{ $errors->has('fase') ? 'border-[1px] border-red-500' : 'border-gray-300' }}"
                            aria-label="Default select example">
                            <option value="" class="hidden">Pilih Fase</option>
                            @foreach ($fase as $item)
                                {{-- <option value="{{ $item->kode }}">{{ $item->fase }}</option> --}}
                                {{-- cegah refresh selected after validation  --}}
                                <option value="{{ $item->kode }}"
                                    {{ old('fase') == $item->kode ? 'selected' : '' }}>
                                    {{ $item->fase }}</option>
                            @endforeach
                        </select>
                        @error('fase')
                            <span class="text-red-500 font-bold text-xs pt-2">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex flex-col w-full mb-4">
                        <label for="no_hp" class="mb-2 text-sm">No.hp<sup
                                class="text-red-500 pl-1">&#42;</sup></label>
                        <input type="tel"
                            class="w-full bg-gray-100 outline-none rounded-xl text-xs p-2 {{ $errors->has('no_hp') ? 'border-[1px] border-red-500' : 'border-gray-300' }}"
                            name="no_hp" placeholder="Masukkan No.HP" value="{{ @old('no_hp') }}">
                        @error('no_hp')
                            <span class="text-red-500 font-bold text-xs pt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="flex mx-4 gap-4">
                    <div class="flex flex-col w-full mb-4">
                        <label class="mb-2 text-sm">Kelas<sup class="text-red-500 pl-1">&#42;</sup></label>
                        <select name="kelas" id="id_kelas"
                            class="form-select w-full bg-gray-100 outline-none rounded-xl text-xs p-2 cursor-pointer {{ $errors->has('kelas') ? 'border-[1px] border-red-500' : 'border-gray-300' }}">
                            <option value="" class="hidden">Pilih Kelas</option>
                        </select>
                        @error('kelas')
                            <span class="text-red-500 font-bold text-xs pt-2">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex flex-col w-full mb-4">
                        <label for="status" class="mb-2 text-sm">Status<sup
                                class="text-red-500 pl-1">&#42;</sup></label>
                        <select name="status"
                            class="form-select w-full bg-gray-100 outline-none rounded-xl text-xs p-2 cursor-pointer {{ $errors->has('status') ? 'border-[1px] border-red-500' : 'border-gray-300' }}">
                            <option value="" class="hidden">Pilih Status</option>
                            <option value="Siswa" {{ @old('status') === 'Siswa' ? 'selected' : '' }}>Siswa</option>
                            <option value="Guru" {{ @old('status') === 'Guru' ? 'selected' : '' }}>Guru</option>
                        </select>
                        @error('status')
                            <span class="text-red-500 font-bold text-xs pt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <button
                    class="bg-gray-700 w-[93%] rounded-full h-[40px] text-white font-bold text-sm mx-4 mt-4">Daftar</button>
            </div>
        </div>
    </div>
</form>

<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

<script>
    $(document).ready(function() {
        var oldKelas = "{{ old('kelas') }}"; // Ambil kelas yang dipilih jika ada

        $('#id_fase').on('change', function() {
            var kode_fase = $(this).val();
            if (kode_fase) {
                $.ajax({
                    url: '/kelas/' + kode_fase,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#id_kelas').empty();
                        $('#id_kelas').append(
                            '<option value="" class="hidden">Pilih Kelas</option>'
                        );
                        $.each(data, function(key, kelas) {
                            $('#id_kelas').append(
                                '<option value="' + kelas.kelas + '"' +
                                (oldKelas == kelas.kelas ? ' selected' : '') +
                                '>' +
                                kelas.kelas + '</option>'
                            );
                        });
                    }
                });
            } else {
                $('#id_kelas').empty();
            }
        });

        // Trigger change event on page load to set the kelas options based on old fase value
        if ("{{ old('fase') }}") {
            $('#id_fase').val("{{ old('fase') }}").trigger('change');
        }
    });
</script>


<script>
    $(document).ready(function() {
        var oldMapel = "{{ old('mapel') }}"; // Ambil kelas yang dipilih jika ada

        $('#id_kelas').on('change', function() {
            var kode_kelas = $(this).val();
            if (kode_kelas) {
                $.ajax({
                    url: '/mapel/' + kode_kelas,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#id_mapel').empty();
                        $('#id_mapel').append(
                            '<option value="" class="hidden">Pilih Mata Pelajran----</option>'
                        );
                        $.each(data, function(key, mapel) {
                            $('#id_mapel').append(
                                '<option value="' + mapel.mapel + '"' +
                                (oldMapel == mapel.mapel ? ' selected' : '') +
                                '>' +
                                mapel.mapel + '</option>'
                            );
                        });
                    }
                });
            } else {
                $('#id_mapel').empty();
            }
        });
    });
</script>
