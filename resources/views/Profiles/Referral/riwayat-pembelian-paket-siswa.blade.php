@include('components/sidebar_beranda', [
    'headerSideNav' => 'Riwayat Pembelian',
    'linkBackButton' => route('profile'),
    'backButton' => "<i class='fa-solid fa-chevron-left'></i>",
])

@if (Auth::user()->role === 'Mentor')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda mt-[120px]">

            <div id="container-student-referral-purchase-history" class="overflow-x-auto mt-8 pb-20"
                data-referral-code="{{ $kode_referral }}">
                <!----- Header ----->
                <header class="text-lg font-bold opacity-70">Riwayat Pembelian Paket Siswa Referral</header>
                <!----- Table ----->
                <table id="table-student-referral-purchase-history" class="table mt-4">
                    <thead class="thead-table-student-referral-purchase-history hidden">
                        <tr>
                            <th class="!text-center border border-gray-300 w-10" rowspan="2">
                                No.
                            </th>
                            <th class="!text-center border border-gray-300" rowspan="2">
                                Nama Siswa
                            </th>
                            <th class="!text-center border border-gray-300" rowspan="2">
                                Paket Fitur
                            </th>
                            <th class="!text-center border border-gray-300" rowspan="2">
                                Jenis Paket
                            </th>
                            <th class="!text-center border border-gray-300" colspan="2">
                                Masa Berlaku Paket
                            </th>
                        </tr>
                        <tr>
                            <th class="!text-center border border-gray-300">
                                Aktif
                            </th>
                            <th class="!text-center border border-gray-300">
                                Berakhir
                            </th>
                        </tr>
                    </thead>

                    <tbody id="tbody-student-referral-purchase-history">
                        <!-- show data in ajax -->
                    </tbody>
                </table>

                <div class="pagination-container-student-referral-purchase-history flex justify-center my-4 sm:my-0">
                </div>

                <div id="emptyMessage-student-referral-purchase-history"
                    class="w-full h-96 hidden bg-white shadow-lg rounded-md border">
                    <span class="w-full h-full flex items-center justify-center">
                        Tidak ada riwayat pembelian siswa pada referral kamu.
                    </span>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="flex flex-col min-h-screen items-center justify-center">
        <p>ALERT SEMENTARA</p>
        <p>You do not have access to this pages.</p>
    </div>
@endif

<script src="{{ asset('js/report-user/referral/paginate-student-referral-purchase-history-ajax.js') }}"></script>
