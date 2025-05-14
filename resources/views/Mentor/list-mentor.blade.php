@include('components/sidebar_beranda', ['headerSideNav' => 'Mentor'])
@extends('components/sidebar_beranda_mobile')

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            @if (session('success-update-data-mentor'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-update-data-mentor'),
                ])
            @endif
            <main>
                <section class="bg-white shadow-lg rounded-lg p-4 border border-gray-200 h-60">
                    <span class="text-xl font-bold mb-4 opacity-60">List Mentor</span>
                    @if ($dataMentor->isNotEmpty())
                        <div class="overflow-x-auto mt-4 pb-20">
                            <table class="table" id="filterTable">
                                <thead class="thead-table">
                                    <tr>
                                        <th class="th-table">No</th>
                                        <th class="th-table">Nama Lengkap</th>
                                        <th class="th-table">no_hp</th>
                                        <th class="th-table">Status Pendidikan</th>
                                        <th class="th-table">Bidang</th>
                                        <th class="th-table">Jurusan</th>
                                        <th class="th-table">Tahun Lulus</th>
                                        <th class="th-table">Sekolah Mengajar</th>
                                        <th class="th-table">Status Akun</th>
                                        <th class="th-table">Status Mentor</th>
                                    </tr>
                                </thead>
                                <tbody id="filterList">
                                    @foreach ($dataMentor as $item)
                                        <tr>
                                            <td class="border text-center border-gray-300">{{ $loop->iteration }}</td>
                                            <td class="border border-gray-300">{{ $item->nama_lengkap }}</td>
                                            <td class="border border-gray-300">{{ $item->UserAccount->no_hp }}</td>
                                            <td class="border text-center border-gray-300">
                                                {{ $item->status_pendidikan }}
                                            </td>
                                            <td class="border border-gray-300">{{ $item->bidang }}</td>
                                            <td class="border border-gray-300">{{ $item->jurusan }}</td>
                                            <td class="border text-center border-gray-300">
                                                {{ $item->tahun_lulus ?? '-' }}
                                            </td>
                                            <td class="border border-gray-300">{{ $item->sekolah_mengajar ?? '-' }}</td>
                                            <td class="border border-gray-300">
                                                @if ($item->UserAccount->status_akun === 'aktif')
                                                    <div class="flex justify-center">
                                                        <button
                                                            class="bg-[#4189e0cc] py-2 px-6 rounded-lg text-white font-bold"
                                                            disabled>
                                                            Aktif
                                                        </button>
                                                    </div>
                                                @else
                                                    <div class="flex justify-center">
                                                        <button
                                                            class="bg-[#4189e0cc] py-2 px-6 rounded-lg text-white font-bold"
                                                            onclick="activeAccount(this, {{ $item->id }})"
                                                            data-form="{{ route('activeMentor.update', $item->id) }}">
                                                            Aktifkan
                                                        </button>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="border border-gray-300 text-center">
                                                {{ $item->status_mentor }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-container-siswa"></div>
                            <div class="flex justify-center">
                                <span class="showMessage hidden absolute top-2/4">Tidak ada
                                    riwayat</span>
                            </div>
                        </div>

                        <!-- Modal aktifkan akun mentor -->
                        @foreach ($dataMentor as $item)
                            <dialog id="my_modal_1_{{ $item->id }}" class="modal">
                                <div class="modal-box bg-white w-max focus:outline-none" tabindex="-1">
                                    <span class="text-xl font-bold flex justify-center">Akun Mentor</span>
                                    <form id="activeMentor-form_{{ $item->id }}"
                                        action="{{ route('activeMentor.update', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="nama_lengkap">
                                        <!-- Email -->
                                        <div class="my-6 w-80">
                                            <label class="text-sm">Email</label>
                                            <input type="text" name="email"
                                                class="w-full bg-white shadow-lg h-11 border-gray-200 border-[1px] outline-none rounded-full text-xs px-3 mt-2 {{ $errors->has('email') && session('formErrorId') == $item->id ? 'border-red-400' : 'focus:border-[dodgerblue] focus:shadow-[0_0_6px_0_dodgerblue]' }}"
                                                placeholder="Masukkan Email"
                                                value="{{ session('formErrorId') == $item->id ? old('email') : '' }}">
                                            @if (session('formErrorId') == $item->id)
                                                <span
                                                    class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('email') }}</span>
                                            @endif
                                        </div>

                                        <!-- Password -->
                                        <div class="my-6 w-80">
                                            <label class="text-sm">Password</label>
                                            <input type="text" name="password"
                                                class="w-full bg-white shadow-lg h-11 border-gray-200 border-[1px] outline-none rounded-full text-xs px-3 mt-2 {{ $errors->has('password') && session('formErrorId') == $item->id ? 'border-red-400' : 'focus:border-[dodgerblue] focus:shadow-[0_0_6px_0_dodgerblue]' }}"
                                                placeholder="Masukkan password" maxlength="16"
                                                value="{{ session('formErrorId') == $item->id ? old('password') : '' }}">
                                            @if (session('formErrorId') == $item->id)
                                                <span
                                                    class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('password') }}</span>
                                            @endif
                                        </div>

                                        <!-- Submit -->
                                        <div class="flex justify-end">
                                            <button type="submit"
                                                class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all">
                                                Tambah
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <form method="dialog" class="modal-backdrop">
                                    <button>close</button>
                                </form>
                            </dialog>
                        @endforeach
                    @else
                        <div class="flex justify-center items-center mt-8">
                            <span class="text-sm">Belum ada data mentor</span>
                        </div>
                    @endif
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

<script>
    function activeAccount(element, id) {
        const modal = document.getElementById('my_modal_1_' + id);
        const actionForm = element.getAttribute('data-form');
        const input = document.querySelectorAll('input[type="text"]');

        document.getElementById('activeMentor-form_' + id).setAttribute('action', actionForm);

        modal.showModal();
        input.blur();
    }
</script>

<script>
    document.getElementById('submit').addEventListener('click', function() {
        const form = document.querySelector('#my_modal_1 form[action]');
        form.submit(); // Submit the form
    });
</script>

@if (session('formErrorId'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let modalId = "my_modal_1_" + {{ session('formErrorId') }};
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
    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(function() {
            document.getElementById('alertSuccess').remove();
        }, 3000);

        document.getElementById('btnClose').addEventListener('click', function() {
            document.getElementById('alertSuccess').remove();
        })
    });
</script>
