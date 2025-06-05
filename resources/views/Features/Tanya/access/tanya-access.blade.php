@include('components/sidebar_beranda', ['headerSideNav' => 'Libur TANYA'])
@extends('components/sidebar_beranda_mobile')

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            <!--- alert success insert data atau update data libur tanya ---->
            @if (session('success-insert-data'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-insert-data'),
                ])
            @endif
            @if (session('success-update-data'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-update-data'),
                ])
            @endif
            <!--- content libur tanya ---->
            <main>
                <section class="bg-white shadow-lg rounded-lg p-4 border border-gray-200 h-60">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-xl font-bold">Libur TANYA</span>
                        <button onclick="uploadJadwalAccess(this)"
                            class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold p-3 rounded-lg shadow-md transition-all text-xs">
                            Buat Libur TANYA
                        </button>
                    </div>
                    @if ($dataTanyaAccess->isNotEmpty())
                        <div class="overflow-x-auto h-full">
                            <table class="table w-full border-collapse border border-gray-300">
                                <thead class="font-bold">
                                    <tr>
                                        <th class="!text-center border border-gray-300">
                                            Tanggl Mulai
                                        </th>
                                        <th class="!text-center border border-gray-300">
                                            Tanggal Akhir
                                        </th>
                                        <th class="!text-center border border-gray-300">
                                            Status Tanggal Libur
                                        </th>
                                        <th class="!text-center border border-gray-300">
                                            Status TANYA
                                        </th>
                                        <th class="!text-center border border-gray-300">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataTanyaAccess as $item)
                                        <tr>
                                            <td class="!text-center border border-gray-300">
                                                {{ $item->tanggal_mulai->locale('id')->translatedFormat('l, d F Y') }}
                                            </td>
                                            <td class="!text-center border border-gray-300">
                                                {{ $item->tanggal_akhir->locale('id')->translatedFormat('l, d F Y') }}
                                            </td>
                                            <td class="!text-center border border-gray-300">
                                                {{ $item->status_access }}
                                            </td>
                                            <td class="!text-center border border-gray-300">
                                                {{ $item->status_access === 'Aktif' ? 'Locked' : 'Unlocked' }}
                                            </td>
                                            <td class="!text-center border border-gray-300">
                                                <div class="dropdown dropdown-left">
                                                    <div tabindex="0" role="button">
                                                        <i class="fa-solid fa-ellipsis-vertical cursor-pointer"></i>
                                                    </div>
                                                    <ul tabindex="0"
                                                        class="dropdown-content menu bg-base-100 rounded-box z-1 w-max p-2 shadow-sm  z-[9999]">
                                                        <li class="text-xs" onclick="editAccess(this)"
                                                            data-tanggal-mulai="{{ $item->tanggal_mulai }}"
                                                            data-tanggal-akhir="{{ $item->tanggal_akhir }}">
                                                            <a>
                                                                <i class="fa-solid fa-pen text-[#4189e0]"></i>
                                                                Atur Tanggal
                                                            </a>
                                                        </li>
                                                        <li class="text-xs" onclick="my_modal_3.showModal()">
                                                            <a>
                                                                <i class="fa-solid fa-eye text-[#4189e0]"></i>
                                                                View History
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="flex justify-center items-center h-full">
                            <span class="text-lg font-bold">Belum ada libur tanya</span>
                        </div>
                    @endif
                    <!--- modal untuk create libur tanya --->
                    <dialog id="my_modal_1" class="modal">
                        <div class="modal-box bg-white w-max h-[620px]">
                            <form action="{{ route('tanyaAccessStore') }}" method="POST">
                                @csrf
                                <!---- tanggal mulai access ---->
                                <div class="w-max relative flex flex-col gap-2 mt-4">
                                    <span class="text-sm font-bold text-gray-600">Tanggal Mulai</span>
                                    <input type="text" id="datepicker" name="tanggal_mulai"
                                        class="w-[385px] bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 cursor-pointer
                                        focus-within:border-[1px] focus-within:border-[dodgerblue] focus-within:shadow-[0_0_9px_0_dodgerblue] {{ $errors->has('tanggal_mulai') ? 'border-[1px] border-red-400' : '' }}"
                                        value="{{ old('tanggal_mulai') }}" placeholder="dd-mm-yyyy">
                                    <i class="fa-regular fa-calendar absolute top-[60%] right-4"></i>
                                </div>
                                @error('tanggal_mulai')
                                    <span class="text-red-500 font-bold text-xs">{{ $message }}</span>
                                @enderror
                                <!---- tanggal akhir access ---->
                                <div class="w-max relative flex flex-col gap-2 mt-4">
                                    <span class="text-sm font-bold text-gray-600">Tanggal Akhir</span>
                                    <input type="text" id="datepicker" name="tanggal_akhir"
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
                    <!--- modal untuk edit libur tanya --->
                    <dialog id="my_modal_2" class="modal">
                        <div class="modal-box bg-white w-max h-[620px]">
                            @if ($dataTanyaAccess->isNotEmpty())
                                <form action="{{ route('tanyaAccessUpdate', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <!---- tanggal mulai access ---->
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
                                    <!---- tanggal akhir access ---->
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
                            @endif
                        </div>
                        <form method="dialog" class="modal-backdrop">
                            <button>close</button>
                        </form>
                    </dialog>
                    <!--- modal untuk show history upload libur TANYA --->
                    @if ($dataTanyaAccess->isNotEmpty())
                        <dialog id="my_modal_3" class="modal">
                            <div class="modal-box bg-white text-center">
                                <span class="text-2xl">History</span>
                                <div class="flex items-center justify-between mt-6">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-circle-user text-5xl"></i>
                                        <div class="flex flex-col text-start">
                                            <span>
                                                {{ $item->UserAccount->Profile->nama_lengkap }}
                                            </span>
                                            <span class="text-sm">
                                                {{ $item->UserAccount->role }}
                                            </span>
                                            <span class="text-xs leading-6">
                                                [{{ $item->created_at->locale('id')->translatedFormat('d-M-Y') }}]
                                            </span>
                                        </div>
                                    </div>
                                    <div class="">
                                        <span class="text-[#4189e0] text-sm">Publisher</span>
                                    </div>
                                </div>
                            </div>
                            <form method="dialog" class="modal-backdrop">
                                <button>close</button>
                            </form>
                        </dialog>
                    @endif
                    @if (session('failed-insert-data'))
                        @include('components.alert.failed-insert-data', [
                            'message' => session('failed-insert-data'),
                        ])
                    @endif
                </section>
            </main>
        </div>
    </div>
@else
    <p>You do not have access to this pages.</p>
@endif

<script>
    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(function() {
            document.getElementById('alertSuccess').remove();
        }, 3000);

        document.getElementById('btnClose').addEventListener('click', function() {
            document.getElementById('alertSuccess').remove();
        })
    });
</script>

<script>
    function uploadJadwalAccess(element) {
        let modal = document.getElementById("my_modal_1");
        modal.showModal();

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
    }

    function editAccess(element) {
        let modal = document.getElementById("my_modal_2");
        let tanggalMulai = element.getAttribute("data-tanggal-mulai");
        let tanggalAkhir = element.getAttribute("data-tanggal-akhir");

        modal.showModal();

        // document.getElementById("datepicker-tanggal-mulai").value = tanggalMulai;
        // document.getElementById("datepicker-tanggal-akhir").value = tanggalAkhir;

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
            defaultDate: tanggalMulai, // Set nilai default dari data
            clickOpens: true
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
            defaultDate: tanggalAkhir, // Set nilai default dari data
            clickOpens: true
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
