<x-script></x-script>
@if (session('user')->status === 'Siswa' or session('user')->status === 'Murid')
    <div class="sidebar-beranda hidden md:block">
        <div class="logo_details flex items-center justify-center">
            <a href="/">
                <img src="../image/logoBC.png" alt="" class="w-2/5">
            </a>
        </div>
        <div class="nav-list">
            <div class="menu-murid">
                <a href="/beranda">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="link_name">Beranda</span>
                </a>
                <span class="tooltip">Beranda</span>
            </div>
            <div class="menu-murid">
                <a href="/histori">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="link_name">Pembelian</span>
                </a>
                <span class="tooltip">Pembelian</span>
            </div>
            <div>
                <span class="text_name">LMS</span>
            </div>
            <div class="menu-murid">
                <a href="#">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="link_name">Belajar Cerdas</span>
                </a>
                <span class="tooltip">Belajar Cerdas</span>
            </div>
            <div class="menu-murid">
                <a href="#">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="link_name">Laporan</span>
                </a>
                <span class="tooltip">Laporan</span>
            </div>
            <div class="menu-murid">
                <a href="#">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="link_name">Administrasi</span>
                </a>
                <span class="tooltip">Administrasi</span>
            </div>
            <div class="menu-murid">
                <a href="#">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="link_name">Tentang Sekolah</span>
                </a>
                <span class="tooltip">Tentang Sekolah</span>
            </div>
            <div class="hideSidebar cursor-pointer" id="close">
                <i class="fa-solid fa-chevron-left" id="log_outt"></i>
                <span>Sembunyikan</span>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="text-white font-bold">Logout</button>
            </form>
        </div>
    </div>
    <div class="home-beranda hidden md:block !z-[-1]">
        <div class="content">
            {{-- Navbar for PC --}}
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
                        <span class="name">{{ session('user')->nama_lengkap ?? '' }}</span>
                        <span class="class">{{ session('user')->kelas ?? '' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif(session('user')->status === 'Guru')
    <div class="sidebar-beranda hidden md:block">
        <div class="logo_details">
            <img src="image/logo-BC.png" alt="">
        </div>
        <div class="nav-list">
            <div class="menu-murid">
                <a href="/beranda">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="link_name">Beranda</span>
                </a>
                <span class="tooltip">Beranda</span>
            </div>
            <div>
                <span class="text_name">LMS</span>
            </div>
            <div class="menu-murid">
                <a href="#">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="link_name">Belajar Cerdas</span>
                </a>
                <span class="tooltip">Belajar Cerdas</span>
            </div>
            <div class="menu-murid">
                <a href="#">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="link_name">Laporan</span>
                </a>
                <span class="tooltip">Laporan</span>
            </div>
            <div class="menu-murid">
                <a href="#">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="link_name">Administrasi</span>
                </a>
                <span class="tooltip">Administrasi</span>
            </div>
            <div class="menu-murid">
                <a href="#">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="link_name">Tentang Sekolah</span>
                </a>
                <span class="tooltip">Tentang Sekolah</span>
            </div>
            <div class="hideSidebar cursor-pointer" id="close">
                <i class="fa-solid fa-chevron-left" id="log_outt"></i>
                <span>Sembunyikan</span>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="text-white font-bold">Logout</button>
            </form>
        </div>
    </div>
    <div class="home-beranda hidden md:block">
        <div class="content">
            {{-- Navbar for PC --}}
            <div class="navbar-beranda">
                <header>Beranda</header>
                <div class="profile">
                    <i class="fa-solid fa-user"></i>
                    <div class="information-profile">
                        <span class="name">{{ session('user')->nama_lengkap ?? '' }}</span>
                        <span class="class">{{ session('user')->kelas ?? '' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif(session('user')->status === 'Mentor')
    <div class="sidebar-beranda hidden md:block">
        <div class="logo_details">
            <img src="../image/logoBC.png" alt="">
        </div>
        <div class="nav-list">
            <div class="menu-murid">
                <a href="/beranda">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="link_name">Beranda</span>
                </a>
                <span class="tooltip">Beranda</span>
            </div>
            <div>
                <span class="text_name">LMS</span>
            </div>
            <div class="menu-murid">
                <a href="#">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="link_name">Belajar Cerdas</span>
                </a>
                <span class="tooltip">Belajar Cerdas</span>
            </div>
            <div class="menu-murid">
                <a href="#">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="link_name">Laporan</span>
                </a>
                <span class="tooltip">Laporan</span>
            </div>
            <div class="menu-murid">
                <a href="#">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="link_name">Administrasi</span>
                </a>
                <span class="tooltip">Administrasi</span>
            </div>
            <div class="hideSidebar cursor-pointer" id="close">
                <i class="fa-solid fa-chevron-left" id="log_outt"></i>
                <span>Sembunyikan</span>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="text-white font-bold">Logout</button>
            </form>
        </div>
    </div>
    <div class="home-beranda hidden md:block">
        <div class="content">
            {{-- Navbar for PC --}}
            <div class="navbar-beranda">
                <header>Beranda</header>
                <div class="information-account">
                    <div class="">
                        {{-- @if ($dataAccept[$user->email]->count() >= 8 && $validatedMentorAccepted[$user->email]->count() >= 8)
                                <i class="fas fa-medal text-3xl text-[#C0C0C0]"></i>
                            @elseif($dataAccept[$user->email]->count() >= 1 && $validatedMentorAccepted[$user->email]->count() >= 1)
                                <i class="fas fa-medal text-5xl text-[#CD7F32]"></i>
                            @endif --}}
                    </div>
                    {{-- <div class="notification">
                            <i class="fa-solid fa-bell"></i>
                        </div>
                        <div class="coin">
                            <i class="fa-solid fa-coins"></i>
                        </div> --}}

                    <div class="profile">
                        <i class="fa-solid fa-user"></i>
                        <div class="information-profile">
                            <span class="name">{{ session('user')->nama_lengkap ?? '' }}</span>
                            <span class="class">{{ session('user')->sekolah ?? '' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif (session('user')->status === 'Admin')

@elseif(session('user')->status === 'Administrator')
    <aside class="sidebar-beranda-administrator hidden md:block">
        <div class="logo_details flex items-center justify-center">
            <img src="image/logoBC.png" alt="" class="w-2/4">
        </div>
        <ul class="mt-8">
            <li class="list-item">
                <div class="toggle-menu">
                    <i class="fas fa-house"></i>
                    <a href="/beranda">Beranda</a>
                </div>
            </li>
            <li class="list-item">
                <div class="dropdown-menu">
                    <div class="toggle-menu">
                        <i class="fa-solid fa-question !text-lg"></i>
                        <span class="toggle-dropdown">TANYA</span>
                        <i class="fas fa-chevron-down absolute right-0" id="rotate"></i>
                    </div>
                    <div class="content-dropdown">
                        <a href="">List Mentor</a>
                    </div>
                </div>
            </li>
            <li class="list-item">
                <div class="dropdown-menu">
                    <div class="toggle-menu">
                        <i class="fas fa-house"></i>
                        <span class="toggle-dropdown">English Zone</span>
                        <i class="fas fa-chevron-down absolute right-0" id="rotate"></i>
                    </div>
                    <div class="content-dropdown">
                        <a href="/upload-materi">Upload Materi</a>
                        <a href="/upload-soal">Upload Soal</a>
                        <a href="/question-for-release">Question For Release</a>
                    </div>
                </div>
            </li>
        </ul>
    </aside>

    <div class="home-beranda hidden md:block">
        <div class="content">
            {{-- Navbar for PC --}}
            <div class="navbar-beranda">
                <header>Beranda</header>
                <div class="information-account">
                    <div class="profile">
                        <i class="fa-solid fa-user"></i>
                        <div class="information-profile">
                            <span class="name">{{ session('user')->nama_lengkap ?? '' }}</span>
                            <span class="class">{{ session('user')->status ?? '' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif(session('user')->status === 'Wakil Kepala Sekolah' or session('user')->status === 'Kepala Sekolah')

@elseif(session('user')->status === 'Team Leader')
    <div class="sidebar-beranda hidden md:block">
        <div class="logo_details">
            <img src="../image/logo-BC.png" alt="">
        </div>
        <div class="nav-list">
            <div class="menu-murid">
                <a href="/beranda">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="link_name">Beranda</span>
                </a>
                <span class="tooltip">Beranda</span>
            </div>
            <div>
                <span class="text_name">LMS</span>
            </div>
            <div class="menu-murid">
                <a href="#">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="link_name">Belajar Cerdas</span>
                </a>
                <span class="tooltip">Belajar Cerdas</span>
            </div>
            <div class="menu-murid">
                <a href="/laporan">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="link_name">Laporan</span>
                </a>
                <span class="tooltip">Laporan</span>
            </div>
            <div class="menu-murid">
                <a href="#">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="link_name">Administrasi</span>
                </a>
                <span class="tooltip">Administrasi</span>
            </div>
            <div class="hideSidebar cursor-pointer" id="close">
                <i class="fa-solid fa-chevron-left" id="log_outt"></i>
                <span>Sembunyikan</span>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="text-white font-bold">Logout</button>
            </form>
        </div>
    </div>
    <div class="home-beranda hidden md:block">
        <div class="content">
            {{-- Navbar for PC --}}
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
                        <span class="name">{{ session('user')->nama_lengkap ?? '' }}</span>
                        <span class="class">{{ session('user')->status ?? '' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif(session('user')->status === 'XR')
    <div class="sidebar-beranda hidden md:block">
        <div class="logo_details">
            <img src="../image/logo-BC.png" alt="">
        </div>
        <div class="nav-list">
            <div class="menu-murid">
                <a href="/beranda">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="link_name">Beranda</span>
                </a>
                <span class="tooltip">Beranda</span>
            </div>
            <div>
                <span class="text_name">LMS</span>
            </div>
            <div class="menu-murid">
                <a href="#">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="link_name">Belajar Cerdas</span>
                </a>
                <span class="tooltip">Belajar Cerdas</span>
            </div>
            <div class="menu-murid">
                <a href="/laporan">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="link_name">Laporan</span>
                </a>
                <span class="tooltip">Laporan</span>
            </div>
            <div class="menu-murid">
                <a href="#">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="link_name">Administrasi</span>
                </a>
                <span class="tooltip">Administrasi</span>
            </div>
            <div class="hideSidebar cursor-pointer" id="close">
                <i class="fa-solid fa-chevron-left" id="log_outt"></i>
                <span>Sembunyikan</span>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="text-white font-bold">Logout</button>
            </form>
        </div>
    </div>
    <div class="home-beranda hidden md:block">
        <div class="content">
            {{-- Navbar for PC --}}
            <div class="navbar-beranda">
                <header>Beranda</header>
                <div class="profile">
                    <i class="fa-solid fa-user"></i>
                    <div class="information-profile">
                        <span class="name">{{ session('user')->nama_lengkap ?? '' }}</span>
                        <span class="class">{{ session('user')->status ?? '' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif(session('user')->status === 'Sales')
    <aside class="sidebar-beranda-administrator hidden md:block">
        <div class="logo_details flex items-center justify-center">
            <img src="../image/logoBC.png" alt="" class="w-2/4">
        </div>
        <ul class="mt-8">
            <li class="list-item">
                <a href="/beranda">
                    <div class="toggle-menu">
                        <i class="fas fa-house"></i>
                        <span>Beranda</span>
                    </div>
                </a>
            </li>
            <li class="list-item">
                <div class="dropdown-menu">
                    <!-- Dropdown utama -->
                    <div class="toggle-menu">
                        <i class="fas fa-house"></i>
                        <span class="toggle-dropdown">Administrasi Sales</span>
                        <i class="fas fa-chevron-down absolute right-0" id="rotate"></i>
                    </div>

                    <div class="content-dropdown">
                        <!-- Dropdown kedua (sub menu) -->
                        <div class="toggle-menu2">
                            <span class="toggle-dropdown">Visitasi</span>
                            <i class="fas fa-chevron-down absolute right-0" id="rotate2"></i>
                        </div>

                        <div class="list-content-dropdown">
                            <a href="{{ route('jadwalKunjungan') }}">Buat Jadwal Kunjungan</a>
                            <a href="{{ route('dataKunjungan') }}">Laporan Kunjungan</a>
                        </div>
                    </div>
                </div>
            </li>
            <li class="list-item">
                <div class="dropdown-menu">
                    <!-- Dropdown utama -->
                    <div class="toggle-menu">
                        <i class="fas fa-house"></i>
                        <span class="toggle-dropdown">Management Surat PKS</span>
                        <i class="fas fa-chevron-down absolute right-0" id="rotate"></i>
                    </div>

                    <div class="content-dropdown">
                        <a href="{{ route('input-surat-pks') }}">Buat Surat PKS</a>
                        <a href="{{ route('cetakPKS') }}">Cetak Surat PKS</a>
                    </div>
                </div>
            </li>
        </ul>
    </aside>

    <div class="home-beranda hidden md:block">
        <div class="content">
            {{-- Navbar for PC --}}
            <div class="navbar-beranda">
                <header>Beranda</header>
                <div class="information-account">
                    <div class="profile">
                        <i class="fa-solid fa-user"></i>
                        <div class="information-profile">
                            <span class="name">{{ session('user')->nama_lengkap ?? '' }}</span>
                            <span class="class">{{ session('user')->status ?? '' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif(session('user')->status === 'Admin Sales')
    <aside class="sidebar-beranda-administrator hidden md:block">
        <div class="logo_details flex items-center justify-center">
            <img src="image/logoBC.png" alt="" class="w-2/4">
        </div>
        <ul class="mt-8">
            <li class="list-item">
                <div class="toggle-menu">
                    <i class="fas fa-house"></i>
                    <a href="/beranda">Beranda</a>
                </div>
            </li>
            <li class="list-item">
                <div class="dropdown-menu">
                    <!-- Dropdown utama -->
                    <div class="toggle-menu">
                        <i class="fas fa-house"></i>
                        <span class="toggle-dropdown">Master Data PKS</span>
                        <i class="fas fa-chevron-down absolute right-0" id="rotate"></i>
                    </div>

                    <div class="content-dropdown">
                        <!-- Dropdown kedua (sub menu) -->
                        <div class="toggle-menu2">
                            <span class="toggle-dropdown">Input PKS</span>
                            <i class="fas fa-chevron-down absolute right-0" id="rotate2"></i>
                        </div>

                        <div class="list-content-dropdown">
                            <a href="{{ route('input-murid') }}">Data Civitas Sekolah</a>
                        </div>
                    </div>

                    <div class="content-dropdown">
                        <!-- Dropdown ketiga (sub menu) -->
                        <div class="toggle-menu2">
                            <span class="toggle-dropdown">Management PKS</span>
                            <i class="fas fa-chevron-down absolute right-0" id="rotate2"></i>
                        </div>

                        <div class="list-content-dropdown">
                            <a href="{{ route('data-sekolah-pks') }}">Data Civitas Sekolah</a>
                            <a href="{{ route('data-sekolah') }}">Data Sekolah</a>
                        </div>
                    </div>
                </div>
            </li>

            <li class="list-item">
                <div class="dropdown-menu">
                    <!-- Dropdown utama -->
                    <div class="toggle-menu">
                        <i class="fas fa-house"></i>
                        <span class="toggle-dropdown">Templates</span>
                        <i class="fas fa-chevron-down absolute right-0" id="rotate"></i>
                    </div>

                    <div class="content-dropdown">
                        <!-- Dropdown ketiga (sub menu) -->
                        <div class="toggle-menu2">
                            <span class="toggle-dropdown">BulkUpload Services</span>
                            <i class="fas fa-chevron-down absolute right-0" id="rotate2"></i>
                        </div>

                        <div class="list-content-dropdown">
                            <a href="{{ route('bulk-upload-civitas-sekolah') }}">Civitas Sekolah</a>
                            <a href="">Soal EnglishZone</a>
                        </div>
                    </div>

                    <div class="content-dropdown">
                        <a href="{{ route('suratPKS') }}">Surat PKS</a>
                    </div>
                </div>
            </li>
        </ul>
    </aside>

    <div class="home-beranda hidden md:block">
        <div class="content">
            {{-- Navbar for PC --}}
            <div class="navbar-beranda">
                <header>Beranda</header>
                <div class="information-account">
                    <div class="profile">
                        <i class="fa-solid fa-user"></i>
                        <div class="information-profile">
                            <span class="name">{{ session('user')->nama_lengkap ?? '' }}</span>
                            <span class="class">{{ session('user')->status ?? '' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <p>You do not have access to this dashboard.</p>
@endif


<script>
    document.addEventListener("DOMContentLoaded", () => {
        const toggles = document.querySelectorAll(".toggle-menu"); // Dropdown utama
        const toggles2 = document.querySelectorAll(".toggle-menu2"); // Sub-dropdown

        // Fungsi untuk menutup semua dropdown
        function closeAllSubDropdowns(except = null) {
            document.querySelectorAll(".content-dropdown").forEach(dropdown => {
                if (dropdown !== except) {
                    dropdown.classList.remove("show");
                }
            });
        }

        function closeAllListDropdowns(except = null) {
            document.querySelectorAll(".list-content-dropdown").forEach(dropdown => {
                if (dropdown !== except) {
                    dropdown.classList.remove("show");
                }
            });
        }

        // Event listener untuk dropdown utama
        toggles.forEach(toggle => {
            toggle.addEventListener("click", () => {
                const parent = toggle.closest('.list-item'); // Cari elemen utama

                // Toggle dropdown utama
                parent.classList.toggle("show");

                // Tutup dropdown lain yang tidak diklik
                document.querySelectorAll(".list-item").forEach(dropdown => {
                    if (dropdown !== parent) {
                        dropdown.classList.remove("show");
                    }
                });

                // Tutup semua sub-dropdown saat dropdown utama berubah
                closeAllSubDropdowns();
            });
        });

        // Event listener untuk sub-dropdown (toggle-menu2)
        toggles2.forEach(toggle => {
            toggle.addEventListener("click", () => {
                const parent = toggle.closest('.content-dropdown'); // Cari elemen sub-dropdown

                if (parent.classList.contains("show")) {
                    // Jika sudah terbuka, tutup
                    parent.classList.remove("show");
                } else {
                    // Jika belum terbuka, tutup yang lain lalu buka yang ini
                    closeAllSubDropdowns();
                    closeAllListDropdowns();
                    parent.classList.add("show");
                }
            });
        });
    });
</script>




<script>
    window.onload = function() {
        const sidebar = document.querySelector(".sidebar-beranda");
        const closeSidebar = document.querySelector("#close");
        const closeBtn = document.querySelector("#log_outt");

        closeSidebar.addEventListener("click", function() {
            sidebar.classList.toggle("open")
            menuBtnChange()
        })

        function menuBtnChange() {
            if (sidebar.classList.contains("open")) {
                closeBtn.classList.replace("fa-chevron-left", "fa-chevron-right");
            } else {
                closeBtn.classList.replace("fa-chevron-right", "fa-chevron-left");
            }
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuItems = document.querySelectorAll('.menu-murid a');

        // Ambil URL halaman saat ini
        const currentPage = window.location.pathname;

        // Periksa setiap menu dan beri kelas 'active' jika href-nya cocok dengan URL saat ini
        menuItems.forEach(item => {
            const menuLink = item.getAttribute('href'); // ambil href dari setiap link

            // Jika href menu sesuai dengan URL halaman saat ini
            if (currentPage.includes(menuLink)) {
                item.closest('.menu-murid').classList.add('active'); // tambahkan kelas active
            } else {
                item.closest('.menu-murid').classList.remove(
                    'active'); // hapus kelas active jika tidak sesuai
            }
        });
    });
</script>
