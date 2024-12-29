@include('components/sidebar_beranda')
@extends('components/sidebar_beranda_mobile')

<div class="home-beranda bg-white">
    <div class="content-beranda">
        <div class="relative">
            <form action="{{ route('englishZoneJawaban.store') }}" method="POST" class="form">
                @csrf
                <div class="hidden">
                    <input type="text" name="nama_lengkap" value="{{ $user->nama_lengkap }}">
                    <input type="text" name="email" value="{{ $user->email }}">
                    <input type="text" name="sekolah" value="{{ $user->sekolah }}">
                    <input type="text" name="kelas" value="{{ $user->kelas }}">
                    <input type="text" name="status" value="{{ $user->status }}">
                    <input type="text" name="jenjang_murid" value="{{ $user->kode_jenjang_murid }}">
                </div>
                @foreach ($getSoal as $item)
                    <div
                        class="container-accordion mb-8 {{ $errors->has('jawaban') ? 'border-red-500 border-[1px]' : '' }}">
                        <div class="wrapper-content-accordion-pengayaan">
                            <div class="toggleButton-pengayaan">
                                <div class="flex gap-1">
                                    {!! $loop->iteration !!}.
                                    {!! $item->soal !!}
                                    <input type="hidden" name="id_soal[{{ $loop->iteration }}]"
                                        value="{{ $item->id }}"> {{-- untuk relasi dengan id yang ada pada $getSoal (englishZoneSoal) --}}
                                    <input type="hidden" name="no_soal[{{ $loop->iteration }}]"
                                        value="{{ $loop->iteration }}">
                                </div>
                                <i class="fa-solid fa-chevron-up icon"></i>
                            </div>
                            <div class="content-accordion">
                                <div class="wrapper-content-accordion-soal">
                                    {{-- jika id_soal pada $getJawaban sama dengan $item->id pada $getSoal maka menampilkan data tersebut --}}
                                    @if (isset($getJawaban[$item->id]))
                                        @foreach ($getJawaban[$item->id] as $bobot)
                                            <label class="pilihanSoal">
                                                @if ($bobot->bobot_A >= 5 && $bobot->pilihan_ganda === 'A')
                                                    {{-- jika nilai bobot_A >= 5 dan pilihan_ganda(jawaban user) A, warna hijau --}}
                                                    <div class="option-disabled-true bg-green-400">
                                                        <span class="text-sm">{!! $item->pilihan_A !!}</span>
                                                        <p class="text-xs">{{ $bobot->bobot_A }}</p>
                                                    </div>
                                                @elseif($bobot->bobot_A >= 5)
                                                    {{-- jika nilai bobot_A >= 5 dan pilihan_ganda(jawaban user) bukan A, maka option A dengan bobot 5 warna hijau --}}
                                                    <div class="option-disabled-true bg-green-400">
                                                        <span class="text-sm">{!! $item->pilihan_A !!}</span>
                                                        <p class="text-xs">{{ $bobot->bobot_A }}</p>
                                                    </div>
                                                @elseif($bobot->bobot_A < 5 && $bobot->pilihan_ganda === 'A')
                                                    {{-- jika nilai bobot_A < 5 dan pilihan_ganda(jawaban user) A, warna gray (simple nya jawaban user A tapi option A bobot nya < 5) --}}
                                                    <div class="option-disabled-current bg-gray-200">
                                                        <span class="text-sm">{!! $item->pilihan_A !!}</span>
                                                        <p class="text-xs">{{ $bobot->bobot_A }}</p>
                                                    </div>
                                                @else
                                                    {{-- option yang tidak dipilih user  dan yang bobot option nya < 5 --}}
                                                    <div class="option-disabled">
                                                        <span class="text-sm">{!! $item->pilihan_A !!}</span>
                                                        <p class="text-xs">{{ $bobot->bobot_A }}</p>
                                                    </div>
                                                @endif
                                            </label>
                                        @endforeach
                                    @else
                                        <input type="radio" id="pilihan_A_{{ $loop->iteration }}"
                                            name="jawaban[{{ $loop->iteration }}]"
                                            value="A|{!! $item->pilihan_A !!}|{!! $item->bobot_A !!}|{!! $item->bobot_B !!}|{!! $item->bobot_C !!}|{!! $item->bobot_D !!}|{!! $item->bobot_E !!}">
                                        {{-- ketika user memilih option A maka akan mengirim semua bobot a - e --}}
                                        <label for="pilihan_A_{{ $loop->iteration }}" class="pilihanSoal">
                                            <div class="option">
                                                <span class="text-sm">{!! $item->pilihan_A !!}</span>
                                            </div>
                                        </label>
                                    @endif

                                    @if (isset($getJawaban[$item->id]))
                                        @foreach ($getJawaban[$item->id] as $bobot)
                                            <label class="pilihanSoal">
                                                @if ($bobot->bobot_B >= 5 && $bobot->pilihan_ganda === 'B')
                                                    {{-- jika nilai bobot_B >= 5 dan pilihan_ganda(jawaban user) B, warna hijau --}}
                                                    <div class="option-disabled-true bg-green-400">
                                                        <span class="text-sm">{!! $item->pilihan_B !!}</span>
                                                        <p class="text-xs">{{ $bobot->bobot_B }}</p>
                                                    </div>
                                                @elseif($bobot->bobot_B >= 5)
                                                    {{-- jika nilai bobot_B >= 5 dan pilihan_ganda(jawaban user) bukan B, maka option B dengan bobot 5 warna hijau --}}
                                                    <div class="option-disabled-true bg-green-400">
                                                        <span class="text-sm">{!! $item->pilihan_B !!}</span>
                                                        <p class="text-xs">{{ $bobot->bobot_B }}</p>
                                                    </div>
                                                @elseif($bobot->bobot_B < 5 && $bobot->pilihan_ganda === 'B')
                                                    {{-- jika nilai bobot_B < 5 dan pilihan_ganda(jawaban user) B, warna gray (simple nya jawaban user B tapi option B bobot nya < 5) --}}
                                                    <div class="option-disabled-current bg-gray-200">
                                                        <span class="text-sm">{!! $item->pilihan_B !!}</span>
                                                        <p class="text-xs">{{ $bobot->bobot_B }}</p>
                                                    </div>
                                                @else
                                                    {{-- option yang tidak dipilih user  dan yang bobot option nya < 5 --}}
                                                    <div class="option-disabled">
                                                        <span class="text-sm">{!! $item->pilihan_B !!}</span>
                                                        <p class="text-xs">{{ $bobot->bobot_B }}</p>
                                                    </div>
                                                @endif
                                            </label>
                                            </label>
                                        @endforeach
                                    @else
                                        <input type="radio" id="pilihan_B_{{ $loop->iteration }}"
                                            name="jawaban[{{ $loop->iteration }}]"
                                            value="B|{!! $item->pilihan_B !!}|{!! $item->bobot_A !!}|{!! $item->bobot_B !!}|{!! $item->bobot_C !!}|{!! $item->bobot_D !!}|{!! $item->bobot_E !!}">
                                        {{-- ketika user memilih option A maka akan mengirim semua bobot a - e --}}
                                        <label for="pilihan_B_{{ $loop->iteration }}" class="pilihanSoal">
                                            <div class="option">
                                                <span class="text-sm">{!! $item->pilihan_B !!}</span>
                                            </div>
                                        </label>
                                    @endif

                                    @if (isset($getJawaban[$item->id]))
                                        @foreach ($getJawaban[$item->id] as $bobot)
                                            <label class="pilihanSoal">
                                                @if ($bobot->bobot_C >= 5 && $bobot->pilihan_ganda === 'C')
                                                    {{-- jika nilai bobot_C >= 5 dan pilihan_ganda(jawaban user) C, warna hijau --}}
                                                    <div class="option-disabled-true bg-green-400">
                                                        <span class="text-sm">{!! $item->pilihan_C !!}</span>
                                                        <p class="text-xs">{{ $bobot->bobot_C }}</p>
                                                    </div>
                                                @elseif($bobot->bobot_C >= 5)
                                                    {{-- jika nilai bobot_C >= 5 dan pilihan_ganda(jawaban user) bukan C, maka option C dengan bobot 5 warna hijau --}}
                                                    <div class="option-disabled-true bg-green-400">
                                                        <span class="text-sm">{!! $item->pilihan_C !!}</span>
                                                        <p class="text-xs">{{ $bobot->bobot_C }}</p>
                                                    </div>
                                                @elseif($bobot->bobot_C < 5 && $bobot->pilihan_ganda === 'C')
                                                    {{-- jika nilai bobot_C < 5 dan pilihan_ganda(jawaban user) C, warna gray (simple nya jawaban user C tapi option C bobot nya < 5) --}}
                                                    <div class="option-disabled-current bg-gray-200">
                                                        <span class="text-sm">{!! $item->pilihan_C !!}</span>
                                                        <p class="text-xs">{{ $bobot->bobot_C }}</p>
                                                    </div>
                                                @else
                                                    {{-- option yang tidak dipilih user  dan yang bobot option nya < 5 --}}
                                                    <div class="option-disabled">
                                                        <span class="text-sm">{!! $item->pilihan_C !!}</span>
                                                        <p class="text-xs">{{ $bobot->bobot_C }}</p>
                                                    </div>
                                                @endif
                                            </label>
                                            </label>
                                        @endforeach
                                    @else
                                        <input type="radio" id="pilihan_C_{{ $loop->iteration }}"
                                            name="jawaban[{{ $loop->iteration }}]"
                                            value="C|{!! $item->pilihan_C !!}|{!! $item->bobot_A !!}|{!! $item->bobot_B !!}|{!! $item->bobot_C !!}|{!! $item->bobot_D !!}|{!! $item->bobot_E !!}">
                                        {{-- ketika user memilih option A maka akan mengirim semua bobot a - e --}}
                                        <label for="pilihan_C_{{ $loop->iteration }}" class="pilihanSoal">
                                            <div class="option">
                                                <span class="text-sm">{!! $item->pilihan_C !!}</span>
                                            </div>
                                        </label>
                                    @endif

                                    @if (isset($getJawaban[$item->id]))
                                        @foreach ($getJawaban[$item->id] as $bobot)
                                            <label class="pilihanSoal">
                                                @if ($bobot->bobot_D >= 5 && $bobot->pilihan_ganda === 'D')
                                                    {{-- jika nilai bobot_D >= 5 dan pilihan_ganda(jawaban user) D, warna hijau --}}
                                                    <div class="option-disabled-true bg-green-400">
                                                        <span class="text-sm">{!! $item->pilihan_D !!}</span>
                                                        <p class="text-xs">{{ $bobot->bobot_D }}</p>
                                                    </div>
                                                @elseif($bobot->bobot_D >= 5)
                                                    <div class="option-disabled-true bg-green-400">
                                                        {{-- jika nilai bobot_D >= 5 dan pilihan_ganda(jawaban user) bukan D, maka option D dengan bobot 5 warna hijau --}}
                                                        <span class="text-sm">{!! $item->pilihan_D !!}</span>
                                                        <p class="text-xs">{{ $bobot->bobot_D }}</p>
                                                    </div>
                                                @elseif($bobot->bobot_D < 5 && $bobot->pilihan_ganda === 'D')
                                                    {{-- jika nilai bobot_D < 5 dan pilihan_ganda(jawaban user) D, warna gray (simple nya jawaban user D tapi option D bobot nya < 5) --}}
                                                    <div class="option-disabled-current bg-gray-200">
                                                        <span class="text-sm">{!! $item->pilihan_D !!}</span>
                                                        <p class="text-xs">{{ $bobot->bobot_D }}</p>
                                                    </div>
                                                @else
                                                    {{-- option yang tidak dipilih user  dan yang bobot option nya < 5 --}}
                                                    <div class="option-disabled">
                                                        <span class="text-sm">{!! $item->pilihan_D !!}</span>
                                                        <p class="text-xs">{{ $bobot->bobot_D }}</p>
                                                    </div>
                                                @endif
                                            </label>
                                            </label>
                                        @endforeach
                                    @else
                                        <input type="radio" id="pilihan_D_{{ $loop->iteration }}"
                                            name="jawaban[{{ $loop->iteration }}]"
                                            value="D|{!! $item->pilihan_D !!}|{!! $item->bobot_A !!}|{!! $item->bobot_B !!}|{!! $item->bobot_C !!}|{!! $item->bobot_D !!}|{!! $item->bobot_E !!}">
                                        {{-- ketika user memilih option A maka akan mengirim semua bobot a - e --}}
                                        <label for="pilihan_D_{{ $loop->iteration }}" class="pilihanSoal">
                                            <div class="option">
                                                <span class="text-sm">{!! $item->pilihan_D !!}</span>
                                            </div>
                                        </label>
                                    @endif

                                    @if (isset($getJawaban[$item->id]))
                                        @foreach ($getJawaban[$item->id] as $bobot)
                                            <label class="pilihanSoal">
                                                @if ($bobot->bobot_E >= 5 && $bobot->pilihan_ganda === 'E')
                                                    {{-- jika nilai bobot_E >= 5 dan pilihan_ganda(jawaban user) E, warna hijau --}}
                                                    <div class="option-disabled-true bg-green-400">
                                                        <span class="text-sm">{!! $item->pilihan_E !!}</span>
                                                        <p class="text-xs">{{ $bobot->bobot_E }}</p>
                                                    </div>
                                                @elseif($bobot->bobot_E >= 5)
                                                    {{-- jika nilai bobot_E >= 5 dan pilihan_ganda(jawaban user) bukan E, maka option E dengan bobot 5 warna hijau --}}
                                                    <div class="option-disabled-true bg-green-400">
                                                        <span class="text-sm">{!! $item->pilihan_E !!}</span>
                                                        <p class="text-xs">{{ $bobot->bobot_E }}</p>
                                                    </div>
                                                @elseif($bobot->bobot_E < 5 && $bobot->pilihan_ganda === 'E')
                                                    {{-- jika nilai bobot_E < 5 dan pilihan_ganda(jawaban user) E, warna gray (simple nya jawaban user E tapi option E bobot nya < 5) --}}
                                                    <div class="option-disabled-current bg-gray-200">
                                                        <span class="text-sm">{!! $item->pilihan_E !!}</span>
                                                        <p class="text-xs">{{ $bobot->bobot_E }}</p>
                                                    </div>
                                                @else
                                                    {{-- option yang tidak dipilih user  dan yang bobot option nya < 5 --}}
                                                    <div class="option-disabled">
                                                        <span class="text-sm">{!! $item->pilihan_E !!}</span>
                                                        <p class="text-xs">{{ $bobot->bobot_E }}</p>
                                                    </div>
                                                @endif
                                            </label>
                                            </label>
                                        @endforeach
                                    @else
                                        <input type="radio" id="pilihan_E_{{ $loop->iteration }}"
                                            name="jawaban[{{ $loop->iteration }}]"
                                            value="E|{!! $item->pilihan_E !!}|{!! $item->bobot_A !!}|{!! $item->bobot_B !!}|{!! $item->bobot_C !!}|{!! $item->bobot_D !!}|{!! $item->bobot_E !!}">
                                        {{-- ketika user memilih option A maka akan mengirim semua bobot a - e --}}
                                        <label for="pilihan_E_{{ $loop->iteration }}" class="pilihanSoal">
                                            <div class="option">
                                                <span class="text-sm">{!! $item->pilihan_E !!}</span>
                                            </div>
                                        </label>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <button
                    class="absolute right-1 bg-red-500 w-[150px] h-8 text-white font-bold rounded-lg">Kirim</button>
            </form>
        </div>
    </div>
</div>

<script src="js/accordion-soal.js"></script> {{-- accordion script --}}
