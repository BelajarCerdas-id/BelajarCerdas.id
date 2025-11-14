@include('components/sidebar_beranda', [
    'headerSideNav' => 'Management Materi Detail',
    'linkBackButton' => route('EZ.managementMateri.view'),
    'backButton' => "<i class='fa-solid fa-chevron-left'></i>",
])

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[40px] md:mt-0">
        <div class="content-beranda">

            <!--- alert succes after success update materi ----->
            <div id="alert-success-update-materi"></div>

            <!--- alert succes after success delete materi ----->
            <div id="alert-success-delete-materi"></div>

            <main>
                <section>
                    <span class="text-lg font-bold opacity-70">LIST MATERI DETAIL</span>
                    <div id="container-management-materi-detail" class="overflow-x-auto mt-4 pb-14"
                        data-level-id="{{ $id }}">
                        <table class="table" id="table-management-materi-detail">
                            <thead class="thead-table-management-materi-detail hidden">
                                <tr>
                                    <th class="th-table text-black opacity-70">No</th>
                                    <th class="th-table text-black opacity-70 py-2 px-3 text-center">Level</th>
                                    <th class="th-table text-black opacity-70 py-2 px-3 text-center">Sesi</th>
                                    <th class="th-table text-black opacity-70 py-2 px-3 text-center">Vocabulary</th>
                                    <th class="th-table text-black opacity-70 py-2 px-3 text-center">Grammar</th>
                                    <th class="th-table text-black opacity-70 py-2 px-3 text-center">Lesson Plan</th>
                                    <th class="th-table text-black opacity-70 py-2 px-3 text-center">Reading</th>
                                    <th class="th-table text-black opacity-70 py-2 px-3 text-center">Writing</th>
                                    <th class="th-table text-black opacity-70 py-2 px-3 text-center">Listening</th>
                                    <th class="th-table text-black opacity-70 py-2 px-3 text-center">Speaking</th>
                                    <th class="th-table text-black opacity-70 py-2 px-3 text-center">Materi Pembelajaran</th>
                                    <th class="th-table text-black opacity-70 py-2 px-3 text-center">WorkSheet</th>
                                    <th class="th-table text-black opacity-70 py-2 px-3 text-center">Video</th>
                                    <th class="th-table text-black opacity-70 py-2 px-3 text-center">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="table-list-management-materi-detail">
                                <!-- show data in ajax -->
                            </tbody>
                        </table>

                        <div class="pagination-container-management-materi-detail flex justify-center my-4 sm:my-0">
                        </div>

                        <div id="empty-message-management-materi-detail" class="w-full h-96 hidden">
                            <span class="w-full h-full flex items-center justify-center">
                                Tidak ada daftar materi pada level ini.
                            </span>
                        </div>
                    </div>
                </section>

                <!-- Modal Edit Materi -->
                <dialog id="my_modal_3" class="modal">
                    <div class="modal-box bg-white max-w-6xl">

                        <!-- untuk menghilangkan focus input type pada saat open modal  --->
                        <div tabindex="-1"></div> <!-- Tambahkan ini -->

                        <form id="edit-materi-form" enctype="multipart/form-data" autocomplete="OFF">
                            <span class="text-xl font-bold flex justify-center">Edit Materi</span>
                            <!-- Materi -->
                            <h2 class="text-lg font-semibold text-gray-700 pointer-events-none my-6">📂 Materi</h2>

                            <!-- Video -->
                            <div class="w-full">
                                <label class="text-sm font-bold opacity-70">
                                    Video
                                    <sup class="text-red-500">&#42;</sup>
                                </label>
                                <input type="text" id="video_materi" name="video_materi"
                                    placeholder="Masukkan Link Video"
                                    class="w-full border bg-white shadow-lg rounded-md h-12 px-2 text-sm outline-none">
                                <span id="error-video_materi"
                                    class="text-red-500 text-xs mt-1 font-bold pt-[2px]"></span>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 my-6 gap-6">
                                <!-- Vocabulary -->
                                <div class="flex flex-col">
                                    <div class="border rounded-lg p-4 shadow-sm hover:shadow-md transition h-max">
                                        <div data-prefix="materi-pdf-vocabulary"
                                            class="file-wrapper flex items-center justify-between">
                                            <div id="pdfPreviewContainer-materi-pdf-vocabulary"
                                                class="flex items-center gap-3">
                                                <div id="fileArrowUp-materi-pdf-vocabulary"
                                                    class="bg-blue-100 p-3 rounded-lg">
                                                    <i class="fa-solid fa-file-arrow-up text-blue-600 text-xl"></i>
                                                </div>
                                                <img id="pdfLogo-materi-pdf-vocabulary"
                                                    class="max-w-[56px] max-h-[56px] hidden">
                                                <div>
                                                    <div class="flex flex-col gap-1">
                                                        <p class="text-sm font-bold opacity-70">Vocabulary</p>
                                                        <p class="text-xs text-gray-400">PDF (Max 100MB)</p>
                                                    </div>
                                                    <p id="textPreview-materi-pdf-vocabulary" class="text-xs mt-1"></p>
                                                    <div class="flex flex-row gap-1 items-center">
                                                        <p id="textSize-materi-pdf-vocabulary" class="text-xs"></p>
                                                        <p id="textCircle-materi-pdf-vocabulary" class="text-[5px]"></p>
                                                        <p id="textPages-materi-pdf-vocabulary" class="text-xs"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <label for="file-materi-pdf-vocabulary"
                                                class="px-4 py-2 bg-blue-500 text-white rounded-lg cursor-pointer hover:bg-blue-600">
                                                Upload
                                            </label>
                                        </div>
                                    </div>

                                    <!-- BUNGKUS INPUT + SPAN -->
                                    <div class="flex flex-col">
                                        <input id="file-materi-pdf-vocabulary" type="file" class="hidden"
                                            name="materi_vocabulary"
                                            onchange="previewPDF(event, 'materi-pdf-vocabulary')" accept=".pdf">

                                        <span id="error-materi_vocabulary"
                                            class="text-red-500 text-xs mt-1 font-bold"></span>
                                    </div>
                                </div>

                                <!-- Grammar -->
                                <div class="flex flex-col">
                                    <div class="border rounded-lg p-4 shadow-sm hover:shadow-md transition h-max">
                                        <div data-prefix="materi-pdf-grammar"
                                            class="file-wrapper flex items-center justify-between">
                                            <div id="pdfPreviewContainer-materi-pdf-grammar"
                                                class="flex items-center gap-3">
                                                <div id="fileArrowUp-materi-pdf-grammar"
                                                    class="bg-blue-100 p-3 rounded-lg">
                                                    <i class="fa-solid fa-file-arrow-up text-blue-600 text-xl"></i>
                                                </div>
                                                <img id="pdfLogo-materi-pdf-grammar"
                                                    class="max-w-[56px] max-h-[56px] hidden">
                                                <div>
                                                    <div class="flex flex-col gap-1">
                                                        <p class="text-sm font-bold opacity-70">Grammar</p>
                                                        <p class="text-xs text-gray-400">PDF (Max 100MB)</p>
                                                    </div>
                                                    <p id="textPreview-materi-pdf-grammar" class="text-xs mt-1"></p>
                                                    <div class="flex flex-row gap-1 items-center">
                                                        <p id="textSize-materi-pdf-grammar" class="text-xs"></p>
                                                        <p id="textCircle-materi-pdf-grammar" class="text-[5px]">
                                                        </p>
                                                        <p id="textPages-materi-pdf-grammar" class="text-xs"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <label for="file-materi-pdf-grammar"
                                                class="px-4 py-2 bg-blue-500 text-white rounded-lg cursor-pointer hover:bg-blue-600">
                                                Upload
                                            </label>
                                        </div>
                                    </div>

                                    <!-- BUNGKUS INPUT + SPAN -->
                                    <div class="flex flex-col">
                                        <input id="file-materi-pdf-grammar" type="file" class="hidden"
                                            name="materi_grammar" onchange="previewPDF(event, 'materi-pdf-grammar')"
                                            accept=".pdf">

                                        <span id="error-materi_grammar"
                                            class="text-red-500 text-xs mt-1 font-bold"></span>
                                    </div>
                                </div>

                                <!-- Lesson Plan -->
                                <div class="flex flex-col">
                                    <div class="border rounded-lg p-4 shadow-sm hover:shadow-md transition h-max">
                                        <div data-prefix="materi-pdf-lesson-plan"
                                            class="file-wrapper flex items-center justify-between">
                                            <div id="pdfPreviewContainer-materi-pdf-lesson-plan"
                                                class="flex items-center gap-3">
                                                <div id="fileArrowUp-materi-pdf-lesson-plan" class="bg-blue-100 p-3 rounded-lg">
                                                    <i class="fa-solid fa-file-arrow-up text-blue-600 text-xl"></i>
                                                </div>
                                                <img id="pdfLogo-materi-pdf-lesson-plan"
                                                    class="max-w-[56px] max-h-[56px] hidden">
                                                <div>
                                                    <div class="flex flex-col gap-1">
                                                        <p class="text-sm font-bold opacity-70">Lesson Plan</p>
                                                        <p class="text-xs text-gray-400">PDF (Max 100MB)</p>
                                                    </div>
                                                    <p id="textPreview-materi-pdf-lesson-plan" class="text-xs mt-1"></p>
                                                    <div class="flex flex-row gap-1 items-center">
                                                        <p id="textSize-materi-pdf-lesson-plan" class="text-xs"></p>
                                                        <p id="textCircle-materi-pdf-lesson-plan" class="text-[5px]">
                                                        </p>
                                                        <p id="textPages-materi-pdf-lesson-plan" class="text-xs"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <label for="file-materi-pdf-lesson-plan"
                                                class="px-4 py-2 bg-blue-500 text-white rounded-lg cursor-pointer hover:bg-blue-600">
                                                Upload
                                            </label>
                                        </div>
                                    </div>

                                    <!-- BUNGKUS INPUT + SPAN -->
                                    <div class="flex flex-col">
                                        <input id="file-materi-pdf-lesson-plan" type="file" class="hidden"
                                            name="materi_lesson_plan" onchange="previewPDF(event, 'materi-pdf-lesson-plan')"
                                            accept=".pdf">

                                        <span id="error-materi_lesson_plan"
                                            class="text-red-500 text-xs mt-1 font-bold"></span>
                                    </div>
                                </div>

                            <!-- Reading -->
                            <div class="flex flex-col">
                                <div class="border rounded-lg p-4 shadow-sm hover:shadow-md transition h-max">
                                    <div data-prefix="materi-pdf-reading"
                                        class="file-wrapper flex items-center justify-between">
                                        <div id="pdfPreviewContainer-materi-pdf-reading"
                                            class="flex items-center gap-3">
                                            <div id="fileArrowUp-materi-pdf-reading" class="bg-blue-100 p-3 rounded-lg">
                                                <i class="fa-solid fa-file-arrow-up text-blue-600 text-xl"></i>
                                            </div>
                                            <img id="pdfLogo-materi-pdf-reading"
                                                class="max-w-[56px] max-h-[56px] hidden">
                                            <div>
                                                <div class="flex flex-col gap-1">
                                                    <p class="text-sm font-bold opacity-70">Reading</p>
                                                    <p class="text-xs text-gray-400">PDF (Max 100MB)</p>
                                                </div>
                                                <p id="textPreview-materi-pdf-reading" class="text-xs mt-1"></p>
                                                <div class="flex flex-row gap-1 items-center">
                                                    <p id="textSize-materi-pdf-reading" class="text-xs"></p>
                                                    <p id="textCircle-materi-pdf-reading" class="text-[5px]">
                                                    </p>
                                                    <p id="textPages-materi-pdf-reading" class="text-xs"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <label for="file-materi-pdf-reading"
                                            class="px-4 py-2 bg-blue-500 text-white rounded-lg cursor-pointer hover:bg-blue-600">
                                            Upload
                                        </label>
                                    </div>
                                </div>

                                <!-- BUNGKUS INPUT + SPAN -->
                                <div class="flex flex-col">
                                    <input id="file-materi-pdf-reading" type="file" class="hidden"
                                        name="materi_reading" onchange="previewPDF(event, 'materi-pdf-reading')"
                                        accept=".pdf">

                                    <span id="error-materi_reading"
                                        class="text-red-500 text-xs mt-1 font-bold"></span>
                                </div>
                            </div>

                            <!-- Writing -->
                            <div class="flex flex-col">
                                <div class="border rounded-lg p-4 shadow-sm hover:shadow-md transition h-max">
                                    <div data-prefix="materi-pdf-writing"
                                        class="file-wrapper flex items-center justify-between">
                                        <div id="pdfPreviewContainer-materi-pdf-writing"
                                            class="flex items-center gap-3">
                                            <div id="fileArrowUp-materi-pdf-writing" class="bg-blue-100 p-3 rounded-lg">
                                                <i class="fa-solid fa-file-arrow-up text-blue-600 text-xl"></i>
                                            </div>
                                            <img id="pdfLogo-materi-pdf-writing"
                                                class="max-w-[56px] max-h-[56px] hidden">
                                            <div>
                                                <div class="flex flex-col gap-1">
                                                    <p class="text-sm font-bold opacity-70">Writing</p>
                                                    <p class="text-xs text-gray-400">PDF (Max 100MB)</p>
                                                </div>
                                                <p id="textPreview-materi-pdf-writing" class="text-xs mt-1"></p>
                                                <div class="flex flex-row gap-1 items-center">
                                                    <p id="textSize-materi-pdf-writing" class="text-xs"></p>
                                                    <p id="textCircle-materi-pdf-writing" class="text-[5px]">
                                                    </p>
                                                    <p id="textPages-materi-pdf-writing" class="text-xs"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <label for="file-materi-pdf-writing"
                                            class="px-4 py-2 bg-blue-500 text-white rounded-lg cursor-pointer hover:bg-blue-600">
                                            Upload
                                        </label>
                                    </div>
                                </div>

                                <!-- BUNGKUS INPUT + SPAN -->
                                <div class="flex flex-col">
                                    <input id="file-materi-pdf-writing" type="file" class="hidden"
                                        name="materi_writing" onchange="previewPDF(event, 'materi-pdf-writing')"
                                        accept=".pdf">

                                    <span id="error-materi_writing"
                                        class="text-red-500 text-xs mt-1 font-bold"></span>
                                </div>
                            </div>

                            <!-- Listening -->
                            <div class="flex flex-col">
                                <div class="border rounded-lg p-4 shadow-sm hover:shadow-md transition h-max">
                                    <div data-prefix="materi-pdf-listening"
                                        class="file-wrapper flex items-center justify-between">
                                        <div id="pdfPreviewContainer-materi-pdf-listening"
                                            class="flex items-center gap-3">
                                            <div id="fileArrowUp-materi-pdf-listening" class="bg-blue-100 p-3 rounded-lg">
                                                <i class="fa-solid fa-file-arrow-up text-blue-600 text-xl"></i>
                                            </div>
                                            <img id="pdfLogo-materi-pdf-listening"
                                                class="max-w-[56px] max-h-[56px] hidden">
                                            <div>
                                                <div class="flex flex-col gap-1">
                                                    <p class="text-sm font-bold opacity-70">Listening</p>
                                                    <p class="text-xs text-gray-400">PDF (Max 100MB)</p>
                                                </div>
                                                <p id="textPreview-materi-pdf-listening" class="text-xs mt-1"></p>
                                                <div class="flex flex-row gap-1 items-center">
                                                    <p id="textSize-materi-pdf-listening" class="text-xs"></p>
                                                    <p id="textCircle-materi-pdf-listening" class="text-[5px]">
                                                    </p>
                                                    <p id="textPages-materi-pdf-listening" class="text-xs"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <label for="file-materi-pdf-listening"
                                            class="px-4 py-2 bg-blue-500 text-white rounded-lg cursor-pointer hover:bg-blue-600">
                                            Upload
                                        </label>
                                    </div>
                                </div>

                                <!-- BUNGKUS INPUT + SPAN -->
                                <div class="flex flex-col">
                                    <input id="file-materi-pdf-listening" type="file" class="hidden"
                                        name="materi_listening" onchange="previewPDF(event, 'materi-pdf-listening')"
                                        accept=".pdf">

                                    <span id="error-materi_listening"
                                        class="text-red-500 text-xs mt-1 font-bold"></span>
                                </div>
                            </div>

                            <!-- Speaking -->
                            <div class="flex flex-col">
                                <div class="border rounded-lg p-4 shadow-sm hover:shadow-md transition h-max">
                                    <div data-prefix="materi-pdf-speaking"
                                        class="file-wrapper flex items-center justify-between">
                                        <div id="pdfPreviewContainer-materi-pdf-speaking"
                                            class="flex items-center gap-3">
                                            <div id="fileArrowUp-materi-pdf-speaking" class="bg-blue-100 p-3 rounded-lg">
                                                <i class="fa-solid fa-file-arrow-up text-blue-600 text-xl"></i>
                                            </div>
                                            <img id="pdfLogo-materi-pdf-speaking"
                                                class="max-w-[56px] max-h-[56px] hidden">
                                            <div>
                                                <div class="flex flex-col gap-1">
                                                    <p class="text-sm font-bold opacity-70">Speaking</p>
                                                    <p class="text-xs text-gray-400">PDF (Max 100MB)</p>
                                                </div>
                                                <p id="textPreview-materi-pdf-speaking" class="text-xs mt-1"></p>
                                                <div class="flex flex-row gap-1 items-center">
                                                    <p id="textSize-materi-pdf-speaking" class="text-xs"></p>
                                                    <p id="textCircle-materi-pdf-speaking" class="text-[5px]">
                                                    </p>
                                                    <p id="textPages-materi-pdf-speaking" class="text-xs"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <label for="file-materi-pdf-speaking"
                                            class="px-4 py-2 bg-blue-500 text-white rounded-lg cursor-pointer hover:bg-blue-600">
                                            Upload
                                        </label>
                                    </div>
                                </div>

                                <!-- BUNGKUS INPUT + SPAN -->
                                <div class="flex flex-col">
                                    <input id="file-materi-pdf-speaking" type="file" class="hidden"
                                        name="materi_speaking" onchange="previewPDF(event, 'materi-pdf-speaking')"
                                        accept=".pdf">

                                    <span id="error-materi_speaking"
                                        class="text-red-500 text-xs mt-1 font-bold"></span>
                                </div>
                            </div>

                            <!-- WorkSheet -->
                            <div class="flex flex-col">
                                <div class="border rounded-lg p-4 shadow-sm hover:shadow-md transition h-max">
                                    <div data-prefix="materi-pdf-worksheet"
                                        class="file-wrapper flex items-center justify-between">
                                        <div id="pdfPreviewContainer-materi-worksheet"
                                            class="flex items-center gap-3">
                                            <div id="fileArrowUp-materi-pdf-worksheet" class="bg-blue-100 p-3 rounded-lg">
                                                <i class="fa-solid fa-file-arrow-up text-blue-600 text-xl"></i>
                                            </div>
                                            <img id="pdfLogo-materi-pdf-worksheet"
                                                class="max-w-[56px] max-h-[56px] hidden">
                                            <div>
                                                <div class="flex flex-col gap-1">
                                                    <p class="text-sm font-bold opacity-70">WorkSheet</p>
                                                    <p class="text-xs text-gray-400">PDF (Max 100MB)</p>
                                                </div>
                                                <p id="textPreview-materi-pdf-worksheet" class="text-xs mt-1"></p>
                                                <div class="flex flex-row gap-1 items-center">
                                                    <p id="textSize-materi-pdf-worksheet" class="text-xs"></p>
                                                    <p id="textCircle-materi-pdf-worksheet" class="text-[5px]">
                                                    </p>
                                                    <p id="textPages-materi-pdf-worksheet" class="text-xs"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <label for="file-materi-pdf-worksheet"
                                            class="px-4 py-2 bg-blue-500 text-white rounded-lg cursor-pointer hover:bg-blue-600">
                                            Upload
                                        </label>
                                    </div>
                                </div>

                                <!-- BUNGKUS INPUT + SPAN -->
                                <div class="flex flex-col">
                                    <input id="file-materi-pdf-worksheet" type="file" class="hidden"
                                        name="worksheet" onchange="previewPDF(event, 'materi-pdf-worksheet')"
                                        accept=".pdf">

                                    <span id="error-worksheet"
                                        class="text-red-500 text-xs mt-1 font-bold"></span>
                                </div>
                            </div>

                            <!-- Materi Pembelajaran -->
                            <div class="flex flex-col">
                                <div class="border rounded-lg p-4 shadow-sm hover:shadow-md transition h-max">
                                    <div data-prefix="materi-pembelajaran"
                                        class="file-wrapper flex items-center justify-between">
                                        <div id="pdfPreviewContainer-materi-pembelajaran"
                                            class="flex items-center gap-3">
                                            <div id="fileArrowUp-materi-pembelajaran" class="bg-blue-100 p-3 rounded-lg">
                                                <i class="fa-solid fa-file-arrow-up text-blue-600 text-xl"></i>
                                            </div>
                                            <img id="pdfLogo-materi-pembelajaran"
                                                class="max-w-[56px] max-h-[56px] hidden">
                                            <div>
                                                <div class="flex flex-col gap-1">
                                                    <p class="text-sm font-bold opacity-70">Materi Pembelajaran</p>
                                                    <p class="text-xs text-gray-400">PDF (Max 100MB)</p>
                                                </div>
                                                <p id="textPreview-materi-pembelajaran" class="text-xs mt-1"></p>
                                                <div class="flex flex-row gap-1 items-center">
                                                    <p id="textSize-materi-pembelajaran" class="text-xs"></p>
                                                    <p id="textCircle-materi-pembelajaran" class="text-[5px]">
                                                    </p>
                                                    <p id="textPages-materi-pembelajaran" class="text-xs"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <label for="file-materi-pembelajaran"
                                            class="px-4 py-2 bg-blue-500 text-white rounded-lg cursor-pointer hover:bg-blue-600">
                                            Upload
                                        </label>
                                    </div>
                                </div>

                                <!-- BUNGKUS INPUT + SPAN -->
                                <div class="flex flex-col">
                                    <input id="file-materi-pembelajaran" type="file" class="hidden"
                                        name="materi_pembelajaran" onchange="previewPDF(event, 'materi-pembelajaran')"
                                        accept=".pdf">

                                    <span id="error-materi_pembelajaran"
                                        class="text-red-500 text-xs mt-1 font-bold"></span>
                                </div>
                            </div>
                            </div>

                            <div class="flex justify-end mt-8">
                                <button type="button" id="submit-button"
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

                <!---- modal delete materi  ---->
                <dialog id="my_modal_4" class="modal">
                    <div class="modal-box bg-white">
                        <h3 class="font-bold text-lg text-red-600">Konfirmasi Hapus</h3>
                        <p class="py-4">Materi yang sudah dihapus tidak dapat dikembalikan.
                            Apakah kamu
                            yakin
                            ingin menghapus materi ini?</p>
                        <div class="modal-action">
                            <span id="hapus-modal" class="btn" onclick="closeModal()">Batal</span>
                            <form id="delete-materi-form">
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
            </main>
        </div>
    </div>
@else
    <div class="flex flex-col min-h-screen items-center justify-center">
        <p>ALERT SEMENTARA</p>
        <p>You do not have access to this pages.</p>
    </div>
@endif

<script src="{{ asset('js/Features/english-zone/management-materi/management-materi-detail.js') }}"></script> <!--- management materi detail ---->

<!--- COMPONENTS ---->
<script src="{{ asset('js/components/clear-error-on-input.js') }}"></script> <!--- clear error on input ---->
<script src="{{ asset('js/components/preview/pdf-upload-preview.js') }}"></script> <!--- show pdf ---->

<!--- PUSHER LISTENER ---->
<script src="{{ asset('js/pusher-listener/english-zone/management-materi-detail.js') }}"></script> <!--- pusher listener pada saat CRUD materi detail ---->
