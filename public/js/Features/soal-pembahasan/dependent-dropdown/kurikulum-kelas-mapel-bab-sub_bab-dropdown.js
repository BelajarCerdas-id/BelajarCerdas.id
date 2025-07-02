$(document).ready(function () {
        var oldKurikulum = $('#id_kurikulum').attr('data-old-kurikulum');
        var oldKelas = $('#id_kelas').attr('data-old-kelas'); // Ambil kelas yang dipilih jika ada
        var oldMapel = $('#id_mapel').attr('data-old-mapel'); // Ambil mapel yang dipilih jika ada
        var oldBab = $('#id_bab').attr('data-old-bab'); // Ambil bab yang dipilih jika ada
        var oldSubBab = $('#id_sub_bab').attr('data-old-sub_bab'); // Ambil sub bab yang dipilih jika ada

        var selectKelas = document.getElementById('id_kelas');
        var selectMapel = document.getElementById('id_mapel');
        var selectBab = document.getElementById('id_bab');
        var selectSubBab = document.getElementById('id_sub_bab');

        $('#id_kurikulum').on('change', function() {
            var kurikulum_id = $(this).val();
            if (kurikulum_id) {
                $.ajax({
                    url: '/kurikulum/kelas/' + kurikulum_id,
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

        if (oldKurikulum) {
            $('#id_kurikulum').val(oldKurikulum).trigger('change');
        }

        // Ketika id_kelas berubah
        $('#id_kelas').on('change', function() {
            var kelas_id = $(this).val();
            if (kelas_id) {
                $.ajax({
                    url: '/kelas/mapel/' + kelas_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        selectMapel.disabled =
                            false; // untuk menonaktifkan disabled pada select kelas ketika fase sudah dipilih
                        selectMapel.classList.replace('cursor-default', 'cursor-pointer');
                        selectMapel.classList.replace('opacity-50', 'opacity-100');
                        $('#id_mapel').empty();
                        $('#id_mapel').append(
                            '<option value="" class="hidden">Pilih Mata Pelajaran</option>'
                        );
                        $.each(data, function(key, mapel) {
                            $('#id_mapel').append(
                                '<option value="' + mapel.id + '"' +
                                (oldMapel == mapel.id ? ' selected' : '') +
                                '>' +
                                mapel.mata_pelajaran + '</option>'
                            );
                        });

                        if (oldMapel) {
                            $('#id_mapel').val(oldMapel).trigger('change');
                        }
                    }
                });
            } else {
                $('#id_mapel').empty();
            }
        });

        // Jika ada kelas yang dipilih sebelumnya, set dan trigger mapel
        if (oldKelas) {
            $('#id_kelas').val(oldKelas).trigger('change');
        }

        // Ketika id_mapel berubah
        $('#id_mapel').on('change', function() {
            var mapel_id = $(this).val();
            if (mapel_id) {
                $.ajax({
                    url: '/soal-pembahasan/bab/' + mapel_id,
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

        // Ketika id_bab berubah
        $('#id_bab').on('change', function() {
            var bab_id = $(this).val();
            if (bab_id) {
                $.ajax({
                    url: '/sub-bab/' + bab_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        selectSubBab.disabled =
                            false; // untuk menonaktifkan disabled pada select kelas ketika fase sudah dipilih
                        selectSubBab.classList.replace('cursor-default', 'cursor-pointer');
                        selectSubBab.classList.replace('opacity-50', 'opacity-100');
                        $('#id_sub_bab').empty();
                        $('#id_sub_bab').append(
                            '<option value="" class="hidden">Pilih Sub Bab</option>'
                        );
                        $.each(data, function(key, sub_bab) {
                            $('#id_sub_bab').append(
                                '<option value="' + sub_bab.id + '"' +
                                (oldSubBab == sub_bab.id ? ' selected' :
                                    '') +
                                '>' +
                                sub_bab.sub_bab + '</option>'
                            );
                        });

                        // Set nilai bab yang dipilih jika ada
                        if (oldSubBab) {
                            $('#id_sub_bab').val(oldSubBab).trigger('change');
                        }
                    }
                });
            } else {
                $('#id_sub_bab').empty();
            }
        });

        // Jika ada bab yang dipilih sebelumnya, set
        if (oldBab) {
            $('#id_bab').val(oldBab).trigger('change');
        }
    });
