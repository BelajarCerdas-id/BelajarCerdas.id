@include('components/sidebar_beranda', ['headerSideNav' => 'Mentor Tanya'])

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            <span class="text-lg font-bold opacity-70">Data Mentor Tanya</span>
            <div id="grid-list-mentor-tanya" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- cards akan di-append via AJAX --}}
            </div>

            <div class="pagination-container-list-mentor-tanya flex justify-center mt-4"></div>

            <div class="flex justify-center">
                <span class="noDataMessageListMentorTanya hidden text-gray-500">Tidak ada riwayat</span>
            </div>
            {{-- <div class="grid grid-cols-1  lg:grid-cols-2 gap-6 border">
                @foreach ($getMentor as $group => $item)
                    <div class="w-full bg-white shadow-lg rounded-lg h-32 flex items-center justify-between px-4">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-user-circle text-4xl"></i>
                            <div class="flex flex-col">
                                {{ $item->Mentor->MentorProfiles->nama_lengkap ?? '' }}
                                Soal menunggu
                                verified</span>
                            </div>
                        </div>
                        <a href="" class="text-[#4189e0] font-bold">Lihat Detail</a>
                    </div>
                    @endforeach
            </div> --}}
        </div>
    </div>
@else
    <div class="flex flex-col min-h-screen items-center justify-center">
        <p>ALERT SEMENTARA</p>
        <p>You do not have access to this pages.</p>
    </div>
@endif

<script src="js/Tanya/payment-mentor/list-mentor-tanya-ajax.js"></script>
<script src="js/Tanya/payment-mentor/verification-tanya-mentor-ajax.js"></script>


<script>
    // mendengarkan event broadcast menghitung pertanyaan baru yang belum di verifikasi admin
    document.addEventListener("DOMContentLoaded", () => {
        window.Echo.channel('CountMentorQuestionsAwaitVerification')
            .listen('.count.mentor.questions.await.verification', (e) => {
                paginateListMentorTanya();
            });
    });
</script>
