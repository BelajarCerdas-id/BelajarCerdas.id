@include('components/sidebar_beranda', [
    'linkBackButton' => route('report-mentor'),
    'backButton' => "<i class='fa-solid fa-chevron-left'></i>",
    'headerSideNav' => 'Batch Detail',
])

@if (Auth::user()->role === 'Mentor')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            <main>
                <!--- batch detail pembayaran mentor --->
                <section>
                    <div id="container-batch-detail-payment-mentor" class="overflow-x-auto mt-8 pb-20"
                        data-payment-mentor-id="{{ $id }}">
                        <span class="text-lg font-bold opacity-70">Batch Detail Pembayaran</span>
                        <table id="tableReportBatchDetailPaymentMentor" class="table mt-4">
                            <thead class="thead-table-batch-detail-payment-mentor hidden">
                                <tr>
                                    <th class="!text-center border border-gray-300 w-10">
                                        No.
                                    </th>
                                    <th class="!text-center border border-gray-300">
                                        Jumlah
                                    </th>
                                    <th class="!text-center border border-gray-300">
                                        Sumber Pendapatan
                                    </th>
                                    <th class="!text-center border border-gray-300">
                                        Tanggal
                                    </th>
                                </tr>
                            </thead>

                            <tbody id="tbodyBatchDetailPaymentMentor">
                                <!-- show data in ajax -->
                            </tbody>
                        </table>

                        <div class="pagination-container-batch-detail-payment-mentor flex justify-center my-4 sm:my-0">
                        </div>

                        <div id="emptyMessage-riwayat-batch-detail-payment-mentor"
                            class="w-full h-96 hidden bg-white shadow-lg rounded-md border">
                            <span class="w-full h-full flex items-center justify-center">
                                Tidak ada batch detail pembayaran.
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

<script src="{{ asset('js/report-user/paginate-batch-detail-pembayaran-mentor-ajax.js') }}"></script>
