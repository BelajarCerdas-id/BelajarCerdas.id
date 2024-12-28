<x-script></x-script>
@if (isset($user))
    @if ($user->status === 'Siswa' or $user->status === 'Murid')
        <div class="sidebar-beranda hidden md:block bg-[--color-default]">
            <div class="logo_details">
                <!-- <i class="bx bxl-audible icon"></i>
                <div class="logo_name">Code Effect</div>
                <i class="bx bx-menu" id="btn"></i> -->
                {{-- <img src="image/logoBC-example.png" alt=""> --}}
                <img src="image/logo-sementara.png" alt="">
                <span class="text-lg text-white font-bold">BelajarCerdas</span>
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
                            <span class="name">{{ $user->nama_lengkap }}</span>
                            <span class="class">{{ $user->kelas }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif($user->status === 'Guru')
        <div class="sidebar-beranda hidden md:block">
            <div class="logo_details">
                <!-- <i class="bx bxl-audible icon"></i>
                <div class="logo_name">Code Effect</div>
                <i class="bx bx-menu" id="btn"></i> -->
                <img src="image/logoBC-example.png" alt="">
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
                            <span class="name">{{ $user->nama_lengkap }}</span>
                            <span class="class">{{ $user->kelas }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif($user->status === 'Mentor')
        <div class="sidebar-beranda hidden md:block">
            <div class="logo_details">
                <!-- <i class="bx bxl-audible icon"></i>
                <div class="logo_name">Code Effect</div>
                <i class="bx bx-menu" id="btn"></i> -->
                <img src="image/logoBC-example.png" alt="">
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
                                <span class="name">{{ $user->nama_lengkap }}</span>
                                <span class="class">{{ $user->sekolah }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($user->status === 'Admin')

    @elseif($user->status === 'Administrator')
        {{-- <aside class="sidebar-beranda hidden md:block">
            <div class="logo_details">
                <!-- <i class="bx bxl-audible icon"></i>
                <div class="logo_name">Code Effect</div>
                <i class="bx bx-menu" id="btn"></i> -->
                {{-- <img src="image/logoBC-example.png" alt=""> --}
                <img src="image/logo-sementara.png" alt="">
                <span class="text-lg text-white font-bold">BelajarCerdas</span>
            </div>
            <nav>
                <div class="sidebar">
                    <a href="#" class="menu-item">Beranda</a>

                    <div class="menu-item">
                        <span class="toggle-dropdown">Kelas Pintar Regular</span>
                        <div class="content-dropdown">
                            <a href="#">Manage Content</a>
                            <a href="#">Content for Release</a>
                        </div>
                    </div>

                    <div class="menu-item">
                        <span class="toggle-dropdown">Question</span>
                        <div class="content-dropdown">
                            <a href="#">Manage Question</a>
                            <a href="#">Question for Release</a>
                        </div>
                    </div>

                    <a href="#" class="menu-item">Games</a>

                    <div class="menu-item">
                        <span class="toggle-dropdown">Syllabus & Service</span>
                        <div class="content-dropdown">
                            <a href="#">Manage Syllabus</a>
                            <a href="#">Guru Ahli</a>
                        </div>
                    </div>

                    <a href="#" class="menu-item">Document IKM</a>
                    <a href="#" class="menu-item">TANYA</a>
                    <a href="#" class="menu-item">PTN</a>
                </div>
            </nav>
        </aside> --}}
        <aside class="sidebar-beranda-administrator">
            <div class="logo_details flex items-center gap-2 justify-center">
                <img src="image/logo-sementara.png" alt="" class="w-[50px]">
                <span class="text-lg text-white font-bold">BelajarCerdas</span>
            </div>
            <ul class="mt-8">
                <li class="list-item">
                    <div class="toggle-menu">
                        <i class="fas fa-house"></i>
                        <a href="#">Beranda</a>
                    </div>
                </li>
                <li class="list-item">
                    <div class="dropdown-menu">
                        <div class="toggle-menu">
                            <i class="fas fa-house"></i>
                            <span class="toggle-dropdown">English Zone</span>
                            <i class="fas fa-chevron-down absolute right-0"></i>
                        </div>
                        <div class="content-dropdown">
                            <a href="">Upload Materi</a>
                            <a href="">Upload Soal</a>
                        </div>
                    </div>
                </li>
                <li class="list-item">
                    <div class="dropdown-menu">
                        <div class="toggle-menu">
                            <i class="fas fa-house"></i>
                            <span class="toggle-dropdown">TANYA</span>
                            <i class="fas fa-chevron-down absolute right-0"></i>
                        </div>
                        <div class="content-dropdown">
                            <a href="">Lorem Ipsum</a>
                            <a href="">Lorem Ipsum</a>
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
                        <div class="notification">
                            <i class="fa-solid fa-bell"></i>
                        </div>
                        <div class="coin">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                        <div class="profile">
                            <i class="fa-solid fa-user"></i>
                            <div class="information-profile">
                                <span class="name">{{ $user->nama_lengkap }}</span>
                                <span class="class">{{ $user->status }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif($user->status === 'Wakil Kepala Sekolah' or $user->status === 'Kepala Sekolah')

    @elseif($user->status === 'Team Leader')
        <div class="sidebar-beranda hidden md:block">
            <div class="logo_details">
                <!-- <i class="bx bxl-audible icon"></i>
                <div class="logo_name">Code Effect</div>
                <i class="bx bx-menu" id="btn"></i> -->
                <img src="../image/logoBC-example.png" alt="">
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
                            <span class="name">{{ $user->nama_lengkap }}</span>
                            <span class="class">{{ $user->status }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif($user->status === 'XR')
        <div class="sidebar-beranda hidden md:block">
            <div class="logo_details">
                <!-- <i class="bx bxl-audible icon"></i>
                <div class="logo_name">Code Effect</div>
                <i class="bx bx-menu" id="btn"></i> -->
                <img src="../image/logoBC-example.png" alt="">
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
                            <span class="name">{{ $user->nama_lengkap }}</span>
                            <span class="class">{{ $user->status }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <p>You do not have access to this dashboard.</p>
    @endif
@else
    <p>You are not logged in.</p>
@endif


<script>
    document.addEventListener("DOMContentLoaded", () => {
        const toggles = document.querySelectorAll(".toggle-menu");

        toggles.forEach(toggle => {
            toggle.addEventListener("click", () => {
                const parent = toggle.closest(
                    '.list-item'); // Mendapatkan elemen dropdown-menu

                // Toggle visibilitas dropdown
                parent.classList.toggle("show");

                // Tutup dropdown lain jika perlu
                document.querySelectorAll(".list-item").forEach(dropdown => {
                    if (dropdown !== parent) {
                        dropdown.classList.remove("show");
                    }
                });
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
