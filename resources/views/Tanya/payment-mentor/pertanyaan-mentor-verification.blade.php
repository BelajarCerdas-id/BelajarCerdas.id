@include('components/sidebar_beranda', [
    'linkBackButton' => route('tanya.mentor'),
    'backButton' => "<i class='fa-solid fa-chevron-left'></i>",
    'headerSideNav' => 'Tanya Verification',
])

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            <main>
                <section>
                    <div id="question-verification-container" class="overflow-x-auto mt-8 pb-20"
                        data-mentor-id="{{ $id }}">
                        <table id="tableTanyaVerification" class="table">
                            <thead id="thead-table-tanya-verification" class="hidden">
                                <tr>
                                    <th class="!text-center border border-gray-300">
                                        No
                                    </th>
                                    <th class="!text-center border border-gray-300">
                                        Pertanyaan
                                    </th>
                                    <th class="!text-center border border-gray-300">
                                        Fase
                                    </th>
                                    <th class="!text-center border border-gray-300">
                                        Kelas
                                    </th>
                                    <th class="!text-center border border-gray-300">
                                        Mata Pelajaran
                                    </th>
                                    <th class="!text-center border border-gray-300">
                                        Bab
                                    </th>
                                    <th class="!text-center border border-gray-300">
                                        Lihat Soal
                                    </th>
                                    <th class="!text-center border border-gray-300">
                                        Status Verifikasi
                                    </th>
                                    <th class="!text-center border border-gray-300">
                                        Action Verifikasi
                                    </th>
                                </tr>
                            </thead>

                            <tbody id="tableQuestionVerification">
                                <!-- show data in ajax -->

                            </tbody>
                        </table>

                        <div class="pagination-container-question-verification-mentor flex justify-center my-4 sm:my-0">
                        </div>

                        <div id="emptyMessage-riwayat-question-verification"
                            class="w-full h-96 hidden bg-white shadow-lg rounded-md border">
                            <span class="w-full h-full flex items-center justify-center">
                                Tidak ada pertanyaan untuk di verifikasi.
                            </span>
                        </div>
                    </div>

                    <!-- modal (open by button ajax) --->
                    <dialog id="my_modal_1" class="modal">
                        <div class="modal-box bg-white w-max">
                            <form id="form-tolak-soal">
                                <span class="text-xl font-bold flex justify-center">Tolak Soal</span>
                                <div class="mt-4 w-96">
                                    <label class="text-sm">Alasan Penolakan</label>
                                    <textarea type="text" id="alasan_verifikasi_ditolak" name="alasan_verifikasi_ditolak"
                                        class="w-full bg-white shadow-lg h-32 border-gray-200 border-[1px] outline-none rounded-md text-xs p-4 resize-none"
                                        placeholder="Masukkan alasan dan uraian penolakan"></textarea>
                                    <div id="error-alasan-ditolak" class="text-red-500 text-xs mt-1 font-bold"></div>
                                </div>
                                <div class="flex justify-end mt-8">
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all">
                                        Tolak
                                    </button>
                                </div>
                            </form>
                        </div>
                        <form method="dialog" class="modal-backdrop">
                            <button>close</button>
                        </form>
                    </dialog>
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

<script src="../../js/Tanya/payment-mentor/verification-tanya-mentor-ajax.js"></script>

<script>
    // untuk mendengarkan event broadcast ketika admin memverifikasi diterima atau ditolak soal mentor
    document.addEventListener("DOMContentLoaded", () => {
        window.Echo.channel('TanyaMentorVerifications')
            .listen('.tanya.mentor.verifications', (e) => {
                paginateQuestionVerificationMentor();
            });
    });
</script>
