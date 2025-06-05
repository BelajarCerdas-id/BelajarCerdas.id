@include('components/sidebar_beranda', ['headerSideNav' => 'Laporan'])

@if (Auth::user()->role === 'Mentor')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            <section>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="border bg-white shadow-lg rounded-md h-50 p-4">
                        <span class="text-sm font-bold opacity-70">Menerima Pertanyaan</span>
                        <div class="border-b-[3px] border-[--color-default] w-20"></div>

                        <div class="flex flex-col items-center justify-center h-full">
                            <span class="text-sm font-bold opacity-70">Total</span>
                            <span class="text-lg font-bold opacity-70">{{ $countDataTanyaMentor ?? 0 }} Pertanyaan</span>
                        </div>
                    </div>
                    <div class="border bg-white shadow-lg rounded-md h-50 p-4">
                        <i class="fa-solid fa-money-bills text-green-500"></i>
                        <span class="text-sm font-bold opacity-70">Pendapatan Tanya</span>
                        <div class="border-b-[3px] border-green-300 w-20"></div>

                        <div class="flex flex-col items-center justify-center h-full">
                            <span class="text-sm font-bold opacity-70">Total</span>
                            <span
                                class="text-lg font-bold opacity-70">{{ number_format($countPendapatanTanyaMentor ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="border bg-white shadow-lg rounded-md h-50 p-4">
                        <i class="fa-solid fa-code text-md text-[#4189E0]"></i>
                        <span class="text-sm font-bold opacity-70">Referral User</span>
                        <div class="border-b-[3px] border-[#4189E0] w-20"></div>

                        <div class="flex flex-col items-center justify-center h-full">
                            <span class="text-sm font-bold opacity-70">Total Pengguna</span>
                            <span class="text-lg font-bold opacity-70">{{ $countReferralCodeUserMentor ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </section>
            <!--- batch pembayaran tanya ---->
            <section>
                <div class="overflow-x-auto mt-8 pb-20">
                    <span class="text-lg font-bold opacity-70">Batch Pembayaran</span>
                    <table id="tableReportPaymentTanyaMentor" class="table mt-4">
                        <thead id="thead-table-report-payment-tanya-mentor" class="hidden">
                            <tr>
                                <th class="!text-center border border-gray-300 w-10">
                                    No. Batch
                                </th>
                                <th class="!text-center border border-gray-300">
                                    Jumlah yang akan dibayarkan
                                </th>
                                <th class="!text-center border border-gray-300">
                                    Status Pembayaran
                                </th>
                                <th class="!text-center border border-gray-300">
                                    Batch Detail
                                </th>
                            </tr>
                        </thead>

                        <tbody id="tbodyReportPaymentTanyaMentor">
                            <!-- show data in ajax -->
                        </tbody>
                    </table>

                    <div class="pagination-container-report-payment-tanya-mentor flex justify-center my-4 sm:my-0">
                    </div>

                    <div id="emptyMessage-riwayat-report-payment-tanya-mentor"
                        class="w-full h-96 hidden bg-white shadow-lg rounded-md border">
                        <span class="w-full h-full flex items-center justify-center">
                            Tidak ada batch pembayaran.
                        </span>
                    </div>
                </div>
            </section>
        </div>
    </div>
@else
    <div class="flex flex-col min-h-screen items-center justify-center">
        <p>ALERT SEMENTARA</p>
        <p>You do not have access to this pages.</p>
    </div>
@endif

<script src="{{ asset('js/report-user/paginate-report-tanya-mentor-ajax.js') }}"></script>

<script>
    // untuk mendengarkan event broadcast untuk tracking data pembayaran dan masuk ke laporan mentor
    document.addEventListener("DOMContentLoaded", function() {
        window.Echo.channel('paymentTanyaMentor')
            .listen('.payment.tanya.mentor', (e) => {
                paginateReportPaymentTanyaMentor();
            })
    })
</script>
