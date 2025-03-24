@include('components/sidebar_beranda')
@extends('components/sidebar_beranda_mobile')

@if (session('user')->status === 'Admin Sales')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            <header class="text-lg mb-4 font-bold">DATA PAKET PKS SEKOLAH</header>
            <main>
                <section>
                    {{-- <div class="flex justify-end my-4">
                        <button onclick="my_modal_2.showModal()"
                            class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all">
                            Tambah Paket
                        </button>
                    </div> --}}
                    @if ($getDataPKS->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="table w-full border-collapse border border-gray-300">
                                <thead class="font-bold">
                                    <tr>
                                        <th class="!text-center border border-gray-300" rowspan="2">
                                            No
                                        </th>
                                        <th class="!text-center border border-gray-300" rowspan="2">
                                            Nama Sekolah
                                        </th>
                                        <th class="!text-center border border-gray-300" rowspan="2">
                                            Paket Kerjasama
                                        </th>
                                        <th class="!text-center border border-gray-300" colspan="2">
                                            Tanggal Kerjasama
                                        </th>
                                        <th class="!text-center border border-gray-300" rowspan="2">
                                            Status Kontrak
                                        </th>
                                        <th class="!text-center border border-gray-300" rowspan="2">
                                            Status PKS
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="!text-center border border-gray-300">
                                            Tanggal Mulai
                                        </th>
                                        <th class="!text-center border border-gray-300">
                                            Tanggal Akhir
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($getDataPKS as $item)
                                        <tr>
                                            <td class="!text-center border border-gray-300">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td class="!text-center border border-gray-300">
                                                {{ $item->sekolah }}
                                            </td>
                                            <td class="!text-center border border-gray-300">
                                                {{ $item->paket_kerjasama }}
                                            </td>
                                            <td class="!text-center border border-gray-300">
                                                @if ($item->status_paket_kerjasama === 'not-active' && $item->tanggal_mulai)
                                                    <span class="text-red-500">
                                                        {{ $item->tanggal_mulai ? $item->tanggal_mulai->locale('id')->translatedFormat('l, d-F-Y') : '-' }}
                                                    </span>
                                                @else
                                                    {{ $item->tanggal_mulai ? $item->tanggal_mulai->locale('id')->translatedFormat('l, d-F-Y') : '-' }}
                                                @endif
                                            </td>

                                            <td class="!text-center border border-gray-300">
                                                @if ($item->status_paket_kerjasama === 'not-active' && $item->tanggal_akhir)
                                                    <span class="text-red-500">
                                                        {{ $item->tanggal_akhir ? $item->tanggal_akhir->locale('id')->translatedFormat('l, d-F-Y') : '-' }}
                                                    </span>
                                                @else
                                                    {{ $item->tanggal_akhir ? $item->tanggal_akhir->locale('id')->translatedFormat('l, d-F-Y') : '-' }}
                                                @endif
                                            </td>
                                            <td class="!text-center border border-gray-300">
                                                {{ $item->status_paket_kerjasama }}
                                            </td>
                                            <td class="!text-center border border-gray-300">
                                                {{ $item->status_pks }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!---- modal untuk update tanggal mulai dan akhir kerjasama ---->
                        <dialog id="my_modal_1" class="modal">
                            <div class="modal-box bg-white w-max h-[620px]">
                                <!---- identitas paket kerjasama ---->
                                <div id="head-text" class="flex flex-col gap-2">
                                    <span class="text-sm font-bold text-gray-600">
                                        Tipe Paket Kerjasama
                                    </span>
                                    <div id="text-paket-kerjasama"
                                        class="bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 flex items-center">
                                    </div>
                                </div>
                                <form action="{{ route('dataPksSekolah.update', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <!---- tanggal mulai paket kerjasama ---->
                                    <div class="w-max relative flex flex-col gap-2 mt-4">
                                        <span class="text-sm font-bold text-gray-600">Tanggal Mulai</span>
                                        <input type="text" id="datepicker" name="tanggal_mulai"
                                            class="w-[385px] bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 cursor-pointer focus-within:border-[1px] focus-within:border-[dodgerblue] focus-within:shadow-[0_0_9px_0_dodgerblue] {{ $errors->has('tanggal_mulai') ? 'border-[1px] border-red-400' : '' }}"
                                            placeholder="dd-mm-yyyy">
                                        <i class="fa-regular fa-calendar absolute top-[60%] right-4"></i>
                                    </div>
                                    <!---- tanggal akhir paket kerjasama ---->
                                    <div class="w-max relative flex flex-col gap-2 mt-4">
                                        <span class="text-sm font-bold text-gray-600">Tanggal Akhir</span>
                                        <input type="text" id="datepicker" name="tanggal_akhir"
                                            class="w-[385px] bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 cursor-pointer focus-within:border-[1px] focus-within:border-[dodgerblue] focus-within:shadow-[0_0_9px_0_dodgerblue] {{ $errors->has('tanggal_mulai') ? 'border-[1px] border-red-400' : '' }}"
                                            placeholder="dd-mm-yyyy">
                                        <i class="fa-regular fa-calendar absolute top-[60%] right-4"></i>
                                    </div>
                                    <!---- button submit ---->
                                    <div class="flex justify-end mt-8">
                                        <button
                                            class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all">
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <form method="dialog" class="modal-backdrop">
                                <button>close</button>
                            </form>
                        </dialog>
                    @else
                        <div class="flex justify-center items-center min-h-[70vh]">
                            Tidak ada paket PKS yang terdaftar
                        </div>
                    @endif
                    <!---- modal untuk menambahkan paket kerjasama (jangan dihapus dulu) ---->
                    {{-- <dialog id="my_modal_2" class="modal">
                        <div class="modal-box bg-white overflow-y-auto">
                            <!---- identitas paket kerjasama ---->
                            @if ($getDataPKS->isNotEmpty())
                                <span class="text-sm font-bold text-gray-600 ">
                                    Paket Kerjasama Terdaftar
                                </span>
                                <div class="grid grid-cols-2 gap-2 mt-4">
                                    <!---- data paket kerjasama terdaftar setiap sekolah---->
                                    @foreach ($getDataPKS as $item)
                                        <div
                                            class="bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 flex items-center">
                                            {{ $item->paket_kerjasama }}
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <!---- add text in here ---->
                            @endif

                            <form action="{{ route('tambahPaketPks.store') }}" method="POST">
                                @csrf
                                <!---- data sekolah ----->
                                <!---- mengecek apakah datanya kosong atau tidak ----->
                                <div class="hidden">
                                    <input type="text" name="provinsi"
                                        value="{{ $groupedDataPKS->provinsi ?? '' }}">
                                    <input type="text" name="kab_kota"
                                        value="{{ $groupedDataPKS->kab_kota ?? '' }}">
                                    <input type="text" name="kecamatan"
                                        value="{{ $groupedDataPKS->kecamatan ?? '' }}">
                                    <input type="text" name="jenjang_sekolah"
                                        value="{{ $groupedDataPKS->jenjang_sekolah ?? '' }}">
                                    <input type="text" name="sekolah" value="{{ $groupedDataPKS->sekolah ?? '' }}">
                                    <input type="text" name="status_sekolah"
                                        value="{{ $groupedDataPKS->status_sekolah ?? '' }}">
                                    <input type="text" name="alamat_sekolah"
                                        value="{{ $groupedDataPKS->alamat_sekolah ?? '' }}">
                                    <input type="text" name="nama_kepsek"
                                        value="{{ $groupedDataPKS->nama_kepsek ?? '' }}">
                                    <input type="text" name="nip_kepsek"
                                        value="{{ $groupedDataPKS->nip_kepsek ?? '' }}">
                                </div>
                                <div class="mt-6">
                                    <span class="text-sm font-bold text-gray-600">
                                        Paket Kerjasama Terbaru
                                    </span>
                                    <div id="paket-container" class="my-8">
                                        @foreach (old('paket_kerjasama', ['']) as $index => $kerjasama)
                                            <div class="paket w-full flex flex-col relative">
                                                <label class="text-md mb-2">
                                                    <span class="text-sm">Tipe Paket</span>
                                                    <span
                                                        class="paket-label text-sm">{{ count(old('paket_kerjasama', [''])) > 1 ? $index + 1 : '' }}</span>
                                                    <sup class="text-red-500">&#42;</sup>
                                                </label>
                                                <select name="paket_kerjasama[]"
                                                    class="bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer {{ $errors->has('paket_kerjasama.' . $index) ? 'border-[1px] border-red-400' : '' }}">
                                                    <option value="" class="hidden">Pilih Paket</option>
                                                    <option value="englishZone"
                                                        {{ old('paket_kerjasama.' . $index) == 'englishZone' ? 'selected' : '' }}>
                                                        English Zone</option>
                                                </select>
                                                @error('paket_kerjasama.' . $index)
                                                    <span
                                                        class="text-red-500 font-bold text-sm pl-2 mt-2">{{ $message }}</span>
                                                @enderror
                                                @if ($index > 0)
                                                    <button type="button"
                                                        class="hapus-paket absolute top-0 right-2 text-red-500 font-bold">
                                                        <i class="fa-solid fa-trash"></i>
                                                        <span class="text-sm">Hapus</span>
                                                    </button>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    <!---- Button tambah paket ------->
                                    <button id="tambah-paket" type="button"
                                        class="bg-[#4189e0] hover:bg-blue-500 w-[200px] h-8 text-white font-bold rounded-lg my-8 text-sm">
                                        <i class="fas fa-plus"></i>
                                        <span>Tambah Paket</span>
                                    </button>

                                    <div class="flex justify-end mt-8">
                                        <button
                                            class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all">
                                            Simpan
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <form method="dialog" class="modal-backdrop">
                            <button>close</button>
                        </form>
                    </dialog> --}}
                </section>
            </main>
        </div>
    </div>
@else
    <p>You do not have access to this pages.</p>
@endif



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
            <div class="mt-4 flex flex-col relative">
                <label class="mb-2">
                    <span class="text-sm">Tipe Paket</span>
                    <span class="paket-label text-sm"></span>
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
