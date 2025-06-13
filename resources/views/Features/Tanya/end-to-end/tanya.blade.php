@include('components/sidebar_beranda', ['headerSideNav' => 'TANYA'])

@if (Auth::user()->role === 'Siswa')
    <div class="home-beranda z-[-1] md:z-0 mt-[40px] md:mt-0">
        <div class="content-beranda">
            <!--- alert success setelah kirim pertanyaan --->
            <div id="alert-success-insert-question"></div>
            @if (session('success-reject-tanya'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-reject-tanya'),
                ])
            @endif
            @if (session('not-enough-coin-tanya'))
                @include('components.alert.failed-insert-data', [
                    'message' => session('not-enough-coin-tanya'),
                ])
            @endif
            <div class="bg-[--color-second] w-full h-20 shadow-lg rounded-t-xl flex items-center pl-10 mb-4">
                <div class="text-white font-bold flex items-center gap-3">
                    <i class="fas fa-question text-2xl"></i>
                    <span class="text-xl">Tanya</span>
                </div>
            </div>
            {{-- <div class="flex justify-end">
                <form action="{{ route('tanya.claimCoinDaily') }}" method="POST">
                    @csrf
                    @if (!$historyCoinDaily)
                        <button class="flex gap-2 bg-[#4189E0] w-max px-2 py-2 rounded-md text-white font-bold">
                            <div class="flex gap-[2px]">
                                <span class="text-sm">Klaim 10 koin harian</span>
                            </div>
                        </button>
                    @endif
                </form>
            </div> --}}
            <div class="flex mt-4">
                <div class="w-full hover:bg-gray-100" onclick="content()">
                    <input type="radio" class="hidden" name="radio" id="radio1" checked>
                    <div class="checked-timeline">
                        <label for="radio1" class="cursor-pointer">
                            <span class="text-lg flex justify-center relative top-1">Tanya</span>
                            <div class="w-full border-b-[1px] border-gray-200 h-2"></div>
                        </label>
                    </div>
                </div>
                <div class="w-full hover:bg-gray-100" onclick="riwayat()">
                    <input type="radio" class="hidden" name="radio" id="radio2">
                    <div class="checked-timeline">
                        <label for="radio2" class="cursor-pointer">
                            <span class="text-lg flex justify-center relative top-1">Riwayat</span>
                            <div class="w-full border-b-[1px] border-gray-200 h-2"></div>
                        </label>
                    </div>
                </div>
            </div>
            <div class="w-full bg-white rounded-lg shadow-lg gap-12 px-6 py-6 relative overflow-hidden">
                <div class="w-full h-auto" id="content">
                    <form id="questions-form" enctype="multipart/form-data">
                        <!--- Option kelas & Mata pelajaran ----->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!--- Option fase ----->
                            <div class="flex flex-col">
                                <label class="mb-2 text-sm">Fase<sup class="text-red-500 pl-1">&#42;</sup></label>
                                <select name="fase_id" id="id_fase"
                                    class="w-full bg-white shadow-lg h-12 border-gray-200 border outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer"
                                    data-old-fase="{{ old('fase_id') }}">
                                    <option value="" class="hidden">Pilih Fase</option>
                                    @foreach ($getFase as $item)
                                        @if ($item->id === Auth::user()->Profile->fase_id)
                                            <option value="{{ $item->id }}">
                                                {{ $item->nama_fase }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <span id="error-fase_id" class="text-red-500 font-bold text-xs pt-2"></span>
                            </div>
                            <!--- Option kelas ----->
                            <div class="flex flex-col">
                                <label class="mb-2 text-sm">Kelas<sup class="text-red-500 pl-1">&#42;</sup></label>
                                <select name="kelas_id" id="id_kelas"
                                    class="bg-white shadow-lg h-12 border-gray-200 border outline-none rounded-md px-2 opacity-50 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-default"
                                    data-old-kelas="{{ old('kelas_id') }}" disabled>
                                    <option class="hidden">Harap pilih fase</option>
                                </select>
                                <span id="error-kelas_id" class="text-red-500 font-bold text-xs pt-2"></span>
                            </div>
                            <!--- Option Mata pelajaran ----->
                            <div class="flex flex-col">
                                <div class="">
                                    <span>
                                        Mata Pelajaran
                                        <sup class="text-red-500 pl-1">&#42;</sup>
                                    </span>

                                    <!-- Hidden input agar value tetap bisa dikirim saat submit -->
                                    <input type="hidden" name="mapel_id" id="id_mapel"
                                        data-old-mapel="{{ old('mapel_id') }}"
                                        data-coin-image="{{ asset('image/koin.png') }}">
                                    <input type="hidden" name="harga_koin" id="harga_koin">

                                    <div class="w-full relative my-2 border-gray-200 border outline-none"
                                        id="dropdownWrapper">
                                        <div id="dropdownButton"
                                            class="h-12 px-4 bg-white shadow-lg cursor-pointer flex justify-between items-center font-bold pointer-events-none opacity-50"
                                            onclick="toggleDropdown()">
                                            <!--- left content ---->
                                            <span id="selectedKurikulum" class="!font-normal">Harap pilih fase</span>
                                            <!--- right content ---->
                                            <div class="flex items-center gap-8">
                                                <!--- label harga koin dan font awesome --->
                                                <div class="flex items-center gap-2 font-normal">
                                                    <span id="selectedIconKoin"></span>
                                                    <span id="selectedKoin"></span>
                                                </div>
                                                <i class="fas fa-chevron-up transition-transform duration-500 text-xs"
                                                    id="dropdownArrow"></i>
                                            </div>
                                        </div>

                                        <div class="absolute w-full bg-white shadow-md hidden z-10" id="dropdown">
                                            <!-- Options akan di-append di sini -->
                                        </div>
                                    </div>
                                    <span id="error-mapel_id" class="text-red-500 font-bold text-xs pt-2"></span>
                                </div>
                            </div>
                            <!--- Option bab ----->
                            <div class="flex flex-col">
                                <label class="mb-2 text-sm">Bab<sup class="text-red-500 pl-1">&#42;</sup></label>
                                <select name="bab_id" id="id_bab"
                                    class="bg-white shadow-lg h-12 border-gray-200 border outline-none rounded-md px-2 opacity-50 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-default"
                                    data-old-bab="{{ old('bab_id') }}" disabled>
                                    <option class="hidden">Harap pilih mata pelajaran</option>
                                </select>
                                <span id="error-bab_id" class="text-red-500 font-bold text-xs pt-2"></span>
                            </div>
                        </div>

                        <!--- Kolom Pertanyaan & Upload image pertanyaan ----->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 my-8">
                            <!--- Kolom Pertanyaan ----->
                            <div class="flex flex-col h-44 relative">
                                <label class="mb-2 text-sm">Pertanyaan<sup class="text-red-500 pl-1">&#42;</sup></label>
                                <textarea name="pertanyaan"
                                    class="p-4 resize-none bg-white shadow-lg h-32 border-gray-200 border outline-none rounded-md px-4 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue]"
                                    placeholder="Masukkan Pertanyaan....">{{ @old('pertanyaan') }}</textarea>
                                <span id="error-pertanyaan"
                                    class="text-error text-red-500 font-bold text-xs pt-2"></span>
                            </div>
                            <!--- Upload image pertanyaan ----->
                            <div class="">
                                <div class="w-full lg:md:2/4 h-auto">
                                    <span class="text-sm">Upload Gambar<sup>&#42;Optional</sup></span>
                                    <div class="text-xs mt-1">
                                        <span>Maksimum ukuran file 2MB. <br> File dapat dalam format
                                            .jpg/.png/.jpeg.</span>
                                    </div>
                                    <div class="upload-icon">
                                        <div class="flex flex-col max-w-[260px]">
                                            <div id="imagePreview" class="max-w-[140px] cursor-pointer mt-4"
                                                onclick="openModal()">
                                                {{-- Image will be inserted here --}}
                                                <img id="popupImage" alt="" class="object-contain">
                                            </div>
                                            <div id="textPreview" class="text-red-500 font-bold mt-2"></div>
                                        </div>
                                        <div class="text-icon"></div>
                                    </div>
                                    <div
                                        class="content-upload w-full lg:w-[200px] h-10 bg-[#4189e0] hover:bg-blue-500 text-white font-bold rounded-lg mt-6">
                                        <label for="file-upload"
                                            class="w-full h-full flex justify-center items-center cursor-pointer gap-2">
                                            <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                            <span>Upload Gambar</span>
                                        </label>
                                        <input id="file-upload" name="image_tanya" class="hidden"
                                            onchange="previewImage(event)" type="file" accept=".jpg, .png, .jpeg">
                                    </div>
                                </div>
                                <span id="error-image_tanya" class="text-red-500 font-bold text-xs pt-2"></span>
                            </div>
                        </div>

                        <!--- Button form ----->
                        <div class="flex justify-end mt-20 lg:mt-8">
                            <button id="submit-button"
                                class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all">
                                Kirim
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Modal for displaying the image -->
                <dialog id="my_modal_1" class="modal">
                    <div class="modal-box bg-white">
                        <img id="modalImage" alt="" class="w-full h-auto">
                    </div>
                    <form method="dialog" class="modal-backdrop">
                        <button>close</button>
                    </form>
                </dialog>

                <!-- History Tanya -->
                <div class="w-full h-auto hidden" id="riwayat">
                    <div class="flex justify-end">
                        <select name="" id="statusFilterRiwayatStudent"
                            class="w-[150px] h-10 rounded-lg flex justify-center items-center px-2 border-[1px] border-gray-200 outline-none text-sm cursor-pointer">
                            <div class="border-none">
                                <option value="" class="hidden">Filter Data</option>
                                <option value="semua">Lihat Semua</option>
                                <option value="Diterima">Diterima</option>
                                <option value="Ditolak">Ditolak</option>
                            </div>
                        </select>
                    </div>
                    <div class="overflow-x-auto mt-4">
                        <table class="table" id="filterTable">
                            <thead class="thead-table">
                                <tr>
                                    <th class="th-table">No</th>
                                    <th class="th-table">Pertanyaan</th>
                                    <th class="th-table">Mata Pelajaran</th>
                                    <th class="th-table">Bab</th>
                                    <th class="th-table">Jam_Tanya</th>
                                    <th class="th-table">Jam_Jawab</th>
                                    <th class="th-table">status</th>
                                    <th class="th-table">Jawaban</th>
                                    <th class="th-table">Alasan Ditolak</th>
                                    <th class="th-table">Detail</th>
                                </tr>
                            </thead>
                            <tbody id="filterList">
                                {{-- show data in ajax --}}
                            </tbody>
                        </table>
                        <div class="pagination-container-siswa flex justify-center my-4 sm:my-0"></div>
                        <div class="flex justify-center">
                            <span class="showMessage hidden absolute top-2/4">Tidak ada riwayat</span>
                        </div>
                    </div>
                    <div id="emptyMessageRiwayatTanyaStudent" class="w-full hidden">
                        <span class="w-full h-full flex items-center justify-center">
                            Belum ada riwayat pertanyaan terjawab.
                        </span>
                    </div>
                </div>
            </div>
            <!---- History tanya daily ---->
            <div class="lg:w-2/4 w-full h-full mt-5 bg-white rounded-lg shadow-md">
                <header class="pb-6 p-4">
                    <span>Riwayat Harian</span>
                </header>
                <div class="border-b-2 border-gray-200"></div>
                <div class="w-full text-center mt-6">
                    <div class="flex">
                        <div class="w-full hover:bg-gray-100" onclick="contentUnanswered()">
                            <input type="radio" name="radio1" id="unanswered" checked>
                            <div class="historyTanya">
                                <label for="unanswered" class="cursor-pointer">
                                    <span class="text-md flex justify-center relative top-1">Menunggu</span>
                                    <div class="w-full border-b-[1px] border-gray-200 h-2"></div>
                                </label>
                            </div>
                        </div>
                        <div class="w-full hover:bg-gray-100">
                            <input type="radio" name="radio1" id="answer" onclick="contentAnswer()">
                            <div class="historyTanya">
                                <label for="answer" class="cursor-pointer">
                                    <span id="answeredText" class="text-md flex justify-center relative top-1">
                                        Terjawab
                                        <span id="notifBadgeAnswered"
                                            class="relative left-2 top-[1px] text-[15px] text-green-500 {{ $countDataTanyaAnsweredUser ? '' : 'hidden' }}">
                                            {{ $countDataTanyaAnsweredUser }}
                                        </span>
                                    </span>
                                    <div class="w-full border-b-[1px] border-gray-200 h-2"></div>
                                </label>
                            </div>
                        </div>
                        <div class="w-full hover:bg-gray-100" onclick="contentReject()">
                            <input type="radio" name="radio1" id="reject">
                            <div class="historyTanya">
                                <label for="reject" class="cursor-pointer">
                                    <span id="rejectedText" class="text-md flex justify-center relative top-1">
                                        Ditolak
                                        <span id="notifBadgeRejected"
                                            class="relative left-2 top-[1px] text-[15px] text-red-500 {{ $countDataTanyaRejectedUser ? '' : 'hidden' }}">
                                            {{ $countDataTanyaRejectedUser }}
                                        </span>
                                    </span>
                                    <div class="w-full border-b-[1px] border-gray-200 h-2"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative w-full max-h-[494px] overflow-y-auto overflow-hidden" id="dailyTanya">
                    <div class="w-full" id="contentUnanswered">
                        <div class="p-6 w-full">

                            <div id="cardUnAnswer"></div>

                            <div id="emptyMessageTanyaHarianBelumTerjawab" class="w-full hidden">
                                <span class="w-full h-full flex items-center justify-center">
                                    Belum ada riwayat harian.
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="w-full h-auto hidden" id="contentAnswer">
                        <div class="p-6 w-full">

                            <button id="updateStatusSoalAll"
                                class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold p-2 rounded-lg shadow-md transition-all text-sm hidden"
                                data-url-id="{{ route('tanya.updateAllStatusSoalById', Auth::user()->id) }}">
                                Tandai Semua
                            </button>

                            <div id="cardAnswer"></div>

                            <div id="emptyMessageTanyaHarianTerjawab" class="w-full hidden">
                                <span class="w-full h-full flex items-center justify-center">
                                    Belum ada riwayat harian.
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="w-full h-auto hidden" id="contentReject">
                        <div class="p-6 w-full">
                            <button id="updateStatusSoalAllRejected"
                                class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold p-2 rounded-lg shadow-md transition-all text-sm hidden"
                                data-url-id="{{ route('tanya.updateAllStatusSoalRejectedById', Auth::user()->id) }}">
                                Tandai Semua
                            </button>

                            <div id="cardRejected"></div>

                            <div id="emptyMessageTanyaHarianRejected" class="w-full hidden">
                                <span class="w-full h-full flex items-center justify-center">
                                    Belum ada riwayat harian.
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif(Auth::user()->role === 'Mentor')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            @if (session('success-answer-tanya'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-answer-tanya'),
                ])
            @endif
            @if (session('success-reject-tanya'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-reject-tanya'),
                ])
            @endif
            <div class="bg-[--color-second] w-full h-20 shadow-lg rounded-t-xl flex items-center pl-10 mb-10">
                <div class="text-white font-bold flex items-center gap-3">
                    <i class="fas fa-question text-2xl"></i>
                    <span class="text-xl">Tanya</span>
                </div>
            </div>
            <div class="flex mt-10">
                <div class="w-full hover:bg-gray-100" onclick="content()">
                    <input type="radio" name="radio" id="radio1" checked>
                    <div class="checked-timeline">
                        <label for="radio1" class="cursor-pointer">
                            <span class="text-lg flex justify-center relative top-1">Tanya</span>
                            <div class="w-full border-b-[1px] border-gray-200 h-2"></div>
                        </label>
                    </div>
                </div>
                <div class="w-full hover:bg-gray-100" onclick="riwayat()">
                    <input type="radio" name="radio" id="radio2">
                    <div class="checked-timeline">
                        <label for="radio2" class="cursor-pointer">
                            <span class="text-lg flex justify-center relative top-1">Riwayat</span>
                            <div class="w-full border-b-[1px] border-gray-200 h-2"></div>
                        </label>
                    </div>
                </div>
            </div>
            <div class="relative w-full h-max overflow-hidden bg-white shadow-lg">
                <div class="w-full h-auto" id="content">
                    <div class="overflow-x-auto m-4">
                        <table class="table" id="tableTanyaTeacher">
                            <thead class="thead-table-tanya-teacher hidden">
                                <tr>
                                    <th class="th-table">No</th>
                                    <th class="th-table">Nama Siswa</th>
                                    <th class="th-table">Kelas</th>
                                    <th class="th-table">Pertanyaan</th>
                                    <th class="th-table">Mata Pelajaran</th>
                                    <th class="th-table">Bab</th>
                                    <th class="th-table">Jam_Tanya</th>
                                    <th class="th-table">Detail</th>
                                </tr>
                            </thead>
                            <tbody id="tableListTeacher">
                                {{-- show data in ajax --}}
                            </tbody>
                        </table>

                        <div class="pagination-container-tanya flex justify-center my-4 sm:my-0"></div>

                        <div id="emptyMessageTanyaTeacher" class="w-full h-96 hidden">
                            <span class="w-full h-full flex items-center justify-center">
                                Tidak ada pertanyaan.
                            </span>
                        </div>
                    </div>
                </div>
                <div class="w-full h-auto hidden" id="riwayat">
                    <div class="absolute right-8 top-2 flex justify-center items-center my-4">
                        <select name="" id="statusFilterRiwayatMentor"
                            class="w-[150px] h-10 rounded-lg px-2 border-[1px] outline-none text-sm cursor-pointer bg-white">
                            <div class="border-none">
                                <option value="" class="hidden">Filter Data</option>
                                <option value="semua">Lihat Semua</option>
                                <option value="Diterima">Diterima</option>
                                <option value="Ditolak">Ditolak</option>
                            </div>
                        </select>
                    </div>
                    <div class="overflow-x-auto mt-24 mb-4 mx-4">
                        <table class="table" id="filterTableTeacher">
                            <thead class="thead-table-riwayat-teacher hidden">
                                <tr>
                                    <th class="th-table">No</th>
                                    <th class="th-table">Nama Siswa</th>
                                    <th class="th-table">Kelas</th>
                                    <th class="th-table">Pertanyaan</th>
                                    <th class="th-table">Mata Pelajaran</th>
                                    <th class="th-table">Bab</th>
                                    <th class="th-table">Jam_Tanya</th>
                                    <th class="th-table">Jam_Jawab</th>
                                    <th class="th-table">status</th>
                                    <th class="th-table">Jawaban</th>
                                    <th class="th-table">Alasan Ditolak</th>
                                    <th class="th-table">Detail</th>
                                </tr>
                            </thead>
                            <tbody id="filterListTeacher">
                                {{-- show data in ajax --}}
                            </tbody>
                        </table>

                        <div class="pagination-container-riwayat flex justify-center my-4 sm:my-0"></div>

                        <div id="emptyMessageRiwayatTeacher" class="w-full h-96 hidden">
                            <div class="w-full h-full flex items-center justify-center">
                                Tidak ada riwayat.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="flex flex-col min-h-screen items-center justify-center">
        <p>ALERT SEMENTARA</p>
        <p>You do not have access to this pages.</p>
    </div>
@endif

<!--- end to end  ---->
<script src="{{ asset('js/Tanya/end-to-end/students-submit-questions-ajax.js') }}"></script> <!--- submit pertanyaan siswa ---->
<script src="{{ asset('js/upload-image.js') }}"></script> <!--- show image tanya ---->
<script src="{{ asset('js/Tanya/dependent-dropdown/fase-kelas-mapel-bab-dropdown.js') }}"></script> <!--- dependent dropdown ---->

<!--- riwayat tanya user ---->
<script src="{{ asset('js/Tanya/riwayat-siswa-ajax.js') }}"></script> <!--- riwayat student ---->
<script src="{{ asset('js/Tanya/riwayat-guru-ajax.js') }}"></script> <!--- riwayat mentor ---->
<script src="{{ asset('js/Tanya/tanya-guru-ajax.js') }}"></script> <!--- riwayat pertanyaan mentor ---->

<!--- riwayat harian student ---->
<script src="{{ asset('js/Tanya/content-riwayat-harian-siswa-toggle.js') }}"></script> <!--- toggle conntent riwayat harian siswa ---->
<script src="{{ asset('js/Tanya/content-riwayat-tanya-harian-student.js') }}"></script> <!--- content tanya riwayat harian ---->
<script src="{{ asset('js/Tanya/updateStatusSoal-daily-student-handler.js') }}"></script> <!--- handler button mark the question as read (tanya harian) ---->

<!--- components ---->
<script src="{{ asset('js/components/clear-error-on-input.js') }}"></script> <!--- clear error on input ---->
<script src="{{ asset('js/components/question-answer-page-section/content-tanya-riwayat-toggle.js') }}"></script> <!--- content riwayat tanya ---->
<script src="{{ asset('js/components/dropdown-mapel.js') }}"></script> <!--- dropdown mapel ---->
<script src="{{ asset('js/components/btn-close-alert-success.js') }}"></script> <!--- btn close alert success ---->
<script src="{{ asset('js/components/question-answer-page-section/restore-tanya-riwayat-radio-on-back.js') }}">
</script> <!--- untuk mengembalikan radio header content tanya riwayat  ---->

<!--- PUSHER LISTENER TANYA ---->
<script src="{{ asset('js/pusher-listener/tanya/notif-badge-answered-rejected.js') }}"></script> <!--- pusher listener notif badge answer, rejected ---->
{{-- <script src="{{ asset('js/pusher-listener/tanya/end-to-end-tanya.js') }}"></script> <!--- pusher listener mengirim dan menjawab pertanyaan ----> --}}

<script>
    // Script untuk mendengarkan event broadcast pada saat student bertanya ke mentor
    let currentStatusTanyaMentor = 'semua';
    document.addEventListener("DOMContentLoaded", () => {
        window.Echo.channel('tanya')
            .listen('.question.created', (e) => {
                // console.log('✅ Komentar diterima dari broadcast:', e);
                // Saat ada data baru, ambil ulang semua data dengan AJAX
                console.log('✅ Broadcast diterima:', e); // <- Harusnya muncul
                fetchFilteredDataTanyaMentor(currentStatusTanyaMentor);
            });
    });
</script>
