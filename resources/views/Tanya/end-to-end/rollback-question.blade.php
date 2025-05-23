@include('components/sidebar_beranda', ['headerSideNav' => 'List Pertanyaan'])

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            <header class="text-lg font-bold opacity-70">LIST PERTANYAAN</header>

            <div class="w-full h-auto bg-white rounded-lg shadow-lg py-4 border">
                <div class="overflow-x-auto m-4">
                    <table class="table" id="tableTanyaRollback">
                        <thead class="thead-table-tanya-rollback hidden">
                            <tr>
                                <th class="th-table">No</th>
                                <th class="th-table">Nama Siswa</th>
                                <th class="th-table">Kelas</th>
                                <th class="th-table">Pertanyaan</th>
                                <th class="th-table">Mata Pelajaran</th>
                                <th class="th-table">Bab</th>
                                <th class="th-table">Jam_Tanya</th>
                                <th class="th-table">Status Soal</th>
                                <th class="th-table">Viewed By</th>
                                <th class="th-table">Rollback Action</th>
                            </tr>
                        </thead>
                        <tbody id="tableListTanyaRollback">
                            {{-- show data in ajax --}}
                        </tbody>
                    </table>

                    <div class="pagination-container-tanya-rollback flex justify-center my-4 sm:my-0"></div>

                    <div id="emptyMessageTanyaRollback" class="w-full h-96 hidden">
                        <span class="w-full h-full flex items-center justify-center">
                            Tidak ada pertanyaan.
                        </span>
                    </div>
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

<script src="js/Tanya/list-pertanyaan-rollback-ajax.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dengar event broadcast soal update status viewed
        window.Echo.channel('tanya')
            .listen('.question.created', (e) => {
                console.log('⚡️ Broadcast diterima di admin rollback:', e);

                // Refresh data rollback otomatis
                fetchFilteredDataTanyaRollback();
            });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.Echo.channel('tanya')
            .listen('.question.rollback', (e) => {
                // Mendengarkan event broadcast untuk menerima riwayat soal (diterima & ditolak)
                fetchFilteredDataTanyaRollback();
            })
    })
</script>
