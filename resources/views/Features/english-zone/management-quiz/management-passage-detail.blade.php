@include('components/sidebar_beranda', [
    'headerSideNav' => 'Management Passage',
    'linkBackButton' => route('EZ.managementPassage.view'),
    'backButton' => "<i class='fa-solid fa-chevron-left'></i>",
])


@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">

            <!--- alert succes after success update pasage ----->
            <div id="alert-success-update-passage"></div>

            <!--- alert succes after success delete pasage ----->
            <div id="alert-success-delete-passage"></div>

            <div id="container-passage-list" data-level-id="{{ $level_id }}" data-passage-type="{{ $passage_type }}">
                <div id="passage-list">
                    <!-- show data in ajax -->
                </div>
            </div>

            <div id="empty-message-management-passage-detail" class="w-full h-96 hidden">
                <span class="w-full h-full flex items-center justify-center">
                    Tidak ada daftar passage.
                </span>
            </div>

            <form id="edit-passage-form" enctype="multipart/form-data">
                <!--- modal bulkupload --->
                <dialog id="my_modal_1" class="modal">
                    <div class="modal-box bg-white w-max !max-h-[600px]">

                        <!--- hidden input id passage ---->
                        <input type="hidden" id="passage_id" name="passage_id">

                        <span class="text-md flex justify-center font-bold opacity-70">Upload Passage</span>

                        <!--- show bulkUpload word errors --->
                        <div id="error-bulkUpload" class="my-4 max-h-42 overflow-y-auto"></div>

                        <!-- AUDIO -->
                        <div class="flex flex-col">
                            <div class="border rounded-lg p-4 shadow-sm hover:shadow-md transition h-max">
                                <div data-prefix="audio-passage" class="file-wrapper flex items-center justify-between">
                                    <div id="wordPreviewContainer-audio-passage" class="flex items-center gap-3">
                                        <div id="fileArrowUp-audio-passage" class="bg-blue-100 p-3 rounded-lg">
                                            <i class="fa-solid fa-file-audio text-blue-600 text-xl"></i>
                                        </div>

                                        <div>
                                            <div class="flex flex-col gap-1">
                                                <p class="text-sm font-bold opacity-70">Audio (Listening Only)</p>
                                                <p class="text-xs text-gray-400">MP3 (Max 100MB)</p>
                                            </div>
                                            <p id="textPreview-audio-passage" class="text-xs mt-1"></p>
                                            <div class="flex flex-row gap-1 items-center">
                                                <p id="textSize-audio-passage" class="text-xs"></p>
                                                <p id="textCircle-audio-passage" class="text-[5px]"></p>
                                                <p id="textPages-audio-passage" class="text-xs"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <label for="file-audio-passage"
                                        class="px-4 py-2 bg-blue-500 text-white rounded-lg cursor-pointer hover:bg-blue-600">
                                        Upload
                                    </label>
                                </div>
                            </div>

                            <!-- BUNGKUS INPUT + SPAN -->
                            <div class="flex flex-col">
                                <input id="file-audio-passage" type="file" class="hidden" name="audio_file"
                                    onchange="previewAudio(event, 'audio-passage')" accept=".mp3">

                                <span id="error-audio_file" class="text-red-500 text-xs mt-1 font-bold"></span>
                            </div>
                        </div>

                        <div class="w-full mt-8">
                            <div class="w-full h-auto">
                                <div class="text-xs mt-1">
                                    <span>Maksimum ukuran file 100MB. <br> File dapat dalam format
                                        .docx.</span>
                                </div>
                                <div class="upload-icon">
                                    <div class="flex flex-col max-w-[260px]">
                                        <div id="wordPreview" class="max-w-[280px] cursor-pointer mt-4">
                                            <div id="wordPreviewContainer-bulkUpload-word"
                                                class="bg-white shadow-lg rounded-lg w-max py-2 pr-4 border-[1px] border-gray-200 hidden">
                                                <div class="flex items-center">
                                                    <img id="logo-bulkUpload-word" class="w-[56px] h-max">
                                                    <div class="mt-2 leading-5">
                                                        <span id="textPreview-bulkUpload-word"
                                                            class="font-bold text-sm"></span><br>
                                                        <span id="textSize-bulkUpload-word" class="text-xs"></span>
                                                        <span id="textCircle-bulkUpload-word"
                                                            class="relative top-[-2px] text-[5px]"></span>
                                                        <span id="textPages-bulkUpload-word" class="text-xs"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="content-upload w-[385px] h-9 bg-[#4189e0] hover:bg-blue-500 text-white font-bold rounded-lg mt-6 mb-2">
                                <label for="file-bulkUpload-word"
                                    class="w-full h-full flex justify-center items-center cursor-pointer gap-2">
                                    <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                    <span>Upload File</span>
                                </label>
                                <input id="file-bulkUpload-word" name="bulkUpload-management-passage" class="hidden"
                                    onchange="previewWord(event, 'bulkUpload-word')" type="file" accept=".docx">
                                <span id="error-bulkUpload-management-passage"
                                    class="text-red-500 font-bold text-xs pt-2"></span>
                            </div>
                        </div>

                        <!-- Tombol Kirim -->
                        <div class="flex justify-end mt-8 z-[-1]">
                            <button type="button" id="submit-button"
                                class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all outline-none">
                                Kirim
                            </button>
                        </div>
                    </div>
            </form>
            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
            </dialog>

            <!---- modal delete passage  ---->
            <dialog id="my_modal_2" class="modal">
                <div class="modal-box bg-white">
                    <h3 class="font-bold text-lg text-red-600">Konfirmasi Hapus</h3>
                    <p class="py-4">Passage yang sudah dihapus tidak dapat dikembalikan.
                        Apakah kamu
                        yakin
                        ingin menghapus passage ini?</p>
                    <div class="modal-action">
                        <span id="hapus-modal" class="btn" onclick="closeModal()">Batal</span>
                        <form id="delete-passage-form">
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
        </div>
    </div>
@else
    <div class="flex flex-col min-h-screen items-center justify-center">
        <p>ALERT SEMENTARA</p>
        <p>You do not have access to this pages.</p>
    </div>
@endif

<script src="{{ asset('js/Features/english-zone/management-quiz/management-passage-detail.js') }}"></script> <!--- management passage detail ---->

<!--- COMPONENTS ---->
<script src="{{ asset('js/components/preview/audio-upload-preview.js') }}"></script> <!--- show audio ---->
<script src="{{ asset('js/components/preview/word-upload-preview.js') }}"></script> <!--- show word ---->

<!--- PUSHER LISTENER ---->
<script src="{{ asset('js/pusher-listener/english-zone/management-passage-detail.js') }}"></script> <!--- pusher listener pada saat CRUD passage detail ---->
