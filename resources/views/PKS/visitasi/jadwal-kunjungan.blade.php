@include('components/sidebar_beranda')
@extends('components/sidebar_beranda_mobile')

@if (isset($user))
    @if ($user->status === 'Sales')
        <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
            <div class="content-beranda">
                <main>
                    <section class="bg-white shadow-lg p-6 rounded-lg border-gray-200 border-[1px]">
                        <form action="{{ route('visitasiData.store') }}" method="POST">
                            @csrf
                            <header class="text-lg mb-4">DATA SEKOLAH</header>
                            <div class="grid grid-cols-12 gap-8 mb-10">
                                <div class="col-span-12 md:col-span-6 flex flex-col">
                                    <label class="mb-2 text-sm">
                                        Provinsi
                                        <sup class="text-red-500">&#42;</sup>
                                    </label>
                                    <select id="provinsi" name="provinsi"
                                        class="bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] {{ $errors->has('provinsi') ? 'border-[1px] border-red-400' : '' }}">
                                        <option value="" class="hidden">Pilih Provinsi</option>
                                    </select>
                                    @error('provinsi')
                                        <span class="text-red-500 font-bold text-sm pl-2 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-span-12 md:col-span-6 flex flex-col">
                                    <label class="mb-2 text-sm">
                                        Kabupaten / Kota
                                        <sup class="text-red-500">&#42;</sup>
                                    </label>
                                    <select id="kabupaten" name="kab_kota"
                                        class="bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] {{ $errors->has('kab_kota') ? 'border-[1px] border-red-400' : '' }}">
                                        <option value="" class="hidden">Pilih Kabupaten / Kota</option>
                                    </select>
                                    @error('kab_kota')
                                        <span class="text-red-500 font-bold text-sm pl-2 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-8 mb-10">
                                <div class="col-span-12 md:col-span-6 flex flex-col">
                                    <label class="mb-2 text-sm">
                                        Kecamatan
                                        <sup class="text-red-500">&#42;</sup>
                                    </label>
                                    <select id="kecamatan" name="kecamatan"
                                        class="col-span-12 bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] {{ $errors->has('kecamatan') ? 'border-[1px] border-red-400' : '' }}">
                                        <option value="" class="hidden">Pilih Kecamatan</option>
                                    </select>
                                    @error('kecamatan')
                                        <span class="text-red-500 font-bold text-sm pl-2 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-span-12 md:col-span-6 flex flex-col">
                                    <label class="mb-2 text-sm">
                                        Jenjang Sekolah
                                        <sup class="text-red-500">&#42;</sup>
                                    </label>
                                    <select id="jenjang_sekolah" name="jenjang_sekolah"
                                        class="col-span-12 bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] {{ $errors->has('jenjang_sekolah') ? 'border-[1px] border-red-400' : '' }}">
                                        <option value="" class="hidden">Pilih Jenjang</option>
                                        <option value="SD" {{ @old('jenjang_sekolah') === 'SD' ? 'selected' : '' }}>
                                            SD
                                        </option>
                                        <option value="SMP"
                                            {{ @old('jenjang_sekolah') === 'SMP' ? 'selected' : '' }}>
                                            SMP
                                        </option>
                                        <option value="SMA"
                                            {{ @old('jenjang_sekolah') === 'SMA' ? 'selected' : '' }}>
                                            SMA
                                        </option>
                                        <option value="SMK"
                                            {{ @old('jenjang_sekolah') === 'SMK' ? 'selected' : '' }}>
                                            SMK
                                        </option>
                                    </select>
                                    @error('jenjang_sekolah')
                                        <span class="text-red-500 font-bold text-sm pl-2 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-8">
                                <div class="col-span-12 md:col-span-6 flex flex-col">
                                    <label class="mb-2 text-sm">
                                        Sekolah
                                        <sup class="text-red-500">&#42;</sup>
                                    </label>
                                    {{-- <select id="sekolah"
                                        class="bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue]">
                                        <option value="">Pilih sekolah</option>
                                    </select> --}}
                                    <input type="text" name="sekolah"
                                        class="col-span-6 bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] {{ $errors->has('sekolah') ? 'border-[1px] border-red-400' : '' }}"
                                        value="{{ @old('sekolah') }}" placeholder="Masukkan Nama Sekolah">
                                    @error('sekolah')
                                        <span class="text-red-500 font-bold text-sm pl-2 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-span-12 md:col-span-6 flex flex-col">
                                    <label class="mb-2 text-sm">
                                        Status Sekolah
                                        <sup class="text-red-500">&#42;</sup>
                                    </label>
                                    <select name="status_sekolah"
                                        class="col-span-12 bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] {{ $errors->has('status_sekolah') ? 'border-[1px] border-red-400' : '' }}">
                                        <option value="" class="hidden">Pilih Status Sekolah</option>
                                        <option value="B2B"
                                            {{ @old('status_sekolah') === 'B2B' ? 'selected' : '' }}>
                                            B2B
                                        </option>
                                        <option value="B2G"
                                            {{ @old('status_sekolah') === 'B2G' ? 'selected' : '' }}>
                                            B2G
                                        </option>
                                    </select>
                                    @error('jenjang_sekolah')
                                        <span class="text-red-500 font-bold text-sm pl-2 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <header class="text-lg mb-4 col-span-12 mt-10">Jadwal Kunjungan</header>
                            <div class="grid grid-cols-12 gap-8">
                                <div class="col-span-12 md:col-span-6 flex flex-col">
                                    <label class="mb-2 text-sm">
                                        Tanggal Mulai
                                        <sup class="text-red-500">&#42;</sup>
                                    </label>
                                    <div
                                        class="relative focus-within:border-[1px] focus-within:border-[dodgerblue] focus-within:shadow-[0_0_9px_0_dodgerblue] {{ $errors->has('tanggal_mulai') ? 'border-[1px] border-red-400' : '' }}">
                                        <input type="text" id="datepicker" name="tanggal_mulai"
                                            class="w-full bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 cursor-pointer"
                                            placeholder="dd-mm-yyyy">
                                        <i class="fa-regular fa-calendar absolute top-[32%] right-4"></i>
                                    </div>
                                    @error('tanggal_mulai')
                                        <span class="text-red-500 font-bold text-sm pl-2 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-span-12 md:col-span-6 flex flex-col">
                                    <label class="mb-2 text-sm">
                                        Tanggal Akhir
                                        <sup class="text-red-500">&#42;</sup>
                                    </label>
                                    <div
                                        class="relative focus-within:border-[1px] focus-within:border-[dodgerblue] focus-within:shadow-[0_0_9px_0_dodgerblue] {{ $errors->has('tanggal_akhir') ? 'border-[1px] border-red-400' : '' }}">
                                        <input type="text" id="datepicker" name="tanggal_akhir"
                                            class="w-full bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 cursor-pointer"
                                            placeholder="dd-mm-yyyy">
                                        <i class="fa-regular fa-calendar absolute top-[32%] right-4"></i>
                                    </div>
                                    @error('tanggal_akhir')
                                        <span class="text-red-500 font-bold text-sm pl-2 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="flex justify-end mt-8">
                                <button
                                    class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all">
                                    Kirim
                                </button>
                            </div>
                        </form>
                    </section>
                </main>
            </div>
        </div>
    @else
        <div class="flex flex-col min-h-screen items-center justify-center"></div>
        <p>ALERT SEMENTARA</p>
        <p>You do not have access to this pages.</p>
    @endif
@else
    <span>You are not logged in</span>
@endif

<script>
    flatpickr("#datepicker", {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "j F, Y",
        theme: "dark", // Tambahkan tema jika perlu
        minDate: 'today',
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const provinsiSelect = document.getElementById("provinsi");
        const kabupatenSelect = document.getElementById("kabupaten");
        const kecamatanSelect = document.getElementById("kecamatan");
        const sekolahSelect = document.getElementById("sekolah");

        var oldProvinsi = "{{ old('provinsi') }}";
        var oldKabKota = "{{ old('kab_kota') }}";
        var oldKecamatan = "{{ old('kecamatan') }}";
        var oldSekolah = "{{ old('sekolah') }}";

        // 🔹 Ambil data provinsi dari API Emsifa
        fetch("https://ibnux.github.io/data-indonesia/provinsi.json")
            .then(res => res.json())
            .then(data => {
                data.forEach(prov => {
                    let option = new Option(prov.nama, prov
                        .nama); // Gunakan prov.nama sebagai value
                    option.setAttribute('data-id', prov.id) // Simpan ID sebagai atribut data

                    // 🔹 Tandai opsi yang sesuai dengan oldProvinsi
                    if (oldProvinsi === prov.nama) {
                        option.selected = true;
                    }

                    provinsiSelect.add(option);
                });
                // 🔹 Jika ada oldProvinsi, panggil event change agar oldKabKota bisa dimuat
                if (oldProvinsi) {
                    provinsiSelect.dispatchEvent(new Event('change'));
                }
            });

        // 🔹 Ambil data kabupaten berdasarkan provinsi
        provinsiSelect.addEventListener("change", function() {
            // let provinsiNama = this.value;  Nama provinsi dipilih
            let provID = this.options[this.selectedIndex].getAttribute(
                'data-id'); // Ambil ID dari atribut data

            if (provID) {
                fetch(`https://ibnux.github.io/data-indonesia/kabupaten/${provID}.json`)
                    .then(res => res.json())
                    .then(data => {
                        data.forEach(kab => {
                            let option = new Option(kab.nama, kab.nama);
                            option.setAttribute('data-id', kab.id)

                            // 🔹 Tandai opsi yang sesuai dengan oldKabKota
                            if (oldKabKota === kab.nama) {
                                option.selected = true;
                            }

                            kabupatenSelect.add(option);
                        });

                        // 🔹 Jika ada oldKabKota, panggil event change untuk memuat kecamatan
                        if (oldKabKota) {
                            kabupatenSelect.dispatchEvent(new Event('change'));
                        }
                    });
            }
        });

        // 🔹 Ambil data kecamatan berdasarkan kabupaten
        kabupatenSelect.addEventListener("change", function() {
            // let kabID = this.value; nama kabupaten dipilih
            let kabID = this.options[this.selectedIndex].getAttribute('data-id')

            if (kabID) {
                fetch(`https://ibnux.github.io/data-indonesia/kecamatan/${kabID}.json`)
                    .then(res => res.json())
                    .then(data => {
                        data.forEach(kec => {
                            let option = new Option(kec.nama, kec.nama);

                            // 🔹 Tandai opsi yang sesuai dengan oldKecamatan
                            if (oldKecamatan === kec.nama) {
                                option.selected = true;
                            }

                            kecamatanSelect.add(option);
                        });

                        // 🔹 Jika ada oldKecamatan, panggil event change untuk memuat desa / sekolah
                        if (oldKecamatan) {
                            kecamatanSelect.dispatchEvent(new Event('change'));
                        }
                    });
            }
        });
    });
</script>
