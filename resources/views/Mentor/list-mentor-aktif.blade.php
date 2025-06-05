@include('components/sidebar_beranda', ['headerSideNav' => 'Mentor Aktif'])

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            @if (session('success-update-data-mentor'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-update-data-mentor'),
                ])
            @endif
            <main>
                <section class="bg-white shadow-lg rounded-lg p-4 border border-gray-200 h-60">
                    <span class="text-xl font-bold mb-4 opacity-60">List Mentor Aktif</span>
                    @if ($dataMentorAktif->isNotEmpty())
                        <div class="overflow-x-auto mt-4 pb-20">
                            <table class="table" id="filterTable">
                                <thead class="thead-table">
                                    <tr>
                                        <th class="th-table">No</th>
                                        <th class="th-table">Nama Lengkap</th>
                                        <th class="th-table">no_hp</th>
                                        <th class="th-table">Status Pendidikan</th>
                                        <th class="th-table">Bidang</th>
                                        <th class="th-table">Jurusan</th>
                                        <th class="th-table">Tahun Lulus</th>
                                        <th class="th-table">Sekolah Mengajar</th>
                                        @foreach ($dataFeaturesRoles as $item)
                                            <th class="th-table">{{ $item->Features->nama_fitur }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody id="filterList">
                                    @foreach ($dataMentorAktif as $item)
                                        <tr>
                                            <td class="border text-center border-gray-300">{{ $loop->iteration }}</td>
                                            <td class="border border-gray-300">{{ $item->nama_lengkap }}</td>
                                            <td class="border border-gray-300">{{ $item->UserAccount->no_hp }}</td>
                                            <td class="border text-center border-gray-300">
                                                {{ $item->status_pendidikan }}
                                            </td>
                                            <td class="border border-gray-300">{{ $item->bidang }}</td>
                                            <td class="border border-gray-300">{{ $item->jurusan }}</td>
                                            <td class="border text-center border-gray-300">
                                                {{ $item->tahun_lulus ?? '-' }}
                                            </td>
                                            <td class="border border-gray-300">{{ $item->sekolah_mengajar ?? '-' }}</td>
                                            @foreach ($dataFeaturesRoles as $featureItem)
                                                <td class="!text-center border border-gray-300">
                                                    <label class="relative inline-flex items-center cursor-pointer">
                                                        <input type="checkbox" class="hidden peer toggle-mentor"
                                                            data-id-mentor="{{ $item->id }}"
                                                            data-feature-id="{{ $featureItem->feature_id }}"
                                                            @if (isset($statusMentorFeature[$item->id][$featureItem->feature_id]) &&
                                                                    $statusMentorFeature[$item->id][$featureItem->feature_id] === 'aktif') checked @endif>
                                                        <div
                                                            class="w-12 h-6 bg-gray-300 peer-checked:bg-green-500 rounded-full transition-colors duration-300 ease-in-out">
                                                        </div>
                                                        <div
                                                            class="absolute left-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300 ease-in-out peer-checked:translate-x-3">
                                                        </div>
                                                    </label>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-container-siswa"></div>
                            <div class="flex justify-center">
                                <span class="showMessage hidden absolute top-2/4">Tidak ada
                                    riwayat</span>
                            </div>
                        </div>
                    @else
                        <div class="flex justify-center items-center mt-8">
                            <span class="text-sm">Belum ada data mentor</span>
                        </div>
                    @endif
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

<script>
    $(document).ready(function() {
        $('.toggle-mentor').change(function() {
            let id = $(this).data('id-mentor');
            let feature_id = $(this).data('feature-id');
            let status_mentor = $(this).is(':checked') ? 'aktif' : 'tidak aktif';

            $.ajax({
                url: '/feature-active/mentor/' + id,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    status_mentor: status_mentor,
                    feature_id: feature_id // Kirim feature_id ke server
                },
                success: function(response) {
                    console.log(response.message);
                },
                error: function(xhr) {
                    alert('Gagal mengubah status.');
                    $(this).prop('checked', !$(this).is(':checked'));
                }
            });
        });
    });
</script>
