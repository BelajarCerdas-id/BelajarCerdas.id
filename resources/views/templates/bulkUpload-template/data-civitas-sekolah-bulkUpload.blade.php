@include('components/sidebar_beranda')
@extends('components/sidebar_beranda_mobile')

@if (session('user')->status === 'Admin Sales')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            <main>
                <section class="bg-white shadow-lg p-6 rounded-lg border-gray-200 border-[1px]">
                    <form action="{{ route('bulk-upload-civitas-sekolah.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="hidden">
                            <input type="text" name="nama_lengkap" value="{{ session('user')->nama_lengkap }}">
                            <input type="text" name="status" value="{{ session('user')->status }}">
                            <input type="text" name="jenis_file" value="Excel">
                            <input type="text" name="status_template" value="BulkUpload_civitas_sekolah">
                        </div>
                        <div class="w-full">
                            <div class="w-full h-auto">
                                <span class="text-sm">Template Civitas Sekolah<sup
                                        class="text-red-500 pl-1">&#42;</sup></span>
                                <div class="text-xs mt-1">
                                    <span>Maksimum ukuran file 10MB. <br> File dapat dalam format .xlsx.</span>
                                </div>
                                <div class="upload-icon">
                                    <div class="flex flex-col max-w-[260px]">
                                        <div id="excelPreview" class="max-w-[280px] cursor-pointer mt-4">
                                            <div id="excelPreviewContainer-civitas-data-sekolah"
                                                class="bg-white shadow-lg rounded-lg w-max py-2 pr-4 border-[1px] border-gray-200 hidden">
                                                <div class="flex items-center">
                                                    <img id="pdfLogo-civitas-data-sekolah" class="w-[56px] h-max">
                                                    <div class="mt-2 leading-5">
                                                        <span id="textPreview-civitas-data-sekolah"
                                                            class="font-bold text-sm"></span><br>
                                                        <span id="textSize-civitas-data-sekolah" class="text-xs"></span>
                                                        <span id="textCircle-civitas-data-sekolah"
                                                            class="relative top-[-2px] text-[5px]"></span>
                                                        <span id="textPages-civitas-data-sekolah"
                                                            class="text-xs"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="content-upload w-full h-10 bg-[#4189e0] hover:bg-blue-500 text-white font-bold rounded-lg mt-6 mb-2">
                                <label for="file-upload-civitas-data-sekolah"
                                    class="w-full h-full flex justify-center items-center cursor-pointer gap-2">
                                    <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                    <span>Upload BulkUpload Template</span>
                                </label>
                                <input id="file-upload-civitas-data-sekolah" name="nama_file" class="hidden"
                                    onchange="previewExcel(event, 'civitas-data-sekolah')" type="file"
                                    accept=".xlsx">
                            </div>
                            @error('nama_file')
                                <span class="text-red-500 font-bold text-sm pl-2 mt-2">{{ $message }}</span>
                            @enderror
                            @if ($errors->has('status_template'))
                                <!--- first digunakan untuk menampilkan status_template yang sesuai dengan validasi, jika input kosong maka require akan berjalan, dll --->
                                <!--- jika tidak menggunakan first dan ada salah satu kegagalan, maka semua validasi akan berjalan --->
                                <div class="text-red-500 text-sm font-bold mt-2">
                                    {{ $errors->first('status_template') }}
                                </div>
                            @endif
                        </div>
                        <!-- Tombol Kirim -->
                        <div class="flex justify-end mt-8">
                            <button
                                class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all">
                                Kirim
                            </button>
                        </div>
                    </form>
                </section>
            </main>
        </div>
    </div>
@else
    <p>You do not have access to this pages.</p>
@endif

<script src="js/upload-excel.js"></script>
