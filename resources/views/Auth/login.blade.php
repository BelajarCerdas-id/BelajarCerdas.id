<x-script></x-script>
{{-- <form action="{{ route('login') }}" method="POST">
    @csrf
    <div class="flex justify-center items-center min-h-screen max-w-full">
        <div class="w-[1000px] h-[430px] flex bg-white shadow-lg rounded-xl overflow-hidden">
            <div class="w-2/4">
                <img src="/image/logo-sementara.png" alt="" class="w-full h-[80%] object-contain">
            </div>
            <div class="w-2/4">
                <header class="flex justify-center text-slate-600 font-bold my-4">SELAMAT DATANG</header>
                @if (session('alert'))
                    <span class="text-red-500 font-bold text-sm flex justify-center mb-4">{{ session('alert') }}</span>
                @endif
                <div class="mx-4">
                    <div class="flex flex-col w-full mb-6">
                        <label for="email" class="mb-2 text-sm">Email<sup
                                class="text-red-500 pl-1">&#42;</sup></label>
                        <input type="text"
                            class="w-full bg-gray-100 outline-none rounded-xl text-sm p-2 {{ $errors->has('email') ? 'border-[1px] border-red-500' : '' }}"
                            name="email" placeholder="Masukkan email" value="{{ @old('email') }}">
                        @error('email')
                            <span class="text-red-500 font-bold text-sm pt-2">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex flex-col w-full mb-10">
                        <label for="password" class="mb-2 text-sm">Password<sup
                                class="text-red-500 pl-1">&#42;</sup></label>
                        <input type="password"
                            class="w-full bg-gray-100 outline-none rounded-xl text-sm p-2 {{ $errors->has('email') ? 'border-[1px] border-red-500' : '' }}"
                            name="password" placeholder="Masukkan password">
                        @error('password')
                            <span class="text-red-500 font-bold text-sm pt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <button
                    class="bg-[--color-default] w-[93%] rounded-full h-[40px] text-white font-bold text-sm mx-4">Login</button>

                <div class="text-center mt-10 text-blue-500 font-bold">
                    <a href="/daftar">Belum memiliki akun?</a>
                </div>
            </div>
        </div>
    </div>
</form> --}}
@if (session('alert'))
    {{-- <div class="flex justify-center">
        <div role="alert" class="alert alert-error w-max" id="time-alert">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current cursor-pointer" fill="none"
                viewBox="0 0 24 24" id="btnClose">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Error! Task failed successfully.</span>
        </div>
    </div>

    <script>
        setTimeout(function() {
            document.getElementById('time-alert').remove();
        }, 3000);

        document.getElementById('btnClose').addEventListener('click', function() {
            document.getElementById('time-alert').remove();
        })
    </script> --}}
    <script>
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "{{ session('alert') }}!",
        });
    </script>
@endif
<div class="flex justify-center items-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md text-center">
        <img src="image/logoBC.png" alt="Logo" class="mx-auto mb-6 w-2/5">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Selamat Datang kembali</h2>
        <form action="{{ route('login') }}" method="POST" class="flex flex-col space-y-4">
            @csrf
            <div class="w-full">
                <input type="text" name="email" placeholder="Masukkan Email"
                    class="w-full text-sm border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-[#4179e0] focus:border-transparent {{ $errors->has('email') ? 'border-[1px] border-red-500' : '' }}"
                    value="{{ @old('email') }}">
                @error('email')
                    <span class="text-red-500 font-bold text-xs pt-2 flex flex-start">{{ $message }}</span>
                @enderror
            </div>
            <div class="w-full">
                <input type="password" name="password" placeholder="Masukkan Password"
                    class="w-full text-sm border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-[#4179e0] focus:border-transparent {{ $errors->has('password') ? 'border-[1px] border-red-500' : '' }}"
                    value="{{ @old('password') }}">
                @error('password')
                    <span class="text-red-500 font-bold text-xs pt-2 flex flex-start">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit"
                class="bg-[#4179e0] text-white py-3 rounded-lg font-semibold hover:bg-[#4189e0] transition">
                Login
            </button>
        </form>
        {{-- <div class="mt-6 flex items-center justify-center space-x-2">
                <span class="h-px w-full bg-gray-300"></span>
                <span class="text-gray-500">Atau</span>
                <span class="h-px w-full bg-gray-300"></span>
            </div>
            <button
                class="mt-4 flex items-center justify-center border border-gray-300 rounded-lg py-2 px-4 w-full hover:bg-gray-100 transition">
                <img src="https://via.placeholder.com/20x20" alt="Google Logo" class="mr-2">
                Masuk dengan Google
            </button> --}}
        <div class="mt-6 text-sm">
            Belum punya akun? <a href="/daftar" class="text-blue-500 font-semibold hover:underline">Daftar
                sekarang</a>
        </div>
    </div>
</div>
