@include('components/sidebar_beranda', [
    'headerSideNav' => 'Edit',
    'linkBackButton' => route('EZ.managementBankSoalQuiz.view', [$level_id, $passage_id, $passage_type]),
    'backButton' => "<i class='fa-solid fa-chevron-left'></i>",
]);

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            <!--- alert succes after success insert quiz questions ----->
            <div id="alert-success-bank-soal-edit-question"></div>

            <main>
                <section>
                    <div id="editor-container" data-level-id="{{ $level_id }}" data-passage-id="{{ $passage_id }}" data-passage-type="{{ $passage_type }}" data-question-id="{{ $question_id }}"
                        data-upload-url="{{ route('englishZone.editImage', ['_token' => csrf_token()]) }}"
                        data-delete-url="{{ route('englishZone.deleteImage') }}">
                        <!---- form in ajax ---->
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

<script src="{{ asset('js/Features/english-zone/management-quiz/form-action-edit-quiz-question.js') }}"></script> <!--- form action edit quiz question ---->

<!--- COMPONENTS ---->
<script src="{{ asset('js/components/clear-error-on-input.js') }}"></script> <!--- clear error on input ---->
<script src="{{ asset('js/components/preview/word-upload-preview.js') }}"></script> <!--- show word ---->
<script src="{{ asset('js/accordion-soal.js') }}"></script> <!-- accordion script -->

<!--- PUSHER LISTENER ---->
<script src="{{ asset('js/pusher-listener/english-zone/bank-soal-quiz-edit.js') }}"></script> <!--- pusher listener update soal ---->