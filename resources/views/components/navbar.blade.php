<x-script>

</x-script>

<header class="mb-10">
    <main>
        <section class="flex justify-between items-center px-5 h-24">
            <div class="flex items-center">
                <div class="relative left-[-68px] h-28 w-28 bg-white rounded-full shadow-[0_10px_24px_rgba(0,0,0,0.23)]">
                </div>
                <a href="{{ route('homePage') }}" class="relative left-[-15px]">
                    <img src="image/logo-bc/asset logo landing page.png" class="w-[240px]">
                </a>
            </div>

            <nav class="hidden md:flex space-x-6">
                <x-nav-active href="/about" :active="request()->is('murid')">Murid</x-nav-active>
                <x-nav-active href="/about" :active="request()->is('guru')">Guru</x-nav-active>
                <x-nav-active href="/about" :active="request()->is('sekolah')">Sekolah</x-nav-active>
            </nav>
            <div class="flex items-center space-x-3">
                <label class="input border-none bg-[#ededed] rounded-full w-max h-8 hidden lg:flex items-center">
                    <input type="search" required placeholder="Search..."
                        class="ml-2 text-sm placeholder-gray-400 placeholder:italic text-right" />
                    <svg class="h-[1.5em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <defs>
                            <!-- Gradient dari atas ke bawah -->
                            <linearGradient id="gradientColor" x1="0%" y1="0%" x2="0%"
                                y2="100%">
                                <stop offset="0%" stop-color="#60B5FF" />
                                <stop offset="50%" stop-color="#60B5FF" />
                                <stop offset="50%" stop-color="#5EF2D5" />
                                <stop offset="100%" stop-color="#5EF2D5" />
                            </linearGradient>
                        </defs>
                        <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none">
                            <circle cx="11" cy="11" r="8" stroke="url(#gradientColor)"></circle>
                            <path d="M21 21l-4.3-4.3" stroke="#5EF2D5"></path>
                        </g>
                    </svg>
                </label>
                <a href="{{ route('login') }}">
                    <button class="bg-[--color-default] text-white px-4 py-2 rounded-full">Masuk / Daftar</button>
                </a>
            </div>
        </section>
    </main>
</header>


{{-- <nav>
    <div class="navbar fixed z-[999] font-bold flex justify-between border border-e-red-300">
        <div class="relative w-24 h-24 left-[-45px] bg-white rounded-full shadow-[0_10px_24px_rgba(0,0,0,0.23)]"></div>

        <a href="{{ route('homePage') }}">
            <img src="image/logo-bc/asset logo landing page.png" class="w-[180px]">
        </a>

        <div class="NavLink gap-2 hidden md:flex">
            <x-nav-active href="/about" :active="request()->is('about')">About Us</x-nav-active>
            <x-nav-active href="/about" :active="request()->is('about')">Contact</x-nav-active>
            <x-nav-active href="/about" :active="request()->is('about')">Service</x-nav-active>
        </div>

        <label class="input border-none bg-[#ededed] rounded-full w-max h-8 hidden md:flex items-center">
            <input type="search" required placeholder="Search..."
                class="ml-2 text-sm placeholder-gray-400 placeholder:italic text-right" />
            <svg class="h-[1.5em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <defs>
                    <!-- Gradient dari atas ke bawah -->
                    <linearGradient id="gradientColor" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#60B5FF" />
                        <stop offset="50%" stop-color="#60B5FF" />
                        <stop offset="50%" stop-color="#5EF2D5" />
                        <stop offset="100%" stop-color="#5EF2D5" />
                    </linearGradient>
                </defs>
                <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none">
                    <circle cx="11" cy="11" r="8" stroke="url(#gradientColor)"></circle>
                    <path d="M21 21l-4.3-4.3" stroke="#5EF2D5"></path>
                </g>
            </svg>
        </label>

        <div class="auth gap-6 lg:flex md:hidden sm:hidden hidden">
            <a href="daftar">
                <button class="bg-[#60b5cf] px-8 py-2 rounded-lg text-white text-sm font-bold">Daftar</button>
            </a>
            <a href="/login">
                <button class="bg-[#60b5cf] px-8 py-2 rounded-lg text-white text-sm font-bold">Login</button>
            </a>
        </div>

        <ul class="bars lg:hidden md:flex px-6 list-none cursor-pointer">
            <li class="" onclick="openSidebar()" id="Show"><i class="fa-solid fa-bars"></i></li>
            <li class="hidden" onclick="hideSidebar()" id="Hide"><i class="fa-solid fa-xmark"></i></li>
        </ul>
    </div>


    <div class="Sidebar fixed top-[64px] left-[0px] z-[999] w-full h-auto bg-white shadow-lg font-bold pb-8 md:pb-0 hidden"
        id="accordion">
        <div class="item w-[250px] m-2 p-2 lg:hidden">
            <div class="header p-2 px-2 flex items-center rounded-xl text-md text-slate-500 gap-4 cursor-pointer">
                <div class="">
                    <span>Product</span>
                    {{-- <span>Overview</span> --}
                </div>
                <ul class="relative ... text-md">
                    <li class="nonIcon"><i class="fa-solid fa-chevron-right"></i></li>
                    <li class="activeIcon"><i class="fa-solid fa-chevron-down"></i></li>
                </ul>
            </div>
            <div class="content transition-all duration-500 text-slate-500">
                <ul>
                    <li><span>Murid</span></li>
                    <li><span>Guru</span></li>
                    <li><span>Sekolah</span></li>
                </ul>
            </div>
        </div>
        <div class="Login text-slate-500 p-2 px-6 flex gap-4 md:h-20 lg:hidden">
            <a href="/daftar">
                <button>Daftar</button>
            </a>
            <a href="/login">
                <button>Login</button>
            </a>
        </div>
    </div>

    <div class="Sidebar fixed top-[64px] left-[0px] z-[999] xl:hidden w-full h-40 bg-white shadow-lg hidden text-white font-bold"
        id="accordion">
        <div class="w-2/4 rounded-lg overflow-hidden  bg-slate-800 flex flex-col gap-1">
            <div class="item">
                <div class="header p-6 bg-slate-600 flex justify-between items-center">
                    <div class="flex flex-col">
                        <span>STEP 1</span>
                        <span>Overview</span>
                    </div>
                    <ul>
                        <li class="nonIcon"><i class="fa-solid fa-chevron-down"></i></li>
                        <li class="activeIcon"><i class="fa-solid fa-chevron-up"></i></li>
                    </ul>
                </div>
                <div class="content transition-all duration-500">
                    <p>
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has
                        been the industry's standard dummy text ever since the 1500s, when an unknown printer took a
                        galley of type and scrambled it to make a type specimen book. It has survived not only five
                    </p>
                </div>
            </div>
            <div class="item">
                <div class="header p-6 bg-slate-600 flex justify-between items-center">
                    <div class="flex flex-col">
                        <span>STEP 1</span>
                        <span>Overview</span>
                    </div>
                    <ul>
                        <li class="nonIcon"><i class="fa-solid fa-chevron-down"></i></li>
                        <li class="activeIcon"><i class="fa-solid fa-chevron-up"></i></li>
                    </ul>
                </div>
                <div class="content transition-all duration-500">
                    <p>
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has
                        been the industry's standard dummy text ever since the 1500s, when an unknown printer took a
                        galley of type and scrambled it to make a type specimen book. It has survived not only five
                        versions of Lorem Ipsum.
                    </p>
                </div>
            </div>
        </div>
    </div>
</nav> --}}

{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="css/BelajarCerdas.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <nav>
        <div class="navbar fixed z-[999] bg-white shadow-lg text-[#60b5cf] font-bold gap-4 flex justify-between px-10">
            <a href="/" class="">
                <img src="image/logoBC.png" class="w-[80px]">
            </a>

            <div class="NavLink gap-2 hidden md:flex">
                <x-nav-active href="/murid" :active="request()->is('murid')">Murid</x-nav-active>
                <x-nav-active href="/guru" :active="request()->is('guru')">Guru</x-nav-active>
                <x-nav-active href="/sekolah" :active="request()->is('sekolah')">Sekolah</x-nav-active>
                <x-nav-active href="/post" :active="request()->is('post')">Post</x-nav-active>
                <x-nav-active href="/about" :active="request()->is('about')">About</x-nav-active>
            </div>

            <div class="auth gap-6 lg:flex md:hidden sm:hidden hidden">
                <a href="daftar">
                    <button class="bg-[#60b5cf] px-8 py-2 rounded-lg text-white text-sm font-bold">Daftar</button>
                </a>
                <a href="/login">
                    <button class="bg-[#60b5cf] px-8 py-2 rounded-lg text-white text-sm font-bold">Login</button>
                </a>
            </div>

            <ul class="bars lg:hidden md:flex px-6 list-none cursor-pointer">
                <li class="" onclick="openSidebar()" id="Show"><i class="fa-solid fa-bars"></i></li>
                <li class="hidden" onclick="hideSidebar()" id="Hide"><i class="fa-solid fa-xmark"></i></li>
            </ul>
        </div>


        <div class="Sidebar fixed top-[64px] left-[0px] z-[999] w-full h-auto bg-white shadow-lg font-bold pb-8 md:pb-0 hidden"
            id="accordion">
            <div class="item w-[250px] m-2 p-2 lg:hidden">
                <div class="header p-2 px-2 flex items-center rounded-xl text-md text-slate-500 gap-4 cursor-pointer">
                    <div class="">
                        <span>Product</span>
                        {{-- <span>Overview</span> --}
</div>
<ul class="relative ... text-md">
    <li class="nonIcon"><i class="fa-solid fa-chevron-right"></i></li>
    <li class="activeIcon"><i class="fa-solid fa-chevron-down"></i></li>
</ul>
</div>
<div class="content transition-all duration-500 text-slate-500">
    <ul>
        <li><span>Murid</span></li>
        <li><span>Guru</span></li>
        <li><span>Sekolah</span></li>
    </ul>
</div>
</div>
<div class="Login text-slate-500 p-2 px-6 flex gap-4 md:h-20 lg:hidden">
    <a href="/daftar">
        <button>Daftar</button>
    </a>
    <a href="/login">
        <button>Login</button>
    </a>
</div>
</div>

{{-- <div class="Sidebar fixed top-[64px] left-[0px] z-[999] xl:hidden w-full h-40 bg-white shadow-lg hidden text-white font-bold"
            id="accordion">
            <div class="w-2/4 rounded-lg overflow-hidden  bg-slate-800 flex flex-col gap-1">
                <div class="item">
                    <div class="header p-6 bg-slate-600 flex justify-between items-center">
                        <div class="flex flex-col">
                            <span>STEP 1</span>
                            <span>Overview</span>
                        </div>
                        <ul>
                            <li class="nonIcon"><i class="fa-solid fa-chevron-down"></i></li>
                            <li class="activeIcon"><i class="fa-solid fa-chevron-up"></i></li>
                        </ul>
                    </div>
                    <div class="content transition-all duration-500">
                        <p>
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has
                            been the industry's standard dummy text ever since the 1500s, when an unknown printer took a
                            galley of type and scrambled it to make a type specimen book. It has survived not only five
                        </p>
                    </div>
                </div>
                <div class="item">
                    <div class="header p-6 bg-slate-600 flex justify-between items-center">
                        <div class="flex flex-col">
                            <span>STEP 1</span>
                            <span>Overview</span>
                        </div>
                        <ul>
                            <li class="nonIcon"><i class="fa-solid fa-chevron-down"></i></li>
                            <li class="activeIcon"><i class="fa-solid fa-chevron-up"></i></li>
                        </ul>
                    </div>
                    <div class="content transition-all duration-500">
                        <p>
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has
                            been the industry's standard dummy text ever since the 1500s, when an unknown printer took a
                            galley of type and scrambled it to make a type specimen book. It has survived not only five
                            versions of Lorem Ipsum.
                        </p>
                    </div>
                </div>
            </div>
        </div> --}
    </nav>
</body>

<script>
    let items = document.querySelectorAll('#accordion .item .header');
    let lastClickedItem = null; // Variabel untuk melacak item terakhir yang diklik

    items.forEach((item) => {
        item.addEventListener("click", (e) => {
            const clickedItem = e.currentTarget.closest(".item");

            // Jika item yang diklik adalah item terakhir yang diklik, maka toggle class active
            if (lastClickedItem === clickedItem) {
                // Hapus active class dari item terakhir yang diklik
                clickedItem.classList.remove('active');
                lastClickedItem = null; // Reset variabel item terakhir yang diklik
            } else {
                // Hapus active class dari semua item
                items.forEach((header) => {
                    header.closest('.item').classList.remove('active');
                });

                // Tambahkan active class ke item yang diklik
                clickedItem.classList.add('active');
                lastClickedItem = clickedItem; // Update variabel item terakhir yang diklik
            }
        });
    });
</script>

<script>
    var sidebar = document.querySelector('.Sidebar');
    var open = document.getElementById('Show');
    var close = document.getElementById('Hide');

    function openSidebar() {
        sidebar.style.display = "block";
        open.style.display = "none";
        close.style.display = "block";
    }

    function hideSidebar() {
        close.style.display = "none";
        open.style.display = "block";
        sidebar.style.display = "none";
    }
</script>

</html> --}}
