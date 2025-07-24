    function selectPayment(radio) {
        const logo = radio.dataset.logo;
        const name = radio.dataset.name;
        const id = radio.dataset.id;
        const type = radio.closest('.content-method-payment').querySelector('.title-method').innerText;

        document.getElementById('input-payment-method').value = name;
        // Update elemen utama
        const container = document.querySelector('.selected-payment');
        container.innerHTML = `
            <div class="border-2 border-gray-300 w-full h-[69px] rounded-lg flex justify-between items-center px-4 cursor-pointer"
                onclick="my_modal_2.showModal()">
                <div class="flex gap-2 items-center">
                    <div>
                        <img src="${logo}" alt="${name}" class="w-[55px]">
                    </div>
                    <div class="flex flex-col">
                        <span class="text-md">${type}</span>
                        <span class="text-sm font-bold">${name}</span>
                    </div>
                </div>
                <i class="fa solid fa-chevron-down"></i>
            </div>
        `;

        // Tutup modal setelah pilih
        document.getElementById('my_modal_2').close();
    }
