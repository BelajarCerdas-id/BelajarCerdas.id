@include('components/sidebar_beranda', [
    'linkBackButton' => route('bab.index', [$nama_kurikulum, $kurikulum_id, $fase_id, $kelas_id, $mapel_id]),
    'backButton' => "<i class='fa-solid fa-chevron-left'></i>",
    'headerSideNav' => 'Sub Bab',
])

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            @if (session('success-insert-data-sub-bab'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-insert-data-sub-bab'),
                ])
            @endif
            <!--- alert nya menggunakan dari response json --->
            <div id="alert-success-update-data-sub-bab"></div>
            <div id="alert-success-delete-data-sub-bab"></div>
            <main>
                <section class="bg-white shadow-lg p-6 rounded-lg border-gray-200 border-[1px]">
                    <!---- Form insert sub bab  ---->
                    <form
                        action="{{ route('subBab.store', [$nama_kurikulum, $kurikulum_id, $fase_id, $kelas_id, $mapel_id, $bab_id]) }}"
                        method="POST">
                        @csrf
                        <label class="text-sm">Sub Bab</label>
                        <div class="flex relative max-w-lg mt-2">
                            <div class="flex gap-2 w-full">
                                <input type="text" name="sub_bab"
                                    class="w-full bg-white shadow-lg h-11 border-gray-200 border-[2px] outline-none rounded-full text-xs px-2
                                    focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue]
                                    {{ $errors->has('sub_bab') && session('formError') === 'create' ? 'border-[1px] border-red-400' : '' }}"
                                    value="{{ $errors->has('sub_bab') && session('formError') === 'create' ? old('sub_bab') : '' }}"
                                    placeholder="Masukkan Sub Bab">
                                <button
                                    class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-full shadow-md transition-all h-max text-md">
                                    Tambah
                                </button>
                            </div>
                        </div>
                        @if ($errors->has('sub_bab') && session('formError') === 'create')
                            <span class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('sub_bab') }}</span>
                        @endif
                    </form>

                    <div class="border-b-2 border-gray-200 mt-4"></div>

                    <!---- Table list data sub bab  ---->
                    <div id="container-syllabus-sub-bab" class="overflow-x-auto mt-8 pb-24"
                        data-nama-kurikulum="{{ $nama_kurikulum }}" data-kurikulum-id="{{ $kurikulum_id }}"
                        data-fase-id="{{ $fase_id }}" data-kelas-id="{{ $kelas_id }}"
                        data-mapel-id="{{ $mapel_id }}" data-bab-id="{{ $bab_id }}">
                        <table id="tableSyllabusSubBab" class="table w-full border-collapse border border-gray-300">
                            <thead class="thead-table-syllabus-sub-bab hidden">
                                <tr>
                                    <th class="border border-gray-300 w-[80%] text-[14px]" rowspan="2">
                                        Sub Bab
                                    </th>
                                    <th class="!text-center border border-gray-300"
                                        colspan="{{ count($dataFeaturesRoles) ?? 0 }}">
                                        Features
                                    </th>
                                    <th class="!text-center border border-gray-300" rowspan="2">
                                        <i class="fa-solid fa-ellipsis-vertical cursor-pointer"></i>
                                    </th>
                                </tr>
                                <tr>
                                    @foreach ($dataFeaturesRoles as $featuresItem)
                                        <th class="border border-gray-300 text-center">
                                            {{ $featuresItem->Features->nama_fitur }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody id="tableListSyllabusSubBab">
                                {{-- show data in ajax --}}
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-container-syllabus-sub-bab flex justify-center my-4 sm:my-0"></div>

                    <div id="emptyMessageSyllabusSubBab" class="w-full h-96 hidden">
                        <span class="w-full h-full flex items-center justify-center">
                            Tidak ada Sub Bab.
                        </span>
                    </div>

                    <!---- modal edit sub bab ---->
                    <dialog id="my_modal_1" class="modal">
                        <div class="modal-box bg-white w-max">
                            <form id="subBabForm">
                                <span class="text-xl font-bold flex justify-center">Edit Sub Bab</span>
                                <div class="mt-4 w-80">
                                    <!---- Form sub bab---->
                                    <label class="text-sm">Sub Bab</label>
                                    <input type="text" id="sub_bab" name="sub_bab"
                                        class="w-full bg-white shadow-lg h-11 border-gray-200 border-[1px] outline-none rounded-full text-xs px-2 mt-2"
                                        value="" placeholder="Masukkan Sub Bab">
                                    <span id="error-sub-bab" class="text-red-500 text-xs mt-1 font-bold"></span>
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
                    <!---- modal history Sub Bab  ---->
                    <dialog id="my_modal_2" class="modal">
                        <div class="modal-box bg-white text-center">
                            <span class="text-2xl">History Sub Bab</span>
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
                    <!---- modal delete sub bab  ---->
                    <dialog id="my_modal_3" class="modal">
                        <div class="modal-box bg-white">
                            <h3 class="font-bold text-lg text-red-600">Konfirmasi Hapus</h3>
                            <p class="py-4">Sub Bab akan dihapus secara permanen. Apakah kamu yakin
                                ingin menghapus Sub Bab ini?</p>
                            <div class="modal-action">
                                <span id="hapus-modal" class="btn" onclick="closeModal()">Batal</span>
                                <form id="deleteSubBabForm">
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

<script src="{{ asset('js/syllabus-services/paginate-syllabus-sub-bab-ajax.js') }}"></script> <!--- paginate sub bab ---->

<!--- COMPONENTS ---->
<script src="{{ asset('js/components/clear-error-on-input.js') }}"></script> <!--- clear error on input ---->


<!--- PUSHER LISTENER ---->
<script src="{{ asset('js/pusher-listener/syllabus-services/list-sub-bab-listener.js') }}"></script> <!--- pusher listener list sub bab ---->
