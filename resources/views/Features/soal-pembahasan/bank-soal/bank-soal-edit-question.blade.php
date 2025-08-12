@include('components/sidebar_beranda', [
    'linkBackButton' => route('bankSoal.detail.view', [$subBabId, $id]),
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
                    <div id="editor-container" data-sub-bab-id="{{ $subBabId }}" data-question-id="{{ $id }}"
                        data-upload-url="{{ route('soalPembahasan.editImage', ['_token' => csrf_token()]) }}"
                        data-delete-url="{{ route('soalPembahasan.deleteImage') }}">
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

<script src="{{ asset('js/Features/soal-pembahasan/bank-soal/form-action-edit-question.js') }}"></script> <!--- form action edit question ---->
<!-- script ckeditor untuk menampilkan dan mendelete gambar diserver setelah user menghapus gambar di editor --->
{{-- <script src="{{ asset('js/Features/soal-pembahasan/bank-soal/edit-question-ckeditor.js') }}"></script> --}}

<!--- PUSHER LISTENER ---->
<script src="{{ asset('js/pusher-listener/soal-pembahasan/bank-soal-edit-question.js') }}"></script> <!--- pusher listener update soal ---->
