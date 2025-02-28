$(document).ready(function() {
        // 1. Ambil daftar provinsi dari API Emsifa
        fetch("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json", function(data) {
            $.each(data, function(index, provinsi) {
                $('#provinsi').append(
                    `<option value="${provinsi.id}">${provinsi.name}</option>`);
            });
        });

        // 2. Ambil Kabupaten/Kota berdasarkan Provinsi
        $('#provinsi').change(function() {
            let provId = $(this).val();
            $('#kota').empty().append('<option>Pilih Kota</option>');
            fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provId}.json`,
                function(data) {
                    $.each(data, function(index, kota) {
                        $('#kota').append(
                            `<option value="${kota.id}">${kota.name}</option>`);
                    });
                });
        });

        // 3. Ambil Kecamatan berdasarkan Kota
        $('#kota').change(function() {
            let kotaId = $(this).val();
            $('#kecamatan').empty().append('<option>Pilih Kecamatan</option>');
            fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${kotaId}.json`,
                function(data) {
                    $.each(data, function(index, kecamatan) {
                        $('#kecamatan').append(
                            `<option value="${kecamatan.id}">${kecamatan.name}</option>`
                        );
                    });
                });
        });

        // 4. Ambil daftar sekolah berdasarkan kecamatan → GUNAKAN PROXY!
        document.getElementById("kecamatan").addEventListener("change", function() {
            let kecamatanKode = this.value;
            let selectSekolah = document.getElementById("sekolah");
            selectSekolah.innerHTML = '<option>Loading...</option>';

            // 🚀 Gunakan proxy backend untuk menghindari CORS
            fetch(`/api/sekolah?kecamatan=${kecamatanKode}`)
                .then(response => response.json()) // Response harus JSON
                .then(data => {
                    selectSekolah.innerHTML = ''; // Kosongkan sebelum isi ulang
                    if (data.length > 0) {
                        data.forEach(sekolah => {
                            let option = document.createElement("option");
                            option.value = sekolah.npsn;
                            option.textContent = sekolah.nama;
                            selectSekolah.appendChild(option);
                        });
                    } else {
                        selectSekolah.innerHTML = '<option>Tidak ada sekolah</option>';
                    }
                })
                .catch(error => console.error("Error fetching schools:", error));
        });
    });
