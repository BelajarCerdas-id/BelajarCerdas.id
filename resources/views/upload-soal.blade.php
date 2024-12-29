@include('components/sidebar_beranda')
@extends('components/sidebar_beranda_mobile')
@if (isset($user))
    @if ($user->status === 'Administrator')
        <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
            <div class="content-beranda">
                <div class="bg-white shadow-lg rounded-lg w-full h-auto border-[1px] border-gray-200">
                    <div class="pt-6 px-5">
                        <h1>Tipe Upload</h1>
                        <input type="radio" name="tipeUpload" id="soal" checked>
                        <label for="soal" class="radioList">
                            <a>Soal</a>
                        </label>
                        <input type="radio" name="tipeUpload" id="bulkUpload">
                        <label for="bulkUpload" class="radioList">
                            <a>Bulk Upload</a>
                        </label>
                    </div>
                    <form action="{{ route('englishZone.uploadSoal') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="flex w-full" id="editor-container"
                            data-upload-url="{{ route('englishZone.uploadImage', ['_token' => csrf_token()]) }}"
                            data-delete-url="{{ route('englishZone.deleteImage') }}">

                            <input type="hidden" name="nama_lengkap" value="{{ $user->nama_lengkap }}">
                            <input type="hidden" name="status" value="{{ $user->status }}">
                            <input type="hidden" name="tipe_upload" value="Soal">
                            <div class="mx-6 my-4 w-full">
                                <div class="leading-10">
                                    <span>Soal</span>
                                    <textarea name="soal" class="editor"></textarea>
                                </div>
                                <div class="grid grid-cols-2 gap-8 mt-10">
                                    @foreach ($englishZoneBobot as $item)
                                        <div class="w-full leading-10">
                                            <span class="">{{ $item['title_editor'] }}</span>
                                            <textarea name="{{ $item['value_editor'] }}" class="editor"></textarea>
                                            <select name="{{ $item['value_bobot'] }}"
                                                class="w-full bg-gray-100 outline-none text-xs p-3 cursor-pointer mb-2">
                                                <option value="" class="hidden">{{ $item['title_bobot'] }}
                                                </option>
                                                <option>5 Poin</option>
                                                <option>4 Poin</option>
                                                <option>3 Poin</option>
                                                <option>2 Poin</option>
                                                <option>1 Poin</option>
                                            </select>
                                        </div>
                                    @endforeach
                                    <div class="relative mt-4 w-full">
                                        <label>Tingkat Kesulitan</label>
                                        <select name="tingkat_kesulitan"
                                            class="w-full bg-gray-100 outline-none rounded-xl text-xs p-3 cursor-pointer mt-2 mb-2">
                                            <option value="" class="hidden">Pilih tingkat kesulitan</option>
                                            <option>Mudah</option>
                                            <option>Sedang</option>
                                            <option>Sulit</option>
                                        </select>

                                    </div>
                                    <div class="leading-10">
                                        <span>jawaban</span>
                                        <textarea name="jawaban" class="editor"></textarea>
                                    </div>
                                    <div class="relative leading-10 mb-20">
                                        <span>Deskripsi Jawaban</span>
                                        <textarea name="deskripsi_jawaban" class="editor"></textarea>
                                        <button
                                            class="absolute right-0 bg-red-500 w-[150px] h-8 text-white font-bold rounded-lg mt-4">Kirim</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="flex w-full" id="editor-container"
                            data-upload-url="{{ route('englishZone.uploadImage', ['_token' => csrf_token()]) }}"
                            data-delete-url="{{ route('englishZone.deleteImage', ['_token' => csrf_token()]) }}">
                            <input type="text" class="border-2" name="title">
                            <textarea name="description" id="editor" cols="60" rows="60"></textarea>
                            <button type="submit">Kirim</button>
                        </div> --}}
                    </form>
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


<script src="js/ckeditor.js"></script> {{-- script ckeditor untuk menampilkan dan mendelete gambar diserver setelah user menghapus gambar di editor --}}
