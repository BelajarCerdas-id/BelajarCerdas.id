@include('components/sidebar_beranda')
@extends('components/sidebar_beranda_mobile')

@if (isset($user))
    @if ($user->status === 'Siswa')
        <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
            <div class="content-beranda">
                <div class="bg-[--color-default] w-full h-20 shadow-lg rounded-t-xl flex items-center pl-10 mb-10">
                    <div class="text-white font-bold flex items-center gap-4">
                        <i class="fa-solid fa-file-lines text-4xl"></i>
                        <span class="text-xl">English Zone</span>
                    </div>
                </div>
                <div class="flex mt-10">
                    <div class="w-full hover:bg-gray-100" onclick="content()">
                        <input type="radio" class="hidden" name="radio" id="radio1" checked>
                        <div class="checked-timeline">
                            <label for="radio1" class="cursor-pointer">
                                <span class="text-lg flex justify-center relative top-1">Materi</span>
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
                <div class="relative w-full h-auto overflow-hidden bg-white shadow-lg">
                    <div class="w-full h-auto" id="content">
                        materi
                    </div>
                    <div class="w-full h-auto hidden" id="riwayat">
                        riwayat
                    </div>
                </div>
            </div>
        </div>
    @elseif($user->status === 'Mentor')
        <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
            <div class="content-beranda">
                <form action="{{ route('englishZone.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label>Judul PDF:</label>
                    <input type="text" name="mentor" value="{{ $user->nama_lengkap }}" required><br><br>

                    <label>File PDF:</label>
                    <input type="file" name="pdf_file" accept="application/pdf" required><br><br>

                    <button type="submit">Unggah PDF</button>
                    @foreach ($get as $item)
                        <span>{{ $item->mentor }}</span>
                        <a href="{{ route('englishZone.show', $item->id) }}" target="_blank">Lihat</a>
                    @endforeach
                    <input type="url" class="border-4">
                    {{-- @foreach ($get as $data)
                        <div>
                            <h3>Mentor: {{ $data->mentor }}</h3>

                            @if (!empty($data->pdf_file))
                                <!-- Tampilkan PDF menggunakan iframe -->
                                <iframe src="{{ asset('storage/' . $data->pdf_file) }}" width="100%" height="600px"
                                    class="border-4"></iframe>
                                <p>
                                    <a href="{{ asset('storage/' . $data->pdf_file) }}" target="_blank">Lihat PDF</a>
                                </p>
                            @else
                                <p>Tidak ada file PDF untuk ditampilkan.</p>
                            @endif
                        </div>
                        <hr>
                    @endforeach --}}
                </form>
            </div>
        </div>
    @elseif($user->status === 'Murid')
        <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0 bg-white">
            <div class="content-beranda">
                <div class="bg-[--color-default] w-full h-20 shadow-lg rounded-t-xl flex items-center pl-10 mb-10">
                    <div class="text-white font-bold flex items-center gap-4">
                        <i class="fa-solid fa-file-lines text-4xl"></i>
                        <span class="text-xl">English Zone</span>
                    </div>
                </div>
                <div class="flex mt-10">
                    <div class="w-full hover:bg-gray-100" onclick="content()">
                        <input type="radio" class="hidden" name="radio" id="radio1" checked>
                        <div class="checked-timeline">
                            <label for="radio1" class="cursor-pointer">
                                <span class="text-lg flex justify-center relative top-1">Materi</span>
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
                <div class="relative w-full h-auto overflow-hidden bg-white shadow-lg">
                    <div class="w-full h-auto" id="content">
                        @foreach ($getMateri as $item)
                            <div class="container-accordion">
                                <div class="wrapper-content-accordion">
                                    <button class="toggleButton">
                                        <div class="">
                                            <span>{{ $item->modul }}.</span>
                                            <span>{{ $item->judul }}</span>
                                        </div>
                                        <i class="fa-solid fa-chevron-up icon"></i>
                                    </button>
                                    <div class="content-accordion">
                                        <div class="wrapper-content-accordion1">
                                            <div class="box-content-bab">
                                                <div class="title-content-bab">
                                                    <div class="logo-content-bab"></div>
                                                    <div class="header-title">
                                                        <span> Materi </span>
                                                    </div>
                                                    <div class="bottom-title">
                                                        <span> {{ $item->judul }} </span>
                                                    </div>
                                                </div>
                                                <button class="button-link-concept" formaction="">
                                                    <div onclick="showMateri(this)"
                                                        data-pdf="{{ asset('englishZone_pdf/' . $item->pdf_file) }}#toolbar=0">
                                                        Lihat Materi
                                                        <i class="fa-solid fa-chevron-right"></i>
                                                    </div>
                                                </button>
                                            </div>
                                            <div class="box-content-bab">
                                                <div class="title-content-bab">
                                                    <div class="logo-content-bab"></div>
                                                    <div class="header-title">
                                                        <span> Video Materi </span>
                                                    </div>
                                                    <div class="bottom-title">
                                                        <span> {{ $item->judul }} </span>
                                                    </div>
                                                </div>
                                                <button class="button-link-ppt">
                                                    <a href="{{ $item->video_materi }}" target="_blank"> Lihat Video
                                                    </a>
                                                </button>
                                            </div>
                                            <div class="box-content-bab">
                                                <div class="title-content-bab">
                                                    <div class="logo-content-bab"></div>
                                                    <div class="header-title">
                                                        <span> Pengayaan </span>
                                                    </div>
                                                    <div class="bottom-title">
                                                        <span> {{ $item->judul }} </span>
                                                    </div>
                                                </div>
                                                <form>
                                                    <button class="button-link-ebook" formaction="">
                                                        <a href="/pengayaan"> Lihat Soal </a>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <dialog id="my_modal_5" class="modal">
                            <div class="modal-box bg-white">
                                <!-- Menggunakan iframe untuk menampilkan PDF -->
                                <iframe id="modal_image" src="" class="hidden min-w-full h-[735px]"></iframe>

                                <div class="h-full w-full flex items-center justify-center" id="no_image"
                                    style="display: none;">
                                    <p>PDF tidak tersedia</p>
                                </div>
                            </div>
                            <form method="dialog" class="modal-backdrop">
                                <button>close</button>
                            </form>
                        </dialog>

                    </div>
                    <div class="w-full h-auto hidden" id="riwayat">
                        riwayat
                    </div>
                </div>
            </div>
        </div>
    @elseif($user->status === 'Guru')

    @elseif($user->status === 'Administrator')
        <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
            <div class="content-beranda">

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

<script src="js/content-riwayat.js"></script> {{-- content slide --}}
<script src="js/accordion.js"></script> {{-- accordion script --}}

<script>
    // Menerapkan CKEditor untuk setiap elemen dengan kelas 'task-textarea'
    document.querySelectorAll('.task-textarea').forEach(function(textarea) {
        ClassicEditor
            .create(textarea)
            .catch(error => {
                console.error(error);
            });
    });
</script>

<script>
    function showMateri(element) {
        const modal = document.getElementById('my_modal_5');
        const modalImage = document.getElementById('modal_image');
        const modalCatatan = document.getElementById('modal_catatan');
        const noImage = document.getElementById('no_image');
        const headCatatan = document.getElementById('head-catatan');

        // Ambil data dari atribut
        const imageSrc = element.getAttribute('data-pdf');
        const catatan = element.getAttribute('data-catatan');

        // Set gambar dan data
        modalImage.src = imageSrc;

        // Cek apakah sumber gambar tidak kosong
        if (imageSrc) {
            modalImage.style.display = 'block';
            noImage.style.display = 'none';
        } else {
            modalImage.style.display = 'none';
            noImage.style.display = 'block';
        }
        // Tampilkan dialog
        modal.showModal();
    }
</script>
