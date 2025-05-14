@include('components/sidebar_beranda', ['headerSideNav' => 'TANYA'])

@if (Auth::user()->role === 'Siswa' or Auth::user()->role === 'Murid')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            <!--- alert success setelah kirim pertanyaan --->
            @if (session('success-insert-tanya'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-insert-tanya'),
                ])
            @endif
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
            <div class="bg-[--color-second] w-full h-20 shadow-lg rounded-t-xl flex items-center pl-10 mb-10">
                <div class="text-white font-bold flex items-center gap-3">
                    <i class="fas fa-question text-2xl"></i>
                    <span class="text-xl">Tanya</span>
                </div>
            </div>
            <div class="flex mt-10">
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
                    <form id="tanya-form" method="POST" enctype="multipart/form-data">
                        @csrf
                        <!--- Option kelas & Mata pelajaran ----->
                        <div class="grid grid-cols-12 gap-8">
                            <!--- Option fase ----->
                            <div class="col-span-12 lg:col-span-6 flex flex-col">
                                <label class="mb-2 text-sm">Fase<sup class="text-red-500 pl-1">&#42;</sup></label>
                                <select name="fase_id" id="id_fase"
                                    class="bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer
                                    {{ session('formError') === 'create' && $errors->has('fase_id') ? 'border-red-500' : '' }}">
                                    <option value="" class="hidden">Pilih Fase</option>
                                    @foreach ($getFase as $item)
                                        @if ($item->nama_fase === Auth::user()->Profile->fase)
                                            <option value="{{ $item->id }}">{{ $item->nama_fase }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @if (session('formError') === 'create' && $errors->has('fase_id'))
                                    <span
                                        class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('fase_id') }}</span>
                                @endif
                            </div>
                            <!--- Option kelas ----->
                            <div class="col-span-12 lg:col-span-6 flex flex-col">
                                <label class="mb-2 text-sm">Kelas<sup class="text-red-500 pl-1">&#42;</sup></label>
                                <select name="kelas_id" id="id_kelas"
                                    class="bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 opacity-50 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-default
                                    {{ session('formError') === 'create' && $errors->has('kelas_id') ? 'border-red-500' : '' }}"
                                    disabled>
                                    <option class="hidden">Harap pilih fase</option>
                                </select>
                                @if (session('formError') === 'create' && $errors->has('kelas_id'))
                                    <span
                                        class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('kelas_id') }}</span>
                                @endif
                            </div>
                            <!--- Option Mata pelajaran ----->
                            <div class="col-span-12 lg:col-span-6 flex flex-col">
                                <div class="">
                                    <span>
                                        Mata Pelajaran
                                        <sup class="text-red-500 pl-1">&#42;</sup>
                                    </span>

                                    <!-- Hidden input agar value tetap bisa dikirim saat submit -->
                                    <input type="hidden" name="mapel_id" id="id_mapel">
                                    <input type="hidden" name="harga_koin" id="harga_koin">

                                    <div class="w-full relative my-2 border-gray-200 border-[2px] outline-none"
                                        id="dropdownWrapper">
                                        <div id="dropdownButton"
                                            class="h-12 px-4 bg-white shadow-lg cursor-pointer flex justify-between items-center font-bold pointer-events-none opacity-50 {{ session('formError') === 'create' && $errors->has('mapel_id') ? 'border border-red-500' : '' }}"
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
                                    @if (session('formError') === 'create' && $errors->has('mapel_id'))
                                        <span
                                            class="error-message-mapel text-red-500 font-bold text-xs pt-2">{{ $errors->first('mapel_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <!--- Option bab ----->
                            <div class="col-span-12 lg:col-span-6 flex flex-col">
                                <label class="mb-2 text-sm">Bab<sup class="text-red-500 pl-1">&#42;</sup></label>
                                <select name="bab_id" id="id_bab"
                                    class="bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 opacity-50 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-default
                                    {{ session('formError') === 'create' && $errors->has('bab_id') ? 'border-red-500' : '' }}"
                                    disabled>
                                    <option class="hidden">Harap pilih mata pelajaran</option>
                                </select>
                                @if (session('formError') === 'create' && $errors->has('bab_id'))
                                    <span
                                        class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('bab_id') }}</span>
                                @endif
                            </div>
                        </div>

                        <!--- Kolom Pertanyaan & Upload image pertanyaan ----->
                        <div class="grid grid-cols-12 gap-8 my-8">
                            <!--- Kolom Pertanyaan ----->
                            <div class="col-span-12 lg:col-span-6 flex flex-col h-44 relative">
                                <label class="mb-2 text-sm">Pertanyaan<sup class="text-red-500 pl-1">&#42;</sup></label>
                                <textarea name="pertanyaan"
                                    class="p-4 resize-none bg-white shadow-lg h-32 border-gray-200 border-[2px] outline-none rounded-md px-4 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue]
                                    {{ session('formError') === 'create' && $errors->has('pertanyaan') ? 'border-red-500' : '' }}"
                                    placeholder="Masukkan Pertanyaan....">{{ @old('pertanyaan') }}</textarea>
                                @if (session('formError') === 'create' && $errors->has('pertanyaan'))
                                    <span
                                        class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('pertanyaan') }}</span>
                                @endif
                            </div>
                            <!--- Upload image pertanyaan ----->
                            <div class="col-span-12 lg:col-span-6">
                                <div class="w-2/4 h-auto">
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
                                        class="content-upload w-[200px] h-10 bg-[#4189e0] hover:bg-blue-500 text-white font-bold rounded-lg mt-6">
                                        <label for="file-upload"
                                            class="w-full h-full flex justify-center items-center cursor-pointer gap-2">
                                            <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                            <span>Upload Gambar</span>
                                        </label>
                                        <input id="file-upload" name="image_tanya" class="hidden"
                                            onchange="previewImage(event)" type="file" accept=".jpg, .png, .jpeg">
                                    </div>
                                </div>
                                @if (session('formError') === 'create' && $errors->has('image_tanya'))
                                    <span
                                        class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('image_tanya') }}</span>
                                @endif
                            </div>
                        </div>

                        <!--- Button form ----->
                        <div class="flex justify-end mt-8">
                            @if ($getLimitedTanya->count() == 100)
                                <div class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all"
                                    onclick="limitTanyaAlert()">
                                    limit
                                </div>
                            @else
                                <button
                                    class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all">
                                    Kirim
                                </button>
                            @endif
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
                    @if (isset($siswaHistoryRestore) && is_iterable($siswaHistoryRestore) && $siswaHistoryRestore->isNotEmpty())
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
                            <div class="pagination-container-siswa"></div>
                            <div class="flex justify-center">
                                <span class="showMessage hidden absolute top-2/4">Tidak ada
                                    riwayat</span>
                            </div>
                        </div>
                    @else
                        <div class="h-full flex justify-center items-center">
                            <span>Tidak ada riwayat</span>
                        </div>
                    @endif
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
                                    <span class="text-md flex justify-center relative top-1">Belum Terjawab</span>
                                    <div class="w-full border-b-[1px] border-gray-200 h-2"></div>
                                </label>
                            </div>
                        </div>
                        <div class="w-full hover:bg-gray-100">
                            <input type="radio" name="radio1" id="answer" onclick="contentAnswer()">
                            <div class="historyTanya">
                                <label for="answer" class="cursor-pointer">
                                    <span class="text-md flex justify-center relative top-1">
                                        Terjawab
                                        @if ($countDataTanyaAnsweredUser->count() > 0)
                                            <span id="notifBadgeAnswered"
                                                class="relative left-2 top-[1px] text-[15px] text-green-500">
                                                {{ $countDataTanyaAnsweredUser->count() }}
                                            </span>
                                        @endif
                                    </span>
                                    <div class="w-full border-b-[1px] border-gray-200 h-2"></div>
                                </label>
                            </div>
                        </div>
                        <div class="w-full hover:bg-gray-100" onclick="contentReject()">
                            <input type="radio" name="radio1" id="reject">
                            <div class="historyTanya">
                                <label for="reject" class="cursor-pointer">
                                    <span class="text-md flex justify-center relative top-1">
                                        Ditolak
                                        @if ($countDataTanyaRejectedUser->count() > 0)
                                            <span id="notifBadgeRejected"
                                                class="relative left-2 top-[1px] text-[15px] text-red-500">
                                                {{ $countDataTanyaRejectedUser->count() ?? '' }}
                                            </span>
                                        @endif
                                    </span>
                                    <div class="w-full border-b-[1px] border-gray-200 h-2"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative w-full max-h-[494px] overflow-y-auto overflow-hidden" id="dailyTanya">
                    <div class="w-full" id="contentUnanswered">
                        <div class="p-6">
                            @if (isset($historyStudent) && is_iterable($historyStudent) && $historyStudent->isNotEmpty())
                                @foreach ($historyStudent as $item)
                                    <div class="flex items-center gap-8 leading-8 mt-6 p-4">
                                        @if (!empty($item->image_tanya))
                                            <div class="w-[60px] h-[75px]">
                                                <img src="{{ asset('images_tanya/' . $item->image_tanya) }}"
                                                    alt="" class="h-full w-full">
                                            </div>
                                        @else
                                            <div
                                                class="w-[60px] h-[85px] flex items-center text-xs bg-white shadow-md rounded-md">
                                                <span class="text-center w-full">No <br>Image</span>
                                            </div>
                                        @endif
                                        <div class="flex flex-col justify-center">
                                            <span class="text-xs">{{ $item->Kelas->kelas }}</span>
                                            <span class="text-sm">{{ $item->Mapel->mata_pelajaran }}</span>
                                            <span class="text-sm font-bold">{{ $item->Bab->nama_bab }}</span>
                                            <div class="">
                                                <i class="fa-solid fa-clock text-gray-400"></i>
                                                <span
                                                    class="text-xs">{{ $item->created_at->locale('id')->translatedFormat('l, d-M-Y, H:i:s') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-b-2 border-gray-200"></div>
                                @endforeach
                            @else
                                <div class="h-full w-full flex justify-center items-center">
                                    <span>Belum ada riwayat harian</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="w-full h-auto hidden" id="contentAnswer">
                        <div class="p-6 w-full">
                            @if (isset($historyStudentAnswered) && is_iterable($historyStudentAnswered) && $historyStudentAnswered->isNotEmpty())
                                <button id="updateStatusSoalAll"
                                    class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold p-2 rounded-lg shadow-md transition-all text-sm"
                                    data-url-id="{{ route('tanya.updateAllStatusSoalById', Auth::user()->id) }}">
                                    Tandai Semua
                                </button>
                                @foreach ($historyStudentAnswered as $item)
                                    <div class="updateStatusSoal flex items-center justify-between mt-6 p-4 rounded-lg
                                        {{ $item->status_soal_student === 'Belum Dibaca' ? 'unRead bg-blue-50 cursor-pointer' : '' }}"
                                        data-url-id="{{ route('tanya.updateStatusSoalById', $item->id) }}">

                                        <div class="flex items-center gap-8 leading-8">
                                            @if (!empty($item->image_tanya))
                                                <div class="w-[60px] h-[75px]">
                                                    <img src="{{ asset('images_tanya/' . $item->image_tanya) }}"
                                                        alt="" class="h-full w-full">
                                                </div>
                                            @else
                                                <div
                                                    class="w-[60px] h-[85px] flex items-center text-xs bg-white shadow-md rounded-md">
                                                    <span class="text-center w-full">No <br>Image</span>
                                                </div>
                                            @endif
                                            <div class="flex flex-col justify-center">
                                                <span class="text-xs">{{ $item->Kelas->kelas }}</span>
                                                <span class="text-sm">{{ Str::limit($item->pertanyaan, 20) }}</span>
                                                <span class="text-sm font-bold">{{ $item->Bab->nama_bab }}</span>
                                                <div class="">
                                                    <i class="fa-solid fa-clock text-gray-400"></i>
                                                    <span
                                                        class="text-xs">{{ $item->created_at->locale('id')->translatedFormat('l, d-M-Y, H:i:s') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Tombol Lihat -->
                                        <div class="">
                                            <a href="{{ route('tanya.updateStatusSoalRestore', $item->id) }}"
                                                class="text-[#4189e0] font-bold text-sm">
                                                Lihat
                                            </a>
                                        </div>
                                    </div>

                                    <div class="border-b-2 border-gray-200"></div>
                                @endforeach
                            @else
                                <div class="h-full w-full flex justify-center items-center">
                                    <span>Belum ada riwayat terjawab</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="w-full h-auto hidden" id="contentReject">
                        <div class="p-6 w-full">
                            @if (isset($historyStudentReject) && is_iterable($historyStudentReject) && $historyStudentReject->isNotEmpty())
                                <button id="updateStatusSoalAllRejected"
                                    class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold p-2 rounded-lg shadow-md transition-all text-sm"
                                    data-url-id="{{ route('tanya.updateAllStatusSoalRejectedById', Auth::user()->id) }}">
                                    Tandai Semua
                                </button>
                                @foreach ($historyStudentReject as $item)
                                    <div class="updateStatusSoalRejected flex items-center justify-between mt-6 p-4 rounded-lg
                                        {{ $item->status_soal_student === 'Belum Dibaca' ? 'rejectedUnRead bg-blue-50 cursor-pointer' : '' }}"
                                        data-url-id="{{ route('tanya.updateStatusSoalById', $item->id) }}">

                                        <div class="flex items-center gap-8 leading-8">
                                            @if (!empty($item->image_tanya))
                                                <div class="w-[60px] h-[75px]">
                                                    <img src="{{ asset('images_tanya/' . $item->image_tanya) }}"
                                                        alt="" class="h-full w-full">
                                                </div>
                                            @else
                                                <div
                                                    class="w-[60px] h-[85px] flex items-center text-xs bg-white shadow-md rounded-md">
                                                    <span class="text-center w-full">No <br>Image</span>
                                                </div>
                                            @endif
                                            <div class="flex flex-col justify-center">
                                                <span class="text-xs">{{ $item->Kelas->kelas }}</span>
                                                <span class="text-sm">{{ Str::limit($item->pertanyaan, 20) }}</span>
                                                <span class="text-sm font-bold">{{ $item->Bab->nama_bab }}</span>
                                                <div class="">
                                                    <i class="fa-solid fa-clock text-gray-400"></i>
                                                    <span
                                                        class="text-xs">{{ $item->created_at->locale('id')->translatedFormat('l, d-M-Y, H:i:s') }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Tombol Lihat -->
                                        <div class="">
                                            <a href="{{ route('tanya.updateStatusSoalRestore', $item->id) }}"
                                                class="text-[#4189e0] font-bold text-sm">
                                                Lihat
                                            </a>
                                        </div>
                                    </div>

                                    <div class="border-b-2 border-gray-200"></div>
                                @endforeach
                            @else
                                <div class="h-full w-full flex justify-center items-center">
                                    <span>Belum ada riwayat ditolak</span>
                                </div>
                            @endif
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
                    @if (isset($getTanya) && is_iterable($getTanya) && $getTanya->isNotEmpty())
                        <div class="overflow-x-auto m-4">
                            <table class="table" id="tableTanyaTeacher">
                                <thead class="thead-table">
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
                            <div class="pagination-container-tanya"></div>
                        </div>
                    @else
                        <div class="h-96 flex justify-center items-center">
                            <span>Tidak ada pertanyaan</span>
                        </div>
                    @endif
                </div>
                <div class="w-full h-auto hidden" id="riwayat">
                    @if (isset($teacherHistoryRestore) && is_iterable($teacherHistoryRestore) && $teacherHistoryRestore->isNotEmpty())
                        <div class="absolute right-8 top-2">
                            <select name="" id="statusFilterRiwayatMentor"
                                class="w-[150px] h-10 rounded-lg flex justify-center items-center px-2 border-[1px] outline-none text-sm cursor-pointer bg-white">
                                <div class="border-none">
                                    <option value="" class="hidden">Filter Data</option>
                                    <option value="semua">Lihat Semua</option>
                                    <option value="Diterima">Diterima</option>
                                    <option value="Ditolak">Ditolak</option>
                                </div>
                            </select>
                        </div>
                        <div class="overflow-x-auto my-16 mx-4">
                            <table class="table" id="filterTableTeacher">
                                <thead class="thead-table">
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
                            <div class="pagination-container-riwayat"></div>
                            <div class="flex justify-center">
                                <span class="emptyMessage hidden absolute top-2/4">Tidak ada riwayat</span>
                            </div>
                        </div>
                    @else
                        <div class="flex justify-center items-center">
                            Tidak ada Riwayat
                        </div>
                    @endif
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

<script src="js/upload-image.js"></script> {{-- upload image(js) --}}
<script src="js/content-riwayat.js"></script> {{-- content tanya riwayat(js) --}}
<script src="js/Tanya/content-riwayat-harian-siswa.js"></script> {{-- content tanya riwayat harian(js) --}}
<script src="js/Tanya/riwayat-siswa-ajax.js"></script> {{-- script ajax filter data --}}
<script src="js/Tanya/tanya-guru-ajax.js"></script>
<script src="js/Tanya/riwayat-guru-ajax.js"></script>
<script src="js/Tanya/updateStatusSoal-daily-student-handler.js"></script>

<script>
    let currentStatus = 'semua';
    document.addEventListener("DOMContentLoaded", () => {
        window.Echo.channel('tanya')
            .listen('.question.created', (e) => {
                // console.log('✅ Komentar diterima dari broadcast:', e);
                // Saat ada data baru, ambil ulang semua data dengan AJAX
                fetchFilteredDataTanyaMentor(currentStatus);
            });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cek semua inputan dan hapus error message ketika user mengetik
        document.querySelectorAll('input, select, textarea').forEach(function(el) {
            el.addEventListener('input', function() {
                // Hapus error class
                el.classList.remove('border-red-500');
                const errorMessage = el.nextElementSibling;
                if (errorMessage && errorMessage.classList.contains('text-red-500')) {
                    errorMessage.remove();
                }
            });
        });

        // Untuk dropdown mapel
        const dropdownWrapper = document.getElementById('dropdownWrapper');
        const dropdownButton = document.getElementById('dropdownButton');

        dropdownButton.addEventListener('click', function() {
            // Hapus border merah dari dropdown
            dropdownButton.classList.remove('border-red-500');
            dropdownWrapper.classList.remove('border-red-500');

            // Cari dan hapus pesan error
            const errorMessage = document.querySelector('.error-message-mapel');
            if (errorMessage) {
                errorMessage.remove();
            }
        });
    });
</script>


<script>
    function toggleDropdown() {
        let dropdownButton = document.querySelector('#dropdownButton #dropdown');
        dropdown.classList.toggle("hidden");
        let dropdownArrow = document.getElementById('dropdownArrow').classList.toggle('rotate-180');
    }

    function updateSelection(radio) {
        const selectedKurikulum = document.getElementById("selectedKurikulum");
        const selectedKoin = document.getElementById("selectedKoin");
        const selectedIconKoin = document.getElementById("selectedIconKoin");

        const label = radio.nextElementSibling;

        const selectedLabelText = label.querySelector('span').textContent;
        const selectedLabelKoin = label.querySelector('.koin').textContent;
        // kalo mau nyalin selain text (image, font awesome, svg, dll pake clodeNode)
        const selectedLabelIconKoin = label.querySelector('.iconKoin').cloneNode(true);
        const inputMapel = document.getElementById("id_mapel");
        const inputTarifKoin = document.getElementById('harga_koin');

        selectedKurikulum.textContent = selectedLabelText;
        selectedKoin.textContent = selectedLabelKoin;
        selectedIconKoin.innerHTML = '';
        selectedIconKoin.appendChild(selectedLabelIconKoin);

        // Set hidden input mapel dan trigger event change
        inputMapel.value = radio.value;
        $('#id_mapel').trigger('change');

        // Set hidden tarif koin dan trigger event change
        inputTarifKoin.value = selectedLabelKoin;
        $('#harga_koin').trigger('change');

        // Menyembunyikan dropdown setelah memilih
        toggleDropdown();
    }
</script>



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
    function limitTanyaAlert() {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Kamu telah mencapai batas limit harian bertTANYA, silahkan kembali besok!",
        });
    }
</script>

<script>
    const tanyaGuru = document.getElementById('questionTeacher');
    const riwayatGuru = document.getElementById('historyTeacher');

    function questionTeacher() {
        tanyaGuru.style.display = "block";
        riwayatGuru.style.display = "none";
    }

    function historyTeacher() {
        tanyaGuru.style.display = "none";
        riwayatGuru.style.display = "block";
    }
</script>
<script>
    $(document).ready(function() {
        var oldKelas = "{{ old('kelas') }}" // Ambil kelas yang dipilih jika ada
        var oldMapel = "{{ old('mapel') }}"; // Ambil mapel yang dipilih jika ada
        var oldBab = "{{ old('bab') }}"; // Ambil bab yang dipilih jika ada

        var selectKelas = document.getElementById('id_kelas');
        var selectMapel = document.getElementById('id_mapel');
        var selectBab = document.getElementById('id_bab');

        $('#id_fase').on('change', function() {
            var fase_id = $(this).val();
            if (fase_id) {
                $.ajax({
                    url: '/kelas/' + fase_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        selectKelas.disabled =
                            false; // untuk menonaktifkan disabled pada select kelas ketika fase sudah dipilih
                        selectKelas.classList.replace('cursor-default', 'cursor-pointer');
                        selectKelas.classList.replace('opacity-50', 'opacity-100');
                        $('#id_kelas').empty();
                        $('#id_kelas').append(
                            '<option value="" class="hidden">Pilih Kelas</option>'
                        );
                        $.each(data, function(key, kelas) {
                            $('#id_kelas').append(
                                '<option value="' + kelas.id + '"' +
                                (oldKelas == kelas.id ? ' selected' : '') +
                                '>' +
                                kelas.kelas + '</option>'
                            );
                        });

                        if (oldKelas) {
                            $('#id_kelas').val(oldKelas).trigger('change');
                        }
                    }
                });
            } else {
                $('#id_kelas').empty();
            }
        });

        if ("{{ old('fase') }}") {
            $('#id_fase').val("{{ old('fase') }}").trigger('change');
        }
        // Ketika id_fase berubah
        $('#id_fase').on('change', function() {
            var fase_id = $(this).val();
            if (fase_id) {
                $.ajax({
                    url: '/mapel/' + fase_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        const dropdown = $('#dropdown');
                        const titleDropdown = $('#selectedKurikulum');
                        dropdown.empty(); // kosongkan dropdown sebelumnya

                        // ENABLE mapel
                        $('#dropdownButton')
                            .removeClass('pointer-events-none opacity-50');

                        titleDropdown.text('Pilih Mapel');

                        if (data.length === 0) {
                            dropdown.append(
                                `<div class="px-4 py-2 text-xs text-gray-500">Tidak ada mapel</div>`
                            );
                            return;
                        }

                        data.forEach(function(mata_pelajaran, index) {
                            dropdown.append(`
                                <input type="radio" name="radio" id="drop${index}" value="${mata_pelajaran.id}" class="hidden" onchange="updateSelection(this)">
                                <label for="drop${index}" class="flex justify-between items-center hover:bg-gray-100 w-full h-10 px-4 cursor-pointer checked-dropdown-mapel">
                                    <span class="text-xs">${mata_pelajaran.mata_pelajaran}</span>
                                    <div class="text-md flex gap-[4px] items-center text-black font-normal">
                                        <span class="iconKoin">
                                            <img src="{{ asset('image/koin.png') }}" alt="" class="w-[20px] pointer-events-none">
                                        </span>
                                        <span class="koin">${mata_pelajaran.harga_koin}</span>
                                        </div>
                                </label>
                            `);
                        });

                        // Jika ada mapel yang dipilih sebelumnya
                        // Restore old value jika ada
                        if (oldMapel) {
                            const oldInput = $(`input[name="radio"][value="${oldMapel}"]`);
                            if (oldInput.length) {
                                const label = oldInput
                                    .next(); // ambil label setelah input radio
                                const selectedText = label.find('span').text();

                                $('#selectedKurikulum').text(selectedText);
                                $('#id_mapel').val(oldInput.val());
                                oldInput.prop('checked', true); // tandai sebagai terpilih
                            }
                        }

                    }
                });
            }
        });

        // Jika ada fase yang dipilih sebelumnya, set dan trigger mapel
        if ("{{ old('fase') }}") {
            $('#id_fase').val("{{ old('fase') }}").trigger('change');
        }

        // Ketika id_mapel berubah
        $('#id_mapel').on('change', function() {
            var mapel_id = $(this).val();
            var fase_id = $('#id_fase').val();
            if (mapel_id, fase_id) {
                $.ajax({
                    url: '/bab/' + mapel_id + '/' + fase_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        selectBab.disabled =
                            false; // untuk menonaktifkan disabled pada select kelas ketika fase sudah dipilih
                        selectBab.classList.replace('cursor-default', 'cursor-pointer');
                        selectBab.classList.replace('opacity-50', 'opacity-100');
                        $('#id_bab').empty();
                        $('#id_bab').append(
                            '<option value="" class="hidden">Pilih Bab</option>'
                        );
                        $.each(data, function(key, nama_bab) {
                            $('#id_bab').append(
                                '<option value="' + nama_bab.id + '"' +
                                (oldBab == nama_bab.id ? ' selected' :
                                    '') +
                                '>' +
                                nama_bab.nama_bab + '</option>'
                            );
                        });

                        // Set nilai bab yang dipilih jika ada
                        if (oldBab) {
                            $('#id_bab').val(oldBab).trigger('change');
                        }
                    }
                });
            } else {
                $('#id_bab').empty();
            }
        });

        // Jika ada mapel yang dipilih sebelumnya, set
        if ("{{ old('mapel') }}") {
            $('#id_mapel').val("{{ old('mapel') }}").trigger('change');
        }
    });
</script>
