@include('components/sidebar_beranda')
@extends('components/sidebar_beranda_mobile')


@if(isset($user))
    @if($user->status === 'Siswa' or $user->status === 'Murid')
            <div class="home-beranda">
                <div class="content-beranda">
                <header>
                    <div class="bg-[--color-second] w-full h-20 shadow-lg rounded-t-xl flex items-center pl-10 mb-10">
                        <div class="text-white font-bold flex items-center gap-4">
                            <i class="fa-solid fa-file-lines text-4xl"></i>
                            <span class="text-xl">English Zone</span>
                        </div>
                    </div>
                </header>
                <main>
                    <section class="grid grid-cols-12">
                        <div class="col-span-12 md:col-span-7">
                            <div class="wrapper-materi-video">
                                <div class="container-materi show controls">
                                    <iframe id="video-source" class="video" src="https://www.youtube.com/embed/{{ $videoIds[0] }}"
                                        frameborder="0" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen></iframe>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 md:col-span-5">
                            <div class="wrapper-pokok-bahasan">
                                <header> Pokok Bahasan </header>
                                <div class="wrapper-content-pembahasan">
                                    @foreach ($getVideo as $key => $item)
                                        <input type="radio" name="radio" id="pokok-bahasan{{ $key }}" {{ $key == 0 ? 'checked' : '' }}>
                                        <label for="pokok-bahasan{{ $key }}" class="listPokokBahasan">
                                            <div onclick="changeVideo('{{ $videoIds[$key] }}')">
                                                    <div class="pembahasan">
                                                        <div class="gambar-pembahasan">
                                                            <i class="fa-solid fa-circle-play"></i>
                                                        </div>
                                                        <span> {{ $item->judul_video }} </span>
                                                    </div>
                                                <div class="line-pembahasan"></div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <button class="bg-[--color-default] w-32 h-10 rounded-lg text-white font-bold mt-8">
                            <a href="{{ route('englishZone.index') }}">
                                Kembali
                            </a>
                        </button>
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
@else
        <p>You are not logged in.</p>
@endif

<script>
    function changeVideo(videoIds) {
            const videoPlayer = document.getElementById('video-player');
            const videoSource = document.getElementById('video-source');

            // Mengubah sumber video sesuai dengan video ID yang dipilih
            videoSource.src = "https://www.youtube.com/embed/" + videoIds;
            videoPlayer.load();  // Memuat video yang baru
            videoPlayer.play();  // Memulai pemutaran video
        }
</script>
