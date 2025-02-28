@include('components/sidebar_beranda')
@extends('components/sidebar_beranda_mobile')


@if (isset($user))
    @if ($user->status === 'Admin Sales')
        <div class="home-beranda  z-[-1] md:z-0 mt-[80px] md:mt-0">
            <div class="content-beranda">
                <main>
                    <section class="bg-white shadow-lg p-6 rounded-lg border-gray-200 border-[1px]">
                        <header class="text-lg mb-8">UPLOAD SURAT PKS</header>
                        <form action="{{ route('suratPKS.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="hidden">
                                <input type="text" name="nama_lengkap" value="{{ $user->nama_lengkap }}">
                                <input type="text" name="status" value="{{ $user->status }}">
                            </div>
                            <div class="flex flex-col w-full mb-8">
                                <label class="mb-2 text-md">Tipe Surat PKS</label>
                                <select name="tipe_surat_pks"
                                    class="bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer {{ $errors->has('tipe_surat_pks') ? 'border-[1px] border-red-400 shadow-[0_0_10px_0_red]' : '' }}">
                                    <option value="" class="hidden">Pilih Tipe Surat PKS</option>
                                    <option value="englishZone"
                                        {{ @old('tipe_surat_pks') === 'englishZone' ? 'selected' : '' }}>
                                        English Zone
                                    </option>
                                </select>
                                @error('tipe_surat_pks')
                                    <span class="text-red-500 font-bold text-sm pl-2 mt-2">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="w-full">
                                <div class="w-full h-auto">
                                    <span class="text-sm">Upload Surat PKS<sup
                                            class="text-red-500 pl-1">&#42;</sup></span>
                                    <div class="text-xs mt-1">
                                        <span>Maksimum ukuran file 10MB. <br> File dapat dalam format PDF</span>
                                    </div>
                                    <div class="upload-icon">
                                        <div class="flex flex-col max-w-[260px]">
                                            <div id="pdfPreview" class="max-w-[280px] cursor-pointer mt-4">
                                                <div id="pdfPreviewContainer-suratPKS"
                                                    class="bg-white shadow-lg rounded-lg w-max py-2 pr-4 border-[1px] border-gray-200 hidden">
                                                    <div class="flex">
                                                        <img id="pdfLogo-suratPKS" class="w-[56px] h-max">
                                                        <div class="mt-2 leading-5">
                                                            <span id="textPreview-suratPKS"
                                                                class="font-bold text-sm"></span><br>
                                                            <span id="textSize-suratPKS" class="text-xs"></span>
                                                            <span id="textCircle-suratPKS"
                                                                class="relative top-[-2px] text-[5px]"></span>
                                                            <span id="textPages-suratPKS" class="text-xs"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="content-upload w-full h-10 bg-[#4189e0] hover:bg-blue-500 text-white font-bold rounded-lg mt-6 mb-2">
                                    <label for="file-upload-suratPKS"
                                        class="w-full h-full flex justify-center items-center cursor-pointer gap-2">
                                        <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                        <span>Upload Surat PKS</span>
                                    </label>
                                    <input id="file-upload-suratPKS" name="surat_pks" class="hidden"
                                        onchange="previewPDF(event, 'suratPKS')" type="file"
                                        accept="application/pdf">
                                </div>
                                @error('surat_pks')
                                    <span class="text-red-500 font-bold text-sm pl-2 mt-2">{{ $message }}</span>
                                @enderror
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
        <div class="flex flex-col min-h-screen items-center justify-center">
            <p>ALERT SEMENTARA</p>
            <p>You do not have access to this pages.</p>
        </div>
    @endif
    <span>You are not logged in</span>
@endif

<script src="js/upload-pdf.js"></script> {{-- upload pdf --}}
