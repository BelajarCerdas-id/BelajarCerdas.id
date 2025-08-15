    function toggleDropdown() {
        let dropdownButton = document.querySelector('#dropdownButton #dropdown');
        dropdown.classList.toggle("hidden");
        // let dropdownArrow = document.getElementById('dropdownArrow').classList.toggle('rotate-180'); using rotate style
        let dropdownArrow = document.getElementById('dropdownArrow');
    }

    function updateSelection(radio, countTanyaDaily) {
        const selectedKurikulum = document.getElementById("selectedKurikulum");
        const label = radio.nextElementSibling;
        const selectedLabelText = label.querySelector('span').textContent;
        const inputMapel = document.getElementById("id_mapel");
        const inputTarifKoin = document.getElementById('harga_koin');

        selectedKurikulum.textContent = selectedLabelText;
        inputMapel.value = radio.value;

        if (countTanyaDaily >= 3) {
            const selectedKoin = document.getElementById("selectedKoin");
            const selectedIconKoin = document.getElementById("selectedIconKoin");
            const selectedLabelKoin = label.querySelector('.koin').textContent;
            const koinValue = parseInt(selectedLabelKoin, 10) || 0;

            const selectedLabelIconKoin = label.querySelector('.iconKoin').cloneNode(true);
            selectedKoin.textContent = selectedLabelKoin;
            selectedIconKoin.innerHTML = '';
            selectedIconKoin.appendChild(selectedLabelIconKoin);

            inputTarifKoin.value = koinValue;
        } else {
            // Gratis → harga_koin = 0
            inputTarifKoin.value = 0;
        }

        $('#harga_koin').trigger('change');
        $('#id_mapel').trigger('change');
        toggleDropdown();
    }



    $(document).ready(function () {
        var oldFase = $('#id_fase').attr('data-old-fase');
        var oldKelas = $('#id_kelas').attr('data-old-kelas'); // Ambil kelas yang dipilih jika ada
        var oldMapel = $('#id_mapel').attr('data-old-mapel'); // Ambil mapel yang dipilih jika ada
        var oldBab = $('#id_bab').attr('data-old-bab'); // Ambil bab yang dipilih jika ada

        var selectKelas = document.getElementById('id_kelas');
        var selectMapel = document.getElementById('id_mapel');
        var selectBab = document.getElementById('id_bab');

        const coinImg = $('#id_mapel').attr('data-coin-image');

        $('#id_fase').on('change', function() {
            var fase_id = $(this).val();
            if (fase_id) {
                $.ajax({
                    url: '/kelas/' + fase_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        selectKelas.disabled =
                            false; // untuk menonaktifkan disabled pada select kelas ketika fase sudah dipilih
                        selectKelas.classList.replace('cursor-default', 'cursor-pointer');
                        selectKelas.classList.replace('opacity-50', 'opacity-100');
                        $('#id_kelas').empty();
                        $('#id_kelas').append(
                            '<option value="" class="hidden">Pilih Kelas</option>'
                        );
                        $.each(data, function(key, kelas) {
                            $('#id_kelas').append(
                                '<option value="' + kelas.id + '"' +
                                (oldKelas == kelas.id ? ' selected' : '') +
                                '>' +
                                kelas.kelas + '</option>'
                            );
                        });

                        if (oldKelas) {
                            $('#id_kelas').val(oldKelas).trigger('change');
                        }
                    }
                });
            } else {
                $('#id_kelas').empty();
            }
        });

        if (oldFase) {
            $('#id_fase').val(oldFase).trigger('change');
        }

        // Ketika id_fase berubah
        $('#id_fase').on('change', function() {
            var fase_id = $(this).val();
            if (fase_id) {
                $.ajax({
                    url: '/mapel/' + fase_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        const dropdown = $('#dropdown');
                        const titleDropdown = $('#selectedKurikulum');
                        const mapel = data.mata_pelajaran;
                        const countTanyaDaily = data.countTanyaDaily;
                        const remainingTanyaDaily = data.remainingTanyaDaily;
                        dropdown.empty(); // kosongkan dropdown sebelumnya

                        // ENABLE mapel
                        $('#dropdownButton')
                            .removeClass('pointer-events-none opacity-50');

                        titleDropdown.text('Pilih Mata Pelajaran');

                        if (mapel.length === 0) {
                            dropdown.append(
                                `<div class="px-4 py-2 text-xs text-gray-500">Tidak ada mapel</div>`
                            );
                            return;
                        }

                        mapel.forEach(function (mata_pelajaran, index) {
                            if (countTanyaDaily >= 3) {
                                dropdown.append(`
                                    <input type="radio" name="radio" id="drop${index}" value="${mata_pelajaran.id}" class="hidden" onchange="updateSelection(this, ${countTanyaDaily})">
                                    <label for="drop${index}" class="flex justify-between items-center hover:bg-gray-100 w-full h-10 px-4 cursor-pointer checked-dropdown-mapel">
                                        <span class="text-xs">${mata_pelajaran.mata_pelajaran}</span>
                                        <div class="text-md flex gap-[4px] items-center text-black font-normal">
                                            <span class="iconKoin">
                                                <img src="${coinImg}" alt="" class="w-[20px] pointer-events-none">
                                            </span>
                                            <span class="koin text-xs font-bold opacity-70">${mata_pelajaran.harga_koin ?? 0} Koin</span>
                                        </div>
                                    </label>
                                `);
                            } else {
                                dropdown.append(`
                                    <input type="radio" name="radio" id="drop${index}" value="${mata_pelajaran.id}" class="hidden" onchange="updateSelection(this, ${countTanyaDaily})">
                                    <label for="drop${index}" class="flex justify-between items-center hover:bg-gray-100 w-full h-10 px-4 cursor-pointer checked-dropdown-mapel">
                                        <span class="text-xs">${mata_pelajaran.mata_pelajaran}</span>
                                            <div class="text-md flex gap-[4px] items-center text-black font-normal">
                                                <span class="text-xs font-bold opacity-70">Tersisa ${remainingTanyaDaily} pertanyaan</span>
                                            </div>
                                    </label>
                                `);
                            }
                        });

                        // Jika ada mapel yang dipilih sebelumnya
                        // Restore old value jika ada
                        if (oldMapel) {
                            const oldInput = $(`input[name="radio"][value="${oldMapel}"]`);
                            if (oldInput.length) {
                                const label = oldInput
                                    .next(); // ambil label setelah input radio
                                const selectedText = label.find('span').text();

                                $('#selectedKurikulum').text(selectedText);
                                $('#id_mapel').val(oldInput.val());
                                oldInput.prop('checked', true); // tandai sebagai terpilih
                            }
                        }

                    }
                });
            }
        });

        // Jika ada fase yang dipilih sebelumnya, set dan trigger mapel
        if (oldFase) {
            $('#id_fase').val(oldFase).trigger('change');
        }

        // Ketika id_mapel berubah
        $('#id_mapel').on('change', function() {
            var mapel_id = $(this).val();
            if (mapel_id) {
                $.ajax({
                    url: 'tanya/bab/' + mapel_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        selectBab.disabled =
                            false; // untuk menonaktifkan disabled pada select kelas ketika fase sudah dipilih
                        selectBab.classList.replace('cursor-default', 'cursor-pointer');
                        selectBab.classList.replace('opacity-50', 'opacity-100');
                        $('#id_bab').empty();
                        $('#id_bab').append(
                            '<option value="" class="hidden">Pilih Bab</option>'
                        );
                        $.each(data, function(key, nama_bab) {
                            $('#id_bab').append(
                                '<option value="' + nama_bab.id + '"' +
                                (oldBab == nama_bab.id ? ' selected' :
                                    '') +
                                '>' +
                                nama_bab.nama_bab + '</option>'
                            );
                        });

                        // Set nilai bab yang dipilih jika ada
                        if (oldBab) {
                            $('#id_bab').val(oldBab).trigger('change');
                        }
                    }
                });
            } else {
                $('#id_bab').empty();
            }
        });

        // Jika ada mapel yang dipilih sebelumnya, set
        if (oldMapel) {
            $('#id_mapel').val(oldMapel).trigger('change');
        }
    });
