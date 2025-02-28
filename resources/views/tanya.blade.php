@include('components/sidebar_beranda')
@extends('components/sidebar_beranda_mobile')
@if (isset($user))
    @if ($user->status === 'Siswa' or $user->status === 'Murid')
        <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
            <div class="content-beranda">
                <div class="bg-[--color-second] w-full h-20 shadow-lg rounded-t-xl flex items-center pl-10 mb-10">
                    <div class="text-white font-bold flex items-center gap-4">
                        <i class="fa-solid fa-file-lines text-4xl"></i>
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
                        <form action="{{ route('tanya.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="input-hidden hidden">
                                <input type="text" name="nama_lengkap" value="{{ $user->nama_lengkap }}">
                                <input type="text" name="email" value="{{ $user->email }}">
                                <input type="text" name="sekolah" value="{{ $user->sekolah }}">
                                <input type="text" name="fase" value="{{ $user->fase }}">
                                <input type="text" name="kelas" value="{{ $user->kelas }}">
                                <input type="text" name="no_hp" value="{{ $user->no_hp }}">
                            </div>
                            <!--- Option kelas & Mata pelajaran ----->
                            <div class="grid grid-cols-12 gap-8">
                                <!--- Option kelas ----->
                                <div class="col-span-12 lg:col-span-6 flex flex-col">
                                    <label class="mb-2 text-sm">Kelas<sup class="text-red-500 pl-1">&#42;</sup></label>
                                    @if ($user->fase === 'Fase A')
                                        <select name="kelas" id="id_kelas"
                                            class="bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer {{ $errors->has('kelas') ? 'border-[1px] border-red-400 shadow-[0_0_10px_0_red]' : '' }}">
                                            <option value="" class="hidden">Pilih Kelas</option>
                                            <option value="Kelas 1" {{ @old('kelas') === 'Kelas 1' ? 'selected' : '' }}>
                                                Kelas 1
                                            </option>
                                            <option value="Kelas 2" {{ @old('kelas') === 'Kelas 2' ? 'selected' : '' }}>
                                                Kelas 2
                                            </option>
                                        </select>
                                    @elseif($user->fase === 'Fase F+')
                                        <select name="kelas" id="id_kelas"
                                            class="w-full bg-gray-100 outline-none rounded-xl text-xs p-3 cursor-pointer mt-2 {{ $errors->has('kelas') ? 'border-[1px] border-red-500' : '' }}">
                                            <option value="" class="hidden">Pilih Kelas</option>
                                            <option value="Kelas 11"
                                                {{ @old('kelas') === 'Kelas 11' ? 'selected' : '' }}>
                                                Kelas 11
                                            </option>
                                            <option value="Kelas 12"
                                                {{ @old('kelas') === 'Kelas 12' ? 'selected' : '' }}>
                                                Kelas 12
                                            </option>
                                        </select>
                                    @else
                                    @endif
                                    @error('kelas')
                                        <span class="text-red-500 font-bold text-sm mt-1 pl-2">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!--- Option Mata pelajaran ----->
                                <div class="col-span-12 lg:col-span-6 flex flex-col">
                                    <label class="mb-2 text-sm">Mata Pelajaran<sup
                                            class="text-red-500 pl-1">&#42;</sup></label>
                                    <select name="mapel" id="id_mapel"
                                        class="bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer {{ $errors->has('mapel') ? 'border-[1px] border-red-400 shadow-[0_0_10px_0_red]' : '' }}">
                                        <option value="" class="hidden">Pilih mata pelajaran</option>
                                    </select>
                                    @error('mapel')
                                        <span class="text-red-500 font-bold text-sm mt-1 pl-2">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!--- Option Bab ----->
                                <div class="col-span-12 lg:col-span-6 flex flex-col">
                                    <label class="mb-2 text-sm">Bab<sup class="text-red-500 pl-1">&#42;</sup></label>
                                    <select name="bab" id="id_bab"
                                        class="bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer {{ $errors->has('mapel') ? 'border-[1px] border-red-400 shadow-[0_0_10px_0_red]' : '' }}">
                                        <option value="" class="hidden">Pilih Bab</option>
                                    </select>
                                    @error('bab')
                                        <span class="text-red-500 font-bold text-sm mt-1 pl-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <!--- Kolom Pertanyaan & Upload image pertanyaan ----->
                            <div class="grid grid-cols-12 gap-8 my-8">
                                <!--- Kolom Pertanyaan ----->
                                <div class="col-span-12 lg:col-span-6 flex flex-col h-44 relative">
                                    <label class="mb-2 text-sm">Pertanyaan<sup
                                            class="text-red-500 pl-1">&#42;</sup></label>
                                    <textarea name="pertanyaan"
                                        class="p-2 resize-none bg-white shadow-lg h-32 border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer {{ $errors->has('pertanyaan') ? 'border-[1px] border-red-400 shadow-[0_0_10px_0_red]' : '' }}"
                                        placeholder="Masukkan Pertanyaan">{{ @old('pertanyaan') }}</textarea>
                                    @error('pertanyaan')
                                        <span class="text-red-500 font-bold text-sm pl-2">{{ $message }}</span>
                                    @enderror
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
                                                onchange="previewImage(event)" type="file"
                                                accept=".jpg, .png, .jpeg">
                                        </div>
                                        @error('image_tanya')
                                            <div class="w-60">
                                                <span
                                                    class="relative ... top-2 text-red-500 font-bold text-sm">{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!--- Button form ----->
                            <div class="flex justify-end mt-8">
                                <button
                                    class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all">
                                    Kirim
                                </button>
                            </div>
                        </form>
                        <!-- Modal for displaying the image -->
                        <dialog id="imageModal" class="modal">
                            <div class="modal-box bg-white">
                                <button class="absolute right-2 top-2 outline-none hover:bg-slate-200 btn-circle"
                                    onclick="closeModal()">✕</button>
                                <img id="modalImage" alt="" class="w-full h-auto">
                            </div>
                        </dialog>
                    </div>
                    <div class="w-full h-auto hidden" id="riwayat">
                        <div class="flex justify-end">
                            <select name="" id="statusFilter"
                                class="w-[150px] h-10 rounded-lg flex justify-center items-center px-2 border-[1px] outline-none text-sm cursor-pointer">
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
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Pertanyaan</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Bab</th>
                                            <th>Jam_Tanya</th>
                                            <th>Jam_Jawab</th>
                                            <th>Jawaban</th>
                                            <th>status</th>
                                            <th>Detail</th>
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
                {{-- <div class="bg-white shadow-xl relative ... overflow-hidden">
                    <div class="relative ... w-full h-auto" id="tanyaSiswa" style="transform: translateX(0);">
                        <form action="{{ route('tanya.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="input-hidden hidden">
                                <input type="text" name="nama_lengkap" value="{{ $user->nama_lengkap }}">
                                <input type="text" name="email" value="{{ $user->email }}">
                                <input type="text" name="sekolah" value="{{ $user->sekolah }}">
                                <input type="text" name="fase" value="{{ $user->fase }}">
                                <input type="text" name="kelas" value="{{ $user->kelas }}">
                                <input type="text" name="no_hp" value="{{ $user->no_hp }}">
                                {{-- <input type="text" name="jam_tanya" id="jam_tanya"> --}
                            </div>
                            <div class="flex mx-6 my-6 gap-12">
                                <div class="w-full">
                                    <label class="mb-2 text-sm">Kelas<sup class="text-red-500 pl-1">&#42;</sup></label>
                                    @if (isset($user) && $user->fase === 'Fase A')
                                        <select name="kelas" id="id_kelas"
                                            class="w-full bg-gray-100 outline-none rounded-xl text-xs p-3 cursor-pointer mt-2 {{ $errors->has('kelas') ? 'border-[1px] border-red-500' : '' }}">
                                            <option value="" class="hidden">Pilih Kelas</option>
                                            <option value="Kelas 1" {{ @old('kelas') === 'Kelas 1' ? 'selected' : '' }}>
                                                Kelas 1
                                            </option>
                                            <option value="Kelas 2" {{ @old('kelas') === 'Kelas 2' ? 'selected' : '' }}>
                                                Kelas 2
                                            </option>
                                        </select>
                                    @else
                                        <p>Tidak ada akses.</p>
                                    @endif
                                    @error('kelas')
                                        <span class="text-red-500 font-bold text-sm mt-1 pl-2">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="w-full">
                                    <label class="mb-2 text-sm">Mata Pelajaran<sup
                                            class="text-red-500 pl-1">&#42;</sup></label>
                                    <select name="mapel" id="id_mapel"
                                        class="w-full bg-gray-100 outline-none rounded-xl text-xs p-3 cursor-pointer mt-2 {{ $errors->has('mapel') ? 'border-[1px] border-red-500' : '' }}">
                                        <option value="" class="hidden">Pilih mata pelajaran</option>
                                    </select>
                                    @error('mapel')
                                        <span class="text-red-500 font-bold text-sm mt-1 pl-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="w-[47.5%] mx-6">
                                <label class="mb-2 text-sm">Bab<sup class="text-red-500 pl-1">&#42;</sup></label>
                                <select name="bab" id="id_bab"
                                    class="w-full bg-gray-100 outline-none rounded-xl text-xs p-3 cursor-pointer mt-2 {{ $errors->has('bab') ? 'border-[1px] border-red-500' : '' }}">
                                    <option value="" class="hidden">Pilih Bab</option>
                                </select>
                                @error('bab')
                                    <span class="text-red-500 font-bold text-sm mt-1 pl-2">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="flex mx-6 my-6 gap-12">
                                <div class="w-2/4 relative ... h-44">
                                    <label class="mb-2 text-sm">Pertanyaan<sup
                                            class="text-red-500 pl-1">&#42;</sup></label>
                                    <textarea name="pertanyaan"
                                        class="w-full h-20 bg-gray-100 outline-none rounded-xl text-xs p-2 resize-none mt-2 {{ $errors->has('pertanyaan') ? 'border-[1px] border-red-500' : '' }}"
                                        placeholder="Masukkan Pertanyaan">{{ @old('pertanyaan') }}</textarea>
                                    <button
                                        class="absolute right-0 bottom-0 bg-red-500 w-[150px] h-8 text-white font-bold rounded-lg">Kirim</button>
                                    @error('pertanyaan')
                                        <span class="text-red-500 font-bold text-sm mt-1 pl-2">{{ $message }}</span>
                                    @enderror
                                </div>

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
                                                {{-- Image will be inserted here --}
                                                <img id="popupImage" alt="" class="">
                                            </div>
                                            <div id="textPreview" class="text-red-500 font-bold mt-2"></div>
                                        </div>
                                        <div class="text-icon"></div>
                                    </div>

                                    <div
                                        class="content-upload w-[200px] h-10 bg-red-500 text-white font-bold rounded-lg mt-6">
                                        <label for="file-upload"
                                            class="w-full h-full flex justify-center items-center cursor-pointer gap-2">
                                            <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                            <span>Upload Gambar</span>
                                        </label>
                                        <input id="file-upload" name="image_tanya" class="hidden"
                                            onchange="previewImage(event)" type="file" accept=".jpg, .png, .jpeg">
                                    </div>
                                    @error('image_tanya')
                                        <div class="w-60">
                                            <span
                                                class="relative ... top-2 text-red-500 font-bold text-sm">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </form>
                        <!-- Modal for displaying the image -->
                        <dialog id="imageModal" class="modal">
                            <div class="modal-box bg-white">
                                <button class="btn btn-sm btn-circle btn-ghost absolute right-0 top-0 outline-none"
                                    onclick="closeModal()">✕</button>
                                <img id="modalImage" alt="" class="w-full h-auto">
                            </div>
                        </dialog>

                    </div>
                    <div class="absolute top-0 w-full h-full" id="riwayatSiswa" style="transform: translateX(100%);">
                        <div class="relative">
                            <select name="" id="statusFilter"
                                class="absolute right-8 top-2 w-[150px] h-10 rounded-lg flex justify-center items-center px-2 border-[1px] outline-none text-sm cursor-pointer">
                                <div class="border-none">
                                    <option value="" class="hidden">Filter Data</option>
                                    <option value="semua">Lihat Semua</option>
                                    <option value="Diterima">Diterima</option>
                                    <option value="Ditolak">Ditolak</option>
                                </div>
                            </select>
                        </div>
                        @if (isset($siswaHistoryRestore) && is_iterable($siswaHistoryRestore) && $siswaHistoryRestore->isNotEmpty())
                            <div class="overflow-x-auto mt-16">
                                <table class="table" id="filterTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Pertanyaan</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Bab</th>
                                            <th>Jam_Tanya</th>
                                            <th>Jam_Jawab</th>
                                            <th>Jawaban</th>
                                            <th>status</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody id="filterList">
                                        {{-- show data in ajax --}
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
                </div> --}}

                <div class="lg:w-2/4 w-full h-full mt-5 bg-white rounded-lg shadow-md">
                    <header class="pb-6 p-4">
                        <span>Riwayat Harian</span>
                    </header>
                    <hr>
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
                                        <span class="text-md flex justify-center relative top-1">Terjawab</span>
                                        <div class="w-full border-b-[1px] border-gray-200 h-2"></div>
                                    </label>
                                </div>
                            </div>
                            <div class="w-full hover:bg-gray-100" onclick="contentReject()">
                                <input type="radio" name="radio1" id="reject">
                                <div class="historyTanya">
                                    <label for="reject" class="cursor-pointer">
                                        <span class="text-md flex justify-center relative top-1">Ditolak</span>
                                        <div class="w-full border-b-[1px] border-gray-200 h-2"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="relative w-full h-auto overflow-hidden">
                        <div class="w-full h-auto" id="contentUnanswered">
                            <div class="p-6">
                                @if (isset($historyStudent) && is_iterable($historyStudent) && $historyStudent->isNotEmpty())
                                    @foreach ($historyStudent as $item)
                                        <div class="flex gap-8 leading-8 mt-6">
                                            <div class="mb-10">
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
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-xs">{{ $item->kelas }}</span>
                                                <span class="text-sm">{{ $item->mapel }}</span>
                                                <span class="text-sm font-bold">{{ $item->bab }}</span>
                                                <div class="">
                                                    <i class="fa-solid fa-clock text-gray-400"></i>
                                                    <span
                                                        class="text-xs">{{ $item->created_at->locale('id')->translatedFormat('l, d-M-Y, H:i:s') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
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
                                    @foreach ($historyStudentAnswered as $item)
                                        <div class="flex gap-8 leading-8 mt-6">
                                            <div class="mb-10">
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
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-xs">{{ $item->kelas }}</span>
                                                <span class="text-sm">{{ $item->pertanyaan }}</span>
                                                <span class="text-sm font-bold">{{ $item->bab }}</span>
                                                <div class="">
                                                    <i class="fa-solid fa-clock text-gray-400"></i>
                                                    <span
                                                        class="text-xs">{{ $item->created_at->locale('id')->translatedFormat('l, d-M-Y, H:i:s') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
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
                                    @foreach ($historyStudentReject as $item)
                                        <div class="flex gap-8 leading-8 mt-6">
                                            <div class="mb-10">
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
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-xs">{{ $item->kelas }}</span>
                                                <span class="text-sm">{{ $item->mapel }}</span>
                                                <span class="text-sm font-bold">{{ $item->bab }}</span>
                                                <div class="">
                                                    <i class="fa-solid fa-clock text-gray-400"></i>
                                                    <span
                                                        class="text-xs">{{ $item->created_at->locale('id')->translatedFormat('l, d-M-Y, H:i:s') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
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
    @elseif($user->status === 'Mentor')
        <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
            <div class="content-beranda">
                <div class="bg-[--color-second] w-full h-16 shadow-lg rounded-t-xl flex items-center pl-10 mb-10">
                    <div class="text-white font-bold flex items-center gap-4">
                        <i class="fa-solid fa-file-lines text-2xl"></i>
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
                <div class="relative w-full h-auto overflow-hidden bg-white shadow-lg">
                    <div class="w-full h-auto" id="content">
                        @if (isset($getTanya) && is_iterable($getTanya) && $getTanya->isNotEmpty())
                            <div class="overflow-x-auto">
                                <table class="table" id="tableTanyaTeacher">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Siswa</th>
                                            <th>Kelas</th>
                                            <th>Pertanyaan</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Bab</th>
                                            <th>Jam_Tanya</th>
                                            <th>Detail</th>
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
                        <div class="absolute right-8 top-2">
                            <select name="" id="statusFilter"
                                class="w-[150px] h-10 rounded-lg flex justify-center items-center px-2 border-[1px] outline-none text-sm cursor-pointer bg-white">
                                <div class="border-none">
                                    <option value="" class="hidden">Filter Data</option>
                                    <option value="semua">Lihat Semua</option>
                                    <option value="Diterima">Diterima</option>
                                    <option value="Ditolak">Ditolak</option>
                                </div>
                            </select>
                        </div>
                        @if (isset($teacherHistoryRestore) && is_iterable($teacherHistoryRestore) && $teacherHistoryRestore->isNotEmpty())
                            <div class="overflow-x-auto mt-16">
                                <table class="table" id="filterTableTeacher">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Siswa</th>
                                            <th>Kelas</th>
                                            <th>Pertanyaan</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Bab</th>
                                            <th>Jam_Tanya</th>
                                            <th>Jam_Jawab</th>
                                            <th>Jawaban</th>
                                            <th>status</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody id="filterListTeacher">
                                        {{-- show data in ajax --}}
                                    </tbody>
                                </table>
                                <div class="pagination-container-riwayat"></div>
                                <div class="flex justify-center h-96">
                                    <span class="emptyMessage hidden absolute top-2/4">Tidak ada riwayat</span>
                                </div>
                            </div>
                        @else
                            <div class="h-full flex justify-center items-center">
                                <span>Tidak ada riwayat</span>
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
@else
    <p>You are not logged in.</p>
@endif

<script src="js/upload-image.js"></script> {{-- upload image(js) --}}
<script src="js/content-riwayat.js"></script> {{-- content tanya riwayat(js) --}}
<script src="js/riwayat-harian-siswa.js"></script> {{-- content tanya riwayat harian(js) --}}
<script src="js/riwayat-siswa-ajax.js"></script> {{-- script ajax filter data --}}
<script src="js/tanya-guru-ajax.js"></script>
<script src="js/riwayat-guru-ajax.js"></script>

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
        var oldMapel = "{{ old('mapel') }}"; // Ambil mapel yang dipilih jika ada
        var oldBab = "{{ old('bab') }}"; // Ambil bab yang dipilih jika ada

        // Ketika id_kelas berubah
        $('#id_kelas').on('change', function() {
            var kode_kelas = $(this).val();
            if (kode_kelas) {
                $.ajax({
                    url: '/mapel/' + kode_kelas,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#id_mapel').empty();
                        $('#id_mapel').append(
                            '<option value="" class="hidden">---Pilih Mata Pelajaran----</option>'
                        );
                        $.each(data, function(key, mapel) {
                            $('#id_mapel').append(
                                '<option value="' + mapel.kode + '"' +
                                (oldMapel == mapel.kode ? ' selected' : '') +
                                '>' +
                                mapel.mapel + '</option>'
                            );
                        });

                        // Jika ada mapel yang dipilih sebelumnya, trigger change
                        if (oldMapel) {
                            $('#id_mapel').val(oldMapel).trigger('change');
                        }
                    }
                });
            } else {
                $('#id_mapel').empty();
            }
        });

        // Jika ada kelas yang dipilih sebelumnya, set dan trigger
        if ("{{ old('kelas') }}") {
            $('#id_kelas').val("{{ old('kelas') }}").trigger('change');
        }

        // Ketika id_mapel berubah
        $('#id_mapel').on('change', function() {
            var kode_mapel = $(this).val();
            if (kode_mapel) {
                $.ajax({
                    url: '/bab/' + kode_mapel,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#id_bab').empty();
                        $('#id_bab').append(
                            '<option value="" class="hidden">---Pilih Bab----</option>'
                        );
                        $.each(data, function(key, bab) {
                            $('#id_bab').append(
                                '<option value="' + bab.bab + '"' +
                                (oldBab == bab.bab ? ' selected' : '') +
                                '>' +
                                bab.bab + '</option>'
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

{{-- <td>{{ Str::limit($item->pertanyaan, 5) }}</td>
                                        <td>{{ $item->mapel }}</td>
                                        <td>{{ $item->bab }}</td>
                                        <td>{{ $item->created_at->locale('id')->translatedFormat('l, d-M-Y, h:i:s') }}
                                        </td>
                                        <td>{{ $item->updated_at->locale('id')->translatedFormat('l, d-M-Y, h:i:s') }}
                                        </td>
                                        <td>{{ $item->jawaban }}</td>
                                        <td><a href="{{ route('tanya.edit', $item->id) }}">Lihat</a></td>
                                        <td>
                                            @if ($item->image_tanya)
                                                <img src="{{ asset('images_tanya/' . $item->image_tanya) }}"
                                                    alt="Gambar Pertanyaan" style="max-width: 100px;">
                                            @endif
                                        </td> --}
                                </tr> --}}


{{-- Cek dan tampilkan data dari $siswaHistoryRestore di akhir tabel --}}
{{-- @if (isset($siswaHistoryRestore) && is_iterable($siswaHistoryRestore) && $siswaHistoryRestore->isNotEmpty())
                                    @foreach ($siswaHistoryRestore as $item)
                                        <tr>
                                            <td>{{ $item-> }}</td>
                                            <!-- Menyesuaikan nomor urut -->
                                            <td>{{ Str::limit($item->pertanyaan, 5) }}</td>
                                            <td>{{ $item->mapel }}</td>
                                            <td>{{ $item->bab }}</td>
                                            <td>{{ $item->created_at->locale('id')->translatedFormat('l, d-M-Y, h:i:s') }}
                                            </td>
                                            <td>{{ $item->updated_at->locale('id')->translatedFormat('l, d-M-Y, h:i:s') }}
                                            </td>
                                            <td>{{ $item->jawaban }}</td>
                                            <td><a href="{{ route('tanya.edit', $item->id) }}">Lihat</a></td>
                                            <td>
                                                @if ($item->image_tanya)
                                                    <img src="{{ asset('images_tanya/' . $item->image_tanya) }}"
                                                        alt="Gambar Pertanyaan" style="max-width: 100px;">
                                                @else
                                                    <span>Tidak ada gambar</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif --}}
{{-- @else
                <div class="h-full flex justify-center items-center">
                    <span>Tidak ada riwayat</span>
                </div>
    @endif --}}


{{-- @if (isset($siswaHistoryRestore) && is_iterable($siswaHistoryRestore) && $siswaHistoryRestore->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Pertanyaan</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Bab</th>
                                    <th>Jam_Tanya</th>
                                    <th>Jam_Jawab</th>
                                    <th>Jawaban</th>
                                    <th>Detail</th>
                                    <th>Images</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($siswaHistoryRestore as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ Str::limit($item->pertanyaan, 5) }}</td>
                                        <td>{{ $item->mapel }}</td>
                                        <td>{{ $item->bab }}</td>
                                        <td>{{ $item->created_at->locale('id')->translatedFormat('l, d-M-Y, h:i:s') }}
                                        </td>
                                        <td>{{ $item->updated_at->locale('id')->translatedFormat('l, d-M-Y, h:i:s') }}
                                        </td>
                                        <td>{{ $item->jawaban }}</td>
                                        <td><a href="{{ route('tanya.edit', $item->id) }}">Lihat</a></td>
                                        <td>
                                            @if ($item->image_tanya)
                                                <img src="{{ asset('images_tanya/' . $item->image_tanya) }}"
                                                    alt="Gambar Pertanyaan" style="max-width: 100px;">
                                            @endif
                                            {{--  asset untuk merujuk ke berkas seperti css, js, dll yang ada pada folder public --}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="h-full flex justify-center items-center">
                        <span>Tidak ada riwayat</span>
                    </div>
                @endif --}}
