@include('components/sidebar_beranda')
@extends('components/sidebar_beranda_mobile')

@if (isset($user))
    @if ($user->status === 'Siswa')
        <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
            <div class="content-beranda">
                <div class="bg-[--color-second] w-full h-20 shadow-lg rounded-t-xl flex items-center pl-10 mb-10">
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
        <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
            <div class="content-beranda">
                <div class="bg-[--color-second] w-full h-20 shadow-lg rounded-t-xl flex items-center pl-10 mb-10">
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
                        @foreach ($mainMateri as $modul => $item)
                            <div class="container-accordion">
                                <div class="wrapper-content-accordion">
                                    <button class="toggleButton">
                                        <div class="">
                                            <span>{{ $modul }}.</span>
                                            <span>{{ $item->judul_modul }}</span>
                                        </div>
                                        <i class="fa-solid fa-chevron-up icon"></i>
                                    </button>
                                    <div class="content-accordion">
                                        <div class="wrapper-content-accordion1">
                                            <div class="box-content-accordion">
                                                <div class="title-content-bab">
                                                    <div class="logo-content-bab"></div>
                                                    <div class="header-title">
                                                        <span> Materi </span>
                                                    </div>
                                                    <div class="bottom-title">
                                                        <span> {{ $item->judul_modul }} </span>
                                                    </div>
                                                </div>
                                                @if ($item->is_locked)
                                                    <button class="button-link-lock" onclick="showAlertLock()">
                                                        Lihat Materi
                                                        <i class="fa-solid fa-lock"></i>
                                                    </button>
                                                @else
                                                    <button class="button-link-materi">
                                                        <div onclick="showMateri(this)"
                                                            data-pdf="{{ asset('englishZone_pdf/' . $item->pdf_file) }}#toolbar=0">
                                                            Lihat Materi
                                                            <i class="fa-solid fa-chevron-right"></i>
                                                        </div>
                                                    </button>
                                                @endif
                                            </div>
                                            <div class="accordion-video-list">
                                                <div class="box-video-list">
                                                    <div class="title-content-bab">
                                                        <div class="logo-content-bab"></div>
                                                        <div class="header-title">
                                                            <span> Video </span>
                                                        </div>
                                                        <div class="bottom-title">
                                                            <span>{{ $item->judul_modul }}</span>
                                                        </div>
                                                    </div>
                                                    @if ($item->is_locked)
                                                        <button class="button-link-lock" onclick="showAlertLock()">
                                                            <span>Lihat Video</span>
                                                            <i class="fa-solid fa-lock"></i>
                                                        </button>
                                                    @else
                                                        <button class="button-link-videoList">
                                                            <a href="{{ route('englishZone.video', $item->modul) }}">
                                                                <span>Lihat Video</span>
                                                                <i class="fa-solid fa-chevron-right toggle-icon"></i>
                                                            </a>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="box-content-accordion">
                                                <div class="title-content-bab">
                                                    <div class="logo-content-bab"></div>
                                                    <div class="header-title">
                                                        <span> Pengayaan </span>
                                                    </div>
                                                    <div class="bottom-title">
                                                        <span> {{ $item->judul_modul }} </span>
                                                    </div>
                                                </div>
                                                @if ($item->is_locked)
                                                    <button class="button-link-lock" onclick="showAlertLock()">
                                                        Lihat Soal
                                                        <i class="fas fa-lock"></i>
                                                    </button>
                                                @else
                                                    <button class="button-link-pengayaan">
                                                        <a
                                                            href="{{ route('pengayaan', ['modul' => $item->modul, 'id' => $item->id]) }}">
                                                            Lihat Soal
                                                            <i class="fas fa-chevron-right"></i>
                                                        </a>
                                                    </button>
                                                @endif
                                            </div>
                                            @if ($item->modul === 'Final Exam')
                                                <div class="grid grid-cols-12 gap-8">
                                                    <div class="col-span-12 lg:col-span-6">
                                                        <div class="box-content-accordion-modulSertifikat">
                                                            <div class="title-content-bab">
                                                                <div class="logo-content-bab"></div>
                                                                <div class="header-title">
                                                                    <span> Final Test </span>
                                                                </div>
                                                                <div class="bottom-title">
                                                                    <span> Modul </span>
                                                                </div>
                                                            </div>
                                                            @if ($item->is_locked)
                                                                <button class="button-link-lock"
                                                                    onclick="showAlertDownloadModul()">
                                                                    Download Modul
                                                                    <i class="fas fa-lock"></i>
                                                                </button>
                                                            @else
                                                                <button class="button-link-pengayaan">
                                                                    {{-- mengirimkan ke route tujuan dengan parameter url modul, hasil url (pengayaan/modul 1, dst) --}}
                                                                    <a
                                                                        href="{{ route('pengayaan', ['modul' => $item->modul, 'id' => $item->id]) }}">
                                                                        Download Modul
                                                                        <i class="fas fa-chevron-right"></i>
                                                                    </a>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-span-12 lg:col-span-6">
                                                        <div class="box-content-accordion-modulSertifikat">
                                                            <div class="title-content-bab">
                                                                <div class="logo-content-bab"></div>
                                                                <div class="header-title">
                                                                    <span> Final Test </span>
                                                                </div>
                                                                <div class="bottom-title">
                                                                    <span> Sertifikat </span>
                                                                </div>
                                                            </div>
                                                            @if ($item->is_locked)
                                                                <button class="button-link-lock"
                                                                    onclick="showAlertDownloadCertificate()">
                                                                    Download Sertifikat
                                                                    <i class="fas fa-lock"></i>
                                                                </button>
                                                            @else
                                                                <button class="button-link-pengayaan" formaction="">
                                                                    <a href="{{ route('generateCertificate') }}">
                                                                        Download Sertifikat
                                                                        <i class="fas fa-chevron-right"></i>
                                                                    </a>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
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
{{-- <script src="js/accordion.js"></script> accordion script --}}

<script>
    function showAlertLock() {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Harap selesaikan modul sebelumnya untuk membuka modul ini!',
        });
    }

    function showAlertDownloadModul() {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Harap selesaikan final test terlebih dahulu untuk mendownload modul!',
        });
    }

    function showAlertDownloadCertificate() {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Harap selesaikan final test terlebih dahulu untuk mendownload Sertifikat!',
        });
    }
</script>
<script>
    function showMateri(element) {
        const pdfUrl = element.getAttribute('data-pdf');
        const modal = document.getElementById('my_modal_5');
        const iframe = document.getElementById('modal_image');
        const noImage = document.getElementById('no_image');

        // Tampilkan PDF di iframe
        if (pdfUrl) {
            iframe.src = pdfUrl;
            iframe.classList.remove('hidden');
            noImage.style.display = 'none';
        } else {
            iframe.classList.add('hidden');
            noImage.style.display = 'flex';
        }

        // Tampilkan modal
        modal.showModal();
    }
</script>


<script>
    document.querySelectorAll('.toggleButton-videoList').forEach((button) => {
        button.addEventListener('click', function() {
            let videoList = this.closest('.accordion-video-list').querySelector('.content-video-list');
            let icon = this.querySelector('.toggle-icon');
            let contentAccordion = this.closest('.content-accordion');

            // Buka atau tutup video list
            if (videoList.style.height === "0px" || videoList.style.height === "") {
                videoList.style.height = videoList.scrollHeight + "px"; // Membuka video list
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-chevron-down');
            } else {
                videoList.style.height = "0px"; // Menutup video list
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-right');
            }

            // Menyesuaikan tinggi accordion utama setelah video list dibuka/tutup
            setTimeout(() => {
                let totalHeight = 0;
                contentAccordion.querySelectorAll('.content-video-list').forEach(list => {
                    totalHeight += list.scrollHeight; // Menambahkan tinggi video list
                });

                // Menyesuaikan tinggi accordion utama
                if (videoList.style.height !== "0px") {
                    // Jika video list terbuka, tingkatkan tinggi accordion utama
                    contentAccordion.style.height = (contentAccordion.scrollHeight + videoList
                        .scrollHeight) + "px";
                } else {
                    // Jika video list tertutup, kembalikan tinggi accordion utama ke ukuran semula
                    contentAccordion.style.height = (contentAccordion.scrollHeight - videoList
                        .scrollHeight) + "px";
                }
            }, 0); // Menjalankan langsung tanpa delay
        });
    });

    // Script for the main accordion (same as before)
    document.querySelectorAll('.toggleButton').forEach((button) => {
        button.addEventListener('click', function() {
            let content = this.nextElementSibling;
            let icon = this.querySelector('.icon');

            if (content.style.height === "0px" || content.style.height === "") {
                content.style.height = content.scrollHeight + "px";
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            } else {
                content.style.height = "0px";
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            }
        });
    });
</script>

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
