<x-script></x-script>
<div class="navbar-beranda-phone w-full h-20 flex justify-between items-center md:hidden bg-[--color-default] px-6">
    <div class="flex items-center">
        <i class="fas fa-bars text-2xl relative top-1 cursor-pointer text-white" onclick="togglePopup()"></i>
        <img src="image/logoBC-example.png" alt="" class="w-[80%]">
    </div>
    <div class="flex items-center gap-8 text-2xl relative top-1">
        <span>
            <i class="fas fa-bell text-blue-500 font-bold"></i>
        </span>
        <span class="">
            <i class="fas fa-coins text-yellow-500"></i>
            <a class="text-white">0</a>
        </span>
    </div>
</div>
<div class="sidebar-beranda-phone md:hidden" id="popup-1">
    <div class="overlay-sidebar-phone"></div>
    <div class="content-sidebar-phone">
        <header class="w-full h-20 bg-[--color-default] flex items-center justify-between pl-2 pr-6">
            <img src="image/logoBC-example.png" alt="" class="w-24">
            <i class="fas fa-xmark text-2xl text-white cursor-pointer" onclick="togglePopup()"></i>
        </header>
        <main>
            <section>
                <div class="profile-account flex flex-col items-center px-2 my-6">
                    <i class="fas fa-circle-user text-5xl text-gray-500"></i>
                    <span>{{ $user->nama_lengkap }}</span>
                    <span class="text-xs">{{ $user->kelas }}</span>
                </div>
                <div class="navbar-phone">
                    <div class="nav-list">
                        <div class="menu-murid">
                            <a href="/beranda">
                                <i class="fa-solid fa-cart-shopping"></i>
                                <span class="link_name">Beranda</span>
                            </a>
                        </div>
                        <div class="menu-murid">
                            <a href="/histori">
                                <i class="fa-solid fa-cart-shopping"></i>
                                <span class="link_name">Pembelian</span>
                            </a>
                        </div>
                        <div>
                            <div class="border-b-[1px] my-4"></div>
                            <div class="menu-murid">
                                <a href="#">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                    <span class="link_name">Belajar Cerdas</span>
                                </a>
                            </div>
                            <div class="menu-murid">
                                <a href="#">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                    <span class="link_name">Laporan</span>
                                </a>
                            </div>
                            <div class="menu-murid">
                                <a href="#">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                    <span class="link_name">Administrasi</span>
                                </a>
                            </div>
                            <div class="menu-murid">
                                <a href="#">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                    <span class="link_name">Tentang Sekolah</span>
                                </a>
                            </div>
                            <div class="border-b-[1px] my-4"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button
                                    class="text-[--color-default] font-bold flex justify-center items-center w-[90%] bg-red-200 p-2 mt-6 mx-auto rounded-full gap-2 cursor-pointer">
                                    <i class="fas fa-right-from-bracket rotate-[-180deg]"></i>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
            </section>
        </main>
    </div>
</div>

<script>
    function togglePopup() {
        const sidebarMobile = document.getElementById('popup-1').classList.toggle('active');
    }
</script>
