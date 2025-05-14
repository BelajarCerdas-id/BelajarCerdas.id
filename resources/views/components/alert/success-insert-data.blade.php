<x-script></x-script>
<div class=" w-full flex justify-center">
    <div class="fixed z-[9999]">
        <div id="alertSuccess"
            class="relative top-[-45px] opacity-100 scale-90 bg-green-200 w-max p-3 flex items-center space-x-2 rounded-lg shadow-lg transition-all duration-300 ease-out">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current text-green-600" fill="none"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-green-600 text-sm">{{ $message }}</span>
            <i class="fas fa-times cursor-pointer text-green-600" id="btnClose"></i>
        </div>
    </div>
</div>
