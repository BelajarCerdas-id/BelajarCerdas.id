@include('components/sidebar_beranda', ['headerSideNav' => 'Management Materi'])

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[40px] md:mt-0">
        <div class="content-beranda">

            <!--- alert succes after success insert materi ----->
            <div id="alert-success-insert-materi"></div>

            <main class="bg-white shadow-lg border rounded-lg">
                <!---- form action insert materi ---->
                <section class="border-b px-6">
                    <form id="management-materi-form" enctype="multipart/form-data" autocomplete="OFF" class="py-10">
                        <div class="grid lg:grid-cols-2 gap-6">
                            <!-- Pilih level -->
                            <div>
                                <label class="text-sm font-bold opacity-70">
                                    Level
                                    <sup class="text-red-500">&#42;</sup>
                                </label>
                                <select id="level_id" name="level_id" class="select select-bordered w-full"
                                    data-old-level="{{ old('level_id') }}">
                                    <option value="" class="hidden">Pilih Level</option>
                                    @foreach ($getLevels as $item)
                                        <option value="{{ $item->id }}">{{ $item->level_name }}</option>
                                    @endforeach
                                </select>
                                <span id="error-level_id" class="text-red-500 text-xs mt-1 font-bold pt-[2px]"></span>
                            </div>

                            <!-- Pilih Sesi -->
                            <div>
                                <label class="text-sm font-bold opacity-70">
                                    Sesi
                                    <sup class="text-red-500">&#42;</sup>
                                </label>
                                <select id="session" name="session" class="select select-bordered w-full">
                                    <option value="" class="hidden">Pilih Sesi</option>
                                    @for ($i = 1; $i <= 2; $i++)
                                        <option value="{{ $i }}">Sesi {{ $i }}</option>
                                    @endfor
                                </select>
                                <span id="error-session" class="text-red-500 text-xs mt-1 font-bold pt-[2px]"></span>
                            </div>
                        </div>

                        <!-- Materi -->
                        <h2 class="text-lg font-semibold text-gray-700 pointer-events-none my-6">📂 Materi</h2>

                        <!-- Video -->
                        <div class="w-full lg:w-[49%]">
                            <label class="text-sm font-bold opacity-70">
                                Video
                                <sup class="text-red-500">&#42;</sup>
                            </label>
                            <input type="text" id="video_materi" name="video_materi"
                                placeholder="Masukkan Link Video"
                                class="w-full border bg-white shadow-lg rounded-md h-12 px-2 text-sm outline-none">
                            <span id="error-video_materi" class="text-red-500 text-xs mt-1 font-bold pt-[2px]"></span>
                        </div>

                        <div class="grid lg:grid-cols-2 my-6 gap-6">
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
                                        name="materi_vocabulary" onchange="previewPDF(event, 'materi-pdf-vocabulary')"
                                        accept=".pdf">

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
                                            <div id="fileArrowUp-materi-pdf-grammar" class="bg-blue-100 p-3 rounded-lg">
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
                        </div>

                        <!-- Tombol Submit -->
                        <div class="flex justify-end">
                            <button type="button" id="submit-button"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold text-sm">
                                Simpan
                            </button>
                        </div>
                    </form>
                </section>

                <!--- table materi list --->
                <section class="py-6 m-4">
                    <span class="text-lg font-bold opacity-70">LIST MATERI</span>
                    <div class="overflow-x-auto mt-4 pb-14">
                        <table class="table" id="table-management-materi">
                            <thead class="thead-table-management-materi hidden">
                                <tr>
                                    <th class="th-table text-black opacity-70">No</th>
                                    <th class="th-table text-black opacity-70">Materi Level</th>
                                    <th class="th-table text-black opacity-70">
                                        Detail
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="table-list-management-materi">
                                <!-- show data in ajax -->
                            </tbody>
                        </table>

                        <div class="pagination-container-management-materi flex justify-center my-4 sm:my-0"></div>

                        <div id="empty-message-management-materi" class="w-full h-96 hidden">
                            <span class="w-full h-full flex items-center justify-center">
                                Tidak ada daftar materi.
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


<script src="{{ asset('js/Features/english-zone/management-materi/management-materi.js') }}"></script> <!--- management materi ---->

<!--- COMPONENTS ---->
<script src="{{ asset('js/components/clear-error-on-input.js') }}"></script> <!--- clear error on input ---->
<script src="{{ asset('js/components/preview/pdf-upload-preview.js') }}"></script> <!--- show pdf ---->


<!--- PUSHER LISTENER ---->
<script src="{{ asset('js/pusher-listener/english-zone/management-materi.js') }}"></script> <!--- pusher listener pada saat CRUD materi ---->
