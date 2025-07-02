@include('components/sidebar_beranda', [
    'linkBackButton' => route('bankSoal.detail.view', [$subBab, $subBabId, $id]),
    'backButton' => "<i class='fa-solid fa-chevron-left'></i>",
    'headerSideNav' => 'Edit Question',
]);

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[40px] md:mt-0">
        <div class="content-beranda">

            <!---- alert success from ajax ---->
            <div id="alert-success-bank-soal-edit-question"></div>

            <main>
                <section class="bg-white shadow-lg rounded-lg p-8 border border-gray-200">
                    <div id="editor-container" data-sub-bab="{{ $subBab }}" data-sub-bab-id="{{ $subBabId }}"
                        data-question-id="{{ $id }}"
                        data-upload-url="{{ route('soalPembahasan.editImage', ['_token' => csrf_token()]) }}"
                        data-delete-url="{{ route('soalPembahasan.deleteImage') }}">
                        {{-- <form id="bank-soal-edit-question-form" enctype="multipart/form-data"
                            data-sub-bab="{{ $subBab }}" data-sub-bab-id="{{ $subBabId }}"
                            data-question-id="{{ $id }}">
                            <!----  Question  ---->
                            <div class="leading-10 mb-6 w-full">
                                <span>
                                    Question
                                    <sup class="text-red-500 pl-1">&#42;</sup>
                                </span>
                                <textarea name="questions" id="questions" class="editor">{!! $editQuestion->questions !!}</textarea>
                                <span id="error-questions" class="text-red-500 font-bold text-xs pt-2"></span>
                            </div>

                            <!----  Options key & value  ---->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                @foreach ($groupedSoal as $question => $items)
                                    @foreach ($items as $item)
                                        <div class="flex flex-col gap-4">
                                            <span>
                                                <label class="mb-2 text-sm">
                                                    Option
                                                    {{ $item->options_key }}
                                                    <sup class="text-red-500 pl-1">&#42;</sup>
                                                </label>
                                            </span>
                                            <textarea name="options_value[{{ $item->id }}]" id="options_value" class="editor">{!! $item->options_value !!}</textarea>
                                            <span id="error-options_value"
                                                class="text-red-500 font-bold text-xs pt-2"></span>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>

                            <!----  Answer key option  ---->
                            <div class="flex flex-col w-full lg:w-2/4 pr-2 my-6">
                                <label class="mb-2 text-sm">Answer Key<sup class="text-red-500 pl-1">&#42;</sup></label>
                                <select name="answer_key" id="answer_key" value="{{ old('answer_key') }}"
                                    class="bg-white shadow-lg h-12 text-sm border-gray-200 border outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer">
                                    <option value="{{ $editQuestion->answer_key }}" class="hidden">
                                        {{ $editQuestion->answer_key }}
                                    </option>

                                    @foreach ($groupedSoal as $question => $items)
                                        @foreach ($items as $item)
                                            <option value="{{ $item->options_key }}">{{ $item->options_key }}</option>
                                        @endforeach
                                    @endforeach

                                </select>
                                <span id="error-kelas_id" class="text-red-500 font-bold text-xs pt-2"></span>
                            </div>

                            <!----  Skilltag and Difficulty option ---->
                            <div class="grid grid-cols-2 gap-6">
                                <div class="flex flex-col">
                                    <label class="mb-2 text-sm">Skilltag</label>
                                    <select name="skilltag" id="skilltag" value="{{ old('skilltag') }}"
                                        class="bg-white shadow-lg h-12 text-sm border-gray-200 border outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer">
                                        <option value="{{ $editQuestion->skilltag }}" class="hidden">
                                            {{ $editQuestion->skilltag }}
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                    </select>
                                    <span id="error-skilltag" class="text-red-500 font-bold text-xs pt-2"></span>
                                </div>
                                <div class="flex flex-col">
                                    <label class="mb-2 text-sm">Difficulty</label>
                                    <select name="difficulty" id="difficulty" value="{{ old('difficulty') }}"
                                        class="bg-white shadow-lg h-12 text-sm border-gray-200 border outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer">
                                        <option value="{{ $editQuestion->difficulty }}" class="hidden">
                                            {{ $editQuestion->difficulty }}
                                        <option value="Mudah">Mudah</option>
                                        <option value="Sedang">Sedang</option>
                                        <option value="Sulit">Sulit</option>
                                    </select>
                                    <span id="error-difficulty" class="text-red-500 font-bold text-xs pt-2"></span>
                                </div>
                            </div>

                            <!----  Explanation  ---->
                            <div class="leading-10 w-full my-6">
                                <span>
                                    Explanation
                                    <sup class="text-red-500 pl-1">&#42;</sup>
                                </span>
                                <textarea name="explanation" id="explanation" class="editor">{{ $editQuestion->explanation }}</textarea>
                                <span id="error-explanation" class="text-red-500 font-bold text-xs pt-2"></span>
                            </div>
                    </div>

                    <div class="flex justify-end mt-20 lg:mt-8">
                        <button id="submit-button"
                            class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all">
                            Simpan Perubahan
                        </button>
                    </div>
                    </form> --}}
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

<script src="{{ asset('js/Features/soal-pembahasan/bank-soal/form-action-edit-question.js') }}"></script> <!--- form action edit question ---->
<!-- script ckeditor untuk menampilkan dan mendelete gambar diserver setelah user menghapus gambar di editor --->
{{-- <script src="{{ asset('js/Features/soal-pembahasan/bank-soal/edit-question-ckeditor.js') }}"></script> --}}

<!--- PUSHER LISTENER ---->
<script src="{{ asset('js/pusher-listener/soal-pembahasan/bank-soal-edit-question.js') }}"></script> <!--- pusher listener update soal ---->
