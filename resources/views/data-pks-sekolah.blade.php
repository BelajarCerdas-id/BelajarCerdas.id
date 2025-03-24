@include('components/sidebar_beranda')
@extends('components/sidebar_beranda_mobile')
@if (session('user')->status === 'Sales')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            <main>
                <section>
                    @if ($getDataPKS->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="table w-full border-collapse border border-gray-300">
                                <thead class="font-bold">
                                    <tr>
                                        <th class="!text-center border border-gray-300">
                                            No
                                        </th>
                                        <th class="!text-center border border-gray-300">
                                            Provinsi
                                        </th>
                                        <th class="!text-center border border-gray-300">
                                            Kabupaten / Kota
                                        </th>
                                        <th class="!text-center border border-gray-300">
                                            Kecamatan
                                        </th>
                                        <th class="!text-center border border-gray-300">
                                            Nama Sekolah
                                        </th>
                                        <th class="!text-center border border-gray-300">
                                            Status Sekolah
                                        </th>
                                        <th class="!text-center border border-gray-300">
                                            Preview
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($groupedDataPKS as $item)
                                        <td class="!text-center border border-gray-300">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="!text-center border border-gray-300">
                                            {{ $item->provinsi }}
                                        </td>
                                        <td class="!text-center border border-gray-300">
                                            {{ $item->kab_kota }}
                                        </td>
                                        <td class="!text-center border border-gray-300">
                                            {{ $item->kecamatan }}
                                        </td>
                                        <td class="!text-center border border-gray-300">
                                            {{ $item->sekolah }}
                                        </td>
                                        <td class="!text-center border border-gray-300">
                                            {{ $item->status_sekolah }}
                                        </td>
                                        <td class="!text-center text-[#4189e0] font-bold border border-gray-300">
                                            <a href="{{ route('data-pks-sekolah.edit', $item->sekolah) }}">
                                                <button>
                                                    Lihat
                                                    <i class="fas fa-chevron-right text-xs"></i>
                                                </button>
                                            </a>
                                        </td>
                                    @endforeach
                                    {{-- @foreach ($getDataPKS as $item)
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
                                                <div class="flex gap-2 items-center justify-center text-blue-500 cursor-pointer"
                                                    onclick="showDataPKS(this)"
                                                    data-paket_kerjasama="{{ $item->paket_kerjasama }}">
                                                    <i class="fa-solid fa-pen text-xs"></i>
                                                    <span>Atur tanggal</span>
                                                </div>
                                            </td>
                                            <td class="!text-center border border-gray-300">
                                                {{ $item->status_paket_kerjasama }}
                                            </td>
                                        </tr>
                                    @endforeach --}}
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="flex justify-center items-center min-h-[70vh]">
                            Tidak ada data PKS
                        </div>
                    @endif
                </section>
            </main>
        </div>
    </div>
@else
    <p>You do not have access to this pages.</p>
@endif


<script>
    function showDataPKS(element) {
        let paketKerjasama = element.getAttribute("data-paket_kerjasama");
        document.getElementById("text-paket-kerjasama").innerText = paketKerjasama;

        let modal = document.getElementById("my_modal_1");
        modal.showModal();

        flatpickr("#datepicker", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "j F, Y",
            appendTo: document.querySelector(".modal-box"),
            position: "below",
            static: true, // Jangan statis agar tidak tersembunyi
            theme: "dark",
            minDate: 'today',
        });
    }
</script>
