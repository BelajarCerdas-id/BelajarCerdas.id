<x-sidebar_beranda></x-sidebar_beranda>
@if (isset($user))
    @if ($user->status === 'Siswa')
        <div class="home-beranda">
            <div class="content">
                <div class="navbar-beranda">
                    <header>Beranda</header>
                    <!-- <div class="information-account">
                <div class="notification">
                <i class="fa-solid fa-bell"></i>
            </div>
            <div class="coin">
                <i class="fa-solid fa-coins"></i>
            </div> -->
                    <div class="profile">
                        <i class="fa-solid fa-user"></i>
                        <div class="information-profile">
                            <span class="name">{{ $user->nama_lengkap }}</span><br>
                            <span class="class">{{ $user->kelas }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="home-beranda">
            <div class="content-beranda">
                <div class="bg-[--color-default] w-full h-20 shadow-lg rounded-t-xl flex items-center pl-10 mb-6">
                    <div class="text-white font-bold flex items-center gap-4">
                        <i class="fa-solid fa-file-lines text-4xl"></i>
                        <span class="text-xl">Catatan</span>
                    </div>
                </div>

                <div class="flex gap-6">
                    <select name="" id="kelasFilter"
                        class="w-[150px] h-10 rounded-lg flex justify-center items-center px-2 border-[1px] outline-none text-xs cursor-pointer mb-6 bg-white">
                        <div class="border-none">
                            <option value="" class="hidden">Pilih Kelas</option>
                            <option value="Kelas 1">Kelas 1</option>
                            <option value="Kelas 2">Kelas 2</option>
                        </div>
                    </select>

                    <select name="" id="mapelFilter"
                        class="w-[150px] h-10 rounded-lg flex justify-center items-center px-2 border-[1px] outline-none text-xs cursor-pointer mb-6 bg-white">
                        <div class="border-none">
                            <option value="" class="hidden">Pilih Mata Pelajaran</option>
                        </div>
                    </select>
                </div>

                {{-- <div class="grid grid-cols-4 gap-8">
                    @foreach ($catatan as $item)
                        <div class="bg-white border-[1px] h-28 text-sm flex items-center gap-4 relative">
                            <div class="ml-4"
                                onclick="showImage('{{ asset('images_catatan/' . $item->image_catatan) }}')">
                                <img src="{{ asset('images_catatan/' . $item->image_catatan) }}" alt=""
                                    class="h-20 w-[100px] bg-cover bg-fit cursor-pointer">
                            </div>
                            <div class="">
                                <div class="text-xs leading-6 flex flex-col">
                                    <span>{{ $item->kelas }}</span>
                                    <span>{{ $item->mapel }}</span>
                                    <span>{{ $item->bab }}</span>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-user text-[--color-default]"></i>
                                        <span class="text-xs">{{ $item->nama_lengkap }}</span>
                                    </div>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right absolute right-4 text-[--color-default] font-bold cursor-pointer"
                                onclick="showData(this)"
                                data-image="{{ asset('images_catatan/' . $item->image_catatan) }}"
                                data-catatan="{{ $item->catatan }}"></i>
                        </div>
                        {{-- {{ $catatan->links() }} --}
                    @endforeach
                </div> --}}

                @if (isset($catatan) && is_iterable($catatan) && $catatan->isNotEmpty())
                    <div class="" id="filterTableNote">
                        <div class="flex flex-wrap justify-evenly gap-8 border-4" id="filterListNote">
                            {{-- show data in ajax --}}
                        </div>
                    </div>
                    <div class="pagination-container-catatan"></div>
                    <div class="pagination-container-mapel"></div>
            </div>
    @endif

    <dialog id="my_modal_5" class="modal">
        <div class="modal-box bg-white">
            <img id="modal_image" src="" alt="" class="object-cover w-full">
            <div class="flex flex-col mt-4" id="head-catatan">
                <span>Catatan</span>
                <div name="catatan" id="modal_catatan"
                    class="w-full h-20 overflow-y-auto bg-white border-[1px] outline-none rounded-lg text-sm p-2 resize-none shadow-md mt-2 leading-6">
                </div>
            </div>
            <div class="h-full w-full flex items-center justify-center" id="no_image" style="display: none;">
                <p>Gambar tidak tersedia</p>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <script>
        function showData(element) {
            const modal = document.getElementById('my_modal_5');
            const modalImage = document.getElementById('modal_image');
            const modalCatatan = document.getElementById('modal_catatan');
            const noImage = document.getElementById('no_image');
            const headCatatan = document.getElementById('head-catatan');

            // Ambil data dari atribut
            const imageSrc = element.getAttribute('data-image');
            const catatan = element.getAttribute('data-catatan');

            // Set gambar dan data
            modalImage.src = imageSrc;
            modalCatatan.textContent = catatan;

            // Cek apakah sumber gambar tidak kosong
            if (imageSrc) {
                modalImage.style.display = 'block';
                noImage.style.display = 'none';
            } else {
                modalImage.style.display = 'none';
                noImage.style.display = 'block';
            }

            // cek apakah sumber catatan tidak kosong
            if (catatan) {
                headCatatan.style.display = "block";
            } else {
                headCatatan.style.display = "none";
            }
            // Tampilkan dialog
            modal.showModal();
        }
    </script>
    </div>
    </div>
@elseif($user->status === 'Guru')
    <div class="home-beranda">
        <div class="content">
            <div class="navbar-beranda">
                <header>Beranda</header>
                <!-- <div class="information-account">
            <div class="notification">
                <i class="fa-solid fa-bell"></i>
            </div>
            <div class="coin">
                <i class="fa-solid fa-coins"></i>
            </div> -->
                <div class="profile">
                    <i class="fa-solid fa-user"></i>
                    <div class="information-profile">
                        <span class="name">{{ $user->nama_lengkap }}</span><br>
                        <span class="class">{{ $user->kelas }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="home-beranda">
        <div class="content-beranda">
            <div class="bg-[--color-default] w-full h-20 shadow-lg rounded-t-xl flex items-center pl-10 mb-10">
                <div class="text-white font-bold flex items-center gap-4">
                    <i class="fa-solid fa-file-lines text-4xl"></i>
                    <span class="text-xl">Catatan</span>
                </div>
            </div>
            <div class="w-full h-auto shadow-lg border-[1px] rounded-lg">
                <form action="{{ route('catatan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="input-hidden hidden">
                        <input type="text" name="nama_lengkap" value="{{ $user->nama_lengkap }}">
                        <input type="text" name="email" value="{{ $user->email }}">
                        <input type="text" name="sekolah" value="{{ $user->sekolah }}">
                        <input type="text" name="fase" value="{{ $user->fase }}">
                        <input type="text" name="kelas" value="{{ $user->kelas }}">
                        <input type="text" name="no_hp" value="{{ $user->no_hp }}">
                    </div>
                    <div class="flex mx-6 my-6 gap-12">
                        <div class="w-full">
                            <label class="mb-2 text-sm">Fase<sup class="text-red-500 pl-1">&#42;</sup></label>
                            <select name="fase_catatan" id="id_fase"
                                class="w-full bg-gray-100 outline-none rounded-xl text-xs p-3 cursor-pointer mt-2 {{ $errors->has('fase') ? 'border-[1px] border-red-500' : '' }}">
                                <option value="" class="hidden">Pilih Fase</option>
                                @if ($user->kelas === 'Kelas 1')
                                    <option value="Fase A" {{ @old('fase') === 'Fase A' ? 'selected' : '' }}>Fase
                                        A
                                    </option>
                                    <option value="Fase B">Fase B</option>
                                @endif
                            </select>
                            @error('fase')
                                <span class="text-red-500 font-bold text-sm mt-1 pl-2">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="w-full">
                            <label class="mb-2 text-sm">Kelas<sup class="text-red-500 pl-1">&#42;</sup></label>
                            <select name="kelas_catatan" id="id_kelas"
                                class="w-full bg-gray-100 outline-none rounded-xl text-xs p-3 cursor-pointer mt-2 {{ $errors->has('kelas') ? 'border-[1px] border-red-500' : '' }}">
                                <option value="" class="hidden">Pilih Kelas</option>
                            </select>
                            @error('kelas')
                                <span class="text-red-500 font-bold text-sm mt-1 pl-2">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="flex mx-6 my-6 gap-12">
                        <div class="w-full">
                            <label class="mb-2 text-sm">Mata Pelajaran<sup
                                    class="text-red-500 pl-1">&#42;</sup></label>
                            <select name="mapel" id="id_mapel"
                                class="w-full bg-gray-100 outline-none rounded-xl text-xs p-3 cursor-pointer mt-2 {{ $errors->has('mapel') ? 'border-[1px] border-red-500' : '' }}">
                                <option value="" class="hidden">Pilih mata pelajaran</option>
                            </select>
                            @error('mapel')
                                <span class="text-red-500 font-bold text-sm mt-1 pl-2">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="w-full">
                            <label class="mb-2 text-sm">Bab<sup class="text-red-500 pl-1">&#42;</sup></label>
                            <select name="bab" id="id_bab"
                                class="w-full bg-gray-100 outline-none rounded-xl text-xs p-3 cursor-pointer mt-2 {{ $errors->has('bab') ? 'border-[1px] border-red-500' : '' }}">
                                <option value="" class="hidden">Pilih Bab</option>
                            </select>
                            @error('bab')
                                <span class="text-red-500 font-bold text-sm mt-1 pl-2">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="flex mx-6 my-6 gap-12">
                        <div class="w-2/4 relative ... h-44">
                            <label class="mb-2 text-sm">Catatan</label>
                            <textarea name="catatan"
                                class="w-full h-20 bg-gray-100 outline-none rounded-xl text-xs p-2 resize-none mt-2 {{ $errors->has('catatan') ? 'border-[1px] border-red-500' : '' }}"
                                placeholder="Masukkan Catatan">{{ @old('catatan') }}</textarea>
                            <button
                                class="absolute right-0 bottom-4 bg-red-500 w-[150px] h-8 text-white font-bold rounded-lg">Kirim</button>
                            @error('pertanyaan')
                                <span class="text-red-500 font-bold text-sm mt-1 pl-2">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="w-2/4 h-auto">
                            <span class="text-sm">Upload Gambar<sup class="text-red-500 ml-1">&#42;</sup></span>
                            <div class="text-xs mt-1">
                                <span>Maksimum ukuran file 2MB. <br> File dapat dalam format
                                    .jpg/.png/.jpeg.</span>
                            </div>
                            <div class="upload-icon">
                                <div class="flex flex-col max-w-[260px]">
                                    <div id="imagePreview" class="max-w-[140px] cursor-pointer mt-4"
                                        onclick="openModal()">
                                        {{-- Image will be inserted here --}}
                                        <img id="popupImage" alt="" class="">
                                    </div>
                                    <div id="textPreview" class="text-red-500 font-bold mt-2"></div>
                                </div>
                                <div class="text-icon"></div>
                            </div>

                            <div class="content-upload w-[200px] h-10 bg-red-500 text-white font-bold rounded-lg mt-6">
                                <label for="file-upload"
                                    class="w-full h-full flex justify-center items-center cursor-pointer gap-2">
                                    <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                    <span>Upload Gambar</span>
                                </label>
                                <input id="file-upload" name="image_catatan" class="hidden"
                                    onchange="previewImage(event)" type="file" accept=".jpg, .png, .jpeg">
                            </div>
                            @error('image_catatan')
                                <div class="w-60">
                                    <span
                                        class="relative ... top-2 text-red-500 font-bold text-sm">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>
                </form>
                <!-- Modal for displaying the image -->
                <dialog id="imageModal" class="modal">
                    <div class="modal-box bg-white">
                        <button class="btn btn-sm btn-circle btn-ghost absolute right-0 top-0 outline-none"
                            onclick="closeModal()">✕</button>
                        <img id="modalImage" alt="" class="w-full h-auto">
                    </div>
                </dialog>
            </div>
        </div>
    </div>
@else
    <p>You do not have access to this pages.</p>
@endif
@else
<p>You are not logged in.</p>
@endif

<script src="js/upload-image.js"></script> {{-- upload image(js) --}}
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script> {{-- source script combobox(ajax) --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> {{-- source script filter data(ajax) --}}
<script src="js/catatan-siswa-ajax.js"></script>
<script src="js/catatan-mapel-siswa-ajax.js"></script>

<script>
    $(document).ready(function() {
        $('#kelasFilter').on('change', function() {
            var kode_kelas = $(this).val();
            if (kode_kelas) {
                $.ajax({
                    url: '/mapel/' + kode_kelas,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#mapelFilter').empty().append(
                            '<option value="">---Pilih Mata Pelajaran---</option>');
                        $.each(data, function(key, mapel) {
                            $('#mapelFilter').append('<option value="' + mapel
                                .kode + '">' + mapel.mapel + '</option>');
                        });
                    }
                });
                // fetchFilteredDataCatatan(kode_kelas, 'semua');
            } else {
                // $('#mapelFilter').empty();
                // $('#filterListMapel').empty();
                // $('.pagination-container-mapel').hide();
            }
        });

    });
</script>

<script>
    $(document).ready(function() {
        var oldMapel = "{{ old('mapel') }}"; // Ambil mapel yang dipilih jika ada
        var oldBab = "{{ old('bab') }}"; // Ambil bab yang dipilih jika ada
        var oldFase = "{{ old('fase') }}";
        var oldKelas = "{{ old('kelas') }}";

        $('#id_fase').on('change', function() {
            var kode_fase = $(this).val();
            if (kode_fase) {
                $.ajax({
                    url: '/kelas/' + kode_fase,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#id_kelas').empty();
                        $('#id_kelas').append(
                            '<option value="" class="hidden">---Pilih Kelas----</option>'
                        );
                        $.each(data, function(key, kelas) {
                            $('#id_kelas').append(
                                '<option value="' + kelas.kelas + '"' +
                                (oldKelas == kelas.kode ? ' selected' : '') +
                                '>' +
                                kelas.kelas + '</option>'
                            );
                        });

                        // Jika ada mapel yang dipilih sebelumnya, trigger change
                        if (oldKelas) {
                            $('#id_kelas').val(oldKelas).trigger('change');
                        }
                    }
                });
            } else {
                $('#id_kelas').empty();
            }
        });

        // Jika ada kelas yang dipilih sebelumnya, set dan trigger
        if ("{{ old('fase') }}") {
            $('#id_fase').val("{{ old('fase') }}").trigger('change');
        }

        // Ketika id_kelas berubah
        $('#id_kelas').on('change', function() {
            var kode_kelas = $(this).val();
            if (kode_kelas) {
                $.ajax({
                    url: '/mapel/' + kode_kelas,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#id_mapel').empty();
                        $('#id_mapel').append(
                            '<option value="" class="hidden">---Pilih Mata Pelajaran----</option>'
                        );
                        $.each(data, function(key, mapel) {
                            $('#id_mapel').append(
                                '<option value="' + mapel.kode +
                                '">' +
                                mapel.mapel + '</option>'
                            );
                        });
                    }
                });
            } else {
                $('#id_mapel').empty();
            }
        });

        // Jika ada kelas yang dipilih sebelumnya, set dan trigger
        if ("{{ old('kelas') }}") {
            $('#id_kelas').val("{{ old('kelas') }}").trigger('change');
        }

        // Ketika id_mapel berubah
        $('#id_mapel').on('change', function() {
            var kode_mapel = $(this).val();
            if (kode_mapel) {
                $.ajax({
                    url: '/bab/' + kode_mapel,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#id_bab').empty();
                        $('#id_bab').append(
                            '<option value="" class="hidden">---Pilih Bab----</option>'
                        );
                        $.each(data, function(key, bab) {
                            $('#id_bab').append(
                                '<option value="' + bab.bab + '"' +
                                (oldBab == bab.bab ? ' selected' : '') +
                                '>' +
                                bab.bab + '</option>'
                            );
                        });

                        // Set nilai bab yang dipilih jika ada
                        if (oldBab) {
                            $('#id_bab').val(oldBab).trigger('change');
                        }
                    }
                });
            } else {
                $('#id_bab').empty();
            }
        });

        // Jika ada mapel yang dipilih sebelumnya, set
        if ("{{ old('mapel') }}") {
            $('#id_mapel').val("{{ old('mapel') }}").trigger('change');
        }
    });
</script>
