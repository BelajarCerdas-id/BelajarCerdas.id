<div class="relative  w-full h-auto border-red-500 border-2 flex flex-col overflow-hidden">
    <div class="relative ... w-full h-auto flex flex-col" id="contentUnanswered">
        <div class="p-6">
            @if (isset($historyStudent) && is_iterable($historyStudent) && $historyStudent->isNotEmpty())
                @foreach ($historyStudent as $item)
                    <div class="flex gap-8 leading-8 mt-6">
                        <div class="mb-10">
                            @if (!empty($item->image_tanya))
                                <div class="w-[60px] h-[75px]">
                                    <img src="{{ asset('images_tanya/' . $item->image_tanya) }}" alt=""
                                        class="h-full w-full">
                                </div>
                            @else
                                <div class="w-[60px] h-[85px] flex items-center text-xs bg-white shadow-md rounded-md">
                                    <span class="text-center w-full">No <br>Image</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs">{{ $item->kelas }}</span>
                            <span class="text-sm">{{ $item->mapel }}</span>
                            <span class="text-sm font-bold">{{ $item->bab }}</span>
                            <div class="">
                                <i class="fa-solid fa-clock text-gray-400"></i>
                                <span
                                    class="text-xs">{{ $item->created_at->locale('id')->translatedFormat('l, d-M-Y, H:i:s') }}</span>
                            </div>
                        </div>
                    </div>
                    <hr class="border-2">
                @endforeach
            @else
                <div class="h-full w-full flex justify-center items-center">
                    <span>Belum ada riwayat harian</span>
                </div>
            @endif
        </div>
    </div>
    <div class="border-yellow-500 border-2 relative ... right-[-100%]" id="contentAnswer">
        <div class="p-6">
            @if (isset($historyStudentAnswered) && is_iterable($historyStudentAnswered) && $historyStudentAnswered->isNotEmpty())
                @foreach ($historyStudentAnswered as $item)
                    <div class="flex gap-8 leading-8 mt-6">
                        <div class="mb-10">
                            @if (!empty($item->image_tanya))
                                <div class="w-[60px] h-[75px]">
                                    <img src="{{ asset('images_tanya/' . $item->image_tanya) }}" alt=""
                                        class="h-full w-full">
                                </div>
                            @else
                                <div class="w-[60px] h-[85px] flex items-center text-xs bg-white shadow-md rounded-md">
                                    <span class="text-center w-full">No <br>Image</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs">{{ $item->kelas }}</span>
                            <span class="text-sm">{{ $item->mapel }}</span>
                            <span class="text-sm font-bold">{{ $item->bab }}</span>
                            <div class="">
                                <i class="fa-solid fa-clock text-gray-400"></i>
                                <span
                                    class="text-xs">{{ $item->created_at->locale('id')->translatedFormat('l, d-M-Y, H:i:s') }}</span>
                            </div>
                        </div>
                    </div>
                    <hr class="border-2">
                @endforeach
            @else
                <div class="h-full w-full flex justify-center items-center">
                    <span>Belum ada riwayat terjawab</span>
                </div>
            @endif
        </div>
    </div>
    <div class="border-green-500 border-2 relative ... w-full right-[-100%]" id="contentReject">
        <div class="p-6">
            @if (isset($historyStudentReject) && is_iterable($historyStudentReject) && $historyStudentReject->isNotEmpty())
                @foreach ($historyStudentReject as $item)
                    <div class="flex gap-8 leading-8 mt-6">
                        <div class="mb-10">
                            @if (!empty($item->image_tanya))
                                <div class="w-[60px] h-[75px]">
                                    <img src="{{ asset('images_tanya/' . $item->image_tanya) }}" alt=""
                                        class="h-full w-full">
                                </div>
                            @else
                                <div class="w-[60px] h-[85px] flex items-center text-xs bg-white shadow-md rounded-md">
                                    <span class="text-center w-full">No <br>Image</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs">{{ $item->kelas }}</span>
                            <span class="text-sm">{{ $item->mapel }}</span>
                            <span class="text-sm font-bold">{{ $item->bab }}</span>
                            <div class="">
                                <i class="fa-solid fa-clock text-gray-400"></i>
                                <span
                                    class="text-xs">{{ $item->created_at->locale('id')->translatedFormat('l, d-M-Y, H:i:s') }}</span>
                            </div>
                        </div>
                    </div>
                    <hr class="border-2">
                @endforeach
            @else
                <div class="h-full w-full flex justify-center items-center">
                    <span>Belum ada riwayat ditolak</span>
                </div>
            @endif
        </div>
    </div>
</div>


<div class="bg-white border h-28 text-sm flex flex-col flex-1 p-4">
    <div class="flex">
        <div class="flex items-center gap-4"
            onclick="showImage('{{ asset('images_catatan/' . $item->image_catatan) }}')">
            <img src="${application.image_catatan ? '/images_catatan/' + application.image_catatan : ''}"
                class="h-20 w-[100px] bg-cover bg-fit cursor-pointer" onclick="showData(this)"
                data-image="${application.image_catatan ? '/images_catatan/' + application.image_catatan : ''}"
                data-catatan="${application.catatan}">
        </div>
        <div class="flex flex-col leading-6">
            <span>${application.kelas_catatan}</span>
            <span>${application.bab}</span>
            <div class="flex items-center gap-2 mt-2">
                <i class="fas fa-user text-[--color-default]"></i>
                <span>${application.nama_lengkap}</span>
            </div>
        </div>
        <i class="fas fa-chevron-right flex items-center text-[--color-default] font-bold cursor-pointer"
            onclick="showData(this)"
            data-image="${application.image_catatan ? '/images_catatan/' + application.image_catatan : ''}"
            data-catatan="${application.catatan}"></i>
    </div>
</div>


<div class="bg-white border-[1px] h-28 text-sm flex items-center gap-4 relative">
    <div class="ml-4 flex">
        <div class="flex items-center gap-4"
            onclick="showImage('{{ asset('images_catatan/' . $item->image_catatan) }}')">
            <img src="${application.image_catatan ? '/images_catatan/' + application.image_catatan : ''}"
                class="h-20 w-[100px] bg-cover bg-fit cursor-pointer" onclick="showData(this)"
                data-image="${application.image_catatan ? '/images_catatan/' + application.image_catatan : ''}"
                data-catatan ="${application.catatan}" </div>
            <div class="flex flex-col leading-6">
                <span>${application.kelas_catatan}</span>
                <span>${application.mapel}</span>
                <span>${application.bab}</span>
                <div class="flex items-center gap-2 mt-2">
                    <i class="fas fa-user text-[--color-default]"></i>
                    <span class="text-sm">${application.nama_lengkap}</span>
                </div>
            </div>
            <i class="fas fa-chevron-right absolute right-4 text-[--color-default] font-bold cursor-pointer"
                onclick="showData(this)"
                data-image ="${application.image_catatan ? '/images_catatan/' + application.image_catatan : ''}"
                data-catatan ="${application.catatan}"></i>
        </div>
    </div>


    <div class="bg-white border-4 w-[350px] flex justify-between">
        <div class="flex">
            <div class="flex items-center gap-4"
                onclick="showImage('{{ asset('images_catatan/' . $item->image_catatan) }}')">
                <img src="${application.image_catatan ? '/images_catatan/' + application.image_catatan : ''}"
                    class="h-20 w-[100px] bg-cover bg-fit cursor-pointer" onclick="showData(this)"
                    data-image="${application.image_catatan ? '/images_catatan/' + application.image_catatan : ''}"
                    data-catatan="${application.catatan}">
            </div>
            <div class="flex flex-col leading-6">
                <span class="text-xs">${application.kelas_catatan}</span>
                <span class="text-xs">${application.bab}</span>
                <div class="flex items-center gap-2 mt-2">
                    <i class="fas fa-user text-[--color-default]"></i>
                    <span>${application.nama_lengkap}</span>
                </div>
            </div>
        </div>
    </div>
