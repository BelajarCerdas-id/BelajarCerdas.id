// DROPDOWN BERTINGKAT UNIT BY LEVEL
$(document).ready(function () {
    var oldLevel = $('#level_id').attr('data-old-level');
    var oldUnit = $('#unit_id').attr('data-old-unit');
    var selectUnit = document.getElementById('unit_id');

    function enableUnitDropdown() {
        selectUnit.disabled = false;
        selectUnit.classList.replace('opacity-50', 'opacity-100');
        selectUnit.classList.replace('!cursor-default', 'cursor-pointer');
    }

    $('#level_id').on('change', function () {
        var level_id = $(this).val();
        if (level_id) {
            $.ajax({
                url: '/english-zone/dropdown-bertingkat-unit/' + level_id,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#unit_id').empty();
                    $('#unit_id').append(
                        '<option value="" class="hidden">Pilih Unit</option>');

                    if (data.length > 0) {
                        enableUnitDropdown();

                        $.each(data, function (key, unit) {
                            $('#unit_id').append(
                                '<option value="' + unit.id + '"' +
                                (oldUnit == unit.id ? ' selected' : '') +
                                '>' + unit.unit_name + '</option>'
                            );
                        });

                        if (oldUnit) {
                            $('#unit_id').val(oldUnit).trigger('change');
                            oldUnit = null; // Reset agar tidak digunakan lagi
                        }
                    }
                }
            });
        } else {
            $('#unit_id').empty();
        }
    });

    // Trigger hanya jika ada oldLevel (misalnya setelah validasi error)
    if (oldLevel) {
        $('#level_id').val(oldLevel).trigger('change');
    }
});