<x-script></x-script>
<form action="{{ route('login') }}" method="POST">
    @csrf
    <div class="flex justify-center items-center min-h-screen max-w-full">
        <div class="w-[1000px] h-[430px] flex bg-white shadow-lg rounded-xl overflow-hidden">
            <div class="w-2/4">
                <img src="/image/image-auth.png" alt="" class="w-full h-full">
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
                    class="bg-gray-700 w-[93%] rounded-full h-[40px] text-white font-bold text-sm mx-4">Login</button>

                <div class="text-center mt-10 text-blue-500 font-bold">
                    <a href="/daftar">Belum memiliki akun?</a>
                </div>
            </div>
        </div>
    </div>
</form>
