@include('components/sidebar_beranda', ['headerSideNav' => 'Profile'])

@if (Auth::user()->role === 'Siswa')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda mt-[120px]">
            @if (session('success-update-data-personal-student'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-update-data-personal-student'),
                ])
            @endif
            @if (session('success-update-data-pendidikan-student'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-update-data-pendidikan-student'),
                ])
            @endif
            @if (session('success-update-password'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-update-password'),
                ])
            @endif
            <!-- Toast Notification -->
            <div class="flex justify-center">
                <div id="toast-container" class="fixed flex flex-col gap-2 max-h-40 overflow-hidden top-20"></div>
            </div>
            <main>
                <section class="flex flex-col lg:flex-row gap-14">
                    <!--- left profil --->
                    <div class="bg-white w-full lg:w-[500px] h-max lg:h-[520px] shadow-lg rounded-lg py-10">
                        <!--- image user --->
                        <div class="flex justify-center">
                            <i class="fas fa-user-circle text-6xl pb-4"></i>
                        </div>
                        <!--- name & role --->
                        <div class="flex flex-col items-center">
                            {{-- <span>{{ $user->nama_lengkap }}</span> --}}
                            <span class="p-[1px] px-2 bg-[#4189e0] text-white text-sm">{{ Auth::user()->role }}</span>
                        </div>
                        <!--- pengaturan akun --->
                        <div class="flex flex-col gap-4 jsutify-center pt-10 px-6">
                            <!--- label --->
                            <div class="flex items-center gap-2 text-[#4189e0] font-bold">
                                <i class="fa-solid fa-user-gear text-xl"></i>
                                <span class="text-lg">Pengaturan Akun</span>
                            </div>
                            <!--- items --->
                            <ul class="lsit-style-none">
                                <li class="text-sm pl-8 pr-2">
                                    <a href="{{ route('aturUlangSandi') }}" class="flex justify-between w-full">
                                        Atur Ulang Kata Sandi
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!--- right profil --->
                    <div class="w-full flex flex-col gap-4">
                        <!--- Persnal Information --->
                        <div>
                            <div class="flex justify-between">
                                <span class="font-bold text-2xl opacity-60">Personal Information</span>
                                <div class="flex gap-2 items-center cursor-pointer text-[#4189e0] font-bold"
                                    onclick="editPersonalInformation(this, {{ Auth::user()->id }})"
                                    data-form-edit-personal-mentor="{{ route('updatePersonalInformationMentor.update', Auth::user()->id) }}">
                                    <span>Edit</span>
                                    <i class="fas fa-pen"></i>
                                </div>
                            </div>
                            <div
                                class="bg-white h-full shadow-lg rounded-lg px-6 py-6 md:py-2 flex items-center w-full">
                                <div class="grid grid-cols-2 gap-4 w-full">
                                    <div class="flex flex-col gap-2 w-full">
                                        <label for="">Nama Lengkap</label>
                                        <input
                                            class="w-full bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md text-sm px-2"
                                            value="{{ Auth::user()->Profile->nama_lengkap }}" disabled>
                                    </div>
                                    <div class="flex flex-col gap-2 w-full">
                                        <label for="">No.HP</label>
                                        <input
                                            class="w-full bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md text-sm px-2"
                                            value="{{ Auth::user()->no_hp }}" disabled>
                                    </div>
                                    <div class="flex flex-col gap-2 w-full">
                                        <label for="">Email</label>
                                        <input
                                            class="w-full bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md text-sm px-2"
                                            value="{{ Auth::user()->email }}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--- Pendidikan --->
                        <div class="mt-8">
                            <div class="flex justify-between">
                                <span class="font-bold text-2xl opacity-60">Pendidikan</span>
                                <div class="flex gap-2 items-center cursor-pointer text-[#4189e0] font-bold"
                                    onclick="editPendidikan(this, {{ Auth::user()->id }})"
                                    data-form-edit-pendidikan-mentor="{{ route('updatePendidikanMentor.update', Auth::user()->id) }}">
                                    <span>Edit</span>
                                    <i class="fas fa-pen"></i>
                                </div>
                            </div>
                            <div
                                class="bg-white h-full shadow-lg rounded-lg px-6 py-6 md:py-2 flex items-center w-full">
                                <div class="grid grid-cols-2 gap-4 w-full">
                                    <div class="flex flex-col gap-2 w-full">
                                        <label for="">Sekolah</label>
                                        <input
                                            class="w-full bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md text-sm px-2"
                                            value="{{ Auth::user()->Profile->sekolah }}" disabled>
                                    </div>
                                    <div class="flex flex-col gap-2  w-full">
                                        <label for="">Fase</label>
                                        <input
                                            class="w-full bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md text-sm px-2"
                                            value="{{ Auth::user()->Profile->Fase->nama_fase }}" disabled>
                                    </div>
                                    <div class="flex flex-col gap-2  w-full">
                                        <label for="">Kelas</label>
                                        <input
                                            class="w-full bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md text-sm px-2"
                                            value="{{ Auth::user()->Profile->Kelas->kelas }}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
            <!--- modal edit personal information --->
            <dialog id="my_modal_1_{{ Auth::user()->id }}" class="modal">
                <div class="modal-box bg-white w-max focus:outline-none" tabindex="-1">
                    <!---- Form edit personal information ---->
                    <form id="editPersonalInformation_{{ Auth::user()->id }}"
                        action="{{ route('updatePersonalInformationStudent.update', Auth::user()->id) }}"
                        method="POST">
                        @csrf
                        @method('PUT')
                        <span class="text-xl font-bold flex justify-center">Edit Personal Information</span>
                        <!---- Nama Lengkap ---->
                        <div class="mt-4 w-96 md:w-[450px]">
                            <label class="text-sm">
                                Nama Lengkap
                                <sup class="text-red-500 top-0 text-lg">&#42;</sup>
                            </label>
                            <input type="text" id="nama_lengkap_{{ Auth::user()->id }}" name="nama_lengkap"
                                class="w-full bg-white shadow-lg h-11 border-gray-200 border-[1px] outline-none rounded-full text-xs px-2 mt-2
                                {{ $errors->has('nama_lengkap') && session('formErrorInformationId') == Auth::user()->id ? 'border-red-400' : 'focus:border-[dodgerblue] focus:shadow-[0_0_6px_0_dodgerblue]' }}"
                                value="{{ session('formErrorInformationId') == Auth::user()->id ? old('nama_lengkap') : Auth::user()->Profile->nama_lengkap }}"
                                placeholder="Masukkan Nama Lengkap">
                            @if (session('formErrorInformationId') == Auth::user()->id)
                                <span
                                    class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('nama_lengkap') }}</span>
                            @endif
                        </div>
                        <!---- No.HP ---->
                        <div class="mt-4 w-96 md:w-[450px]">
                            <label class="text-sm">
                                No.HP
                                <sup class="text-red-500 top-0 text-lg">&#42;</sup>
                            </label>
                            <input type="text" id="no_hp_{{ Auth::user()->id }}" name="no_hp"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                class="w-full bg-white shadow-lg h-11 border-gray-200 border-[1px] outline-none rounded-full text-xs px-2 mt-2
                                {{ $errors->has('no_hp') && session('formErrorInformationId') == Auth::user()->id ? 'border-red-400' : 'focus:border-[dodgerblue] focus:shadow-[0_0_6px_0_dodgerblue]' }}"
                                value="{{ session('formErrorInformationId') == Auth::user()->id ? old('no_hp') : Auth::user()->no_hp }}"
                                placeholder="Masukkan Nmmor HP" maxlength="12">
                            @if (session('formErrorInformationId') == Auth::user()->id)
                                <span class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('no_hp') }}</span>
                            @endif
                        </div>
                        <!---- Email  ---->
                        <div class="mt-4 w-96 md:w-[450px]">
                            <label class="text-sm">
                                Email
                                <sup class="text-red-500 top-0 text-lg">&#42;</sup>
                            </label>
                            <input type="text" id="email_{{ Auth::user()->id }}" name="email"
                                class="w-full bg-white shadow-lg h-11 border-gray-200 border-[1px] outline-none rounded-full text-xs px-2 mt-2
                                {{ $errors->has('email') && session('formErrorInformationId') == Auth::user()->id ? 'border-red-400' : 'focus:border-[dodgerblue] focus:shadow-[0_0_6px_0_dodgerblue]' }}"
                                value="{{ session('formErrorInformationId') == Auth::user()->id ? old('email') : Auth::user()->email }}"
                                placeholder="Masukkan Email">
                            @if (session('formErrorInformationId') == Auth::user()->id)
                                <span class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <!---- button submit ---->
                        <div class="flex justify-end mt-8">
                            <button id="submit-button"
                                class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
                <form method="dialog" class="modal-backdrop">
                    <button>close</button>
                </form>
            </dialog>

            <!--- modal edit pendidikan --->
            <dialog id="my_modal_2_{{ Auth::user()->id }}" class="modal">
                <div class="modal-box bg-white w-max focus:outline-none" tabindex="-1">
                    <!---- Form edit personal information ---->
                    <form id="editPendidikanForm_{{ Auth::user()->id }}"
                        action="{{ route('updatePendidikanStudent.update', Auth::user()->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <span class="text-xl font-bold flex justify-center">Edit Pendidikan</span>
                        <!---- Sekolah ---->
                        <div class="mt-4 w-96 md:w-[450px]">
                            <label class="text-sm">
                                Sekolah
                                <sup class="text-red-500 top-0 text-lg">&#42;</sup>
                            </label>
                            <input type="text" id="sekolah_{{ Auth::user()->id }}" name="sekolah"
                                class="w-full bg-white shadow-lg h-11 border-gray-200 border-[1px] outline-none rounded-full text-xs px-2 mt-2
                                {{ $errors->has('sekolah') && session('formErrorPendidikanId') == Auth::user()->id ? 'border-red-400' : 'focus:border-[dodgerblue] focus:shadow-[0_0_6px_0_dodgerblue]' }}"
                                value="{{ $errors->has('sekolah') && session('formErrorPendidikanId') == Auth::user()->id ? old('sekolah') : Auth::user()->Profile->sekolah }}"
                                placeholder="Masukkan Nama Sekolah">
                            @if (session('formErrorPendidikanId') == Auth::user()->id)
                                <span
                                    class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('sekolah') }}</span>
                            @endif
                        </div>
                        <!---- Fase ---->
                        <div class="mt-4 w-96 md:w-[450px]">
                            <label class="text-sm">
                                Fase
                                <sup class="text-red-500 top-0 text-lg">&#42;</sup>
                            </label>
                            <select name="fase_id" id="id_fase"
                                class="form-select w-full bg-white shadow-lg h-12 text-sm border-gray-200 border-[2px] outline-none rounded-md px-2
                                focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer
                                {{ $errors->has('fase_id') && session('formErrorPendidikanId') == Auth::user()->id ? 'border-red-400' : 'focus:border-[dodgerblue] focus:shadow-[0_0_6px_0_dodgerblue]' }}">
                                <option value="{{ Auth::user()->Profile->fase_id }}" class="hidden">
                                    {{ Auth::user()->Profile->Fase->nama_fase }}
                                </option>
                                @foreach ($dataFase as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->nama_fase }}
                                    </option>
                                @endforeach
                            </select>
                            @if (session('formErrorPendidikanId') == Auth::user()->id)
                                <span
                                    class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('fase_id') }}</span>
                            @endif
                        </div>
                        <!---- Fase  ---->
                        <div class="mt-4 w-96 md:w-[450px]">
                            <label class="text-sm">
                                Kelas
                                <sup class="text-red-500 top-0 text-lg">&#42;</sup>
                            </label>
                            <!-- Tambahkan hidden input agar nilainya tetap dikirim (kalo ga pake ini, jika select tidak dipilih dan di submit, value akan null dan error karena di disabled) -->
                            <input type="hidden" name="kelas_id" value="{{ Auth::user()->Profile->kelas_id }}">
                            <select name="kelas_id" id="id_kelas"
                                class="form-select w-full bg-white shadow-lg h-12 text-sm border-gray-200 border-[2px] outline-none rounded-md px-2
                                focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] opacity-50
                                {{ $errors->has('kelas_id') && session('formErrorPendidikanId') == Auth::user()->id ? 'border-red-400' : 'focus:border-[dodgerblue] focus:shadow-[0_0_6px_0_dodgerblue]' }}"
                                disabled>
                                <option value="{{ Auth::user()->Profile->kelas_id }}" class="hidden">
                                    {{ Auth::user()->Profile->Kelas->kelas }}
                                </option>
                            </select>
                            @if (session('formErrorPendidikanId') == Auth::user()->id)
                                <span
                                    class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('jurusan') }}</span>
                            @endif
                        </div>
                        <!---- button submit ---->
                        <div class="flex justify-end mt-8">
                            <button id="submit-button"
                                class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
                <form method="dialog" class="modal-backdrop">
                    <button>close</button>
                </form>
            </dialog>
        </div>
    </div>
@elseif(Auth::user()->role === 'Murid')

@elseif(Auth::user()->role === 'Mentor')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda mt-[120px]">
            @if (session('success-update-data-personal-mentor'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-update-data-personal-mentor'),
                ])
            @endif
            @if (session('success-update-data-pendidikan-mentor'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-update-data-pendidikan-mentor'),
                ])
            @endif
            @if (session('success-update-password'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-update-password'),
                ])
            @endif
            <!-- Toast Notification -->
            <div class="flex justify-center">
                <div id="toast-container" class="fixed flex flex-col gap-2 max-h-40 overflow-hidden top-20"></div>
            </div>
            <main>
                <section class="flex flex-col lg:flex-row gap-14">
                    <!--- left profil --->
                    <div class="bg-white w-full lg:w-[500px] h-max lg:h-[820px] shadow-lg rounded-lg py-10">
                        <!--- image user --->
                        <div class="flex justify-center">
                            <i class="fas fa-user-circle text-6xl pb-4"></i>
                        </div>
                        <!--- name & role --->
                        <div class="flex flex-col items-center">
                            {{-- <span>{{ $user->nama_lengkap }}</span> --}}
                            <span class="p-[1px] px-2 bg-[#4189e0] text-white text-sm">{{ Auth::user()->role }}</span>
                        </div>
                        <!--- pengaturan akun --->
                        <div class="flex flex-col gap-4 jsutify-center pt-10 px-6">
                            <!--- label --->
                            <div class="flex items-center gap-2 text-[#4189e0] font-bold">
                                <i class="fa-solid fa-user-gear text-xl"></i>
                                <span class="text-lg">Pengaturan Akun</span>
                            </div>
                            <!--- items --->
                            <ul class="lsit-style-none">
                                <li class="text-sm pl-8 pr-2">
                                    <a href="{{ route('aturUlangSandi') }}" class="flex justify-between w-full">
                                        Atur Ulang Kata Sandi
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!--- Referral Information --->
                        <div class="flex flex-col gap-4 jsutify-center pt-10 px-6">
                            <!--- label --->
                            <div class="flex items-center gap-2 text-[#4189e0] font-bold">
                                <i class="fa-solid fa-code text-xl"></i>
                                <span class="text-lg">Referral</span>
                            </div>
                            <!--- items --->
                            <ul class="lsit-style-none flex flex-col gap-6">
                                <li class="text-sm pl-8 pr-2">
                                    <a href="" class="flex justify-between w-full">
                                        User Terdaftar
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                                <li class="text-sm pl-8 pr-2">
                                    <a href="" class="flex justify-between w-full">
                                        Paket Pembelian User
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!--- right profil --->
                    <div class="w-full flex flex-col gap-4">
                        <!--- Referral Code --->
                        <div class="flex flex-col gap-2 w-full">
                            <label for="">Referral Code</label>
                            <div class="flex gap-2 items-center">
                                <input id="referral-link" type="text" readonly
                                    class="w-full bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md text-sm px-2"
                                    value="{{ Auth::user()->Profile->kode_referral }}">

                                <button type="button" onclick="copyReferralLink()"
                                    class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-lg text-sm hover:bg-blue-600">
                                    Copy
                                </button>
                            </div>
                        </div>
                        <!--- Persnal Information --->
                        <div>
                            <div class="flex justify-between">
                                <span class="font-bold text-2xl opacity-60">Personal Information</span>
                                <div class="flex gap-2 items-center cursor-pointer text-[#4189e0] font-bold"
                                    onclick="editPersonalInformation(this, {{ Auth::user()->id }})"
                                    data-form-edit-personal-mentor="{{ route('updatePersonalInformationMentor.update', Auth::user()->id) }}">
                                    <span>Edit</span>
                                    <i class="fas fa-pen"></i>
                                </div>
                            </div>
                            <div
                                class="bg-white h-full shadow-lg rounded-lg px-6 py-6 md:py-2 flex items-center w-full">
                                <div class="grid grid-cols-2 gap-4 w-full">
                                    <div class="flex flex-col gap-2 w-full">
                                        <label for="">Nama Lengkap</label>
                                        <input
                                            class="w-full bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md text-sm px-2"
                                            value="{{ Auth::user()->Profile->nama_lengkap }}" disabled>
                                    </div>
                                    <div class="flex flex-col gap-2 w-full">
                                        <label for="">No.HP</label>
                                        <input
                                            class="w-full bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md text-sm px-2"
                                            value="{{ Auth::user()->no_hp }}" disabled>
                                    </div>
                                    <div class="flex flex-col gap-2 w-full">
                                        <label for="">Email</label>
                                        <input
                                            class="w-full bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md text-sm px-2"
                                            value="{{ Auth::user()->Profile->personal_email }}" disabled>
                                    </div>
                                    <div class="flex flex-col gap-2 w-full">
                                        <label for="">Tempat Mengajar</label>
                                        <input
                                            class="w-full bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md text-sm px-2"
                                            value="{{ Auth::user()->Profile->sekolah_mengajar ?? 'Belajar Cerdas' }}"
                                            disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--- Pendidikan --->
                        <div class="mt-8">
                            <div class="flex justify-between">
                                <span class="font-bold text-2xl opacity-60">Pendidikan</span>
                                <div class="flex gap-2 items-center cursor-pointer text-[#4189e0] font-bold"
                                    onclick="editPendidikan(this, {{ Auth::user()->id }})"
                                    data-form-edit-pendidikan-mentor="{{ route('updatePendidikanMentor.update', Auth::user()->id) }}">
                                    <span>Edit</span>
                                    <i class="fas fa-pen"></i>
                                </div>
                            </div>
                            <div
                                class="bg-white h-full shadow-lg rounded-lg px-6 py-6 md:py-2 flex items-center w-full">
                                <div class="grid grid-cols-2 gap-4 w-full">
                                    <div class="flex flex-col gap-2 w-full">
                                        <label for="">Status Pendidikan</label>
                                        <input
                                            class="w-full bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md text-sm px-2"
                                            value="{{ Auth::user()->Profile->status_pendidikan }}" disabled>
                                    </div>
                                    <div class="flex flex-col gap-2  w-full">
                                        <label for="">Bidang</label>
                                        <input
                                            class="w-full bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md text-sm px-2"
                                            value="{{ Auth::user()->Profile->bidang }}" disabled>
                                    </div>
                                    <div class="flex flex-col gap-2  w-full">
                                        <label for="">Jurusan</label>
                                        <input
                                            class="w-full bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md text-sm px-2"
                                            value="{{ Auth::user()->Profile->jurusan }}" disabled>
                                    </div>
                                    <div class="flex flex-col gap-2 w-full">
                                        <label for="">Tahun Lulus</label>
                                        <input
                                            class="w-full bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md text-sm px-2"
                                            value="{{ Auth::user()->Profile->tahun_lulus ?? '-' }}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--- Office Account --->
                        <div class="mt-8">
                            <span class="font-bold text-2xl opacity-60">Office Account</span>
                            <div class="bg-white h-max shadow-lg rounded-lg px-6 py-6 md:py-2 w-full">
                                <div class="grid grid-cols-2 gap-2 py-6 w-full">
                                    <div class="flex flex-col gap-2 w-full">
                                        <label for="">Email</label>
                                        <input
                                            class="w-full bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md text-sm px-2"
                                            value="{{ Auth::user()->email }}" disabled>
                                    </div>
                                    <div class="flex flex-col gap-2 w-full relative">
                                        <label for="">Password</label>
                                        <input id="passwordInput" type="password"
                                            class="w-full bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md text-sm px-2 pr-10"
                                            value="{{ Auth::user()->password }}" disabled>
                                        <button type="button" onclick="togglePassword()"
                                            class="absolute right-3 top-17 transform -translate-y-1/2 text-gray-600 focus:outline-none">
                                            <i id="eyeSlash" class="fa-solid fa-eye-slash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6">
                                <span>Mentor Ahli</span>
                                <div
                                    class="bg-white h-max shadow-lg rounded-lg px-6 py-4  w-full mt-2                                                                                                                                                                                                                                                                                        ">
                                    <div class="flex flex-wrap gap-4">
                                        @if ($dataMentorAhli)
                                            @foreach ($dataMentorAhli as $item)
                                                <div
                                                    class="w-32 h-8 flex items-center justify-center bg-[#4189e0] text-white text-sm rounded-md">
                                                    {{ $item->nama_fitur }}
                                                </div>
                                            @endforeach
                                        @else
                                            <span>Belum ada keahlian</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
            <!--- modal edit personal information --->
            <dialog id="my_modal_1_{{ Auth::user()->id }}" class="modal">
                <div class="modal-box bg-white w-max focus:outline-none" tabindex="-1">
                    <!---- Form edit personal information ---->
                    <form id="editPersonalInformation_{{ Auth::user()->id }}"
                        action="{{ route('updatePersonalInformationMentor.update', Auth::user()->id) }}"
                        method="POST">
                        @csrf
                        @method('PUT')
                        <span class="text-xl font-bold flex justify-center">Edit Personal Information</span>
                        <!---- Nama Lengkap ---->
                        <div class="mt-4 w-96 md:w-[450px]">
                            <label class="text-sm">
                                Nama Lengkap
                                <sup class="text-red-500 top-0 text-lg">&#42;</sup>
                            </label>
                            <input type="text" id="nama_lengkap_{{ Auth::user()->id }}" name="nama_lengkap"
                                class="w-full bg-white shadow-lg h-11 border-gray-200 border-[1px] outline-none rounded-full text-xs px-2 mt-2
                                {{ $errors->has('nama_lengkap') && session('formErrorInformationId') == Auth::user()->id ? 'border-red-400' : 'focus:border-[dodgerblue] focus:shadow-[0_0_6px_0_dodgerblue]' }}"
                                value="{{ session('formErrorInformationId') == Auth::user()->id ? old('nama_lengkap') : Auth::user()->Profile->nama_lengkap }}"
                                placeholder="Masukkan Nama Lengkap">
                            @if (session('formErrorInformationId') == Auth::user()->id)
                                <span
                                    class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('nama_lengkap') }}</span>
                            @endif
                        </div>
                        <!---- No.HP ---->
                        <div class="mt-4 w-96 md:w-[450px]">
                            <label class="text-sm">
                                No.HP
                                <sup class="text-red-500 top-0 text-lg">&#42;</sup>
                            </label>
                            <input type="text" id="no_hp_{{ Auth::user()->id }}" name="no_hp"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                class="w-full bg-white shadow-lg h-11 border-gray-200 border-[1px] outline-none rounded-full text-xs px-2 mt-2
                                {{ $errors->has('no_hp') && session('formErrorInformationId') == Auth::user()->id ? 'border-red-400' : 'focus:border-[dodgerblue] focus:shadow-[0_0_6px_0_dodgerblue]' }}"
                                value="{{ session('formErrorInformationId') == Auth::user()->id ? old('no_hp') : Auth::user()->no_hp }}"
                                placeholder="Masukkan Nmmor HP" maxlength="12">
                            @if (session('formErrorInformationId') == Auth::user()->id)
                                <span class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('no_hp') }}</span>
                            @endif
                        </div>
                        <!---- Email  ---->
                        <div class="mt-4 w-96 md:w-[450px]">
                            <label class="text-sm">
                                Email
                                <sup class="text-red-500 top-0 text-lg">&#42;</sup>
                            </label>
                            <input type="text" id="personal_email_{{ Auth::user()->id }}" name="personal_email"
                                class="w-full bg-white shadow-lg h-11 border-gray-200 border-[1px] outline-none rounded-full text-xs px-2 mt-2
                                {{ $errors->has('personal_email') && session('formErrorInformationId') == Auth::user()->id ? 'border-red-400' : 'focus:border-[dodgerblue] focus:shadow-[0_0_6px_0_dodgerblue]' }}"
                                value="{{ session('formErrorInformationId') == Auth::user()->id ? old('personal_email') : Auth::user()->Profile->personal_email }}"
                                placeholder="Masukkan Email">
                            @if (session('formErrorInformationId') == Auth::user()->id)
                                <span
                                    class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('personal_email') }}</span>
                            @endif
                        </div>
                        <!----  Sekolah Mengajar ---->
                        <div class="mt-4 w-96 md:w-[450px]">
                            <label class="text-sm">
                                Sekolah Mengajar
                                <sup class="text-red-500 top-0 text-lg">&#42;</sup>
                            </label>
                            <input type="text" id="sekolah_mengajar_{{ Auth::user()->id }}"
                                name="sekolah_mengajar"
                                class="w-full bg-white shadow-lg h-11 border-gray-200 border-[1px] outline-none rounded-full text-xs px-2 mt-2
                                {{ $errors->has('sekolah_mengajar') && session('formErrorInformationId') == Auth::user()->id ? 'border-red-400' : 'focus:border-[dodgerblue] focus:shadow-[0_0_6px_0_dodgerblue]' }}"
                                value="{{ session('formErrorInformationId') == Auth::user()->id ? old('sekolah_mengajar') : Auth::user()->Profile->sekolah_mengajar }}"
                                placeholder="Masukkan Nama Sekolah Mengajar">
                            @if (session('formErrorInformationId') == Auth::user()->id)
                                <span
                                    class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('sekolah_mengajar') }}</span>
                            @endif
                        </div>
                        <!---- button submit ---->
                        <div class="flex justify-end mt-8">
                            <button id="submit-button"
                                class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
                <form method="dialog" class="modal-backdrop">
                    <button>close</button>
                </form>
            </dialog>

            <!--- modal edit pendidikan --->
            <dialog id="my_modal_2_{{ Auth::user()->id }}" class="modal">
                <div class="modal-box bg-white w-max focus:outline-none" tabindex="-1">
                    <!---- Form edit personal information ---->
                    <form id="editPendidikanForm_{{ Auth::user()->id }}"
                        action="{{ route('updatePendidikanMentor.update', Auth::user()->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <span class="text-xl font-bold flex justify-center">Edit Pendidikan</span>
                        <!---- Nama Lengkap ---->
                        <div class="mt-4 w-96 md:w-[450px]">
                            <label class="text-sm">
                                Status Pendidikan
                                <sup class="text-red-500 top-0 text-lg">&#42;</sup>
                            </label>
                            <input type="text" id="status_pendidikan_{{ Auth::user()->id }}"
                                name="status_pendidikan"
                                class="w-full bg-white shadow-lg h-11 border-gray-200 border-[1px] outline-none rounded-full text-xs px-2 mt-2
                                {{ $errors->has('status_pendidikan') && session('formErrorPendidikanId') == Auth::user()->id ? 'border-red-400' : 'focus:border-[dodgerblue] focus:shadow-[0_0_6px_0_dodgerblue]' }}"
                                value="{{ $errors->has('status_pendidikan') && session('formErrorPendidikanId') == Auth::user()->id ? old('status_pendidikan') : Auth::user()->Profile->status_pendidikan }}"
                                placeholder="Masukkan Status Pendidikan">
                            @if (session('formErrorPendidikanId') == Auth::user()->id)
                                <span
                                    class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('status_pendidikan') }}</span>
                            @endif
                        </div>
                        <!---- No.HP ---->
                        <div class="mt-4 w-96 md:w-[450px]">
                            <label class="text-sm">
                                Bidang
                                <sup class="text-red-500 top-0 text-lg">&#42;</sup>
                            </label>
                            <input type="text" id="bidang_{{ Auth::user()->id }}" name="bidang"
                                class="w-full bg-white shadow-lg h-11 border-gray-200 border-[1px] outline-none rounded-full text-xs px-2 mt-2
                                {{ $errors->has('bidang') && session('formErrorPendidikanId') == Auth::user()->id ? 'border-red-400' : 'focus:border-[dodgerblue] focus:shadow-[0_0_6px_0_dodgerblue]' }}"
                                value="{{ $errors->has('bidang') && session('formErrorPendidikanId') == Auth::user()->id ? old('bidang') : Auth::user()->Profile->bidang }}"
                                placeholder="Masukkan Nama Bidang">
                            @if (session('formErrorPendidikanId') == Auth::user()->id)
                                <span
                                    class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('bidang') }}</span>
                            @endif
                        </div>
                        <!---- Email  ---->
                        <div class="mt-4 w-96 md:w-[450px]">
                            <label class="text-sm">
                                Jurusan
                                <sup class="text-red-500 top-0 text-lg">&#42;</sup>
                            </label>
                            <input type="text" id="jurusan_{{ Auth::user()->id }}" name="jurusan"
                                class="w-full bg-white shadow-lg h-11 border-gray-200 border-[1px] outline-none rounded-full text-xs px-2 mt-2
                                {{ $errors->has('jurusan') && session('formErrorPendidikanId') == Auth::user()->id ? 'border-red-400' : 'focus:border-[dodgerblue] focus:shadow-[0_0_6px_0_dodgerblue]' }}"
                                value="{{ $errors->has('jurusan') && session('formErrorPendidikanId') == Auth::user()->id ? old('jurusan') : Auth::user()->Profile->jurusan }}"
                                placeholder="Masukkan Nama Jurusan">
                            @if (session('formErrorPendidikanId') == Auth::user()->id)
                                <span
                                    class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('jurusan') }}</span>
                            @endif
                        </div>
                        <!----  Sekolah Mengajar ---->
                        <div class="mt-4 w-96 md:w-[450px]">
                            <label class="text-sm">
                                Tahun Lulus (Opsional)
                            </label>
                            <input type="text" id="tahun_lulus_{{ Auth::user()->id }}" name="tahun_lulus"
                                class="w-full bg-white shadow-lg h-11 border-gray-200 border-[1px] outline-none rounded-full text-xs px-2 mt-2
                                {{ $errors->has('tahun_lulus') && session('formErrorPendidikanId') == Auth::user()->id ? 'border-red-400' : 'focus:border-[dodgerblue] focus:shadow-[0_0_6px_0_dodgerblue]' }}"
                                value="{{ $errors->has('tahun_lulus') && session('formErrorPendidikanId') == Auth::user()->id ? old('tahun_lulus') : Auth::user()->Profile->tahun_lulus }}"
                                placeholder="Masukkan Tahun Lulus">
                            @if (session('formErrorPendidikanId') == Auth::user()->id)
                                <span
                                    class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('tahun_lulus') }}</span>
                            @endif
                        </div>
                        <!---- button submit ---->
                        <div class="flex justify-end mt-8">
                            <button id="submit-button"
                                class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
                <form method="dialog" class="modal-backdrop">
                    <button>close</button>
                </form>
            </dialog>
        </div>
    </div>
@else
    <div class="flex flex-col min-h-screen items-center justify-center">
        <p>ALERT SEMENTARA</p>
        <p>You do not have access to this pages.</p>
    </div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            document.getElementById('alertSuccess').remove();
        }, 3000);

        document.getElementById('btnClose').addEventListener('click', function() {
            document.getElementById('alertSuccess').remove();
        })
    })
</script>

<script>
    function editPersonalInformation(element, id) {
        const modal = document.getElementById('my_modal_1_' + id);
        const form = element.getAttribute('data-form-edit-personal-mentor');

        modal.showModal();
    }

    function editPendidikan(element, id) {
        const modal = document.getElementById('my_modal_2_' + id);
        const form = element.getAttribute('data-form-edit-pendidikan-mentor');

        modal.showModal();
    }
</script>

<script>
    function togglePassword() {
        const input = document.getElementById('passwordInput');
        const eye = document.getElementById('eyeSlash');

        if (input.type === 'password') {
            input.type = 'text'
            eye.classList.replace('fa-eye-slash', 'fa-eye')
        } else {
            input.type = 'password'
            eye.classList.replace('fa-eye', 'fa-eye-slash')
        }
    }
</script>


<!-- Script -->
<script>
    function copyReferralLink() {
        const kodeReferral = document.getElementById('referral-link').value;
        const fullLink = `{{ url('/daftar-siswa') }}?ref=${kodeReferral}`;

        // Copy link
        const tempInput = document.createElement('input');
        tempInput.value = fullLink;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);

        // Bikin toast baru
        const toastContainer = document.getElementById('toast-container');
        const newToast = document.createElement('div');
        newToast.className = 'bg-green-400 text-white py-2 px-4 rounded-md shadow-lg text-sm animate-fadeIn';
        newToast.innerText = 'Link referral berhasil disalin!';

        toastContainer.appendChild(newToast);

        // Auto-hapus toast setelah 3 detik
        setTimeout(() => {
            newToast.remove();
        }, 3000);

        // Scroll otomatis ke bawah kalau banyak
        toastContainer.scrollTop = toastContainer.scrollHeight;
    }
</script>


@if (session('formErrorPendidikanId'))
    <script>
        document.getElementById('submit').addEventListener('click', function() {
            const form = document.querySelector('#my_modal_2 form[action]');
            form.submit(); // Submit the form
        });
    </script>
@elseif(session('formErrorInformationId'))
    <script>
        document.getElementById('submit').addEventListener('click', function() {
            const form = document.querySelector('#my_modal_1 form[action]');
            form.submit(); // Submit the form
        });
    </script>
@endif

@if (session('formErrorPendidikanId'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let modalId = "my_modal_2_" + {{ session('formErrorPendidikanId') }};
            let modal = document.getElementById(modalId);
            if (modal) {
                modal.showModal();
            }
        });
    </script>
@elseif(session('formErrorInformationId'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let modalId = "my_modal_1_" + {{ session('formErrorInformationId') }};
            let modal = document.getElementById(modalId);
            if (modal) {
                modal.showModal();
            }
        });
    </script>
@endif

<!---- buat hapus border dan text error ketika after validasi ------>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cek semua inputan dan hapus error message ketika user mengetik
        document.querySelectorAll('input, select, textarea').forEach(function(el) {
            el.addEventListener('input', function() {
                // Hapus error class
                el.classList.remove('border-red-400');
                const errorMessage = el.nextElementSibling;
                if (errorMessage && errorMessage.classList.contains('text-red-500')) {
                    errorMessage.remove();
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        var oldKelas = "{{ old('kelas') }}" // Ambil kelas yang dipilih jika ada

        var selectKelas = document.getElementById('id_kelas');

        $('#id_fase').on('change', function() {
            var fase_id = $(this).val();
            if (fase_id) {
                $.ajax({
                    url: '/kelas/' + fase_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        selectKelas.disabled =
                            false; // untuk menonaktifkan disabled pada select kelas ketika fase sudah dipilih
                        selectKelas.classList.replace('cursor-default', 'cursor-pointer');
                        selectKelas.classList.replace('opacity-50', 'opacity-100');
                        $('#id_kelas').empty();
                        $('#id_kelas').append(
                            '<option value="" class="hidden">Pilih Kelas</option>'
                        );
                        $.each(data, function(key, kelas) {
                            $('#id_kelas').append(
                                '<option value="' + kelas.id + '"' +
                                (oldKelas == kelas.id ? ' selected' : '') +
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
