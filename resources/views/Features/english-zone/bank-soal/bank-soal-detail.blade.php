@include('components/sidebar_beranda', [
    'linkBackButton' => route('EZ.bankSoal.view'),
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

                    <div class="mt-8 flex flex-col md:flex-row md:items-center md:justify-between gap-8">
                        <!--- search bar --->
                        <label class="input input-bordered flex items-center gap-2 w-66 md:w-max">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-70" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1111 3a7.5 7.5 0 015.65 13.65z" />
                            </svg>
                            <input id="search_question" type="search" class="grow text-sm"
                                placeholder="Cari soal..." />
                        </label>
                    </div>

                    <!--- daftar list soal --->
                    <div id="container-bank-soal-detail" data-level-id="{{ $levelId }}">
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


<script src="{{ asset('js/Features/english-zone/bank-soal/bank-soal-detail.js') }}"></script> <!--- bank soal detail ---->
<script src="{{ asset('js/accordion-soal.js') }}"></script> <!-- accordion script -->

<!--- PUSHER LISTENER ---->
<script src="{{ asset('js/pusher-listener/english-zone/bank-soal-detail.js') }}"></script> <!--- pusher listener insert bank soal and edit soal in bankSoal detail ---->
