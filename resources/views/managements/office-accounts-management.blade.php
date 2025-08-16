@include('components/sidebar_beranda', ['headerSideNav' => 'Users Management'])

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">

            <!-- ALERT SUCCESS INSERT OFFICE ACCOUNTS -->
            <div id="alert-success-insert-office-accounts"></div>

            <main class="bg-white shadow-lg border h-max rounded-lg">
                <section class="border-b">
                    <!-- FORM INSERT OFFICE ACCOUNTS -->
                    <form id="form-office-accounts" autocomplete="OFF">
                        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 py-10 px-6">
                            <div class="flex flex-col">
                                <label class="mb-2 text-sm">Nama Lengkap<sup
                                        class="text-red-500 pl-1">&#42;</sup></label>
                                <input type="text" name="nama_lengkap" id="nama_lengkap"
                                    class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer"
                                    value="{{ old('nama_lengkap') }}" placeholder="Masukkan Nama Lengkap">
                                <span id="error-nama_lengkap" class="text-red-500 font-bold text-xs pt-2"></span>
                            </div>

                            <div class="flex flex-col">
                                <label class="mb-2 text-sm">No. HP<sup class="text-red-500 pl-1">&#42;</sup></label>
                                <input type="tel" name="no_hp" id="no_hp"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer"
                                    value="{{ old('no_hp') }}" maxlength="16" placeholder="Masukkan No.HP">
                                <span id="error-no_hp" class="text-red-500 font-bold text-xs pt-2"></span>
                            </div>

                            <div class="flex flex-col">
                                <label class="mb-2 text-sm">Email Office<sup
                                        class="text-red-500 pl-1">&#42;</sup></label>
                                <input type="text" name="email" id="email"
                                    class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer"
                                    value="{{ old('email') }}" placeholder="Masukkan Email Office">
                                <span id="error-email" class="text-red-500 font-bold text-xs pt-2"></span>
                            </div>

                            <div class="flex flex-col relative">
                                <label class="mb-2 text-sm">Password<sup class="text-red-500 pl-1">&#42;</sup></label>
                                <input type="password" name="password" id="password"
                                    class="w-full bg-white shadow-lg h-12 px-2 pr-10 text-sm border-gray-200 border outline-none rounded-md focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer"
                                    value="{{ old('password') }}" placeholder="Masukkan Password">
                                <button type="button" onclick="togglePassword('password', this)"
                                    class="absolute right-3 top-17 transform -translate-y-1/2 text-gray-600 focus:outline-none">
                                    <i class="fa-solid fa-eye-slash"></i>
                                </button>
                                <span id="error-password" class="text-red-500 font-bold text-xs pt-2"></span>
                            </div>

                            <div class="flex flex-col">
                                <label class="mb-2 text-sm">Role<sup class="text-red-500 pl-1">&#42;</sup></label>
                                <select name="role" id="role"
                                    class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer"
                                    value="{{ old('role') }}">
                                    <option class="hidden" value="">Pilih Role</option>
                                    <option value="Administrator">Administrator</option>
                                </select>
                                <span id="error-role" class="text-red-500 font-bold text-xs pt-2"></span>
                            </div>

                            <div class="flex items-center w-full h-full mt-2">
                                <button
                                    class="w-full h-max bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all outline-none">
                                    Kirim
                                </button>
                            </div>
                        </div>
                    </form>
                </section>

                <section class="relative">

                    <span class="text-lg font-bold px-4 opacity-70">LIST OFFICE ACCOUNTS</span>

                    <div class="absolute right-8 top-2 flex justify-center items-center my-4">
                        <select name="" id="status_akun"
                            class="w-[150px] h-10 rounded-lg px-2 border-[1px] outline-none text-sm cursor-pointer bg-white">
                            <!-- show data in ajax -->
                        </select>
                    </div>

                    <!--- table daftar office users --->
                    <div class="overflow-x-auto my-20 mx-4">
                        <table class="table" id="table-office-accounts">
                            <thead class="thead-table-office-accounts">
                                <tr>
                                    <th class="th-table text-black opacity-70">No</th>
                                    <th class="th-table text-black opacity-70">Nama Lengkap</th>
                                    <th class="th-table text-black opacity-70">Email Office</th>
                                    <th class="th-table text-black opacity-70">Role</th>
                                    <th class="th-table text-black opacity-70">Status Akun</th>
                                    <th class="th-table text-black opacity-70">Action</th>
                                </tr>
                            </thead>
                            <tbody id="table-list-office-accounts">
                                {{-- show data in ajax --}}
                            </tbody>
                        </table>

                        <div class="pagination-container-office-accounts flex justify-center my-4 sm:my-0"></div>

                        <div id="empty-message-office-accounts" class="w-full h-96 hidden">
                            <span class="w-full h-full flex items-center justify-center">
                                Tidak ada akun yang terdaftar.
                            </span>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>
@else
    <p>You do not have access to this pages.</p>
@endif

<script src="{{ asset('js/managements/office-accounts/form-action-office-accounts.js') }}"></script> <!--- form action office accounts ---->

<!--- COMPONENTS ---->
<script src="{{ asset('js/components/clear-error-on-input.js') }}"></script> <!--- clear error on input ---->
<script src="{{ asset('js/components/show-password-input.js') }}"></script> <!--- show password input ---->

<!--- PUSHER LISTENER ---->
<script src="{{ asset('js/pusher-listener/managements/office-accounts-listener.js') }}"></script> <!--- pusher listener office accounts ---->
