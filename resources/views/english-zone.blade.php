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
                        TEST
                    </div>
                    <div class="w-full h-auto hidden" id="riwayat">
                        riwayat
                    </div>
                </div>
                <div class="container-accordion">
                    <div class="wrapper-content-accordion">
                        <button class="toggleButton">
                            <div class="">
                                <span>Materi I.</span>
                                <span>Introduction</span>
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
                                            <span> Introduction </span>
                                        </div>
                                    </div>
                                    <form>
                                        <button class="button-link-concept" formaction="">
                                            <a> Lihat Materi </a>
                                            <i class="fa-solid fa-chevron-right"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="box-content-bab">
                                    <div class="title-content-bab">
                                        <div class="logo-content-bab"></div>
                                        <div class="header-title">
                                            <span> Video Presentasi </span>
                                        </div>
                                        <div class="bottom-title">
                                            <span> Menyusun Prosedur </span>
                                        </div>
                                    </div>
                                    <form>
                                        <button class="button-link-ppt" formaction="">
                                            <a> Lihat PPT </a>
                                        </button>
                                    </form>
                                </div>
                                <div class="box-content-bab">
                                    <div class="title-content-bab">
                                        <div class="logo-content-bab"></div>
                                        <div class="header-title">
                                            <span> Ebook </span>
                                        </div>
                                        <div class="bottom-title">
                                            <span> Menyusun Prosedur </span>
                                        </div>
                                    </div>
                                    <form>
                                        <button class="button-link-ebook" formaction="">
                                            <a> Baca E-book </a>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wrapper-content-accordion">
                        <button class="toggleButton">
                            <div class="">
                                <span>Materi II .</span>
                                <span>Write</span>
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
                                            <span> Write </span>
                                        </div>
                                    </div>
                                    <form>
                                        <button class="button-link-concept" formaction="">
                                            <a> Lihat Materi </a>
                                            <i class="fa-solid fa-chevron-right"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="box-content-bab">
                                    <div class="title-content-bab">
                                        <div class="logo-content-bab"></div>
                                        <div class="header-title">
                                            <span> Video Presentasi </span>
                                        </div>
                                        <div class="bottom-title">
                                            <span> Menyusun Prosedur </span>
                                        </div>
                                    </div>
                                    <form>
                                        <button class="button-link-ppt" formaction="">
                                            <a> Lihat PPT </a>
                                        </button>
                                    </form>
                                </div>
                                <div class="box-content-bab">
                                    <div class="title-content-bab">
                                        <div class="logo-content-bab"></div>
                                        <div class="header-title">
                                            <span> Ebook </span>
                                        </div>
                                        <div class="bottom-title">
                                            <span> Menyusun Prosedur </span>
                                        </div>
                                    </div>
                                    <form>
                                        <button class="button-link-ebook" formaction="">
                                            <a> Baca E-book </a>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wrapper-content-accordion">
                        <button class="toggleButton">
                            <div class="">
                                <span>Materi III.</span>
                                <span>Grammar</span>
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
                                            <span> Grammar </span>
                                        </div>
                                    </div>
                                    <form>
                                        <button class="button-link-concept" formaction="">
                                            <a> Lihat Materi </a>
                                            <i class="fa-solid fa-chevron-right"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="box-content-bab">
                                    <div class="title-content-bab">
                                        <div class="logo-content-bab"></div>
                                        <div class="header-title">
                                            <span> Video Presentasi </span>
                                        </div>
                                        <div class="bottom-title">
                                            <span> Menyusun Prosedur </span>
                                        </div>
                                    </div>
                                    <form>
                                        <button class="button-link-ppt" formaction="">
                                            <a> Lihat PPT </a>
                                        </button>
                                    </form>
                                </div>
                                <div class="box-content-bab">
                                    <div class="title-content-bab">
                                        <div class="logo-content-bab"></div>
                                        <div class="header-title">
                                            <span> Ebook </span>
                                        </div>
                                        <div class="bottom-title">
                                            <span> Menyusun Prosedur </span>
                                        </div>
                                    </div>
                                    <form>
                                        <button class="button-link-ebook" formaction="">
                                            <a> Baca E-book </a>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wrapper-content-accordion">
                        <button class="toggleButton">
                            <div class="">
                                <span>Materi IV.</span>
                                <span>Tenses</span>
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
                                            <span> Tenses </span>
                                        </div>
                                    </div>
                                    <form>
                                        <button class="button-link-concept" formaction="">
                                            <a> Lihat Materi </a>
                                            <i class="fa-solid fa-chevron-right"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="box-content-bab">
                                    <div class="title-content-bab">
                                        <div class="logo-content-bab"></div>
                                        <div class="header-title">
                                            <span> Video Presentasi </span>
                                        </div>
                                        <div class="bottom-title">
                                            <span> Menyusun Prosedur </span>
                                        </div>
                                    </div>
                                    <form>
                                        <button class="button-link-ppt" formaction="">
                                            <a> Lihat PPT </a>
                                        </button>
                                    </form>
                                </div>
                                <div class="box-content-bab">
                                    <div class="title-content-bab">
                                        <div class="logo-content-bab"></div>
                                        <div class="header-title">
                                            <span> Ebook </span>
                                        </div>
                                        <div class="bottom-title">
                                            <span> Menyusun Prosedur </span>
                                        </div>
                                    </div>
                                    <form>
                                        <button class="button-link-ebook" formaction="">
                                            <a> Baca E-book </a>
                                        </button>
                                    </form>
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
    <p>You are not logged in.</p>
@endif

<script src="js/content-riwayat.js"></script> {{-- content slide --}}
<script src="js/accordion.js"></script> {{-- accordion script --}}
