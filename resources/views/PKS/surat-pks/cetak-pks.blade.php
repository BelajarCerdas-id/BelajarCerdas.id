@include('components/sidebar_beranda')
@extends('components.sidebar_beranda_mobile')

@if (isset($user))
    @if ($user->status === 'Sales')
        <div class="home-beranda">
            <div class="content-beranda">
                <header class="text-lg mb-4 font-bold">CETAK SURAT PKS</header>
                @if ($getPaketKerjasamaPKS->isNotEmpty())
                    <div class="overflow-x-auto h-96">
                        <table class="table table-question">
                            <thead>
                                <tr>
                                    <th class="!text-center td-question border border-gray-300" rowspan="2">
                                        No
                                    </th>
                                    <th class="!text-center td-question border border-gray-300" rowspan="2">
                                        Nama Sekolah
                                    </th>
                                    <th class="!text-center td-question border border-gray-300" rowspan="2">
                                        Status Sekolah
                                    </th>
                                    <th class="!text-center td-question border border-gray-300" rowspan="2">
                                        Paket Kerjasama
                                    </th>
                                    <th class="!text-center td-question border border-gray-300" colspan="2">
                                        Tanggal Kerjasama
                                    </th>
                                    <th class="!text-center td-question border border-gray-300" rowspan="2">
                                        Status Kontrak
                                    </th>
                                    <th class="!text-center td-question border border-gray-300" rowspan="2">
                                        Tipe Kontrak
                                    </th>
                                    <th class="!text-center td-question border border-gray-300" rowspan="2">
                                        Penjadwalan
                                    </th>
                                    <th class="!text-center td-question border border-gray-300" rowspan="2">
                                        Print PKS
                                    </th>
                                    <th class="!text-center td-question border border-gray-300" rowspan="2">
                                        Status PKS
                                    </th>
                                </tr>
                                <tr>
                                    <th class="!text-center td-question border border-gray-300">
                                        Tanggal Mulai
                                    </th>
                                    <th class="!text-center td-question border border-gray-300">
                                        Tanggal Akhir
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($getPaketKerjasamaPKS as $item)
                                    <tr>
                                        <td class="!text-center td-question border border-gray-300">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="!text-center td-question border border-gray-300">
                                            {{ $item->sekolah }}
                                        </td>
                                        <td class="!text-center td-question border border-gray-300">
                                            {{ $item->status_sekolah }}
                                        </td>
                                        <td class="!text-center td-question border border-gray-300">
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
                                        <td class="!text-center td-question border border-gray-300">
                                            {{ $item->status_paket_kerjasama }}
                                        </td>
                                        <td class="!text-center td-question border border-gray-300">
                                            <!-- mengecek apakah data yang sedang di-loop adalah data pertama kali yang di-insert ke database --->
                                            {{ $item->id === $firstContract[$item->sekolah][$item->paket_kerjasama] ? 'Kontrak Pertama' : 'Perpanjangan Kontrak' }}
                                        </td>
                                        <td class="!text-center td-question border border-gray-300">
                                            @if ($item->status_paket_kerjasama === 'Selesai' && $item->status_pks === 'PKS')
                                                -
                                            @else
                                                <div class="flex gap-2 items-center justify-center text-blue-500 cursor-pointer"
                                                    onclick="showDataPKS(this)" data-id-cetakPks="{{ $item->id }}"
                                                    data-tanggal-mulai="{{ $item->tanggal_mulai }}"
                                                    data-tanggal-akhir="{{ $item->tanggal_akhir }}"
                                                    data-action-cetakPks="{{ route('dataPksSekolah.update', $item->id) }}">
                                                    <i class="fa-solid fa-pen text-xs"></i>
                                                    <span>Ubah tanggal</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="flex justify-center td-question !border-[1px] border-gray-200">
                                            @if ($item->status_pks === 'PKS')
                                                @if ($item->paket_kerjasama === 'englishZone')
                                                    <button
                                                        class="m-1 bg-green-200 border border-green-300 text-green-500 w-10 h-10 rounded-md shadow-md transition-all duration-300 flex items-center justify-center gap-2"
                                                        disabled>
                                                        <i class="fa-solid fa-print text-lg"></i>
                                                    </button>
                                                @endif
                                            @else
                                                <a
                                                    href="{{ route('generateSuratPKSEnglishZone', [$item->id, $item->sekolah]) }}">
                                                    <button
                                                        class="m-1 bg-[--color-default] text-white w-10 h-10 rounded-md shadow-md transition-all duration-300 flex items-center justify-center gap-2">
                                                        <i class="fa-solid fa-print text-lg"></i>
                                                    </button>
                                                </a>
                                            @endif
                                        </td>
                                        <td class="!text-center td-question border border-gray-300">
                                            @if ($item->status_pks === 'Belum PKS')
                                                <form action="{{ route('statusCetakPKS.update', $item->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <button
                                                        class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold w-20 h-10 rounded-lg shadow-md transition-all text-xs">
                                                        {{ $item->status_pks }}
                                                    </button>
                                                </form>
                                            @else
                                                <button
                                                    class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold w-20 h-10 rounded-lg shadow-md transition-all text-md"
                                                    disabled>
                                                    {{ $item->status_pks }}
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <dialog id="my_modal_1" class="modal">
                        <div class="modal-box bg-white w-max h-[620px]">
                            <!---- identitas paket kerjasama ---->
                            <form id="cetakPksForm" action="" method="POST">
                                @csrf
                                @method('PUT')
                                <!---- tanggal mulai paket kerjasama ---->
                                <div class="w-max relative flex flex-col gap-2 mt-4">
                                    <span class="text-sm font-bold text-gray-600">Tanggal Mulai</span>
                                    <input type="text" id="datepicker-tanggal-mulai" name="tanggal_mulai"
                                        class="w-[385px] bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 cursor-pointer
                                        focus-within:border-[1px] focus-within:border-[dodgerblue] focus-within:shadow-[0_0_9px_0_dodgerblue] {{ $errors->has('tanggal_mulai') ? 'border-[1px] border-red-400' : '' }}"
                                        value="{{ old('tanggal_mulai') }}" placeholder="dd-mm-yyyy">
                                    <i class="fa-regular fa-calendar absolute top-[60%] right-4"></i>
                                </div>
                                @error('tanggal_mulai')
                                    <span class="text-red-500 font-bold text-xs">{{ $message }}</span>
                                @enderror
                                <!---- tanggal akhir paket kerjasama ---->
                                <div class="w-max relative flex flex-col gap-2 mt-4">
                                    <span class="text-sm font-bold text-gray-600">Tanggal Akhir</span>
                                    <input type="text" id="datepicker-tanggal-akhir" name="tanggal_akhir"
                                        class="w-[385px] bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 cursor-pointer focus-within:border-[1px]
                                        focus-within:border-[dodgerblue] focus-within:shadow-[0_0_9px_0_dodgerblue] {{ $errors->has('tanggal_akhir') ? 'border-[1px] border-red-400' : '' }}"
                                        value="{{ old('tanggal_akhir') }}" placeholder="dd-mm-yyyy">
                                    <i class="fa-regular fa-calendar absolute top-[60%] right-4"></i>
                                </div>
                                @error('tanggal_akhir')
                                    <span class="text-red-500 font-bold text-xs">{{ $message }}</span>
                                @enderror
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
                        Tidak ada data PKS
                    </div>
                @endif
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
    function showDataPKS(element) {
        let modal = document.getElementById("my_modal_1");
        let tanggalMulai = element.getAttribute('data-tanggal-mulai');
        let tanggalAkhir = element.getAttribute('data-tanggal-akhir');
        let cetakPksForm = element.getAttribute('data-action-cetakPks');

        modal.showModal();

        document.getElementById('datepicker-tanggal-mulai').value = tanggalMulai;
        document.getElementById('datepicker-tanggal-akhir').value = tanggalAkhir;

        document.getElementById('cetakPksForm').setAttribute('action', cetakPksForm);

        // Inisialisasi Flatpickr pada input
        flatpickr("#datepicker-tanggal-mulai", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "j F, Y",
            appendTo: document.querySelector(".modal-box"),
            position: "below",
            static: true,
            theme: "dark",
            minDate: 'today',
            defaultDate: tanggalMulai,
            clickOpens: true // Mencegah focus otomatis pada input type date
        });

        flatpickr("#datepicker-tanggal-akhir", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "j F, Y",
            appendTo: document.querySelector(".modal-box"),
            position: "below",
            static: true,
            theme: "dark",
            minDate: 'today',
            defaultDate: tanggalAkhir,
            clickOpens: true // Mencegah focus otomatis pada input type date
        });
    }
</script>

<script>
    document.getElementById('submit').addEventListener('click', function() {
        const form = document.querySelector('#my_modal_1 form[action]');
        form.submit(); // Submit the form
    });
</script>

@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ensure the modal is displayed if there are validation errors
            document.getElementById('my_modal_1').showModal();
            flatpickr("#datepicker", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "j F, Y",
                appendTo: document.querySelector(".modal-box"),
                position: "below",
                static: true,
                theme: "dark",
                minDate: 'today',
                clickOpens: true // Mencegah focus otomatis pada input type date
            });
        });
    </script>
@endif
