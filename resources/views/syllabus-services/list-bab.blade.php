@include('components/sidebar_beranda', [
    'linkBackButton' => route('mapel.index', [$nama_kurikulum, $kurikulum_id, $nama_fase, $fase_id]),
    'backButton' => "<i class='fa-solid fa-chevron-left'></i>",
    'headerSideNav' => 'Bab',
])
@extends('components/sidebar_beranda_mobile')

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            @if (session('success-insert-data-bab'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-insert-data-bab'),
                ])
            @endif
            @if (session('success-update-data-bab'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-update-data-bab'),
                ])
            @endif
            @if (session('success-delete-data-bab'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-delete-data-bab'),
                ])
            @endif
            <main>
                <section class="bg-white shadow-lg p-6 rounded-lg border-gray-200 border-[1px]">
                    <form action="{{ route('bab.store', [$id, $kurikulum_id, $fase_id]) }}" method="POST">
                        @csrf
                        <label class="text-sm">Nama Bab</label>
                        <div class="flex relative max-w-lg mt-2">
                            <div class="flex gap-2 w-full">
                                <input type="text" name="nama_bab"
                                    class="w-full bg-white shadow-lg h-11 border-gray-200 border-[2px] outline-none rounded-full text-xs px-2
                                    focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue]
                                    {{ $errors->has('nama_bab') && session('formError') === 'create' ? 'border-[1px] border-red-400' : '' }}"
                                    value="{{ $errors->has('nama_bab') && session('formError') === 'create' ? old('nama_bab') : '' }}"
                                    placeholder="Masukkan Nama Bab">
                                <button
                                    class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-full shadow-md transition-all h-max text-md">
                                    Tambah
                                </button>
                            </div>
                        </div>
                        @if ($errors->has('nama_bab') && session('formError') === 'create')
                            <span class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('nama_bab') }}</span>
                        @endif
                    </form>

                    <div class="border-b-2 border-gray-200 mt-4"></div>

                    <!---- Table list data bab  ---->
                    @if ($dataBab->isNotEmpty())
                        <div class="overflow-x-auto mt-8 pb-20">
                            <table class="table w-full border-collapse border border-gray-300">
                                <thead class="font-bold">
                                    <tr>
                                        <th class="border border-gray-300 w-[80%]">
                                            Fase
                                        </th>
                                        @foreach ($dataFeaturesRoles as $featuresItem)
                                            <th class="!text-center border border-gray-300">
                                                {{ $featuresItem->Features->nama_fitur }}
                                            </th>
                                        @endforeach
                                        <th class="!text-center border border-gray-300">
                                            <i class="fa-solid fa-ellipsis-vertical cursor-pointer"></i>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataBab as $babItem)
                                        <tr>
                                            <td class="border border-gray-300">
                                                {{ $babItem->nama_bab }}
                                            </td>
                                            @foreach ($dataFeaturesRoles as $featureItem)
                                                <td class="!text-center border border-gray-300">
                                                    <input type="checkbox"
                                                        class="w-4 h-4 cursor-pointer accent-green-600 checkbox-bab"
                                                        data-id-bab="{{ $babItem->id }}"
                                                        data-feature="{{ $featureItem->feature_id }}"
                                                        @if (isset($statusBabFeature[$babItem->id][$featureItem->feature_id]) &&
                                                                $statusBabFeature[$babItem->id][$featureItem->feature_id] === 'publish') checked @endif>
                                                </td>
                                            @endforeach
                                            <td class="!text-center border border-gray-300">
                                                <div class="dropdown dropdown-left">
                                                    <div tabindex="0" role="button">
                                                        <i class="fa-solid fa-ellipsis-vertical cursor-pointer"></i>
                                                    </div>
                                                    <ul tabindex="0"
                                                        class="dropdown-content menu bg-base-100 rounded-box z-1 w-max p-2 shadow-sm  z-[9999]">
                                                        <li class="text-xs" onclick="editBab(this, {{ $babItem->id }})"
                                                            data-id-bab="{{ $babItem->id }}"
                                                            data-nama_bab="{{ $babItem->nama_bab }}"
                                                            data-action-bab="{{ route('bab.update', [$kurikulum_id, $fase_id, $id, $babItem->id]) }}">
                                                            <a>
                                                                <i class="fa-solid fa-pen text-[#4189e0]"></i>
                                                                Edit Bab
                                                            </a>
                                                        </li>
                                                        </li>
                                                        <li class="text-xs" onclick="historyBab(this)"
                                                            data-nama_lengkap="{{ $babItem->UserAccount->Profile->nama_lengkap }}"
                                                            data-status="{{ $babItem->UserAccount->role }}"
                                                            data-updated_at="[{{ $babItem->updated_at->locale('id')->translatedFormat('d-M-Y, H:i:s') }}]">
                                                            <a>
                                                                <i class="fa-solid fa-eye text-[#4189e0]"></i>
                                                                View History
                                                            </a>
                                                        </li>
                                                        <li class="text-xs"
                                                            onclick="deleteBab(this, {{ $babItem->id }})">
                                                            <a>
                                                                <i class="fa-solid fa-trash text-red-500"></i>
                                                                Delete Bab
                                                            </a>
                                                        </li>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @foreach ($dataBab as $item)
                            <!---- modal edit bab ---->
                            <dialog id="my_modal_1_{{ $item->id }}" class="modal">
                                <div class="modal-box bg-white w-max">
                                    <form id="babForm_{{ $item->id }}"
                                        action="{{ route('bab.update', [$kurikulum_id, $fase_id, $id, $item->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <span class="text-xl font-bold flex justify-center">Edit Fase</span>
                                        <div class="mt-4 w-80">
                                            <!---- Form kurikulum ---->
                                            <label class="text-sm">Nama Bab</label>
                                            <input type="text" id="nama_bab_{{ $item->id }}" name="nama_bab"
                                                class="w-full bg-white shadow-lg h-11 border-gray-200 border-[1px] outline-none rounded-full text-xs px-2 mt-2
                                                {{ $errors->has('nama_bab') && session('formErrorId') == $item->id ? 'border-red-400' : 'focus:border-[dodgerblue] focus:shadow-[0_0_6px_0_dodgerblue]' }}"
                                                value="{{ $errors->has('nama_bab') && session('formErrorId') == $item->id ? old('nama_bab') : $item->nama_bab }}"
                                                placeholder="Masukkan Nama Bab">
                                            @if (session('formErrorId') == $item->id)
                                                <span
                                                    class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('nama_bab') }}</span>
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
                            <!---- modal delete bab  ---->
                            <dialog id="my_modal_3_{{ $item->id }}" class="modal">
                                <div class="modal-box bg-white">
                                    <h3 class="font-bold text-lg text-red-600">Konfirmasi Hapus</h3>
                                    <p class="py-4">Bab akan dihapus secara permanen. Apakah kamu yakin
                                        ingin menghapus bab ini?</p>
                                    <div class="modal-action">
                                        <span id="hapus-modal" class="btn"
                                            onclick="closeModal(this, {{ $item->id }} )">Batal</span>
                                        <form id="deleteForm" action="{{ route('bab.delete', $item->id) }}"
                                            method="POST" data-delete-id="{{ $item->id }}">
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
                        <!---- modal history bab  ---->
                        <dialog id="my_modal_2" class="modal">
                            <div class="modal-box bg-white text-center">
                                <span class="text-2xl">History Bab</span>
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
                            <span class="text-sm">Belum ada bab</span>
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
    function editBab(element, id) {
        const modal = document.getElementById('my_modal_1_' + id);
        const babId = element.getAttribute('data-id-bab');
        const namaBab = element.getAttribute('data-nama_bab');
        const actionBab = element.getAttribute('data-action-bab');
        const inputBab = document.getElementById('nama_bab_' + id);

        document.getElementById('nama_bab_' + id).value = namaBab;
        document.getElementById('babForm_' + id).setAttribute('action', actionBab);

        inputBab.value = namaBab;
        modal.showModal();

        inputBab.blur();
    }

    function historyBab(element) {
        const modal = document.getElementById('my_modal_2');
        const namaLengkap = element.getAttribute('data-nama_lengkap');
        const status = element.getAttribute('data-status');
        const updatedAt = element.getAttribute('data-updated_at');

        document.getElementById('text-nama_lengkap').innerText = namaLengkap;
        document.getElementById('text-status').innerText = status;
        document.getElementById('text-updated_at').innerText = updatedAt;

        modal.showModal();
    }

    function deleteBab(element, id) {
        const modal = document.getElementById('my_modal_3_' + id);
        modal.showModal();
    }

    function closeModal(element, id) {
        const closeModal = document.getElementById('my_modal_3_' + id);
        closeModal.close();
    }
</script>


<script>
    document.getElementById('submit-button').addEventListener('click', function() {
        const form = document.querySelector('#my_modal_1 form[action]');
        form.submit(); // Submit the form
    })
</script>

@if (session('formErrorId'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalId = 'my_modal_1_' + {{ session('formErrorId') }};
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.showModal();
            }
        })
    </script>
@endif


{{-- <script>
    $(document).ready(function() {
        $('.checkbox-bab').change(function() {
            let id = $(this).data('id');
            let status = $(this).is(':checked') ? 'publish' : 'unpublish';

            $.ajax({
                url: '/syllabus/curiculum/bab/activate/' + id,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    status_bab: status
                },
                success: function(response) {
                    console.log(response.message);
                },
                error: function(xhr) {
                    alert('Gagal mengubah status.');
                    $(this).prop('checked', !$(this).is(':checked'));
                }
            });
        });
    });
</script> --}}

<script>
    $(document).ready(function() {
        $('.checkbox-bab').change(function() {
            let id = $(this).data('id-bab'); // bab_id
            let feature_id = $(this).data('feature'); // feature_id
            let status_bab = $(this).is(':checked') ? 'publish' : 'unpublish';

            $.ajax({
                url: '/syllabus/curiculum/bab/activate/' + id,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    status_bab: status_bab,
                    feature_id: feature_id // Kirim feature_id ke server
                },
                success: function(response) {
                    console.log(response.message);
                },
                error: function(xhr) {
                    alert('Gagal mengubah status.');
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
