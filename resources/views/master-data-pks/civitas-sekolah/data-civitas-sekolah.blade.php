@include('components/sidebar_beranda')
@extends('components/sidebar_beranda_mobile')


@if (session('user')->status === 'Admin Sales')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            <main>
                <section>
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12 sm:col-span-6 xl:col-span-4 shadow-lg bg-white px-6 py-6 flex gap-4">
                            <div class="bg-[#A294F9] w-20 h-18 flex items-center justify-center rounded-xl text-white">
                                <i class="fa-solid fa-user text-2xl"></i>
                            </div>
                            <div class="flex flex-col leading-8">
                                <span class="">Murid</span>
                                <span>Total : {{ $getMurid->count() }} Murid</span>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 xl:col-span-4 shadow-lg bg-white px-6 py-6 flex gap-4">
                            <div
                                class="bg-[--color-three] w-20 h-18 flex items-center justify-center rounded-xl text-white">
                                <i class="fa-solid fa-user text-2xl"></i>
                            </div>
                            <div class="flex flex-col leading-8">
                                <span class="">Guru</span>
                                <span>Total :</span>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 xl:col-span-4 shadow-lg bg-white px-6 py-6 flex gap-4">
                            <div
                                class="bg-[--color-three] w-20 h-18 flex items-center justify-center rounded-xl text-white">
                                <i class="fa-solid fa-user text-2xl"></i>
                            </div>
                            <div class="flex flex-col leading-8">
                                <span class="">Admin</span>
                                <span>Total :</span>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>
@else
    <p>You do not have access to this pages.</p>
@endif
