function resetBatchDropdown() {
    const dataFeatureVariantId = $('#input-feature-variant-id').val();
    if (!dataFeatureVariantId) return;

    $.ajax({
        url: '/english-zone/purchase/dropdown-batches/' + dataFeatureVariantId,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            $('#batch_id').empty().append(
                '<option value="" class="hidden">Choose Batch</option>'
            );

            $.each(data.data, function (i, item) {
                $('#batch_id').append(`
                    <option value="${item.id}" data-start-date="${item.startDate}" data-end-date="${item.endDate}">
                        ${item.batch_name} - ${item.startDay} ${item.display_name}
                    </option>
                `);
            });
        }
    });
}

$(document).ready(function () {
    var oldBatch = $('#batch_id').val();
    var oldDays = $('#days_id').val();
    var oldHours = $('#hours_id').val();

    const selectDays = document.getElementById('days_id');
    const selectHours = document.getElementById('hours_id');

    function enableSelectDays() {
        selectDays.disabled = false;
        selectDays.classList.replace('opacity-50', 'opacity-100');
        selectDays.classList.replace('!cursor-default', 'cursor-pointer');
    }

    function enableSelectHours() {
        selectHours.disabled = false;
        selectHours.classList.replace('opacity-50', 'opacity-100');
        selectHours.classList.replace('!cursor-default', 'cursor-pointer');
    }

    // === Feature Variant Trigger Change -> Batch ===
    $('#input-feature-variant-id').on('change', function () {
        // panggil function resetBatchDropdown
        resetBatchDropdown();
    });
    // === Dropdown Batch -> Days ===
    $('#batch_id').on('change', function () {

        $('#input-batch-id').val(this.value); // set batch value ke input hidden

        let startDate = $(this).find(':selected').data('start-date');
        let endDate = $(this).find(':selected').data('end-date');

        $('#masa-aktif').text(startDate + ' - ' + endDate);

        var batch_id = $(this).val();
        if (batch_id) {
            $.ajax({
                url: '/english-zone/purchase/dropdown-days/' + batch_id,
                type: 'GET',
                dataType: 'json',
                success: function (data) {

                    enableSelectDays(); // enabled select days

                    $('#days_id').empty().append(
                        '<option value="" class="hidden">Choose Day</option>'
                    );

                    $('#hours_id').empty().append('<option value="" class="hidden">Choose Hour</option>').prop('disabled', true).addClass('opacity-50 !cursor-default');
                    $('#input-batch-schedule-group').val('');
                    $('#input-batch-schedule-id').val('');
                    validatePurchase($('#input-feature-variant-id').val());

                    $.each(data, function (i, group) {
                        let days = group.days.join(' & ');
                        $('#days_id').append(`
                                <option value="${group.group_id}">${days}</option>
                            `
                        );
                    });
                }
            });
        } else {
            $('#days_id').empty();
        }
    });

    // Trigger jika ada oldBatch (misalnya reload form karena error validasi)
    if (oldBatch) {
        $('#batch_id').val(oldBatch).trigger('change');
    }

    // === Dropdown Days -> Hours ===
    $('#days_id').on('change', function () {

        $('#input-batch-schedule-group').val(this.value); // set batch schedule group value ke input hidden

        var group_id = $(this).val();
        var batch_id = $('#batch_id').val();
        var level_id = $('#input-level-id').val();
        var feature_variant_id = $('#input-feature-variant-id').val();

        // Reset hidden input & revalidate button
        $('#input-batch-schedule-id').val('');
        validatePurchase($('#input-feature-variant-id').val());

        if (group_id && batch_id) {
            $.ajax({
                url: '/english-zone/purchase/dropdown-hours/' + batch_id + '/' + group_id + '/' + level_id + '/' + feature_variant_id,
                type: 'GET',
                dataType: 'json',
                success: function (data) {

                    enableSelectHours(); // enabled select hours

                    $('#hours_id').empty().append(
                        '<option value="" class="hidden">Choose Hour</option>'
                    );

                    $.each(data.data, function (i, hour) {
                        let count = data.studentCounts[hour.schedule_time_group] ?? 0;
                        $('#hours_id').append(`
                            <option value="${hour.schedule_time_group}" data-batch-schedule-id="${hour.ids}">
                                ${hour.time} ${count} / 10
                            </option>
                        `);
                    });
                }
            });
        } else {
            $('#hours_id').empty();
        }
    });

    // Trigger jika ada oldBatch (misalnya reload form karena error validasi)
    if (oldDays) {
        $('#days_id').val(oldDays).trigger('change');
    }

    $('#hours_id').on('change', function () {
        // ambil attribute dari data-batch-schedule-id pada dropdown hours, lalu set data ke value batch_schedule_id
        let selected = $(this).find(':selected');
        // ambil nilai dari attribute data-batch-schedule-id
        let batchScheduleId = selected.data('batch-schedule-id');
        // set nilai itu ke input #input-batch-schedule-id
        $('#input-batch-schedule-id').val(batchScheduleId);

        validatePurchase($('#input-feature-variant-id').val());
    })
});