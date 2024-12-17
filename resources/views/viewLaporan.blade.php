@include('components/sidebar_beranda')
@extends('components/sidebar_beranda_mobile')
@if (isset($user))
    @if ($user->status === 'Team Leader')
        <div class="home-beranda">
            <div class="content-beranda">
                <header class="text-2xl mb-8 font-bold">Laporan Mentor</header>
                <ul class="bg-white shadow-lg w-[450px] h-40 p-4 mb-8 rounded-lg">
                    <li>
                        <span class="text-sm">Nama Lengkap</span> :
                        <span class="text-sm">{{ $mentor->nama_lengkap }}</span>
                    </li>
                    <li>
                        <span class="pr-4 text-sm">Asal Sekolah</span> :
                        <span class="text-sm">{{ $mentor->sekolah }}</span>
                    </li>
                    <li class="flex gap-4 mt-4">
                        {{-- <div class="">
                            <span>Jumlah</span><br>
                            <i class="fas fa-circle text-xs text-[--color-default]"></i>
                            {{ $getLaporan->count() }}
                        </div> --}}
                        <div class="text-center">
                            <span class="text-sm">Soal Diterima</span><br>
                            <i class="fas fa-circle-check text-md text-success"></i>
                            <span class="text-md"> {{ $dataAccept->count() }} </span>
                        </div>
                        <div class="text-center">
                            <span class="text-sm">Soal Ditolak</span><br>
                            <i class="fas fa-circle-xmark text-md text-error"></i>
                            <span class="text-md"> {{ $dataReject->count() }} </span>
                        </div>
                        <div class="text-center">
                            <span class="text-sm">Accepted</span><br>
                            <i class="fas fa-circle-check text-md text-success"></i>
                            <span class="text-md"> {{ $validatedMentorAccepted->count() }} </span>
                        </div>
                        <div class="text-center">
                            <span class="text-sm">Rejected</span><br>
                            <i class="fas fa-circle-xmark text-md text-error"></i>
                            <span class="text-md"> {{ $validatedMentorRejected->count() }} </span>
                        </div>
                    </li>
                </ul>

                {{-- tanpa ajax(jangan dihapus) --}}
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Mata Pelajaran</th>
                                <th>Bab</th>
                                <th>Pertanyaan</th>
                                <th>Jawaban</th>
                                <th>Status</th>
                                <th>Detail</th>
                                <th class="colspan-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($getLaporan as $item)
                                <tr>
                                    <td>{{ $item->nama_lengkap }}</td>
                                    <td>{{ $item->kelas }}</td>
                                    <td>{{ $item->mapel }}</td>
                                    <td>{{ $item->bab }}</td>
                                    <td>{{ Str::limit($item->pertanyaan, 100) }}</td>
                                    <td>{{ $item->jawaban }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td>Lihat</td>

                                    <td class="flex gap-4">
                                        {{-- Mengecek apakah dalam koleksi $statusStar terdapat entri dengan kunci yang sesuai dengan $item->id. --}}
                                        @if (isset($statusStar[$item->id]))
                                            {{-- mengecek apakah dalam koleksi $statusStar terdapat entri dengan kunci yang sesuai dengan $item->id., dan mengecek dengan status Diterima --}}
                                            @if ($statusStar[$item->id]->status === 'Diterima')
                                                <button
                                                    class="text-success font-bold bg-green-200 p-2 w-[165px] rounded-lg cursor-default">{{ $statusStar[$item->id]->status }}</button>
                                            @elseif($statusStar[$item->id]->status === 'Ditolak')
                                                <button
                                                    class="text-error font-bold bg-red-300 p-2 w-[165px] rounded-lg cursor-default">{{ $statusStar[$item->id]->status }}</button>
                                            @endif
                                            {{-- Jika status ada di tabel Star, tampilkan statusnya --}}
                                        @else
                                            {{-- Jika status tidak ada, tampilkan tombol Terima dan Tolak --}}
                                            <form action="{{ route('star.store') }}" method="POST" class="inline">
                                                @csrf
                                                <div class="hidden">
                                                    {{-- input ini untuk mengirimkan parameter page, jika parameter page tidak ada maka akan default ke halaman 1 --}}
                                                    <input type="text" name="page"
                                                        value="{{ request()->query('page', 1) }}">
                                                    <input type="text" name="id" value="{{ $mentor->id }}">
                                                    <input type="text" name="id_tanya" value="{{ $item->id }}">
                                                    <input type="text" name="nama_mentor"
                                                        value="{{ $mentor->nama_lengkap }}">
                                                    <input type="text" name="email" value="{{ $mentor->email }}">
                                                    <input type="text" name="sekolah"
                                                        value="{{ $mentor->sekolah }}">
                                                    <input type="text" name="status" value="Diterima">
                                                </div>
                                                <form action="{{ route('star.store') }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-success !text-white">Terima</button>
                                                </form>
                                            </form>

                                            <form action="{{ route('star.store') }}" method="POST" class="inline">
                                                @csrf
                                                <div class="hidden">
                                                    {{-- input ini untuk mengirimkan parameter page, jika parameter page tidak ada maka akan default ke halaman 1 --}}
                                                    <input type="text" name="page"
                                                        value="{{ request()->query('page', 1) }}">
                                                    <input type="text" name="id" value="{{ $mentor->id }}">
                                                    <input type="hidden" name="id_tanya" value="{{ $item->id }}">
                                                    <input type="text" name="nama_mentor"
                                                        value="{{ $mentor->nama_lengkap }}">
                                                    <input type="text" name="email" value="{{ $mentor->email }}">
                                                    <input type="text" name="sekolah"
                                                        value="{{ $mentor->sekolah }}">
                                                    <input type="text" name="status" value="Ditolak">
                                                </div>
                                                <form action="{{ route('star.store') }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-error !text-white">Tolak</button>
                                                </form>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="">{{ $getLaporan->links() }}</div>
                {{-- menggunakan ajax --}}
                {{-- <select name="" id="statusFilter"
                    class="w-[150px] h-10 rounded-lg flex justify-center items-center px-2 border-[1px] outline-none text-sm cursor-pointer bg-white">
                    <div class="border-none">
                        <option value="" class="hidden">Filter Data</option>
                        <option value="semua">Lihat Semua</option>
                        <option value="Diterima">Diterima</option>
                        <option value="Ditolak">Ditolak</option>
                    </div>
                </select> --}}
                {{-- <div class="overflow-x-auto">
                    <table class="table" id="tableViewLaporanTL">
                        <thead>
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Mata Pelajaran</th>
                                <th>Bab</th>
                                <th>Pertanyaan</th>
                                <th>Jawaban</th>
                                <th>Status</th>
                                <th>Detail</th>
                                <th class="colspan-2">Action</th>
                            </tr>
                        </thead>
                        <tbody id="filterListViewLaporanTL">

                        </tbody>
                    </table>
                </div>
                <div class="pagination-container-viewLaporan-TL"></div> --}}
            </div>
        </div>
    @elseif($user->status === 'XR')
        <div class="home-beranda">
            <div class="content-beranda">
                <header class="text-2xl mb-8 font-bold">Laporan Mentor</header>
                <ul class="bg-white shadow-lg w-[450px] h-40 p-4 mb-8 rounded-lg">
                    <li>
                        <span class="text-sm">Nama Lengkap</span> :
                        <span class="text-sm">{{ $mentor->nama_lengkap }}</span>
                    </li>
                    <li>
                        <span class="pr-3.5 text-sm">Asal Sekolah</span> :
                        <span class="text-sm">{{ $mentor->sekolah }}</span>
                    </li>
                    <li class="flex gap-4 mt-4">
                        <div class="text-center">
                            <span class="text-sm">Dibayar</span><br>
                            <i class="fas fa-circle-check text-md text-success"></i>
                            <span class="text-md"> {{ $paidBatchCount }} </span>
                        </div>
                        <div class="text-center">
                            <span class="text-sm">Perlu Dibayar</span><br>
                            <i class="fas fa-circle-xmark text-md text-error"></i>
                            <span class="text-md"> {{ $unpaidBatchCount }} </span>
                        </div>
                        <div class="text-center">
                            <span class="text-sm">Menunggu...</span><br>
                            <i class="fas fa-circle-xmark text-md text-error"></i>
                            <span class="text-md"> {{ $waitingBatchCount }} </span>
                        </div>
                    </li>
                </ul>
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr class="text-center">
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Asal Mengajar</th>
                                <th>Jumlah Diterima</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tableData as $item)
                                <tr class="text-center">
                                    <td>{{ $item['nama_mentor'] }}</td>
                                    <td>{{ $item['email'] }}</td>
                                    <td>{{ $item['sekolah'] }}</td>
                                    <td>{{ $item['count'] }}</td>
                                    <td>

                                        @if ($item['payment_status'] === 'paid' && $item['kode_payment'] === 'YA' && $item['count'] == 3)
                                            <!-- Tombol Pay untuk batch yang belum dibayar -->
                                            <button
                                                class="text-success bg-green-200 p-2 w-32 rounded-lg cursor-default">PAID</button>
                                        @elseif($item['payment_status'] === 'pay' && $item['kode_payment'] === 'TIDAK' && $item['count'] == 3)
                                            <!-- Tombol PAID jika sudah dibayar -->
                                            <form
                                                action="{{ route('starPayment.update', ['email' => $item['email'], 'batch' => $item['batch']]) }}"
                                                method="POST">
                                                @csrf
                                                {{-- ini penting kalo pada saat mengirim data di view{id} lalu redirect nya ke view{id} tersebut --}}
                                                <input type="hidden" name="id" value="{{ $mentor->id }}">
                                                <input type="" name="page" class="border-4"
                                                    value="{{ request()->query('page', 1) }}">
                                                <button type="submit"
                                                    class="text-white bg-green-500 p-2 w-32 rounded-lg font-bold">Pay</button>

                                            </form>
                                        @else
                                            <!-- Tombol non-aktif jika tidak bisa membayar -->
                                            <button
                                                class="text-white bg-gray-400 p-2 w-32 rounded-lg font-bold cursor-default"
                                                disabled>Pay</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="">{{ $data->links() }}</div>
            </div>
        </div>
    @else
        <div class="flex flex-col min-h-screen items-center justify-center">
            <p>ALERT SEMENTARA</p>
            <p>You do not have access to this pages.</p>
        </div>
    @endif
@else
    <p>You are not logged in.</p>
@endif


<script src="../js/viewLaporan-mentor-tl-ajax.js"></script>
{{-- <script src="../js/test-ajax.js"></script> --}}
