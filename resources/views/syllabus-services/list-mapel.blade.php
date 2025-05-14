@include('components/sidebar_beranda', [
    'linkBackButton' => route('fase.index', [$nama_kurikulum, $kurikulum_id]),
    'backButton' => "<i class='fa-solid fa-chevron-left'></i>",
    'headerSideNav' => 'Mata Pelajaran',
])
@extends('components/sidebar_beranda_mobile')

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            @if (session('success-insert-data-mapel'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-insert-data-mapel'),
                ])
            @endif
            @if (session('success-update-data-mapel'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-update-data-mapel'),
                ])
            @endif
            @if (session('success-delete-data-mapel'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-delete-data-mapel'),
                ])
            @endif
            <main>
                <section class="bg-white shadow-lg p-6 rounded-lg border-gray-200 border-[1px]">
                    <!---- Form input kurikulum  ---->
                    <form action="{{ route('mapel.store', [$id, $kurikulum_id]) }}" method="POST">
                        @csrf
                        <label class="text-sm">Nama Mapel</label>
                        <div class="flex relative max-w-lg mt-2">
                            <div class="flex gap-2 w-full">
                                <input type="text" name="mata_pelajaran"
                                    class="w-full bg-white shadow-lg h-11 border-gray-200 border-[2px] outline-none rounded-full text-xs px-2
                                    focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue]
                                    {{ $errors->has('mata_pelajaran') && session('formError') === 'create' ? 'border-[1px] border-red-400' : '' }}"
                                    value="{{ $errors->has('mata_pelajaran') && session('formError') === 'create' ? old('mata_pelajaran') : '' }}"
                                    placeholder="Masukkan Nama Mata Pelajaran">
                                <button
                                    class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-full shadow-md transition-all h-max text-md">
                                    Tambah
                                </button>
                            </div>
                        </div>
                        @if ($errors->has('mata_pelajaran') && session('formError') === 'create')
                            <span
                                class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('mata_pelajaran') }}</span>
                        @endif
                    </form>

                    <div class="border-b-2 border-gray-200 mt-4"></div>

                    @if ($dataMapel->isNotEmpty())
                        <div class="overflow-x-auto mt-8 pb-20">
                            <table class="table w-full border-collapse border border-gray-300">
                                <thead class="font-bold">
                                    <tr>
                                        <th class="border border-gray-300 w-[80%]">
                                            Fase
                                        </th>
                                        <th class="!text-center border border-gray-300">
                                            Action
                                        </th>
                                        <th class="!text-center border border-gray-300">
                                            Detail
                                        </th>
                                        <th class="!text-center border border-gray-300">
                                            <i class="fa-solid fa-ellipsis-vertical cursor-pointer"></i>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataMapel as $item)
                                        <tr>
                                            <td class="border border-gray-300">
                                                {{ $item->mata_pelajaran }}
                                            </td>
                                            <td class="!text-center border border-gray-300">
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" class="hidden peer toggle-mapel"
                                                        data-id="{{ $item->id }}"
                                                        {{ $item->status_mata_pelajaran === 'publish' ? 'checked' : '' }} />
                                                    <div
                                                        class="w-11 h-6 bg-gray-300 peer-checked:bg-green-500 rounded-full transition-colors duration-300 ease-in-out">
                                                    </div>
                                                    <div
                                                        class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300 ease-in-out peer-checked:translate-x-2.5">
                                                    </div>
                                                </label>
                                            </td>

                                            <td class="!text-center border border-gray-300">
                                                <a
                                                    href="{{ route('bab.index', [$nama_kurikulum, $item->kurikulum_id, $item->Fase->nama_fase, $item->fase_id, $item->mata_pelajaran, $item->id]) }}">
                                                    <div class="text-[#4189e0]">
                                                        <span>Detail</span>
                                                        <i class="fas fa-chevron-right text-xs"></i>
                                                    </div>
                                                </a>
                                            </td>
                                            <td class="!text-center border border-gray-300">
                                                <div class="dropdown dropdown-left">
                                                    <div tabindex="0" role="button">
                                                        <i class="fa-solid fa-ellipsis-vertical cursor-pointer"></i>
                                                    </div>
                                                    <ul tabindex="0"
                                                        class="dropdown-content menu bg-base-100 rounded-box z-1 w-max p-2 shadow-sm  z-[9999]">
                                                        <li class="text-xs"
                                                            onclick="editMapel(this, {{ $item->id }})"
                                                            data-id-mapel="{{ $item->id }}"
                                                            data-mapel="{{ $item->mata_pelajaran }}"
                                                            data-action-mapel="{{ route('mapel.update', [$item->id]) }}">
                                                            <a>
                                                                <i class="fa-solid fa-pen text-[#4189e0]"></i>
                                                                Edit Mata Pelajaran
                                                            </a>
                                                        </li>
                                                        </li>
                                                        <li class="text-xs" onclick="historyMapel(this)"
                                                            data-nama_lengkap="{{ $item->UserAccount->Profile->nama_lengkap }}"
                                                            data-status="{{ $item->UserAccount->role }}"
                                                            data-updated_at="[{{ $item->updated_at->locale('id')->translatedFormat('d-M-Y, H:i:s') }}]">
                                                            <a>
                                                                <i class="fa-solid fa-eye text-[#4189e0]"></i>
                                                                View History
                                                            </a>
                                                        </li>
                                                        <li class="text-xs"
                                                            onclick="deleteMapel(this, {{ $item->id }})">
                                                            <a>
                                                                <i class="fa-solid fa-trash text-red-500"></i>
                                                                Delete Mata Pelajaran
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!---- modal edit fase ---->
                        @foreach ($dataMapel as $item)
                            <dialog id="my_modal_1_{{ $item->id }}" class="modal">
                                <div class="modal-box bg-white w-max">
                                    <form id="mapelForm_{{ $item->id }}"
                                        action="{{ route('mapel.update', [$item->id]) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <span class="text-xl font-bold flex justify-center">Edit Mata Pelajaran</span>
                                        <div class="mt-4 w-80">
                                            <!---- Form kurikulum ---->
                                            <label class="text-sm">Nama Mapel</label>
                                            <input type="text" id="mata_pelajaran_{{ $item->id }}"
                                                name="mata_pelajaran"
                                                class="w-full bg-white shadow-lg h-11 border-gray-200 border-[1px] outline-none rounded-full text-xs px-2 mt-2
                                                {{ $errors->has('mata_pelajaran') && session('formErrorId') == $item->id ? 'border-red-400' : 'focus:border-[dodgerblue] focus:shadow-[0_0_6px_0_dodgerblue]' }}"
                                                value="{{ $errors->has('mata_pelajaran') && session('formErrorId') == $item->id ? old('mata_pelajaran') : $item->mata_pelajaran }}"
                                                placeholder="Masukkan Nama Mapel">
                                            @if (session('formErrorId') == $item->id)
                                                <span
                                                    class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('mata_pelajaran') }}</span>
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
                            <!---- modal delete mapel  ---->
                            <dialog id="my_modal_3_{{ $item->id }}" class="modal">
                                <div class="modal-box bg-white">
                                    <h3 class="font-bold text-lg text-red-600">Konfirmasi Hapus</h3>
                                    <p class="py-4">Mata Pelajaran dan Bab akan dihapus secara permanen. Apakah kamu
                                        yakin
                                        ingin menghapus mata pelajaran ini?</p>
                                    <div class="modal-action">
                                        <span id="hapus-modal" class="btn"
                                            onclick="closeModal(this, {{ $item->id }} )">Batal</span>
                                        <form action="{{ route('mapel.delete', [$item->id]) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="btn btn-error text-white">
                                                Ya, Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <form method="dialog" class="modal-backdrop">
                                    <button>close</button>
                                </form>
                            </dialog>
                        @endforeach
                        <!---- modal history mapel  ---->
                        <dialog id="my_modal_2" class="modal">
                            <div class="modal-box bg-white text-center">
                                <span class="text-2xl">History Mata Pelajaran</span>
                                <div class="flex items-center justify-between mt-6">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-circle-user text-5xl"></i>
                                        <div class="flex flex-col text-start">
                                            <span id="text-nama_lengkap"></span>
                                            <span id="text-status" class="text-sm"></span>
                                            <span id="text-updated_at" class="text-xs leading-6"></span>
                                        </div>
                                    </div>
                                    <div class="">
                                        <span class="text-[#4189e0] text-sm">Publisher</span>
                                    </div>
                                </div>
                            </div>
                            <form method="dialog" class="modal-backdrop">
                                <button>close</button>
                            </form>
                        </dialog>
                    @else
                        <div class="flex justify-center items-center mt-8">
                            <span class="text-sm">Belum ada mata pelajaran</span>
                        </div>
                    @endif
                </section>
            </main>
        </div>
    </div>
@else
    <p>You do not have access to this pages.</p>
@endif

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

<script>
    function editMapel(element, id) {
        let modal = document.getElementById('my_modal_1_' + id);
        let mapelId = element.getAttribute('data-id-mapel');
        let mapel = element.getAttribute('data-mapel');
        let actionMapel = element.getAttribute('data-action-mapel');
        let inputMapel = document.getElementById('mata_pelajaran_' + id);

        document.getElementById('mata_pelajaran_' + id).value = mapel;

        document.getElementById('mapelForm_' + id).setAttribute('action', actionMapel);

        inputMapel.value = mapel;
        modal.showModal();

        inputMapel.blur();
    }

    function historyMapel(element) {
        const modal = document.getElementById('my_modal_2');
        const namaLengkap = element.getAttribute('data-nama_lengkap');
        const status = element.getAttribute('data-status');
        const updatedAt = element.getAttribute('data-updated_at');

        document.getElementById('text-nama_lengkap').innerText = namaLengkap;
        document.getElementById('text-status').innerText = status;
        document.getElementById('text-updated_at').innerText = updatedAt;

        modal.showModal();
    }

    function deleteMapel(element, id) {
        const modal = document.getElementById('my_modal_3_' + id);

        modal.showModal();
    }

    function closeModal(element, id) {
        const closeModal = document.getElementById('my_modal_3_' + id);

        closeModal.close();
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


<script>
    $(document).ready(function() {
        $('.toggle-mapel').change(function() {
            let id = $(this).data('id'); // Ambil ID mapel dari atribut data-id di checkbox
            let status = $(this).is(':checked') ? 'publish' :
                'unpublish'; // Jika toggle ON maka publish, kalau OFF maka unpublish

            $.ajax({
                url: '/syllabus/curiculum/mapel/activate/' + id, // Endpoint ke server
                method: 'PUT', // Method HTTP PUT untuk update data
                data: {
                    _token: '{{ csrf_token() }}', // Kirim CSRF token (penting di Laravel)
                    status_mata_pelajaran: status // Kirim status baru (publish/unpublish)
                },
                success: function(response) {
                    console.log(response
                        .message); // Kalau berhasil, tampilkan pesan ke console
                    // Bisa juga tambahkan notifikasi atau toast di sini
                },
                error: function(xhr) {
                    alert('Gagal mengubah status.');
                    // Kalau gagal, toggle dikembalikan ke kondisi sebelumnya
                    $(this).prop('checked', !$(this).is(':checked'));
                }
            });
        });
    });
</script>

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
