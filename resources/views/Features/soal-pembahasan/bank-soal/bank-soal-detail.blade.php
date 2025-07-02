@include('components/sidebar_beranda', [
    'linkBackButton' => route('bankSoal.view'),
    'backButton' => "<i class='fa-solid fa-chevron-left'></i>",
    'headerSideNav' => 'Bank Soal Detail',
]);

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[40px] md:mt-0">
        <div class="content-beranda">

            <!---- alert success edit question from ajax ---->
            <div id="alert-success-bank-soal-edit-question"></div>

            <main>
                <section>
                    <span class="text-lg font-bold opacity-70">LIST SOAL</span>
                    <!--- daftar list soal --->
                    <div id="container-bank-soal-detail" data-sub-bab="{{ $subBab }}"
                        data-sub-bab-id="{{ $subBabId }}">
                        <div id="grid-list-soal" class="container-accordion mb-8">
                            <!-- show data in ajax -->
                        </div>
                    </div>

                    <div class="pagination-container-bank-soal-detail flex justify-center my-4 sm:my-0"></div>

                    <div id="emptyMessageBankSoalDetail" class="w-full h-96 hidden">
                        <span class="w-full h-full flex items-center justify-center">
                            Tidak ada soal pada bank soal ini.
                        </span>
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


<script src="{{ asset('js/Features/soal-pembahasan/bank-soal/bank-soal-detail.js') }}"></script> <!--- bank soal detail ---->
<script src="{{ asset('js/accordion-soal.js') }}"></script> <!-- accordion script -->

<!--- PUSHER LISTENER ---->
<script src="{{ asset('js/pusher-listener/soal-pembahasan/bank-soal-detail.js') }}"></script> <!--- pusher listener insert bank soal and edit soal in bankSoal detail ---->
