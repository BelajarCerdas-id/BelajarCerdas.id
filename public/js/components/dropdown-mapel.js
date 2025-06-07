    function toggleDropdown() {
        let dropdownButton = document.querySelector('#dropdownButton #dropdown');
        dropdown.classList.toggle("hidden");
        let dropdownArrow = document.getElementById('dropdownArrow').classList.toggle('rotate-180');
    }

    function updateSelection(radio) {
        const selectedKurikulum = document.getElementById("selectedKurikulum");
        const selectedKoin = document.getElementById("selectedKoin");
        const selectedIconKoin = document.getElementById("selectedIconKoin");

        const label = radio.nextElementSibling;

        const selectedLabelText = label.querySelector('span').textContent;
        const selectedLabelKoin = label.querySelector('.koin').textContent;
        // kalo mau nyalin selain text (image, font awesome, svg, dll pake clodeNode)
        const selectedLabelIconKoin = label.querySelector('.iconKoin').cloneNode(true);
        const inputMapel = document.getElementById("id_mapel");
        const inputTarifKoin = document.getElementById('harga_koin');

        selectedKurikulum.textContent = selectedLabelText;
        selectedKoin.textContent = selectedLabelKoin;
        selectedIconKoin.innerHTML = '';
        selectedIconKoin.appendChild(selectedLabelIconKoin);

        // Set hidden input mapel dan trigger event change
        inputMapel.value = radio.value;
        $('#id_mapel').trigger('change');

        // Set hidden tarif koin dan trigger event change
        inputTarifKoin.value = selectedLabelKoin;
        $('#harga_koin').trigger('change');

        // Menyembunyikan dropdown setelah memilih
        toggleDropdown();
    }
