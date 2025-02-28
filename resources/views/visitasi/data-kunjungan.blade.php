@include('components/sidebar_beranda')
@extends('components/sidebar_beranda_mobile')

@if (isset($user))
    @if ($user->status === 'Sales')
        <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
            <div class="content-beranda">
                <main>
                    <section>
                        <header class="text-lg mb-4 font-bold">LAPORAN DATA KUNJUNGAN</header>
                        <div class="overflow-x-auto">
                            <table class="table w-full border-collapse border border-gray-300">
                                <thead>
                                    <tr>
                                        <th class="!text-center td-question border border-gray-300" rowspan="2">
                                            No
                                        </th>
                                        <th class="!text-center td-question border border-gray-300" rowspan="2">
                                            Nama Sekolah
                                        </th>
                                        <th class="!text-center td-question border border-gray-300" colspan="3">
                                            Jadwal Kunjungan
                                        </th>
                                        <th class="!text-center td-question border border-gray-300" rowspan="2">
                                            Status Kunjungan
                                        </th>
                                        <th class="!text-center td-question border border-gray-300" rowspan="2">
                                            Action
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="!text-center td-question border border-gray-300">
                                            Tanggal Mulai
                                        </th>
                                        <th class="!text-center td-question border border-gray-300">
                                            Tanggal Akhir
                                        </th>
                                        <th class="!text-center td-question border border-gray-300">
                                            Tanggal dikunjungi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($getVisitasiData as $item)
                                        <tr>
                                            <td class="!text-center td-question border border-gray-300">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td class="!text-center td-question border border-gray-300">
                                                {{ $item->sekolah }}
                                            </td>
                                            <td class="!text-center td-question border border-gray-300">
                                                @if ($item->status_kunjungan === 'Pending')
                                                    <div class="text-red-500 font-bold">
                                                        Pending :
                                                        {{ $item->tanggal_mulai->locale('id')->translatedFormat('D, d-M-Y') }}
                                                    </div>
                                                @else
                                                    {{ $item->tanggal_mulai->locale('id')->translatedFormat('D, d-M-Y') }}
                                                @endif
                                            </td>
                                            <td class="!text-center td-question border border-gray-300">
                                                @if ($item->status_kunjungan === 'Pending')
                                                    <div class="text-red-500 font-bold">
                                                        Pending :
                                                        {{ $item->tanggal_akhir->locale('id')->translatedFormat('D, d-M-Y') }}
                                                    </div>
                                                @else
                                                    {{ $item->tanggal_akhir->locale('id')->translatedFormat('D, d-M-Y') }}
                                                @endif
                                            </td>
                                            <td class="!text-center td-question border border-gray-300">
                                                @if ($item->status_kunjungan === 'Pending' or $item->status_kunjungan === 'Belum dikunjungi')
                                                    -
                                                @else
                                                    {{ $item->updated_at->locale('id')->translatedFormat('D, d-M-Y, h:i:s') }}
                                                @endif
                                            </td>
                                            <td class="!text-center td-question border border-gray-300">
                                                @if ($item->status_kunjungan === 'Belum dikunjungi')
                                                    <button class="bg-[#b3e0ff] w-[120px] md:w-40 h-10 rounded-lg"
                                                        disabled>
                                                        {{ $item->status_kunjungan }}
                                                    </button>
                                                @elseif ($item->status_kunjungan === 'Pending')
                                                    <button class="bg-[#ff638473] w-[120px] md:w-40 h-10 rounded-lg"
                                                        disabled>
                                                        {{ $item->status_kunjungan }}
                                                    </button>
                                                @elseif ($item->status_kunjungan === 'Telah dikunjungi')
                                                    <button class="bg-green-300 w-[120px] md:w-40 h-10 rounded-lg">
                                                        {{ $item->status_kunjungan }}
                                                    </button>
                                                @endif
                                            </td>
                                            <td class="!text-center td-question border border-gray-300">
                                                @if ($item->status_kunjungan === 'Belum dikunjungi' or $item->status_kunjungan === 'Pending')
                                                    <form action="{{ route('visitasiData.update', $item->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status_kunjungan"
                                                            value="Telah dikunjungi">
                                                        <button
                                                            class="bg-[#4189e0cc] w-[120px] md:w-40 h-10 rounded-lg text-white font-bold">
                                                            Visit
                                                        </button>
                                                    </form>
                                                @elseif($item->status_kunjungan === 'Telah dikunjungi')
                                                    <button class="bg-green-400 w-[120px] md:w-40 h-10 rounded-lg"
                                                        disabled>
                                                        Visited
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
