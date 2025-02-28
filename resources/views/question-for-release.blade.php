@include('components/sidebar_beranda')
@extends('components/sidebar_beranda_mobile')

@if (isset($user))
    @if ($user->status === 'Administrator')
        <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
            <div class="content-beranda">
                <div class="bg-[--color-default] w-full h-20 shadow-lg rounded-t-xl flex items-center pl-10">
                    <div class="text-white font-bold flex items-center gap-4">
                        <i class="fa-solid fa-file-lines text-4xl"></i>
                        <span class="text-xl">English Zone</span>
                    </div>
                </div>
                <div class="flex gap-4 mt-6">
                    <select name="" id="questionStatusFilter"
                        class="w-[150px] h-10 rounded-lg flex justify-center items-center px-2 border-[1px] outline-none text-sm cursor-pointer bg-white">
                        <div class="border-none">
                            <option value="" class="hidden">Filter status</option>
                            <option value="semua">Lihat Semua</option>
                            <option value="published">Publish</option>
                            <option value="unpublish">Unpublish</option>
                        </div>
                    </select>
                    <select name="" id="questionModulFilter"
                        class="w-[150px] h-10 rounded-lg flex justify-center items-center px-2 border-[1px] outline-none text-sm cursor-pointer bg-white">
                        <div class="border-none">
                            <option value="" class="hidden">Filter modul</option>
                            <option value="semua">Lihat Semua</option>
                            <option value="Modul 1">Modul 1</option>
                            <option value="Modul 2">Modul 2</option>
                            <option value="Modul 3">Modul 3</option>
                        </div>
                    </select>
                    <select name="" id="questionJenjangFilter"
                        class="w-[150px] h-10 rounded-lg flex justify-center items-center px-2 border-[1px] outline-none text-sm cursor-pointer bg-white">
                        <div class="border-none">
                            <option value="" class="hidden">Filter jenjang</option>
                            <option value="semua">Lihat Semua</option>
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA">SMA</option>
                        </div>
                    </select>
                </div>
                <form action="{{ route('questionForRelease.update') }}" method="POST">
                    <div class="relative w-full h-9">
                        <button
                            class="absolute right-14 bg-red-500 w-[150px] h-8 text-white font-bold rounded-lg text-xs hidden"
                            id="sendButton">Publish
                            / Unpublish</button>
                    </div>
                    <div class="overflow-x-auto">
                        @csrf
                        @method('PUT')
                        <table class="table table-question">
                            <thead class="thead-question" id="tableListTable">
                                <tr>
                                    <th class="th-question">Action</th>
                                    <th class="th-question">Modul</th>
                                    <th class="th-question">Levels</th>
                                    <th class="th-question">Question</th>
                                    <th class="th-question">Answer</th>
                                    <th class="th-question">Description</th>
                                    <th class="th-question">Create_date</th>
                                    <th class="th-question">Status</th>
                                    <th class="th-question">Lihat</th>
                                </tr>
                            </thead>
                            <tbody class="tbody-question" id="tableListQuestion">
                                {{-- show data with ajax --}}
                                {{-- get data tanpa ajax(jangan dihapus) --}}
                                {{-- @foreach ($getSoal as $item)
                                    <tr class="text-xs">
                                        <td class="td-question">
                                            <input type="checkbox" name="id[]" value="{{ $item->id }}"
                                                onclick="showButton()" class="checkboxButton cursor-pointer">
                                        </td>
                                        <td class="td-question !text-start w-[80px]">{!! $item->modul !!}</td>
                                        <td class="td-question !text-start">{!! $item->jenjang !!}</td>
                                        <td class="td-question !text-start">{!! $item->soal !!}</td>
                                        <td class="td-question">{!! $item->pilihan_A !!}</td>
                                        <td class="td-question">{!! $item->pilihan_B !!}</td>
                                        <td class="td-question">{!! $item->pilihan_C !!}</td>
                                        <td class="td-question">{!! $item->pilihan_D !!}</td>
                                        <td class="td-question">{!! $item->pilihan_E !!}</td>
                                        <td class="td-question !text-start">{!! $item->deskripsi_jawaban !!}</td>
                                        <td class="td-question">
                                            @if ($item->status_soal === 'published')
                                                <button
                                                    class="button-question text-white bg-green-500 p-2 w-27 rounded-lg font-bold cursor-default">{{ $item->status_soal }}</button>
                                            @else
                                                <button
                                                    class="button-question text-white bg-gray-300 p-2 w-27 rounded-lg font-bold cursor-default">{{ $item->status_soal }}</button>
                                            @endif
                                        </td>
                                        <td class="td-question cursor-pointer relative border-2">
                                            <i class="fa-solid fa-ellipsis-vertical text-lg"
                                                onclick="toggleDropdown(event, this)"></i>
                                            <div class="dropdown-menu-preview">
                                                <ul class="list-dropdown-preview">
                                                    <li>
                                                        <i class="fa-regular fa-eye"></i>
                                                        <a href="#">Lihat Pertanyaan</a>
                                                    </li>
                                                    <li>
                                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                                        <a href="#">Histori Pertanyaan</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach --}}
                            </tbody>
                        </table>
                        <div class="pagination-container-question"></div>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="flex flex-col min-h-screen items-center justify-center">
            <p>ALERT SEMENTARA</p>
            <p>You do not have access to this pages.</p>
        </div>
    @endif
@else
    <p>You are not logged in.</p>
@endif

<script src="js/englishZoneSoal-status-ajax.js"></script>


<script>
    function toggleDropdown(event, el) {
        // Cegah klik dari menyebar ke elemen lain
        event.stopPropagation();

        // Menentukan elemen dropdown yang berhubungan dengan tombol elipsis
        const dropdownMenu = el.nextElementSibling;

        // Tampilkan atau sembunyikan dropdown menu
        const isVisible = dropdownMenu.style.display === 'block';
        dropdownMenu.style.display = isVisible ? 'none' : 'block';
    }

    // Menutup dropdown jika klik di luar dropdown
    document.addEventListener('click', function(event) {
        const openDropdowns = document.querySelectorAll('.dropdown-menu-preview');
        openDropdowns.forEach(function(dropdown) {
            if (!dropdown.contains(event.target) && !event.target.classList.contains(
                    'fa-ellipsis-vertical')) {
                dropdown.style.display = 'none';
            }
        });
    });
</script>

{{-- aktifin button ketika checkbox di klik dan non-aktif button ketika checkbox tidak ada yang di klik (jangan dihapus) --}}
{{-- <script>
    const checkbox = document.querySelectorAll('.checkboxButton'); // Pilih semua checkbox
    const button = document.getElementById('sendButton');

    function showButton() {
        // Cek jika ada checkbox yang dicentang
        const anyChecked = Array.from(checkbox).some((box) => box.checked);

        if (anyChecked) {
            button.style.display = 'block'; // Tampilkan tombol
        } else {
            button.style.display = 'none'; // Sembunyikan tombol
        }
    }

    // Tambahkan event listener untuk setiap checkbox
    checkbox.forEach((box) => box.addEventListener('click', showButton));
</script> --}}
