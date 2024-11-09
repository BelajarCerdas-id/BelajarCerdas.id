<x-script></x-script>
<div class="sidebar-beranda">
    <div class="logo_details">
        <!-- <i class="bx bxl-audible icon"></i>
      <div class="logo_name">Code Effect</div>
      <i class="bx bx-menu" id="btn"></i> -->
        <img src="image/logoBC-example.png" alt="">
    </div>
    <ul class="nav-list">
        <li>
            <a href="#">
                <i class="fa-solid fa-cart-shopping"></i>
                <span class="link_name">Beranda</span>
            </a>
            <span class="tooltip">Beranda</span>
        </li>
        <li>
            <a href="#">
                <i class="fa-solid fa-cart-shopping"></i>
                <span class="link_name">Pembelian</span>
            </a>
            <span class="tooltip">Pembelian</span>
        </li>
        <li>
            <span class="text_name">LMS</span>
        </li>
        <li>
            <a href="#">
                <i class="fa-solid fa-cart-shopping"></i>
                <span class="link_name">Belajar Cerdas</span>
            </a>
            <span class="tooltip">Belajar Cerdas</span>
        </li>
        <li>
            <a href="#">
                <i class="fa-solid fa-cart-shopping"></i>
                <span class="link_name">Laporan</span>
            </a>
            <span class="tooltip">Laporan</span>
        </li>
        <li>
            <a href="#">
                <i class="fa-solid fa-cart-shopping"></i>
                <span class="link_name">Administrasi</span>
            </a>
            <span class="tooltip">Administrasi</span>
        </li>
        <li>
            <a href="#">
                <i class="fa-solid fa-cart-shopping"></i>
                <span class="link_name">Tentang Sekolah</span>
            </a>
            <span class="tooltip">Tentang Sekolah</span>
        </li>
        <li class="hideSidebar cursor-pointer" id="close">
            <i class="fa-solid fa-chevron-left" id="log_outt"></i>
            <span>Sembunyikan</span>
        </li>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="text-white font-bold">Logout</button>
        </form>
    </ul>
</div>
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
