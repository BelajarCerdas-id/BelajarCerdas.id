@include('components/sidebar_beranda', [
    'linkBackButton' => route('fase.index', [$nama_kurikulum, $kurikulum_id, $fase_id]),
    'backButton' => "<i class='fa-solid fa-chevron-left'></i>",
    'headerSideNav' => 'Kelas',
])

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            @if (session('success-insert-data-kelas'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-insert-data-kelas'),
                ])
            @endif
            <!--- alert nya menggunakan dari response json --->
            <div id="alert-success-update-data-kelas"></div>
            <div id="alert-success-delete-data-kelas"></div>
            <main>
                <section class="bg-white shadow-lg p-6 rounded-lg border-gray-200 border-[1px]">
                    <!---- Form input kelas  ---->
                    <form action="{{ route('kelas.store', [$nama_kurikulum, $kurikulum_id, $fase_id]) }}" method="POST">
                        @csrf
                        <label class="text-sm">Kelas</label>
                        <div class="flex relative max-w-lg mt-2">
                            <div class="flex gap-2 w-full">
                                <input type="text" name="kelas"
                                    class="w-full bg-white shadow-lg h-11 border-gray-200 border-[2px] outline-none rounded-full text-xs px-2
                                            focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue]
                                            {{ $errors->has('kelas') && session('formError') === 'create' ? 'border-[1px] border-red-400' : '' }}"
                                    value="{{ $errors->has('kelas') && session('formError') === 'create' ? old('kelas') : '' }}"
                                    placeholder="Masukkan Kelas">
                                <button
                                    class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-full shadow-md transition-all h-max text-md">
                                    Tambah
                                </button>
                            </div>
                        </div>
                        @if ($errors->has('kelas') && session('formError') === 'create')
                            <span class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('kelas') }}</span>
                        @endif
                    </form>

                    <div class="border-b-2 border-gray-200 mt-4"></div>

                    <!---- Table list data kelas  ---->
                    <div class="overflow-x-auto mt-8 pb-24">
                        <table id="container-syllabus-kelas" id="tableSyllabusKelas"
                            class="table w-full border-collapse border border-gray-300"
                            data-nama-kurikulum="{{ $nama_kurikulum }}" data-kurikulum-id="{{ $kurikulum_id }}"
                            data-fase-id="{{ $fase_id }}">
                            <thead class="thead-table-syllabus-kelas hidden">
                                <tr>
                                    <th class="border border-gray-300 w-[80%]">
                                        Kelas
                                    </th>
                                    <th class="!text-center border border-gray-300">
                                        Detail
                                    </th>
                                    <th class="!text-center border border-gray-300">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="tableListSyllabusKelas">
                                {{-- show data in ajax --}}
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-container-syllabus-kelas flex justify-center my-4 sm:my-0"></div>

                    <div id="emptyMessageSyllabusKelas" class="w-full h-96 hidden">
                        <span class="w-full h-full flex items-center justify-center">
                            Tidak ada Kelas.
                        </span>
                    </div>

                    <!---- modal edit kelas ---->
                    <dialog id="my_modal_1" class="modal">
                        <div class="modal-box bg-white w-max">
                            <form id="kelasForm">
                                <span class="text-xl font-bold flex justify-center">Edit Kelas</span>
                                <div class="mt-4 w-80">
                                    <!---- Form kelas ---->
                                    <label class="text-sm">Kelas</label>
                                    <input type="text" id="kelas" name="kelas"
                                        class="w-full bg-white shadow-lg h-11 border-gray-200 border-[1px] outline-none rounded-full text-xs px-2 mt-2"
                                        value="" placeholder="Masukkan Kelas">
                                    <span id="error-kelas" class="text-red-500 text-xs mt-1 font-bold"></span>
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
                    <!---- modal history kelas  ---->
                    <dialog id="my_modal_2" class="modal">
                        <div class="modal-box bg-white text-center">
                            <span class="text-2xl">History Kelas</span>
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
                    <!---- modal delete kelas  ---->
                    <dialog id="my_modal_3" class="modal">
                        <div class="modal-box bg-white">
                            <h3 class="font-bold text-lg text-red-600">Konfirmasi Hapus</h3>
                            <p class="py-4">Semua yang berkaitan dengan kelas ini akan dihapus secara
                                permanen. Apakah kamu
                                yakin
                                ingin menghapus kelas ini?</p>
                            <div class="modal-action">
                                <span id="hapus-modal" class="btn" onclick="closeModal()">Batal</span>
                                <form id="deleteKelasForm">
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
                </section>
            </main>
        </div>
    </div>
@else
    <p>You do not have access to this pages.</p>
@endif

<script src="{{ asset('js/syllabus-services/paginate-syllabus-kelas-ajax.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.Echo.channel('syllabus')
            .listen('.syllabus.crud', (event) => {
                paginateSyllabusKelas();
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

<script>
    function historyKelas(element) {
        const modal = document.getElementById('my_modal_2');
        const namaLengkap = element.getAttribute('data-nama_lengkap');
        const status = element.getAttribute('data-status');
        const updatedAt = element.getAttribute('data-updated_at');

        document.getElementById('text-nama_lengkap').innerText = namaLengkap;
        document.getElementById('text-status').innerText = status;
        document.getElementById('text-updated_at').innerText = updatedAt;

        modal.showModal();
    }

    function closeModal() {
        const closeModal = document.getElementById('my_modal_3');

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
    document.addEventListener('DOMContentLoaded', function() {
        // Cek semua inputan dan hapus error message ketika user mengetik
        document.querySelectorAll('input, select, textarea').forEach(function(el) {
            el.addEventListener('input', function() {
                // Hapus error class
                el.classList.remove('border-red-400');
                const errorMessage = el.nextElementSibling;
                if (errorMessage && errorMessage.classList.contains('text-red-500')) {
                    errorMessage.textContent = '';
                }
            });
        });
    });
</script>
