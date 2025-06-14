@include('components/sidebar_beranda', [
    'linkBackButton' => route('kurikulum.index'),
    'backButton' => "<i class='fa-solid fa-chevron-left'></i>",
    'headerSideNav' => 'Fase',
])

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            <main>

                @if (session('success-insert-data-fase'))
                    @include('components.alert.success-insert-data', [
                        'message' => session('success-insert-data-fase'),
                    ])
                @endif
                <!--- alert nya menggunakan dari response json --->
                <div id="alert-success-update-data-fase"></div>
                <div id="alert-success-delete-data-fase"></div>

                <section class="bg-white shadow-lg p-6 rounded-lg border-gray-200 border-[1px]">
                    <!---- Form input kurikulum  ---->
                    @if ($nama_kurikulum === 'Kurikulum Nasional')
                        <form action="{{ route('fase.store', [$id]) }}" method="POST">
                            @csrf
                            <label class="text-sm">Nama Fase</label>
                            <div class="flex relative max-w-lg mt-2">
                                <div class="flex gap-2 w-full">
                                    <input type="text" name="nama_fase"
                                        class="w-full bg-white shadow-lg h-11 border-gray-200 border-[2px] outline-none rounded-full text-xs px-2
                                    focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue]
                                    {{ $errors->has('nama_fase') && session('formError') === 'create' ? 'border-[1px] border-red-400' : '' }}"
                                        value="{{ $errors->has('nama_fase') && session('formError') === 'create' ? old('nama_fase') : '' }}"
                                        placeholder="Masukkan Nama Fase">
                                    <button
                                        class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-full shadow-md transition-all h-max text-md">
                                        Tambah
                                    </button>
                                </div>
                            </div>
                            @if ($errors->has('nama_fase') && session('formError') === 'create')
                                <span
                                    class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('nama_fase') }}</span>
                            @endif
                        </form>

                        <div class="border-b-2 border-gray-200 mt-4"></div>

                        <!---- Table list data fase  ---->
                        <div id="container-syllabus-fase" class="overflow-x-auto mt-8 pb-24"
                            data-nama-kurikulum="{{ $nama_kurikulum }}" data-kurikulum-id="{{ $id }}">
                            <table id="tableSyllabusFase" class="table w-full border-collapse border border-gray-300">
                                <thead class="thead-table-syllabus-fase hidden">
                                    <tr>
                                        <th class="border border-gray-300 w-[80%]">
                                            Fase
                                        </th>
                                        <th class="!text-center border border-gray-300">
                                            Detail
                                        </th>
                                        <th class="!text-center border border-gray-300">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="tableListSyllabusFase">
                                    {{-- show data in ajax --}}
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination-container-syllabus-fase flex justify-center my-4 sm:my-0"></div>

                        <div id="emptyMessageSyllabusFase" class="w-full h-96 hidden">
                            <span class="w-full h-full flex items-center justify-center">
                                Tidak ada Fase.
                            </span>
                        </div>

                        <dialog id="my_modal_1" class="modal">
                            <div class="modal-box bg-white w-max">
                                <form id="faseForm">
                                    <span class="text-xl font-bold flex justify-center">Edit Fase</span>
                                    <div class="mt-4 w-80">
                                        <!---- Form fase ---->
                                        <label class="text-sm">Nama Fase</label>
                                        <input type="text" id="nama_fase" name="nama_fase"
                                            class="w-full bg-white shadow-lg h-11 border-gray-200 border-[1px] outline-none rounded-full text-xs px-2 mt-2"
                                            value="" placeholder="Masukkan Nama Fase">
                                        <span id="error-nama-fase" class="text-red-500 text-xs mt-1 font-bold"></span>
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
                        <!---- modal history fase  ---->
                        <dialog id="my_modal_2" class="modal">
                            <div class="modal-box bg-white text-center">
                                <span class="text-2xl">History Fase</span>
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
                        <!---- modal delete bab  ---->
                        <dialog id="my_modal_3" class="modal">
                            <div class="modal-box bg-white">
                                <h3 class="font-bold text-lg text-red-600">Konfirmasi Hapus</h3>
                                <p class="py-4">Semua yang berkaitan dengan fase ini akan dihapus secara
                                    permanen.
                                    Apakah kamu
                                    yakin
                                    ingin menghapus fase ini?</p>
                                <div class="modal-action">
                                    <span id="hapus-modal" class="btn" onclick="closeModal()">Batal</span>
                                    <form id="deleteFaseForm">
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
                    @else
                        Tidak ada konten
                    @endif
                </section>
            </main>
        </div>
    </div>
@else
    <p>You do not have access to this pages.</p>
@endif

<script src="{{ asset('js/syllabus-services/paginate-syllabus-fase-ajax.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.Echo.channel('syllabus')
            .listen('.syllabus.crud', (event) => {
                paginateSyllabusFase();
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
    function historyFase(element) {
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
    document.getElementById('submit-button').addEventListener('click', function() {
        const form = document.querySelector('#my_modal_1 form[action]');
        form.submit(); // Submit the form
    });
</script>

@if (session('formErrorId'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let modalId = "my_modal_1_" + {{ session('formErrorId') }}
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
                    errorMessage.textContent = '';
                }
            });
        });
    });
</script>
