@include('components/sidebar_beranda')
@extends('components/sidebar_beranda_mobile')

<div class="home-beranda">
    <div class="content-beranda">
        @if ($getJawaban->isNotEmpty())
            <div class="flex items-center justify-between mr-16">
                <button
                    class="bg-[--color-default] w-32 h-10  rounded-lg text-white font-bold flex items-center justify-center gap-2">
                    <a href="{{ route('englishZone.index') }}">
                        <i class="fas fa-chevron-left"></i>
                        <span>Kembali</span>
                    </a>
                </button>
                <div class="">
                    <span class="font-bold">Nilai :</span>
                    {{ $countNilai }} / 100
                </div>
            </div>
        {{-- @else
            <div class="flex items-center justify-between mr-16">
                <button
                    class="bg-[--color-default] w-32 h-10  rounded-lg text-white font-bold flex items-center justify-center gap-2">
                    <a href="{{ route('englishZone.index') }}">
                        <i class="fas fa-chevron-left"></i>
                        <span>Kembali</span>
                    </a>
                </button>
                <div class="">
                    <span class="font-bold">Nilai :</span>
                    {{ $countNilai }} / 100
                </div>
            </div> --}}
        @endif
        <div class="relative top-2">
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
                @foreach ($getSoal as $soal => $item)
                    <div
                        class="container-accordion mb-8 {{ $errors->has('jawaban') ? 'border-red-500 border-[1px]' : '' }}">
                        <div class="wrapper-content-accordion-pengayaan">
                            <div class="toggleButton-pengayaan">
                                <div class="flex gap-1">
                                    {!! $loop->iteration !!}. {!! $soal !!}
                                    <input type="hidden" name="id_soal{{ $loop->iteration }}"
                                        value="{{ $item->id }}">
                                    <input type="hidden" name="no_soal[{{ $loop->iteration }}]"
                                        value="{{ $loop->iteration }}">
                                    <input type="hidden" name="modul" value="{{ $item->modul_soal }}">
                                </div>
                                <i class="fa-solid fa-chevron-up icon"></i>
                            </div>
                            <div class="content-accordion">
                                <div class="wrapper-content-accordion-soal">
                                    @if ($getJawaban->isEmpty())
                                        @foreach ($dataSoal[$item->soal] as $key => $value)
                                            <input type="radio" id="pilihan__{{ $key }}_{{ $soal }}" name="jawaban[{{ $loop->parent->iteration }}]"
                                                value="{{ $value->jawaban_pilihan }}|{{ $value->option_pilihan }}"
                                                onclick="checkJawaban('{{ $value->option_pilihan }}', '{{ $item->jawaban_benar }}', '{{ $getPoint }}', '{{ $soal }}')">
                                            <label for="pilihan__{{ $key }}_{{ $soal }}" class="pilihanSoal pilihan_{{ $soal }}_{{ $key }}">
                                                <div class="option gap-1">
                                                    <span class="text-xs">{{ chr(65 + $key) }}</span>
                                                    <span class="text-xs">{!! $value->jawaban_pilihan !!}</span>
                                                </div>
                                            </label>

                                            <!-- Menambahkan input tersembunyi untuk nilai_jawaban -->
                                            <input type="hidden" id="nilai_jawaban_{{ $soal }}_{{ $value->option_pilihan }}"
                                                name="nilai_jawaban[{{ $loop->parent->iteration }}][{{ $value->option_pilihan }}]" value="0">
                                        @endforeach
                                    @else
                                        @foreach ($getJawaban as $dataJawaban)
                                            @foreach ($dataJawaban as $valueJawaban)
                                                @foreach ($dataSoal[$item->soal] as $item)
                                                    @if ($valueJawaban->jawaban === $item->jawaban_pilihan && $valueJawaban->pilihan_ganda === $item->jawaban_benar)
                                                        @foreach ($dataSoal[$item->soal] as $key => $item)
                                                            <div class="pilihanSoal">
                                                                <div
                                                                    class="{{ $valueJawaban->jawaban === $item->jawaban_pilihan && $valueJawaban->pilihan_ganda === $item->jawaban_benar ? 'option-disabled-true bg-green-300' : 'option-disabled-current' }}">
                                                                    <div class="flex items-center gap-1">
                                                                        <span
                                                                            class="text-xs">{{ chr(65 + $key) }}.</span>
                                                                        <span
                                                                            class="text-sm">{!! $item->jawaban_pilihan !!}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @elseif ($valueJawaban->jawaban === $item->jawaban_pilihan && $valueJawaban->pilihan_ganda !== $item->jawaban_benar)
                                                        @foreach ($dataSoal[$item->soal] as $key => $item)
                                                            <div class="pilihanSoal">
                                                                <div
                                                                    class="{{ $valueJawaban->jawaban === $item->jawaban_pilihan && $valueJawaban->pilihan_ganda !== $item->jawaban_benar ? 'option-disabled-false bg-red-300' : 'option-disabled-current' }} {{ $item->option_pilihan === $item->jawaban_benar ? 'option-disabled-true bg-green-300' : 'option-disabled-current' }}">
                                                                    <div class="flex items-center gap-1">
                                                                        <span
                                                                            class="text-xs">{{ chr(65 + $key) }}.</span>
                                                                        <span
                                                                            class="text-sm">{!! $item->jawaban_pilihan !!}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                @if ($getJawaban->isEmpty())
                    <div class="relative">
                        <div
                            class="bg-[--color-default] w-32 h-10 rounded-lg text-white font-bold flex items-center justify-center gap-2">
                            <a href="{{ route('englishZone.index') }}">
                                <i class="fas fa-chevron-left"></i>
                                <span>Kembali</span>
                            </a>
                        </div>
                        <button
                            class="absolute right-0 top-1 bg-red-500 w-[150px] h-8 text-white font-bold rounded-lg">Kirim</button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

<script src="../js/accordion-soal.js"></script> {{-- accordion script --}}
{{-- <script>
    function checkJawaban(selectedOption, correctAnswer, point, soalId) {
        // Ambil semua input hidden nilai_jawaban untuk soal tertentu
        const nilaiInputs = document.querySelectorAll(
            `input[name="nilai_jawaban[${soalId}][]"]`
        );

        // Reset nilai semua input menjadi 0
        nilaiInputs.forEach(input => {
            input.value = 0;
        });

        // Jika jawaban yang dipilih sesuai dengan jawaban benar
        if (selectedOption === correctAnswer) {
            // Set nilai hanya untuk opsi yang dipilih
            document.getElementById(`nilai_jawaban_${soalId}_${selectedOption}`).value = point;
        } else {
            // Jika salah, set nilai 0
            document.getElementById('nilai_jawaban_' + soalId + '_' + selectedOption).value = 0;
        }
    }
</script> --}}

<script>
    function checkJawaban(option, jawabanBenar, point, soal) {
        const input = document.getElementById(`nilai_jawaban_${soal}_${option}`);
        if (option === jawabanBenar) {
            input.value = point; // Jika jawaban benar, set nilai
            document.querySelectorAll(`.pilihan_${soal}`).forEach(el => {
                el.classList.add('option-default'); // Reset semua opsi ke default
            });
            document.querySelector(`.pilihan_${soal}_${option}`).classList.add('option-disabled-true');
        } else {
            input.value = 0; // Jika jawaban salah, set nilai ke 0
            document.querySelector(`.pilihan_${soal}_${option}`).classList.add('option-disabled-false');
            document.querySelector(`.pilihan_${soal}_${jawabanBenar}`).classList.add('option-disabled-true');
        }
    }
</script>


{{-- view untuk upload jawaban pg murid dengan option A - E dan bobot 1 - 5 --}}
{{-- jika id_soal pada $getJawaban sama dengan $item->id pada $getSoal maka menampilkan data tersebut --}}
{{-- @if (isset($getJawaban[$item->id]))




                                        @foreach ($getJawaban[$item->id] as $bobot)
                                            <label class="pilihanSoal">
                                                @if ($bobot->bobot_A >= 5 && $bobot->pilihan_ganda === 'A')
                                                    {{-- jika nilai bobot_A >= 5 dan pilihan_ganda(jawaban user) A, warna hijau --}
                                                    <div class="option-disabled-true bg-green-100">
                                                        <div class="flex items-center gap-1">
                                                            <span class="text-sm">A.</span>
                                                            <span class="text-sm">{!! $item->pilihan_A !!}</span>
                                                        </div>
                                                        <p class="text-xs">{{ $bobot->bobot_A }}</p>
                                                    </div>
                                                @elseif($bobot->bobot_A >= 5)
                                                    {{-- jika nilai bobot_A >= 5 dan pilihan_ganda(jawaban user) bukan A, maka option A dengan bobot 5 warna hijau --}
                                                    <div class="option-disabled-true bg-green-100">
                                                        <div class="flex items-center gap-1">
                                                            <span class="text-sm">A.</span>
                                                            <span class="text-sm">{!! $item->pilihan_A !!}</span>
                                                        </div>
                                                        <p class="text-xs">{{ $bobot->bobot_A }}</p>
                                                    </div>
                                                @elseif($bobot->bobot_A < 5 && $bobot->pilihan_ganda === 'A')
                                                    {{-- jika nilai bobot_A < 5 dan pilihan_ganda(jawaban user) A, warna gray (simple nya jawaban user A tapi option A bobot nya < 5) --}
                                                    <div class="option-disabled-current bg-gray-200">
                                                        <div class="flex items-center gap-1">
                                                            <span class="text-sm">A.</span>
                                                            <span class="text-sm">{!! $item->pilihan_A !!}</span>
                                                        </div>
                                                        <p class="text-xs">{{ $bobot->bobot_A }}</p>
                                                    </div>
                                                @else
                                                    {{-- option yang tidak dipilih user  dan yang bobot option nya < 5 --}
                                                    <div class="option-disabled">
                                                        <div class="flex items-center gap-1">
                                                            <span class="text-sm">A.</span>
                                                            <span class="text-sm">{!! $item->pilihan_A !!}</span>
                                                        </div>
                                                        <p class="text-xs">{{ $bobot->bobot_A }}</p>
                                                    </div>
                                                @endif
                                            </label>
                                        @endforeach
                                    @else
                                        <input type="radio" id="pilihan_A_{{ $loop->iteration }}"
                                            name="jawaban[{{ $loop->iteration }}]"
                                            value="A|{!! $item->pilihan_A !!}|{!! $item->bobot_A !!}|{!! $item->bobot_B !!}|{!! $item->bobot_C !!}|{!! $item->bobot_D !!}|{!! $item->bobot_E !!}|{{ $item->bobot_A }}">
                                        {{-- ketika user memilih option A maka akan mengirim semua bobot a - e --}
                                        {{-- $item->bobot_A yang terakhir itu untuk nilai_jawaban --}
                                        <label for="pilihan_A_{{ $loop->iteration }}" class="pilihanSoal">
                                            <div class="option gap-1">
                                                <span class="text-xs">A.</span>
                                                <span class="text-sm">{!! $item->pilihan_A !!}</span>
                                            </div>
                                        </label>
                                    @endif

                                    @if (isset($getJawaban[$item->id]))
                                        @foreach ($getJawaban[$item->id] as $bobot)
                                            <label class="pilihanSoal">
                                                @if ($bobot->bobot_B >= 5 && $bobot->pilihan_ganda === 'B')
                                                    {{-- jika nilai bobot_B >= 5 dan pilihan_ganda(jawaban user) B, warna hijau --}
                                                    <div class="option-disabled-true bg-green-100">
                                                        <div class="flex items-center gap-1">
                                                            <span class="text-sm">B.</span>
                                                            <span class="text-sm">{!! $item->pilihan_B !!}</span>
                                                        </div>
                                                        <p class="text-xs">{{ $bobot->bobot_B }}</p>
                                                    </div>
                                                @elseif($bobot->bobot_B >= 5)
                                                    {{-- jika nilai bobot_B >= 5 dan pilihan_ganda(jawaban user) bukan B, maka option B dengan bobot 5 warna hijau --}
                                                    <div class="option-disabled-true bg-green-100">
                                                        <div class="flex items-center gap-1">
                                                            <span class="text-sm">B.</span>
                                                            <span class="text-sm">{!! $item->pilihan_B !!}</span>
                                                        </div>
                                                        <p class="text-xs">{{ $bobot->bobot_B }}</p>
                                                    </div>
                                                @elseif($bobot->bobot_B < 5 && $bobot->pilihan_ganda === 'B')
                                                    {{-- jika nilai bobot_B < 5 dan pilihan_ganda(jawaban user) B, warna gray (simple nya jawaban user B tapi option B bobot nya < 5) --}
                                                    <div class="option-disabled-current bg-gray-200">
                                                        <div class="flex items-center gap-1">
                                                            <span class="text-sm">B.</span>
                                                            <span class="text-sm">{!! $item->pilihan_B !!}</span>
                                                        </div>
                                                        <p class="text-xs">{{ $bobot->bobot_B }}</p>
                                                    </div>
                                                @else
                                                    {{-- option yang tidak dipilih user  dan yang bobot option nya < 5 --}
                                                    <div class="option-disabled">
                                                        <div class="flex items-center gap-1">
                                                            <span class="text-sm">B.</span>
                                                            <span class="text-sm">{!! $item->pilihan_B !!}</span>
                                                        </div>
                                                        <p class="text-xs">{{ $bobot->bobot_B }}</p>
                                                    </div>
                                                @endif
                                            </label>
                                            </label>
                                        @endforeach
                                    @else
                                        <input type="radio" id="pilihan_B_{{ $loop->iteration }}"
                                            name="jawaban[{{ $loop->iteration }}]"
                                            value="B|{!! $item->pilihan_B !!}|{!! $item->bobot_A !!}|{!! $item->bobot_B !!}|{!! $item->bobot_C !!}|{!! $item->bobot_D !!}|{!! $item->bobot_E !!}|{{ $item->bobot_B }}">
                                        {{-- ketika user memilih option B maka akan mengirim semua bobot a - e --}
                                        {{-- $item->bobot_B yang terakhir itu untuk nilai_jawaban --}
                                        <label for="pilihan_B_{{ $loop->iteration }}" class="pilihanSoal">
                                            <div class="option gap-1">
                                                <span class="text-xs">B.</span>
                                                <span class="text-sm">{!! $item->pilihan_B !!}</span>
                                            </div>
                                        </label>
                                    @endif

                                    @if (isset($getJawaban[$item->id]))
                                        @foreach ($getJawaban[$item->id] as $bobot)
                                            <label class="pilihanSoal">
                                                @if ($bobot->bobot_C >= 5 && $bobot->pilihan_ganda === 'C')
                                                    {{-- jika nilai bobot_C >= 5 dan pilihan_ganda(jawaban user) C, warna hijau --}
                                                    <div class="option-disabled-true bg-green-100">
                                                        <div class="flex items-center gap-1">
                                                            <span class="text-sm">C.</span>
                                                            <span class="text-sm">{!! $item->pilihan_C !!}</span>
                                                        </div>
                                                        <p class="text-xs">{{ $bobot->bobot_C }}</p>
                                                    </div>
                                                @elseif($bobot->bobot_C >= 5)
                                                    {{-- jika nilai bobot_C >= 5 dan pilihan_ganda(jawaban user) bukan C, maka option C dengan bobot 5 warna hijau --}
                                                    <div class="option-disabled-true bg-green-100">
                                                        <div class="flex items-center gap-1">
                                                            <span class="text-sm">C.</span>
                                                            <span class="text-sm">{!! $item->pilihan_C !!}</span>
                                                        </div>
                                                        <p class="text-xs">{{ $bobot->bobot_C }}</p>
                                                    </div>
                                                @elseif($bobot->bobot_C < 5 && $bobot->pilihan_ganda === 'C')
                                                    {{-- jika nilai bobot_C < 5 dan pilihan_ganda(jawaban user) C, warna gray (simple nya jawaban user C tapi option C bobot nya < 5) --}
                                                    <div class="option-disabled-current bg-gray-200">
                                                        <div class="flex items-center gap-1">
                                                            <span class="text-sm">C.</span>
                                                            <span class="text-sm">{!! $item->pilihan_C !!}</span>
                                                        </div>
                                                        <p class="text-xs">{{ $bobot->bobot_C }}</p>
                                                    </div>
                                                @else
                                                    {{-- option yang tidak dipilih user  dan yang bobot option nya < 5 --}
                                                    <div class="option-disabled">
                                                        <div class="flex items-center gap-1">
                                                            <span class="text-sm">C.</span>
                                                            <span class="text-sm">{!! $item->pilihan_C !!}</span>
                                                        </div>
                                                        <p class="text-xs">{{ $bobot->bobot_C }}</p>
                                                    </div>
                                                @endif
                                            </label>
                                            </label>
                                        @endforeach
                                    @else
                                        <input type="radio" id="pilihan_C_{{ $loop->iteration }}"
                                            name="jawaban[{{ $loop->iteration }}]"
                                            value="C|{!! $item->pilihan_C !!}|{!! $item->bobot_A !!}|{!! $item->bobot_B !!}|{!! $item->bobot_C !!}|{!! $item->bobot_D !!}|{!! $item->bobot_E !!}|{{ $item->bobot_C }}">
                                        {{-- ketika user memilih option C maka akan mengirim semua bobot a - e --}
                                        {{-- $item->bobot_C yang terakhir itu untuk nilai_jawaban --}
                                        <label for="pilihan_C_{{ $loop->iteration }}" class="pilihanSoal">
                                            <div class="option gap-1">
                                                <span class="text-sm">C.</span>
                                                <span class="text-sm">{!! $item->pilihan_C !!}</span>
                                            </div>
                                        </label>
                                    @endif

                                    @if (isset($getJawaban[$item->id]))
                                        @foreach ($getJawaban[$item->id] as $bobot)
                                            <label class="pilihanSoal">
                                                @if ($bobot->bobot_D >= 5 && $bobot->pilihan_ganda === 'D')
                                                    {{-- jika nilai bobot_D >= 5 dan pilihan_ganda(jawaban user) D, warna hijau --}
                                                    <div class="option-disabled-true bg-green-100">
                                                        <div class="flex items-center gap-1">
                                                            <span class="text-sm">D.</span>
                                                            <span class="text-sm">{!! $item->pilihan_D !!}</span>
                                                        </div>
                                                        <p class="text-xs">{{ $bobot->bobot_D }}</p>
                                                    </div>
                                                @elseif($bobot->bobot_D >= 5)
                                                    {{-- jika nilai bobot_D >= 5 dan pilihan_ganda(jawaban user) bukan D, maka option D dengan bobot 5 warna hijau --}
                                                    <div class="option-disabled-true bg-green-100">
                                                        <div class="flex items-center gap-1">
                                                            <span class="text-sm">D.</span>
                                                            <span class="text-sm">{!! $item->pilihan_D !!}</span>
                                                        </div>
                                                        <p class="text-xs">{{ $bobot->bobot_D }}</p>
                                                    </div>
                                                @elseif($bobot->bobot_D < 5 && $bobot->pilihan_ganda === 'D')
                                                    {{-- jika nilai bobot_D < 5 dan pilihan_ganda(jawaban user) D, warna gray (simple nya jawaban user D tapi option D bobot nya < 5) --}
                                                    <div class="option-disabled-current bg-gray-200">
                                                        <div class="flex items-center gap-1">
                                                            <span class="text-sm">D.</span>
                                                            <span class="text-sm">{!! $item->pilihan_D !!}</span>
                                                        </div>
                                                        <p class="text-xs">{{ $bobot->bobot_D }}</p>
                                                    </div>
                                                @else
                                                    {{-- option yang tidak dipilih user  dan yang bobot option nya < 5 --}
                                                    <div class="option-disabled">
                                                        <div class="flex items-center gap-1">
                                                            <span class="text-sm">D.</span>
                                                            <span class="text-sm">{!! $item->pilihan_D !!}</span>
                                                        </div>
                                                        <p class="text-xs">{{ $bobot->bobot_D }}</p>
                                                    </div>
                                                @endif
                                            </label>
                                            </label>
                                        @endforeach
                                    @else
                                        <input type="radio" id="pilihan_D_{{ $loop->iteration }}"
                                            name="jawaban[{{ $loop->iteration }}]"
                                            value="D|{!! $item->pilihan_D !!}|{!! $item->bobot_A !!}|{!! $item->bobot_B !!}|{!! $item->bobot_C !!}|{!! $item->bobot_D !!}|{!! $item->bobot_E !!}|{{ $item->bobot_D }}">
                                        {{-- ketika user memilih option D maka akan mengirim semua bobot a - e --}
                                        {{-- $item->bobot_D yang terakhir itu untuk nilai_jawaban --}
                                        <label for="pilihan_D_{{ $loop->iteration }}" class="pilihanSoal">
                                            <div class="option gap-1">
                                                <span class="text-sm">D.</span>
                                                <span class="text-sm">{!! $item->pilihan_D !!}</span>
                                            </div>
                                        </label>
                                    @endif

                                    @if (isset($getJawaban[$item->id]))
                                        @foreach ($getJawaban[$item->id] as $bobot)
                                            <label class="pilihanSoal">
                                                @if ($bobot->bobot_E >= 5 && $bobot->pilihan_ganda === 'E')
                                                    {{-- jika nilai bobot_E >= 5 dan pilihan_ganda(jawaban user) E, warna hijau --}
                                                    <div class="option-disabled-true bg-green-100">
                                                        <div class="flex items-center gap-1">
                                                            <span class="text-sm">E.</span>
                                                            <span class="text-sm">{!! $item->pilihan_E !!}</span>
                                                        </div>
                                                        <p class="text-xs">{{ $bobot->bobot_E }}</p>
                                                    </div>
                                                @elseif($bobot->bobot_E >= 5)
                                                    {{-- jika nilai bobot_E >= 5 dan pilihan_ganda(jawaban user) bukan E, maka option E dengan bobot 5 warna hijau --}
                                                    <div class="option-disabled-true bg-green-100">
                                                        <div class="flex items-center gap-1">
                                                            <span class="text-sm">E.</span>
                                                            <span class="text-sm">{!! $item->pilihan_E !!}</span>
                                                        </div>
                                                        <p class="text-xs">{{ $bobot->bobot_E }}</p>
                                                    </div>
                                                @elseif($bobot->bobot_E < 5 && $bobot->pilihan_ganda === 'E')
                                                    {{-- jika nilai bobot_E < 5 dan pilihan_ganda(jawaban user) E, warna gray (simple nya jawaban user E tapi option E bobot nya < 5) --}
                                                    <div class="option-disabled-current bg-gray-200">
                                                        <div class="flex items-center gap-1">
                                                            <span class="text-sm">E.</span>
                                                            <span class="text-sm">{!! $item->pilihan_E !!}</span>
                                                        </div>
                                                        <p class="text-xs">{{ $bobot->bobot_E }}</p>
                                                    </div>
                                                @else
                                                    {{-- option yang tidak dipilih user  dan yang bobot option nya < 5 --}
                                                    <div class="option-disabled">
                                                        <div class="flex items-center gap-1">
                                                            <span class="text-sm">E.</span>
                                                            <span class="text-sm">{!! $item->pilihan_E !!}</span>
                                                        </div>
                                                        <p class="text-xs">{{ $bobot->bobot_E }}</p>
                                                    </div>
                                                @endif
                                            </label>
                                            </label>
                                        @endforeach
                                    @else
                                        <input type="radio" id="pilihan_E_{{ $loop->iteration }}"
                                            name="jawaban[{{ $loop->iteration }}]"
                                            value="E|{!! $item->pilihan_E !!}|{!! $item->bobot_A !!}|{!! $item->bobot_B !!}|{!! $item->bobot_C !!}|{!! $item->bobot_D !!}|{!! $item->bobot_E !!}|{{ $item->bobot_E }}">
                                        {{-- ketika user memilih option E maka akan mengirim semua bobot a - e --}
                                        {{-- $item->bobot_E yang terakhir itu untuk nilai_jawaban --}
                                        <label for="pilihan_E_{{ $loop->iteration }}" class="pilihanSoal">
                                            <div class="option gap-1">
                                                <span class="text-xs">E.</span>
                                                <span class="text-sm">{!! $item->pilihan_E !!}</span>
                                            </div>
                                        </label>
                                    @endif
                                </div>
                                @if (!$totalNilai)
                                    {{-- text in here --}
                                @else
                                    <div class="px-4 my-10 text-sm">
                                        <div class="bg-green-100 p-4 rounded-lg">
                                            <div class="flex gap-2 mb-4">
                                                <span>Jawaban Benar:</span>
                                                <span class="text-green-600 font-bold">{!! $item->jawaban !!}</span>
                                            </div>
                                            <span class="">{!! $item->deskripsi_jawaban !!}</span>
                                        </div>
                                    </div>
                                @endif --}}
