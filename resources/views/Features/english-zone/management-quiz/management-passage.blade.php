@include('components/sidebar_beranda', ['headerSideNav' => 'Management Quiz'])

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">

            <!--- alert succes after success insert pasage ----->
            <div id="alert-success-insert-passage"></div>

            <main class="bg-white shadow-lg border h-max rounded-lg">
                <section>
                    <form id="management-passage-form" enctype="multipart/form-data">

                        <!--- button bulkupload --->
                        <div class="flex justify-end p-6">
                            <button type="button"
                                class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold h-10 px-6 rounded-lg shadow-md transition-all text-sm flex gap-2 items-center justify-center"
                                onclick="my_modal_1.showModal()">
                                <i class="fa-solid fa-circle-plus"></i>
                                Upload Passage
                            </button>
                        </div>

                        <!--- modal bulkupload --->
                        <dialog id="my_modal_1" class="modal">
                            <div class="modal-box bg-white w-max !max-h-[600px]">
                                <span class="text-md flex justify-center font-bold opacity-70">Upload Passage</span>

                            <!--- show bulkUpload word errors --->
                            <div id="error-bulkUpload" class="my-4 max-h-42 overflow-y-auto"></div>

                            <!-- AUDIO -->
                            <div class="flex flex-col">
                                <div class="border rounded-lg p-4 shadow-sm hover:shadow-md transition h-max">
                                    <div data-prefix="audio-passage"
                                        class="file-wrapper flex items-center justify-between">
                                        <div id="wordPreviewContainer-audio-passage"
                                            class="flex items-center gap-3">
                                            <div id="fileArrowUp-audio-passage"
                                                class="bg-blue-100 p-3 rounded-lg">
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
                                    <input id="file-audio-passage" type="file" class="hidden"
                                        name="audio_file" onchange="previewAudio(event, 'audio-passage')"
                                        accept=".mp3">

                                    <span id="error-audio_file"
                                        class="text-red-500 text-xs mt-1 font-bold"></span>
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
                                                                <span id="textSize-bulkUpload-word"
                                                                    class="text-xs"></span>
                                                                <span id="textCircle-bulkUpload-word"
                                                                    class="relative top-[-2px] text-[5px]"></span>
                                                                <span id="textPages-bulkUpload-word"
                                                                    class="text-xs"></span>
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
                                        <input id="file-bulkUpload-word" name="bulkUpload-management-passage"
                                            class="hidden" onchange="previewWord(event, 'bulkUpload-word')"
                                            type="file" accept=".docx">
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
                </section>

                <section class="py-4">
                    <!--- table daftar list bank soal --->
                    <div class="overflow-x-auto m-4 pb-24">
                        <table class="table" id="table-management-passage">
                            <thead class="thead-table-management-passage hidden">
                                <tr>
                                    <th class="th-table text-black opacity-70">No</th>
                                    <th class="th-table text-black opacity-70">Level</th>
                                    <th class="th-table text-black opacity-70">Tipe Passage</th>
                                    <th class="th-table text-black opacity-70">Detail</th>
                                </tr>
                            </thead>
                            <tbody id="table-list-management-passage">
                                {{-- show data in ajax --}}
                            </tbody>
                        </table>

                        <div class="pagination-container-management-passage flex justify-center my-4 sm:my-0"></div>

                        <div id="empty-message-management-passage" class="w-full h-96 hidden">
                            <span class="w-full h-full flex items-center justify-center">
                                Tidak ada daftar passage.
                            </span>
                        </div>
                    </div>
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

<script src="{{ asset('js/Features/english-zone/management-quiz/management-passage.js') }}"></script> <!--- management passage ---->

<!--- COMPONENTS ---->
<script src="{{ asset('js/components/clear-error-on-input.js') }}"></script> <!--- clear error on input ---->
<script src="{{ asset('js/components/preview/audio-upload-preview.js') }}"></script> <!--- show audio ---->
<script src="{{ asset('js/components/preview/word-upload-preview.js') }}"></script> <!--- show word ---->

<!--- PUSHER LISTENER ---->
<script src="{{ asset('js/pusher-listener/english-zone/management-passage.js') }}"></script> <!--- pusher listener pada saat CRUD passage ---->