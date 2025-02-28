@include('components/sidebar_beranda')
@extends('components/sidebar_beranda_mobile')

@if (isset($user))
    @if ($user->status === 'Admin Sales')
        <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
            <div class="content-beranda">
                <main>
                    <section class="bg-white shadow-lg p-6 rounded-lg border-gray-200 border-[1px]">
                        <form action="{{ route('inputDataSekolah.store') }}" method="POST">
                            @csrf
                            <!---- data sekolah ------->
                            <header class="text-lg mb-4">DATA SEKOLAH</header>
                            <!---- Provinsi & Kabupaten / Kota ------->
                            <div class="grid grid-cols-12 gap-8 mb-10">
                                <div class="col-span-12 md:col-span-6 flex flex-col">
                                    <label class="mb-2 text-md">
                                        Provinsi
                                        <sup class="text-red-500">&#42;</sup>
                                    </label>
                                    <select id="provinsi" name="provinsi"
                                        class="bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer {{ $errors->has('provinsi') ? 'border-[1px] border-red-400 shadow-[0_0_10px_0_red]' : '' }}">
                                        <option value="" class="hidden">Pilih Provinsi</option>
                                    </select>
                                    @error('provinsi')
                                        <span class="text-red-500 font-bold text-sm pl-2 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-span-12 md:col-span-6 flex flex-col">
                                    <label class="mb-2 text-md">
                                        Kabupaten / Kota
                                        <sup class="text-red-500">&#42;</sup>
                                    </label>
                                    <select id="kabupaten" name="kab_kota"
                                        class="bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer {{ $errors->has('kab_kota') ? 'border-[1px] border-red-400 shadow-[0_0_10px_0_red]' : '' }}">
                                        <option value="" class="hidden">Pilih Kabupaten / Kota</option>
                                    </select>
                                    @error('kab_kota')
                                        <span class="text-red-500 font-bold text-sm pl-2 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <!---- Kecamatan & jenjang sekolah ------->
                            <div class="grid grid-cols-12 gap-8 mb-10">
                                <div class="col-span-12 md:col-span-6 flex flex-col">
                                    <label class="mb-2 text-md">
                                        Kecamatan
                                        <sup class="text-red-500">&#42;</sup>
                                    </label>
                                    <select id="kecamatan" name="kecamatan"
                                        class="col-span-12 bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer {{ $errors->has('kecamatan') ? 'border-[1px] border-red-400 shadow-[0_0_10px_0_red]' : '' }}">
                                        <option value="" class="hidden">Pilih Kecamatan</option>
                                    </select>
                                    @error('kecamatan')
                                        <span class="text-red-500 font-bold text-sm pl-2 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-span-12 md:col-span-6 flex flex-col">
                                    <label class="mb-2 text-md">
                                        Jenjang
                                        <sup class="text-red-500">&#42;</sup>
                                    </label>
                                    <select id="jenjang" name="jenjang_sekolah"
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
                            <!---- Nama sekolah dan Alamat sekolah ------->
                            <div class="grid grid-cols-12 gap-8">
                                <div class="col-span-12 md:col-span-6 flex flex-col">
                                    <label class="mb-2 text-md">
                                        Sekolah
                                        <sup class="text-red-500">&#42;</sup>
                                    </label>
                                    {{-- <select id="sekolah"
                                        class="col-span-12 bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer">
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
                                    <label class="mb-2 text-md">
                                        Alamat Sekolah
                                        <sup class="text-red-500">&#42;</sup>
                                    </label>
                                    <input type="text" name="alamat_sekolah"
                                        class="col-span-6 bg-white shadow-lg h-12 text-sm border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer {{ $errors->has('alamat_sekolah') ? 'border-[1px] border-red-400 shadow-[0_0_10px_0_red]' : '' }}"
                                        placeholder="Ex. (Jl. Raya Bantar Gebang - Setu No.122, RT.001/RW.007, Padurenan, Kec. Mustika Jaya, Kota Bks, Jawa Barat 16340).">
                                    @error('alamat_sekolah')
                                        <span class="text-red-500 font-bold text-sm pl-2 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <!---- Status sekolah ------->
                            <div class="w-full md:w-[48.5%] flex flex-col mt-8">
                                <label class="mb-2 text-md">
                                    Status Sekolah
                                    <sup class="text-red-500">&#42;</sup>
                                </label>
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
                            <!---- Data pimpinan sekolah ------->
                            <header class="text-lg mb-4 col-span-12 mt-10">DATA PIMPINAN SEKOLAH</header>
                            <!---- Nama kepsek & NIP kepsek ------->
                            <div class="grid grid-cols-12 gap-8">
                                <div class="col-span-12 md:col-span-6 flex flex-col">
                                    <label class="mb-2 text-md">
                                        Nama Kepala Sekolah
                                        <sup class="text-red-500">&#42;</sup>
                                    </label>
                                    <input type="text" name="nama_kepsek"
                                        class="col-span-6 bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer {{ $errors->has('nama_kepsek') ? 'border-[1px] border-red-400 shadow-[0_0_10px_0_red]' : '' }}"
                                        placeholder="Masukkan Nama Kepala Sekolah">
                                    @error('nama_kepsek')
                                        <span class="text-red-500 font-bold text-sm pl-2 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-span-12 md:col-span-6 flex flex-col">
                                    <label class="mb-2 text-md">
                                        NIP
                                        <sup class="text-red-500">&#42;</sup>
                                    </label>
                                    <input type="text" name="nip_kepsek"
                                        class="col-span-6 bg-white shadow-lg h-12 text-sm border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer {{ $errors->has('nip_kepsek') ? 'border-[1px] border-red-400 shadow-[0_0_10px_0_red]' : '' }}"
                                        placeholder="Ex. (Jl. Raya Bantar Gebang - Setu No.122, RT.001/RW.007, Padurenan, Kec. Mustika Jaya, Kota Bks, Jawa Barat 16340).">
                                    @error('nip_kepsek')
                                        <span class="text-red-500 font-bold text-sm pl-2 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <!---- Paket kerjasama sekolah ------->
                            <header class="text-lg mb-4 col-span-12 mt-10">PAKET KERJASAMA</header>
                            <div id="paket-container" class="grid grid-cols-12 gap-8">
                                @foreach (old('paket_kerjasama', ['']) as $index => $kerjasama)
                                    <div class="paket col-span-12 md:col-span-6 flex flex-col">
                                        <label class="text-md mb-2">
                                            <span>Tipe Paket</span>
                                            <span
                                                class="paket-label">{{ count(old('paket_kerjasama', [''])) > 1 ? $index + 1 : '' }}</span>
                                            <sup class="text-red-500">&#42;</sup>
                                        </label>
                                        <select name="paket_kerjasama[]"
                                            class="bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer {{ $errors->has('paket_kerjasama.' . $index) ? 'border-[1px] border-red-400 shadow-[0_0_10px_0_red]' : '' }}">
                                            <option value="" class="hidden">Pilih Paket</option>
                                            <option value="englishZone"
                                                {{ old('paket_kerjasama.' . $index) == 'englishZone' ? 'selected' : '' }}>
                                                English Zone</option>
                                        </select>
                                        @error('paket_kerjasama.' . $index)
                                            <span
                                                class="text-red-500 font-bold text-sm pl-2 mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                            <!---- Button tambah paket ------->
                            <button id="tambah-paket" type="button"
                                class="bg-[#4189e0] hover:bg-blue-500 w-[200px] h-8 text-white font-bold rounded-lg my-8 text-sm">
                                <i class="fas fa-plus"></i>
                                <span>Tambah Paket</span>
                            </button>
                            <!---- Button submit form ------->
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
        <div class="flex flex-col min-h-screen items-center justify-center">
            <p>ALERT SEMENTARA</p>
            <p>You do not have access to this pages.</p>
        </div>
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
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('paket-container');
        const tambahPaketButton = document.getElementById('tambah-paket');

        // Fungsi untuk memperbarui label dan ID
        function updatePaketLabels() {
            const paketElements = container.querySelectorAll('.paket'); // Semua elemen 'materi'
            paketElements.forEach((paket, index) => {
                const labels = paket.querySelectorAll('.paket-label');
                labels.forEach(label => {
                    label.textContent = paketElements.length > 1 ? index + 1 :
                        ''; // Perbarui nomor urut
                });
                paket.setAttribute('data-id', index + 1); // Update ID berdasarkan urutan
            });
        }

        tambahPaketButton.addEventListener('click', function() {
            const newPaket = document.createElement('div');
            newPaket.classList.add('paket', 'col-span-12',
                'md:col-span-6'); // Tambahkan class 'paket'
            newPaket.innerHTML = `
            <div class="col-span-6 flex flex-col relative">
                <label class="mb-2">
                    <span class="text-md">Tipe Paket</span>
                    <span class="paket-label"></span>
                    <sup class="text-red-500">&#42;</sup>
                </label>
                <select name="paket_kerjasama[]" class="bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer">
                    <option value="" class="hidden">Pilih Paket</option>
                    <option value="englishZone">English Zone</option>
                </select>
                <button type="button" class="hapus-paket absolute top-0 right-2 text-red-500 font-bold">
                    <i class="fa-solid fa-trash"></i>
                    <span class="text-sm">Hapus</span>
                </button>
            </div>`;
            container.appendChild(newPaket);
            updatePaketLabels(); // Perbarui label setelah menambahkan
        });

        container.addEventListener('click', function(event) {
            if (event.target.closest('.hapus-paket')) {
                const paketToDelete = event.target.closest('.paket');
                if (paketToDelete) {
                    paketToDelete.remove();
                    updatePaketLabels();
                }
            }
        });

        updatePaketLabels();
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const provinsiSelect = document.getElementById("provinsi");
        const kabupatenSelect = document.getElementById("kabupaten");
        const kecamatanSelect = document.getElementById("kecamatan");
        const sekolahSelect = document.getElementById("sekolah");

        // 🔹 Ambil data provinsi dari API Emsifa
        fetch("https://ibnux.github.io/data-indonesia/provinsi.json")
            .then(res => res.json())
            .then(data => {
                data.forEach(prov => {
                    let option = new Option(prov.nama, prov
                        .nama); // Gunakan prov.nama sebagai value
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
                fetch(`https://ibnux.github.io/data-indonesia/kabupaten/${provID}.json`)
                    .then(res => res.json())
                    .then(data => {
                        data.forEach(kab => {
                            let option = new Option(kab.nama, kab.nama);
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
                fetch(`https://ibnux.github.io/data-indonesia/kecamatan/${kabID}.json`)
                    .then(res => res.json())
                    .then(data => {
                        data.forEach(kec => {
                            let option = new Option(kec.nama, kec.nama);
                            kecamatanSelect.add(option);
                        });
                    });
            }
        });
    });
</script>
