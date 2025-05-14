<x-script></x-script>

<div class="min-h-screen flex items-center justify-center bg-[url('/image/register/background-register.png')] bg-cover">
    <div class="bg-white rounded-xl shadow-md w-full max-w-xl p-6 mx-8">

        <!-- Step Indicator -->
        <div class="flex items-center justify-between mx-8 gap-2">
            <!-- Step 1: Data User -->
            <div class="flex items-center gap-2">
                <div id="step-indicator-1" class="flex flex-col items-center text-gray-400 font-bold relative">
                    <div id="number-indicator-1"
                        class="w-8 h-8 border-2 border-gray-300 flex items-center justify-center font-semibold rounded-full">
                        1
                    </div>
                    <div id="check-indicator-1" class="hidden">
                        <span id="check-indicator-1"
                            class="w-8 h-8 bg-green-500 text-white border-2 border-green-500 flex items-center justify-center font-semibold rounded-full">
                            <i class="fa-solid fa-check"></i>
                        </span>
                    </div>
                    <span class="text-sm mt-1 absolute top-10 text-center w-max">Data User</span>
                </div>
            </div>
            <div id="line-indicator-1" class="flex-1 h-0.5 border-t-4 border-dashed border-gray-300 mx-2"></div>

            <div class="flex items-center gap-2">
                <div id="step-indicator-2" class="flex flex-col items-center text-gray-400 font-bold relative">
                    <div id="number-indicator-2"
                        class="w-8 h-8 border-2 border-gray-300 flex items-center justify-center rounded-full">
                        2
                    </div>
                    <div id="check-indicator-2" class="hidden">
                        <span id="check-indicator-1"
                            class="w-8 h-8 bg-green-500 text-white border-2 border-green-500 flex items-center justify-center font-semibold rounded-full">
                            <i class="fa-solid fa-check"></i>
                        </span>
                    </div>
                    <span class="text-sm mt-1 absolute top-10 text-center w-max">Pendidikan</span>
                </div>
            </div>
            <div id="line-indicator-2" class="flex-1 h-0.5 border-t-4 border-dashed border-gray-300 mx-2"></div>

            <div class="flex items-center gap-2">
                <div id="step-indicator-3" class="flex flex-col items-center text-gray-400 font-bold relative">
                    <div id="number-indicator-3"
                        class="w-8 h-8 border-2 border-gray-300 flex items-center justify-center rounded-full">
                        3
                    </div>
                    <div id="check-indicator-3" class="hidden">
                        <span id="check-indicator-3"
                            class="w-8 h-8 bg-green-500 text-white border-2 border-green-500 flex items-center justify-center font-semibold rounded-full">
                            <i class="fa-solid fa-check"></i>
                        </span>
                    </div>
                    <span class="text-sm mt-1 absolute top-10 text-center w-max">Mengajar</span>
                </div>
                <div id="line-indicator-3" class="w-20 h-0.5 border-t-4 border-dashed border-gray-300 mx-2"></div>
            </div>

            <div class="flex items-center gap-2">
                <div id="step-indicator-4" class="flex flex-col items-center text-gray-400 font-bold relative">
                    <div id="number-indicator-4"
                        class="w-8 h-8 border-2 border-gray-300 flex items-center justify-center rounded-full">
                        4
                    </div>
                    <div id="check-indicator-4" class="hidden">
                        <span id="check-indicator-4"
                            class="w-8 h-8 bg-green-500 text-white border-2 border-green-500 flex items-center justify-center font-semibold rounded-full">
                            <i class="fa-solid fa-check"></i>
                        </span>
                    </div>
                    <span class="text-sm mt-1 absolute top-10 text-center w-max">OTP</span>
                </div>
            </div>
        </div>
        <form id="register-form" action="{{ route('registerMentor.store') }}" method="POST" class="mt-6">
            @csrf
            <!-- STEP 1: DATA USER -->
            <div id="step-1" class="step">
                <!-- Nama Lengkap -->
                <div class="flex flex-col">
                    <label for="nama_lengkap" class="mb-2 text-sm mt-8">Nama Lengkap<sup
                            class="text-red-500 pl-1">&#42;</sup></label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                        class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border-[2px] outline-none rounded-md px-2
                            focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue]"
                        placeholder="Masukkan Username">
                </div>

                <!-- No HP -->
                <div class="flex flex-col">
                    <label for="no_hp" class="mb-2 text-sm mt-8">No.HP<sup
                            class="text-red-500 pl-1">&#42;</sup></label>
                    <input type="tel" name="no_hp" value="{{ old('no_hp') }}"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border-[2px] outline-none rounded-md px-2
                            focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue]"
                        placeholder="Masukkan No.HP" maxlength="12">
                </div>

                <!-- Email -->
                <div class="flex flex-col">
                    <label for="email" class="mb-2 text-sm mt-8">Email<sup
                            class="text-red-500 pl-1">&#42;</sup></label>
                    <input type="text" id="personal_email" name="personal_email" value="{{ old('personal_email') }}"
                        class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border-[2px] outline-none rounded-md px-2
                        focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue]"
                        placeholder="Masukkan Email">
                </div>

                <!-- Navigasi -->
                <div class="flex justify-between mt-6">
                    <button type="button" disabled
                        class="bg-gray-300 text-gray-600 px-4 py-2 rounded-md cursor-not-allowed">Kembali</button>
                    <button type="button" onclick="nextStep(1)"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Lanjut</button>
                </div>
            </div>

            <!-- STEP 2: PENDIDIKAN USER -->
            <div id="step-2" class="step hidden">
                <!-- Pendidikan -->
                <div class="flex flex-col">
                    <label for="status_pendidikan" class="mb-2 text-sm mt-8">Status Pendidikan<sup
                            class="text-red-500 pl-1">&#42;</sup></label>
                    <select name="status_pendidikan" id=""
                        class="bg-white shadow-lg h-12 text-sm border-gray-200 border-[2px] outline-none rounded-md px-2
                            focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue cursor-pointer">
                        <option value="" class="hidden">Pilih Pendidikan</option>
                        <option value="D3" {{ @old('status_pendidikan') === 'D3' ? 'selected' : '' }}>D3</option>
                        <option value="D4" {{ @old('status_pendidikan') === 'D4' ? 'selected' : '' }}>D4</option>
                        <option value="S1" {{ @old('status_pendidikan') === 'S1' ? 'selected' : '' }}>S1</option>
                    </select>
                </div>

                <!-- Bidang Pendidikan -->
                <div class="flex flex-col">
                    <label for="bidang" class="mb-2 text-sm mt-8">Bidang<sup
                            class="text-red-500 pl-1">&#42;</sup></label>
                    <select name="bidang" id=""
                        class="bg-white shadow-lg h-12 text-sm border-gray-200 border-[2px] outline-none rounded-md px-2
                            focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue cursor-pointer">
                        <option value="" class="hidden">Pilih Bidang</option>
                        <option value="pendidikan" {{ @old('bidang') === 'pendidikan' ? 'selected' : '' }}>
                            Pendidikan
                        </option>
                        <option value="non-pendidikan" {{ @old('bidang') === 'non-pendidikan' ? 'selected' : '' }}>
                            Non-pendidikan
                        </option>
                    </select>
                </div>

                <!-- Jurusan -->
                <div class="flex flex-col">
                    <label for="jurusan" class="mb-2 text-sm mt-8">Jurusan<sup
                            class="text-red-500 pl-1">&#42;</sup></label>
                    <input type="text" name="jurusan" value="{{ old('jurusan') }}"
                        class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border-[2px] outline-none rounded-md px-2
                            focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue]"
                        placeholder="Masukkan Jurusan">
                </div>

                <!-- Tahun Lulus -->
                <div class="flex flex-col">
                    <label for="tahun_lulus" class="mb-2 text-sm mt-8">Tahun Lulus (Opsional)</label>
                    <input type="tel" name="tahun_lulus" value="{{ old('tahun_lulus') }}"
                        class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border-[2px] outline-none rounded-md px-2
                            focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue]"
                        placeholder="Masukkan Tahun Lulus" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        maxlength="4">
                </div>
                <!-- Navigasi -->
                <div class="flex justify-between mt-6">
                    <button type="button" onclick="prevStep(2)"
                        class="border border-blue-600 text-blue-600 px-4 py-2 rounded-md hover:bg-blue-50">Kembali</button>
                    <button type="button" onclick="nextStep(2)"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Lanjut</button>
                </div>
            </div>

            <!-- STEP 3: SEKOLAH MENGAJAR -->
            <div id="step-3" class="step hidden">
                <!-- Sekolah -->
                <div class="flex flex-col">
                    <label for="sekolah" class="mb-2 text-sm mt-8">Nama Sekolah (Opsional)</label>
                    <input type="text" name="sekolah_mengajar" value="{{ old('sekolah_mengajar') }}"
                        class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border-[2px] outline-none rounded-md px-2
                        focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue]"
                        placeholder="Masukkan Nama Sekolah">
                </div>
                <!-- Navigasi -->
                <div class="flex justify-between mt-6">
                    <button type="button" onclick="prevStep(3)"
                        class="border border-blue-600 text-blue-600 px-4 py-2 rounded-md hover:bg-blue-50">Kembali</button>
                    <!-- Tombol Kirim OTP -->
                    <button type="button" onclick="sendOtpEmail(3)"
                        class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 mt-4">
                        Lanjut
                    </button>
                </div>
            </div>

            <!-- STEP 4: OTP -->
            <div id="step-4" class="step hidden py-8">
                <input type="hidden" name="step" value="{{ old('step', 4) }}">
                <!-- untuk menyimpan step jika otp gagal dan balik ke step 4 -->
                <div class="flex justify-center">
                    <img src="{{ asset('image/register/otp-image.png') }}" alt="" class="w-[200px]">
                </div>
                <div class="flex flex-col items-center justify-center space-y-4 py-6">
                    <h2 class="text-xl font-semibold">Enter OTP Code</h2>
                    <p class="text-gray-600 text-sm text-center">Masukkan kode OTP yang dikirim ke email kamu.</p>

                    <div id="otp" class="flex space-x-2">
                        @for ($i = 0; $i < 6; $i++)
                            <input type="tel" name="otp[]" value="{{ old('otp.' . $i) }}" maxlength="1"
                                inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                class="otp-input w-12 h-12 text-center border-2 border-gray-300 rounded-lg text-xl focus:outline-none focus:border-blue-500 {{ session('otp-error') ? 'animate-shake border-red-400' : '' }}" />
                        @endfor
                    </div>
                </div>
                @if (session('otp-error'))
                    <div class="text-red-500 text-sm text-center mt-2 pb-4">
                        {{ session('otp-error') }}
                    </div>
                @endif
                <div class="text-center pb-6">
                    <span>Tidak mendapatkan kode?</span>
                    <button id="resendBtn" type="button" onclick="startResendCountdown()"
                        class="text-blue-600 underline hidden">
                        Kirim ulang
                    </button>
                    <div id="otpError" class="text-red-500 text-sm text-center mt-2 hidden"></div>
                </div>
                <!-- Navigasi -->
                <div class="flex justify-end mt-6">
                    {{-- <button type="button" onclick="prevStep(4)"
                        class="border border-blue-600 text-blue-600 px-4 py-2 rounded-md hover:bg-blue-50">Kembali</button> --}}
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Verifikasi & Daftar
                    </button>
                </div>
            </div>
        </form>
        {{-- <form id="form">
                <!-- STEP 1 -->
                <div id="step-1" class="step">
                    <input type="text" placeholder="Nama Lengkap"
                        class="w-full px-4 py-2 mb-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <input type="text" placeholder="Username"
                        class="w-full px-4 py-2 mb-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <input type="email" placeholder="Email"
                        class="w-full px-4 py-2 mb-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <input type="tel" placeholder="Nomor Handphone"
                        class="w-full px-4 py-2 mb-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <select
                        class="w-full px-4 py-2 mb-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>Laki-laki</option>
                        <option>Perempuan</option>
                    </select>
                    <input type="text" placeholder="Kode Referral (Opsional)"
                        class="w-full px-4 py-2 mb-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <label class="flex items-start mt-2 text-sm text-gray-600">
                        <input type="checkbox" class="mt-1 mr-2" /> Saya setuju dengan
                        <a href="#" class="text-blue-600 underline ml-1">Syarat & Ketentuan</a>
                    </label>
                    <div class="flex justify-between mt-6">
                        <button type="button" disabled
                            class="bg-gray-300 text-gray-600 px-4 py-2 rounded-md cursor-not-allowed">Kembali</button>
                        <button type="button" onclick="nextStep(1)"
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Lanjut</button>
                    </div>
                </div>

                <!-- STEP 2 -->
                <div id="step-2" class="step hidden">
                    <input type="text" placeholder="Nama Kelas"
                        class="w-full px-4 py-2 mb-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <div class="flex justify-between mt-6">
                        <button type="button" onclick="prevStep(2)"
                            class="border border-blue-600 text-blue-600 px-4 py-2 rounded-md hover:bg-blue-50">Kembali</button>
                        <button type="button" onclick="nextStep(2)"
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Lanjut</button>
                    </div>
                </div>

                <!-- STEP 3 -->
                <div id="step-3" class="step hidden">
                    <input type="password" placeholder="Kata Sandi"
                        class="w-full px-4 py-2 mb-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <input type="password" placeholder="Konfirmasi Sandi"
                        class="w-full px-4 py-2 mb-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <div class="flex justify-between mt-6">
                        <button type="button" onclick="prevStep(3)"
                            class="border border-blue-600 text-blue-600 px-4 py-2 rounded-md hover:bg-blue-50">Kembali</button>
                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Daftar</button>
                    </div>
                </div>
            </form> --}}
    </div>
    </section>


    {{-- <script>
    let currentStep = 1;

    function showStep(step) {
        for (let i = 1; i <= 3; i++) {
            document.getElementById(`step-${i}`).classList.add('hidden');
            const indicator = document.getElementById(`step-indicator-${i}`);
            indicator.classList.remove('text-blue-600', 'font-semibold');
            indicator.classList.add('text-gray-400');
            indicator.querySelector('div').classList.remove('border-blue-600');
            indicator.querySelector('div').classList.add('border-gray-300');

            indicator.querySelector('div').textContent = i;
        }

        document.getElementById(`step-${step}`).classList.remove('hidden');
        const currentIndicator = document.getElementById(`step-indicator-${step}`);
        currentIndicator.classList.add('text-blue-600', 'font-semibold');
        currentIndicator.classList.remove('text-gray-400');
        currentIndicator.querySelector('div').classList.add('border-blue-600');
        currentIndicator.querySelector('div').classList.remove('border-gray-300');
    }

    function nextStep(step) {
        document.getElementById(`step-indicator-${step}`).querySelector('div').textContent = '✓';
        const numberIndicator = document.getElementById(`number-indicator-${step}`);
        numberIndicator.classList.add('hidden');

        const lineIndicator = document.getElementById(`line-indicator-${step}`);
        lineIndicator.classList.remove('border-gray-300');
        lineIndicator.classList.add('border-blue-600');

        const checkIndicator = document.getElementById(`check-indicator-${step}`);
        checkIndicator.classList.remove('hidden');

        currentStep = step + 1;
        showStep(currentStep);
    }

    function prevStep(step) {
        document.getElementById(`step-indicator-${step}`).querySelector('div').textContent = step;
        const numberIndicator = document.getElementById(`number-indicator-${step - 1}`);
        numberIndicator.classList.remove('hidden');

        const lineIndicator = document.getElementById(`line-indicator-${step - 1}`);
        lineIndicator.classList.remove('border-blue-600');
        lineIndicator.classList.add('border-gray-300');

        const checkIndicator = document.getElementById(`check-indicator-${step - 1}`);
        checkIndicator.classList.add('hidden');

        currentStep = step - 1;
        showStep(currentStep);
    }

    showStep(currentStep);
</script> --}}
    <script>
        document.getElementById('register-form').addEventListener('keydown', function(e) {
            // Deteksi jika tombol yang ditekan adalah Enter
            if (e.key === 'Enter') {
                // Cari step yang aktif
                const currentStep = document.querySelector('.step:not(.hidden)');

                // Kalau step aktif BUKAN step-4, maka cegah submit
                if (currentStep && currentStep.id !== 'step-4') {
                    e.preventDefault();

                    // (opsional) bisa auto klik tombol lanjut kalau mau
                    const nextButton = currentStep.querySelector('button[onclick^="nextStep"]');
                    if (nextButton) {
                        nextButton.click();
                    }
                }
            }
        });
    </script>

    <script>
        let countdown = null;
        const btn = document.getElementById('resendBtn');
        const COUNTDOWN_KEY = 'otp_resend_expire';

        document.addEventListener('DOMContentLoaded', () => {
            // Cek apakah ada countdown aktif (dari localStorage)
            const expireTime = localStorage.getItem(COUNTDOWN_KEY);
            if (expireTime) {
                const remaining = Math.floor((expireTime - Date.now()) / 1000);
                if (remaining > 0) {
                    runCountdown(remaining);
                    return;
                } else {
                    localStorage.removeItem(COUNTDOWN_KEY);
                }
            }
            // Jika tidak ada countdown aktif, tampilkan tombol normal
            btn.style.display = 'inline-block';
            btn.disabled = false;
            btn.textContent = "Kirim ulang";
        });

        // fungsi untuk mulai countdown
        function startResendCountdown() {
            const email = document.getElementById('personal_email').value;
            const seconds = 60;
            const expireTime = Date.now() + seconds * 1000;
            localStorage.setItem(COUNTDOWN_KEY, expireTime);
            runCountdown(seconds);

            fetch("{{ route('register.sendOtpMailMentor') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    credentials: 'include',
                    body: JSON.stringify({
                        personal_email: email
                    })
                })
                .then(res => {
                    if (!res.ok) throw res;
                    return res.json();
                })
        }

        // fungsi untuk menjalankan countdown
        function runCountdown(duration) {
            let seconds = duration;
            btn.style.display = 'inline-block'; // Pastikan tombol tampil
            btn.disabled = true;
            btn.textContent = `${seconds} detik`;

            countdown = setInterval(() => {
                seconds--;
                if (seconds > 0) {
                    btn.textContent = `${seconds} detik`;
                } else {
                    clearInterval(countdown);
                    btn.disabled = false;
                    countdown = null;
                    btn.textContent = "Kirim ulang";
                    localStorage.removeItem(COUNTDOWN_KEY);
                }
            }, 1000);
        }

        // fungsi untuk reset countdown
        function resetCountDown() {
            const seconds = 60;
            const expireTime = Date.now() + seconds * 1000;
            localStorage.setItem(COUNTDOWN_KEY, expireTime);
            runCountdown(seconds);
        }

        // step dimulai dari 1
        let currentStep = {{ old('step', 1) }};

        // fungsi untuk menampilkan step saat ini
        function showStep(step) {
            for (let i = 1; i <= 4; i++) {
                document.getElementById(`step-${i}`).classList.add('hidden');
                const indicator = document.getElementById(`step-indicator-${i}`);
                indicator.classList.remove('text-blue-600', 'font-semibold');
                indicator.classList.add('text-gray-400');
                indicator.querySelector('div').classList.remove('border-blue-600');
                indicator.querySelector('div').classList.add('border-gray-300');
                indicator.querySelector('div').textContent = i;
            }

            document.getElementById(`step-${step}`).classList.remove('hidden');
            const currentIndicator = document.getElementById(`step-indicator-${step}`);
            currentIndicator.classList.add('text-blue-600', 'font-semibold');
            currentIndicator.classList.remove('text-gray-400');
            currentIndicator.querySelector('div').classList.add('border-blue-600');
            currentIndicator.querySelector('div').classList.remove('border-gray-300');


            currentStep = step;

            // Tandai step sebelumnya sebagai selesai (✓) jika currentStep > 1
            for (let i = 1; i < currentStep; i++) {
                const stepIndicator = document.getElementById(`step-indicator-${i}`);
                const numberIndicator = document.getElementById(`number-indicator-${i}`);
                const checkIndicator = document.getElementById(`check-indicator-${i}`);
                const lineIndicator = document.getElementById(`line-indicator-${i}`);

                if (stepIndicator && numberIndicator && checkIndicator && lineIndicator) {

                    stepIndicator.querySelector('div').textContent = '✓';
                    stepIndicator.querySelector('div').classList.remove('border-gray-300');
                    stepIndicator.querySelector('div').classList.add('border-blue-600');

                    numberIndicator.classList.add('hidden');
                    checkIndicator.classList.remove('hidden');
                    lineIndicator.classList.remove('border-gray-300');
                    lineIndicator.classList.add('border-blue-600');
                }
            }

            if (step === 4) {
                const expireTime = localStorage.getItem(COUNTDOWN_KEY);
                if (expireTime) localStorage.removeItem(COUNTDOWN_KEY);
                {
                    // Hentikan countdown lama
                    if (countdown) {
                        clearInterval(countdown);
                        countdown = null;
                    }

                    btn.disabled = false;
                    btn.textContent = "Kirim ulang";
                }
            }
            // untuk menghapus error ketika text input diubah
            document.querySelectorAll('input, select').forEach(el => {
                el.addEventListener('input', () => {
                    el.classList.remove('border-red-400', 'shadow-[0_0_2px_0_red]');
                    const nextEl = el.nextElementSibling;
                    if (nextEl && nextEl.classList.contains('text-error-message')) {
                        nextEl.remove();
                    }
                });
            });
        }

        // fungsi untuk ke step selanjutnya
        function nextStep(step) {
            const form = document.getElementById('register-form');
            const formData = new FormData(form);
            formData.append('step', step);

            clearErrors();

            fetch("{{ route('register.validateStepFormMentor') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    credentials: 'include',
                    body: formData
                })
                .then(res => {
                    if (!res.ok) throw res;
                    return res.json();
                })
                .then(data => {
                    // Update indikator visual (✓)
                    document.getElementById(`step-indicator-${step}`).querySelector('div').textContent = '✓';
                    const numberIndicator = document.getElementById(`number-indicator-${step}`);
                    numberIndicator.classList.add('hidden');

                    const lineIndicator = document.getElementById(`line-indicator-${step}`);
                    lineIndicator.classList.remove('border-gray-300');
                    lineIndicator.classList.add('border-blue-600');

                    const checkIndicator = document.getElementById(`check-indicator-${step}`);
                    checkIndicator.classList.remove('hidden');

                    currentStep = step + 1;

                    showStep(currentStep);
                })
                .catch(async err => {
                    const errorData = await err.json();
                    showErrors(errorData.errors);
                });
        }

        // fungsi untuk kirim otp email
        function sendOtpEmail(step) {
            const email = document.getElementById('personal_email').value;

            fetch("{{ route('register.sendOtpMailMentor') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    credentials: 'include',
                    body: JSON.stringify({
                        personal_email: email,
                        step
                    })
                })
                .then(res => {
                    if (!res.ok) {
                        return res.json().then(errorData => {
                            if (errorData.errors) {
                                showValidationErrors(errorData.errors);
                            } else {
                                alert('Terjadi kesalahan: ' + (errorData.message || 'Unknown error'));
                            }
                            return Promise.reject();
                        });
                    }
                    return res.json();
                })
                .then(data => {
                    // alert(data.message || 'OTP berhasil dikirim ke email!');
                    document.getElementById(`step-indicator-${step}`).querySelector('div').textContent = '✓';
                    const numberIndicator = document.getElementById(`number-indicator-${step}`);
                    numberIndicator.classList.add('hidden');

                    const lineIndicator = document.getElementById(`line-indicator-${step}`);
                    lineIndicator.classList.remove('border-gray-300');
                    lineIndicator.classList.add('border-blue-600');

                    const checkIndicator = document.getElementById(`check-indicator-${step}`);
                    checkIndicator.classList.remove('hidden');


                    currentStep = step + 1;

                    showStep(currentStep);
                })
                .catch(() => {
                    // alert('Gagal mengirim OTP.');
                });
        }

        // fungsi untuk kembalikan ke step sebelumnya
        function prevStep(step) {
            // Reset indikator visual
            const indicator = document.getElementById(`step-indicator-${step}`);
            indicator.querySelector('div').textContent = step;

            document.getElementById(`step-indicator-${step}`).querySelector('div').textContent = step;
            const numberIndicator = document.getElementById(`number-indicator-${step - 1}`);
            numberIndicator.classList.remove('hidden');

            const lineIndicator = document.getElementById(`line-indicator-${step - 1}`);
            lineIndicator.classList.remove('border-blue-600');
            lineIndicator.classList.add('border-gray-300');

            const checkIndicator = document.getElementById(`check-indicator-${step - 1}`);
            checkIndicator.classList.add('hidden');

            clearErrors();

            currentStep = step - 1;
            showStep(currentStep);
        }

        // fungsi untuk menghapus error
        function clearErrors() {
            document.querySelectorAll('.text-error-message').forEach(el => el.remove());

            document.querySelectorAll('input, select').forEach(el => {
                el.classList.remove('border-red-400', 'shadow-[0_0_12px_0_red]');
            });
        }

        // error untuk step 3 (berbeda function pada step 1 - 2 karena beda function pada controller)
        function showValidationErrors(errors) {
            // Menghapus pesan error sebelumnya
            clearErrors();

            // Menampilkan pesan error baru
            for (let field in errors) {
                const el = document.querySelector(`[name="${field}"]`);
                if (el) {
                    el.classList.add('border-red-400', 'shadow-[0_0_2px_0_red]');
                    const errorMessage = document.createElement('span');
                    errorMessage.classList.add('text-red-500', 'font-bold', 'text-xs', 'pt-2', 'text-error-message');
                    errorMessage.innerText = errors[field][0];
                    el.insertAdjacentElement('afterend', errorMessage);
                }
            }
        }

        // error untuk step 1 - 2
        function showErrors(errors) {
            for (let field in errors) {
                const el = document.querySelector(`[name="${field}"]`);
                if (el) {
                    el.classList.add('border-red-400', 'shadow-[0_0_2px_0_red]');
                    const errorMessage = document.createElement('span');
                    errorMessage.classList.add('text-red-500', 'font-bold', 'text-xs', 'pt-2', 'text-error-message');
                    errorMessage.innerText = errors[field][0];
                    el.insertAdjacentElement('afterend', errorMessage);
                }
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            showStep(currentStep);
        });
    </script>

    <script>
        const otpInputs = document.querySelectorAll('.otp-input');

        otpInputs.forEach((input, index) => {
            input.addEventListener('input', function() {
                if (this.value && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', function(e) {
                if (
                    e.key === 'Backspace' &&
                    (this.selectionStart === 0 || !this.value) &&
                    index > 0
                ) {
                    otpInputs[index - 1].focus();
                }
            });

            input.addEventListener('focus', function() {
                this.select(); // auto-select
            });
        });
    </script>



    <script>
        $(document).ready(function() {
            var oldKelas = "{{ old('kelas') }}" // Ambil kelas yang dipilih jika ada

            var selectKelas = document.getElementById('id_kelas');

            $('#id_fase').on('change', function() {
                var kode_fase = $(this).val();
                if (kode_fase) {
                    $.ajax({
                        url: '/kelas/' + kode_fase,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            selectKelas.disabled =
                                false; // untuk menonaktifkan disabled pada select kelas ketika fase sudah dipilih
                            $('#id_kelas').empty();
                            $('#id_kelas').append(
                                '<option value="" class="hidden">Pilih Kelas</option>'
                            );
                            $.each(data, function(key, kelas) {
                                $('#id_kelas').append(
                                    '<option value="' + kelas.kode + '"' +
                                    (oldKelas == kelas.kode ? ' selected' : '') +
                                    '>' +
                                    kelas.kelas + '</option>'
                                );
                            });

                            if (oldKelas) {
                                $('#id_kelas').val(oldKelas).trigger('change');
                            }
                        }
                    });
                } else {
                    $('#id_kelas').empty();
                }
            });

            if ("{{ old('fase') }}") {
                $('#id_fase').val("{{ old('fase') }}").trigger('change');
            }
        });
    </script>
