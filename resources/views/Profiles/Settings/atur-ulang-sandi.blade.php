@include('components/sidebar_beranda', [
    'headerSideNav' => 'Atur Ulang Sandi',
    'backButton' => "<i class='fa-solid fa-chevron-left'></i>",
    'linkBackButton' => route('profile'),
])

@if (Auth::user()->role === 'Siswa' or Auth::user()->role === 'Mentor' or Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda mt-[120px]">
            @if (session('success'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success'),
                ])
            @endif
            <form action="{{ route('aturUlangSandi.update', Auth::user()->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="flex flex-col gap-2 w-full relative mb-10">
                    <label for="current_password">Kata Sandi Lama</label>
                    <input id="currentPasswordInput" type="password" name="current_password"
                        class="w-full bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md text-sm px-2 pr-10 focus:border-[dodgerblue] focus:shadow-[0_0_6px_0_dodgerblue] {{ $errors->has('current_password') ? 'border-red-300' : '' }}"
                        value="{{ old('current_password') }}" placeholder="Masukkan kata sandi lama" maxlength="16">
                    <button type="button" onclick="togglePassword('currentPasswordInput', this)"
                        class="absolute right-3 top-17 transform -translate-y-1/2 text-gray-600 focus:outline-none">
                        <i class="fa-solid fa-eye-slash"></i>
                    </button>
                    @if ($errors->has('current_password'))
                        <span class="text-red-500 font-bold text-xs">{{ $errors->first('current_password') }}</span>
                    @endif
                </div>

                <div class="flex flex-col gap-2 w-full relative mb-10">
                    <label for="new_password">Kata Sandi Baru</label>
                    <input id="newPasswordInput" type="password" name="new_password"
                        class="w-full bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md text-sm px-2 pr-10 focus:border-[dodgerblue] focus:shadow-[0_0_6px_0_dodgerblue] {{ $errors->has('new_password') ? 'border-red-300' : '' }}"
                        placeholder="Masukkan kata sandi baru" value="{{ old('new_password') }}" maxlength="16">
                    <button type="button" onclick="togglePassword('newPasswordInput', this)"
                        class="absolute right-3 top-17 transform -translate-y-1/2 text-gray-600 focus:outline-none">
                        <i class="fa-solid fa-eye-slash"></i>
                    </button>
                    @if ($errors->has('new_password'))
                        <span class="text-red-500 font-bold text-xs">{{ $errors->first('new_password') }}</span>
                    @endif
                </div>

                <div class="flex flex-col gap-2 w-full relative">
                    <label for="new_password_confirmation">Konfirmasi Kata Sandi</label>
                    <input id="confirmPasswordInput" type="password" name="new_password_confirmation"
                        class="w-full bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md text-sm px-2 pr-10 focus:border-[dodgerblue] focus:shadow-[0_0_6px_0_dodgerblue] {{ $errors->has('new_password_confirmation') ? 'border-red-300' : '' }}"
                        placeholder="Konfirmasi kata sandi" value="{{ old('new_password_confirmation') }}"
                        maxlength="16">
                    <button type="button" onclick="togglePassword('confirmPasswordInput', this)"
                        class="absolute right-3 top-17 transform -translate-y-1/2 text-gray-600 focus:outline-none">
                        <i class="fa-solid fa-eye-slash"></i>
                    </button>
                    @if ($errors->has('new_password_confirmation'))
                        <span
                            class="text-red-500 font-bold text-xs">{{ $errors->first('new_password_confirmation') }}</span>
                    @endif
                </div>
                <div class="flex justify-end mt-8">
                    <button id="submit-button"
                        class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif



<script>
    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);
        const icon = button.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('input, select, textarea').forEach(function(el) {
            el.addEventListener('input', function() {
                // Hapus border merah
                el.classList.remove('border-red-300');

                // Cari pesan error di parent container
                const parent = el.closest('div');
                if (parent) {
                    const error = parent.querySelector('.text-red-500');
                    if (error) error.remove();
                }
            });
        });
    });
</script>
