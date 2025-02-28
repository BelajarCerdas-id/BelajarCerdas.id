@include('components/sidebar_beranda')
@extends('components.sidebar_beranda_mobile')

@if (isset($user))
    @if ($user->status === 'Sales')
        <div class="home-beranda">
            <div class="content-beranda">
                <div class="overflow-x-auto h-96">
                    <table class="table table-question">
                        <thead>
                            <tr>
                                <th class="!text-center td-question border border-gray-300">
                                    No
                                </th>
                                <th class="!text-center td-question border border-gray-300">
                                    Nama Sekolah
                                </th>
                                <th class="!text-center td-question border border-gray-300">
                                    Status Sekolah
                                </th>
                                <th class="!text-center td-question border border-gray-300">
                                    Print PKS
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($groupedData as $sekolah => $valueData)
                                <tr>
                                    <td class="!text-center td-question border border-gray-300">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="!text-center td-question border border-gray-300">
                                        {{ $valueData->sekolah }}
                                    </td>
                                    <td class="!text-center td-question border border-gray-300">
                                        {{ $valueData->status_sekolah }}
                                    </td>
                                    <td class="flex justify-center td-question !border-[1px] border-gray-200">
                                        <div class="dropdown dropdown-end">
                                            <div tabindex="0" role="button"
                                                class="m-1 bg-green-500 hover:bg-green-600 text-white p-2 rounded-md shadow-md transition-all duration-300 flex items-center gap-2">
                                                <i class="fa-solid fa-print text-lg"></i>
                                            </div>
                                            <ul tabindex="0"
                                                class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                                                @foreach ($dataPaketKerjasama[$valueData->sekolah] as $item)
                                                    <li>
                                                        @if ($item->paket_kerjasama === 'englishZone')
                                                            <a href="{{ route('generateSuratPKSEnglishZone') }}">
                                                                {{ $item->paket_kerjasama }}
                                                            </a>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
