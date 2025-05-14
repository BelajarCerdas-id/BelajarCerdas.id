@include('components/sidebar_beranda', ['headerSideNav' => 'Kurikulum'])
@extends('components/sidebar_beranda_mobile')

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            @if (session('success-insert-data-kurikulum'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-insert-data-kurikulum'),
                ])
            @endif
            @if (session('success-update-data-kurikulum'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-update-data-kurikulum'),
                ])
            @endif
            @if (session('success-delete-data-kurikulum'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-delete-data-kurikulum'),
                ])
            @endif
            <main>
                <section class="bg-white shadow-lg p-6 rounded-lg border-gray-200 border-[1px]">
                    <!---- Form input kurikulum  ---->
                    <form action="{{ route('kurikulum.store') }}" method="POST">
                        @csrf
                        <label class="text-sm">Nama Kurikulum</label>
                        <div class="flex relative max-w-lg mt-2">
                            <div class="flex gap-2 w-full">
                                <input type="text" name="nama_kurikulum"
                                    class="w-full bg-white shadow-lg h-11 border-gray-200 border-[2px] outline-none rounded-full text-xs px-2
                                    focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue]
                                    {{ $errors->has('nama_kurikulum') && session('formError') === 'create' ? 'border-[1px] border-red-400' : '' }}"
                                    value="{{ $errors->has('nama_kurikulum') && session('formError') === 'create' ? old('nama_kurikulum') : '' }}"
                                    placeholder="Masukkan Nama Kurikulum">
                                <button
                                    class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-full shadow-md transition-all h-max text-md">
                                    Tambah
                                </button>
                            </div>
                        </div>
                        @if ($errors->has('nama_kurikulum') && session('formError') === 'create')
                            <span
                                class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('nama_kurikulum') }}</span>
                        @endif
                    </form>

                    <div class="border-b-2 border-gray-200 mt-4"></div>

                    <!---- Table list data kurikulum  ---->
                    @if ($dataCuriculum->isNotEmpty())
                        <div class="overflow-x-auto mt-8 pb-20">
                            <table class="table w-full border-collapse border border-gray-300">
                                <thead class="font-bold">
                                    <tr>
                                        <th class="border border-gray-300 w-[80%]">
                                            Nama Kurikulum
                                        </th>
                                        <th class="!text-center border border-gray-300">
                                            Detail
                                        </th>
                                        <th class="!text-center border border-gray-300">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataCuriculum as $item)
                                        <tr>
                                            <td class="border border-gray-300">
                                                {{ $item->nama_kurikulum }}
                                            </td>
                                            <td class="!text-center border border-gray-300">
                                                <a href="{{ route('fase.index', [$item->nama_kurikulum, $item->id]) }}">
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
                                                            onclick="editCuriculum(this, {{ $item->id }})"
                                                            data-id-kurikulum="{{ $item->id }}"
                                                            data-nama-kurikulum="{{ $item->nama_kurikulum }}"
                                                            data-action-kurikulum="{{ route('kurikulum.update', $item->id) }}">
                                                            <a>
                                                                <i class="fa-solid fa-pen text-[#4189e0]"></i>
                                                                Edit Kurikulum
                                                            </a>
                                                        </li>
                                                        </li>
                                                        <li class="text-xs" onclick="historyCuriculum(this)"
                                                            data-nama_lengkap="{{ $item->UserAccount->profile->nama_lengkap }}
"
                                                            data-status="{{ $item->UserAccount->role }}"
                                                            data-updated_at="[{{ $item->updated_at->locale('id')->translatedFormat('d-M-Y, H:i:s') }}]">
                                                            <a>
                                                                <i class="fa-solid fa-eye text-[#4189e0]"></i>
                                                                View History
                                                            </a>
                                                        </li>
                                                        <li class="text-xs"
                                                            onclick="deleteCuriculum(this, {{ $item->id }})">
                                                            <a>
                                                                <i class="fa-solid fa-trash text-red-500"></i>
                                                                Delete Kuruikulum
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
                        <!---- modal edit kurikulum ---->
                        @foreach ($dataCuriculum as $item)
                            <dialog id="my_modal_1_{{ $item->id }}" class="modal">
                                <div class="modal-box bg-white w-max">
                                    <form id="curiculumForm_{{ $item->id }}"
                                        action="{{ route('kurikulum.update', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <span class="text-xl font-bold flex justify-center">Edit Kurikulum</span>

                                        <div class="mt-4 w-80">
                                            <label class="text-sm">Nama Kurikulum</label>
                                            <input type="text" id="nama_kurikulum_{{ $item->id }}"
                                                name="nama_kurikulum"
                                                class="w-full bg-white shadow-lg h-11 border-gray-200 border-[1px] outline-none rounded-full text-xs px-2 mt-2
                                                {{ $errors->has('nama_kurikulum') && session('formErrorId') == $item->id ? 'border-red-400' : 'focus:border-[dodgerblue] focus:shadow-[0_0_6px_0_dodgerblue]' }}"
                                                value="{{ $errors->has('nama_kurikulum') && session('formErrorId') == $item->id ? old('nama_kurikulum') : $item->nama_kurikulum }}"
                                                placeholder="Masukkan Nama Kurikulum">
                                            @if (session('formErrorId') == $item->id)
                                                <span
                                                    class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('nama_kurikulum') }}</span>
                                            @endif
                                        </div>


                                        <div class="flex justify-end mt-8">
                                            <button
                                                class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all">
                                                Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <form method="dialog" class="modal-backdrop">
                                    <button>Close</button>
                                </form>
                            </dialog>
                            <!---- modal delete bab  ---->
                            <dialog id="my_modal_3_{{ $item->id }}" class="modal">
                                <div class="modal-box bg-white">
                                    <h3 class="font-bold text-lg text-red-600">Konfirmasi Hapus</h3>
                                    <p class="py-4">Semua yang berkaitan dengan kurikulum ini akan dihapus secara
                                        permanen.
                                        Apakah kamu
                                        yakin
                                        ingin menghapus kurikulum ini?</p>
                                    <div class="modal-action">
                                        <span id="hapus-modal" class="btn"
                                            onclick="closeModal(this, {{ $item->id }} )">Batal</span>
                                        <form action="{{ route('kurikulum.delete', $item->id) }}" method="POST">
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

                        <!---- modal history kurikulum  ---->
                        <dialog id="my_modal_2" class="modal">
                            <div class="modal-box bg-white text-center">
                                <span class="text-2xl">History Kurikulum</span>
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
                        <!---- modal delete kurikulum  ---->
                    @else
                        <div class="flex justify-center items-center mt-8">
                            <span class="text-sm">Belum ada kurikulum</span>
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
    function editCuriculum(element, id) {
        let modal = document.getElementById('my_modal_1_' + id);
        let kurikulumId = element.getAttribute('data-id-kurikulum');
        let namaKurikulum = element.getAttribute('data-nama-kurikulum');
        let actionKurikulum = element.getAttribute('data-action-kurikulum');
        let inputNamaKurikulum = document.getElementById('nama_kurikulum_' + id);

        document.getElementById('nama_kurikulum_' + id).value = namaKurikulum;

        document.getElementById('curiculumForm_' + id).setAttribute('action', actionKurikulum);

        inputNamaKurikulum.value = namaKurikulum;
        modal.showModal();

        inputNamaKurikulum.blur();
    }

    function historyCuriculum(element) {
        const modal = document.getElementById('my_modal_2');
        const namaLengkap = element.getAttribute('data-nama_lengkap');
        const status = element.getAttribute('data-status');
        const updatedAt = element.getAttribute('data-updated_at');

        document.getElementById('text-nama_lengkap').innerText = namaLengkap;
        document.getElementById('text-status').innerText = status;
        document.getElementById('text-updated_at').innerText = updatedAt;

        modal.showModal();
    }

    function deleteCuriculum(element, id) {
        const modal = document.getElementById('my_modal_3_' + id);
        modal.showModal();
    }

    function closeModal(element, id) {
        const closeModal = document.getElementById('my_modal_3_' + id);
        closeModal.close();
    }
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
