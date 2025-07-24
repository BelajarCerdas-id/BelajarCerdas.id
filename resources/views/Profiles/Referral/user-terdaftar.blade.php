@include('components/sidebar_beranda', [
    'headerSideNav' => 'Referral User',
    'linkBackButton' => route('profile'),
    'backButton' => "<i class='fa-solid fa-chevron-left'></i>",
])

@if (Auth::user()->role === 'Mentor')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda mt-[120px]">

            <div id="container-paginate-referral-student-list" class="overflow-x-auto mt-8 pb-20"
                data-referral-code="{{ $kode_referral }}">
                <!----- Header ----->
                <header class="text-lg font-bold opacity-70">Siswa Terdaftar</header>
                <!----- Table ----->
                <table id="table-paginate-referral-student-list" class="table mt-4">
                    <thead class="thead-table-paginate-referral-student-list hidden">
                        <tr>
                            <th class="!text-center border border-gray-300 w-10">
                                No.
                            </th>
                            <th class="!text-center border border-gray-300">
                                Nama Siswa
                            </th>
                            <th class="!text-center border border-gray-300">
                                Tanggal Bergabung
                            </th>
                        </tr>
                    </thead>

                    <tbody id="tbody-paginate-referral-student-list">
                        <!-- show data in ajax -->
                    </tbody>
                </table>

                <div class="pagination-container-paginate-referral-student-list flex justify-center my-4 sm:my-0">
                </div>

                <div id="emptyMessage-paginate-referral-student-list"
                    class="w-full h-96 hidden bg-white shadow-lg rounded-md border">
                    <span class="w-full h-full flex items-center justify-center">
                        Tidak ada user yang terdaftar pada referral kamu.
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

<script src="{{ asset('js/report-user/referral/paginate-referral-student-list-ajax.js') }}"></script>
