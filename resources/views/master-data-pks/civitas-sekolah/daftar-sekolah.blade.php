@include('components/sidebar_beranda')
@extends('components/sidebar_beranda_mobile')


@if (session('user')->status === 'Admin Sales')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            <header class="text-lg mb-4">DAFTAR SEKOLAH PKS</header>
            <main>
                <section>
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
                                        <a href="{{ route('data-civitas-sekolah', $item->sekolah) }}">
                                            <button>
                                                Lihat
                                                <i class="fas fa-chevron-right text-xs"></i>
                                            </button>
                                        </a>
                                    </td>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </main>
        </div>
    </div>
@else
    <p>You do not have access to this pages.</p>
@endif
