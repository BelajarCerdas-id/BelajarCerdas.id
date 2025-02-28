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
                                    <label class="mb-2 text-md">Provinsi</label>
                                    <select id="provinsi" name="provinsi"
                                        class="bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer {{ $errors->has('provinsi') ? 'border-[1px] border-red-400 shadow-[0_0_10px_0_red]' : '' }}">
                                        <option value="" class="hidden">Pilih Provinsi</option>
                                    </select>
                                    @error('provinsi')
                                        <span class="text-red-500 font-bold text-sm pl-2 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-span-12 md:col-span-6 flex flex-col">
                                    <label class="mb-2 text-md">Kabupaten / Kota</label>
                                    <select id="kabupaten" name="kab_kota"
                                        class="bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer {{ $errors->has('kab_kota') ? 'border-[1px] border-red-400 shadow-[0_0_10px_0_red]' : '' }}">
                                        <option value="" class="hidden">Pilih Kabupaten / Kota</option>
                                    </select>
                                    @error('kab_kota')
                                        <span class="text-red-500 font-bold text-sm pl-2 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-8 mb-10">
                                <div class="col-span-12 md:col-span-6 flex flex-col">
                                    <label class="mb-2 text-md">Kecamatan</label>
                                    <select id="kecamatan" name="kecamatan"
                                        class="col-span-12 bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer {{ $errors->has('kecamatan') ? 'border-[1px] border-red-400 shadow-[0_0_10px_0_red]' : '' }}">
                                        <option value="" class="hidden">Pilih Kecamatan</option>
                                    </select>
                                    @error('kecamatan')
                                        <span class="text-red-500 font-bold text-sm pl-2 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-span-12 md:col-span-6 flex flex-col">
                                    <label class="mb-2 text-md">Jenjang</label>
                                    <select id="jenjang_sekolah" name="jenjang_sekolah"
                                        class="col-span-12 bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer {{ $errors->has('jenjang_sekolah') ? 'border-[1px] border-red-400 shadow-[0_0_10px_0_red]' : '' }}">
                                        <option value="" class="hidden">Pilih Jenjang</option>
                                        <option value="SD">SD</option>
                                        <option value="SMP">SMP</option>
                                        <option value="SMA">SMA</option>
                                        <option value="SMK">SMK</option>
                                    </select>
                                    @error('jenjang_sekolah')
                                        <span class="text-red-500 font-bold text-sm pl-2 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-8">
                                <div class="col-span-12 md:col-span-6 flex flex-col">
                                    <label class="mb-2 text-md">Sekolah</label>
                                    {{-- <select id="sekolah"
                                        class="bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer">
                                        <option value="">Pilih sekolah</option>
                                    </select> --}}
                                    <input type="text" name="sekolah"
                                        class="col-span-6 bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer {{ $errors->has('sekolah') ? 'border-[1px] border-red-400 shadow-[0_0_10px_0_red]' : '' }}"
                                        placeholder="Masukkan Nama Sekolah">
                                    @error('sekolah')
                                        <span class="text-red-500 font-bold text-sm pl-2 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-span-12 md:col-span-6 flex flex-col">
                                    <label class="mb-2 text-md">Status Sekolah</label>
                                    <select name="status_sekolah"
                                        class="col-span-12 bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer {{ $errors->has('status_sekolah') ? 'border-[1px] border-red-400 shadow-[0_0_10px_0_red]' : '' }}">
                                        <option value="" class="hidden">Pilih Status Sekolah</option>
                                        <option value="B2B">B2B</option>
                                        <option value="B2G">B2G</option>
                                    </select>
                                    @error('jenjang_sekolah')
                                        <span class="text-red-500 font-bold text-sm pl-2 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <header class="text-lg mb-4 col-span-12 mt-10">Jadwal Kunjungan</header>
                            <div class="grid grid-cols-12 gap-8">
                                <div class="col-span-12 md:col-span-6 flex flex-col">
                                    <label for="">Tanggal Mulai</label>
                                    <div
                                        class="relative focus-within:border-[1px] focus-within:border-[dodgerblue] focus-within:shadow-[0_0_9px_0_dodgerblue] {{ $errors->has('tanggal_mulai') ? 'border-[1px] border-red-400 shadow-[0_0_10px_0_red]' : '' }}">
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
                                    <label for="">Tanggal Akhir</label>
                                    <div
                                        class="relative focus-within:border-[1px] focus-within:border-[dodgerblue] focus-within:shadow-[0_0_9px_0_dodgerblue] {{ $errors->has('tanggal_akhir') ? 'border-[1px] border-red-400 shadow-[0_0_10px_0_red]' : '' }}">
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
        altFormat: "F j, Y",
        theme: "dark" // Tambahkan tema jika perlu
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const provinsiSelect = document.getElementById("provinsi");
        const kabupatenSelect = document.getElementById("kabupaten");
        const kecamatanSelect = document.getElementById("kecamatan");
        // const sekolahSelect = document.getElementById("sekolah");

        // 🔹 Ambil data provinsi dari API Emsifa
        fetch("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json")
            .then(res => res.json())
            .then(data => {
                data.forEach(prov => {
                    let option = new Option(prov.name, prov
                        .name); // Gunakan prov.nama sebagai value
                    option.setAttribute('data-id', prov.id) // Simpan ID sebagai atribut data
                    provinsiSelect.add(option);
                });
            });

        // 🔹 Ambil data kabupaten berdasarkan provinsi
        provinsiSelect.addEventListener("change", function() {
            // let provinsiNama = this.value;  Nama provinsi dipilih
            let provID = this.options[this.selectedIndex].getAttribute(
                'data-id'); // Ambil ID dari atribut data
            kabupatenSelect.innerHTML = "<option value=''>Pilih Kabupaten/Kota</option>";
            kecamatanSelect.innerHTML = "<option value=''>Pilih Kecamatan</option>";
            // sekolahSelect.innerHTML = "<option value=''>Pilih Sekolah</option>";

            if (provID) {
                fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provID}.json`)
                    .then(res => res.json())
                    .then(data => {
                        data.forEach(kab => {
                            let option = new Option(kab.name, kab.name);
                            option.setAttribute('data-id', kab.id)
                            kabupatenSelect.add(option);
                        });
                    });
            }
        });

        // 🔹 Ambil data kecamatan berdasarkan kabupaten
        kabupatenSelect.addEventListener("change", function() {
            // let kabID = this.value; nama kabupaten dipilih
            let kabID = this.options[this.selectedIndex].getAttribute('data-id')
            kecamatanSelect.innerHTML = "<option value=''>Pilih Kecamatan</option>";
            // sekolahSelect.innerHTML = "<option value=''>Pilih Sekolah</option>";

            if (kabID) {
                fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${kabID}.json`)
                    .then(res => res.json())
                    .then(data => {
                        data.forEach(kec => {
                            let option = new Option(kec.name, kec.name);
                            kecamatanSelect.add(option);
                        });
                    });
            }
        });

        // 🔹 Ambil data sekolah berdasarkan kecamatan
        // kecamatanSelect.addEventListener("change", function() {
        //     let kecamatanKode = this.value;
        //     $.getJSON(`https://referensi.data.kemdikbud.go.id/index.php?kode=${kecamatanKode}&level=3`)
        //         .then(res => res.json())
        //         .then(data => {
        //             sekolahSelect.innerHTML = '<option value="">Pilih Sekolah</option>';
        //             data.forEach(sekolah => {
        //                 let option = document.createElement("option");
        //                 option.value = sekolah.npsn;
        //                 option.textContent = sekolah.name;
        //                 sekolahSelect.appendChild(option);
        //             });
        //         });
        // });
    });
</script>
