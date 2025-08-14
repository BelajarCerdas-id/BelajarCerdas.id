@include('components/sidebar_beranda', ['headerSideNav' => 'Pembelian'])

@if (Auth::user()->role === 'Siswa')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">

            <div class="w-full h-26 bg-[#153569] rounded-lg flex items-center">
                <div class="bg-[#FFE588] w-20 h-full flex items-center justify-center rounded-r-full">
                    <i class="fa-solid fa-cart-shopping text-2xl text-[--color-second]"></i>
                </div>
                <div class="text-white font-bold text-lg ml-4">
                    <span>PEMBELIAN</span>
                </div>
            </div>

            <!---- FEATURES ------>
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mt-14">
                @foreach ($features as $item)
                    <article class="border h-[600px] rounded-lg relative">
                        <!-- Gambar fitur -->
                        <figure class="border-b border-gray-200 h-32">
                            <img src="{{ $descriptionsFeatures[$item->nama_fitur]['image_feature'] ?? '' }}"
                                loading="lazy" alt="Logo Fitur {{ $item->nama_fitur }}"
                                class="w-full h-full object-contain pointer-events-none">
                        </figure>

                        <!-- Nama fitur -->
                        <span
                            class="font-bold opacity-70 h-10 flex items-center justify-center border-b">{{ $item->nama_fitur }}</span>

                        <!-- Deskripsi produk -->
                        <section class="px-4 flex flex-col gap-4 overflow-y-auto max-h-69 overflow-hidden">
                            @foreach ($descriptionsFeatures[$item->nama_fitur]['descriptions'] ?? [] as $desc)
                                <span class="inline-flex gap-1 text-sm">
                                    <i class="fa-solid fa-circle-check text-green-500 relative top-[2px]"></i>
                                    <span>{{ $desc }}</span>
                                </span>
                            @endforeach
                        </section>

                        <!-- Harga dan tombol -->
                        <div
                            class="absolute bottom-4 w-full flex flex-col gap-4 border-t-[3px] border-gray-200 border-dashed">
                            <div class="flex flex-col items-center mt-4">
                                <span class="text-xs font-bold opacity-70">Mulai dari</span>
                                <span
                                    class="text-sm font-bold opacity-70">{{ $descriptionsFeatures[$item->nama_fitur]['price'] ?? '' }}</span>
                            </div>
                            <!-- kalau english zone dan soal pembahasan suda ada, if else nya hapus saja, soalnya list features price harus dibikin duluan padahal fitur nya blm siap -->
                            @if ($item->nama_fitur == 'TANYA' || $item->nama_fitur == 'Soal dan Pembahasan')
                                <a href="{{ route('paymentFeaturesView', $item->nama_fitur) }}"
                                    class="w-full flex justify-center" aria-label="{{ $item->nama_fitur }}">
                                    <button
                                        class="bg-[--color-second] w-[90%] rounded-full h-[32px] text-white font-bold text-xs md:text-sm">
                                        {{ $descriptionsFeatures[$item->nama_fitur]['textButton'] ?? '' }}
                                    </button>
                                </a>
                            @else
                                <a href="{{ route('featuresStore') }}" class="w-full flex justify-center"
                                    aria-label="{{ $item->nama_fitur }}">
                                    <button
                                        class="bg-[--color-second] w-[90%] rounded-full h-[32px] text-white font-bold text-xs md:text-sm">
                                        {{ $descriptionsFeatures[$item->nama_fitur]['textButton'] ?? '' }}
                                    </button>
                                </a>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
@else
    <div class="flex flex-col min-h-screen items-center justify-center">
        <p>ALERT SEMENTARA</p>
        <p>You do not have access to this pages.</p>
    </div>
@endif
