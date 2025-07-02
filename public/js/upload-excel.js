function previewExcel(event, target) {
    var file = event.target.files[0];
    var textOutput = document.getElementById('textPreview-' + target);
    var textSize = document.getElementById('textSize-' + target);
    var textPages = document.getElementById('textPages-' + target);
    var textCircle = document.getElementById('textCircle-' + target);
    var excelPreviewContainer = document.getElementById('excelPreviewContainer-' + target);
    var excelLogo = document.getElementById('pdfLogo-' + target);

    // Jika tidak ada file (user cancel), reset preview
    if (!file) {
        // reset excel preview
        $('#excelPreviewContainer-bulkUpload-excel').addClass('hidden');
        $('#textPreview-bulkUpload-excel').text('');
        $('#textSize-bulkUpload-excel').text('');
        $('#textPages-bulkUpload-excel').text('');
        $('#textCircle-bulkUpload-excel').html('');
        $('#logo-bulkUpload-excel img').attr('src', '').hide();
        return;
    }

    if (file && file.name.endsWith('.xlsx')) {

        textOutput.innerHTML = truncateText(file.name, 20);
        textSize.innerHTML = formatFileSize(file.size);
        textCircle.innerHTML = "<i class='fas fa-circle text-gray-500'></i>";
        excelPreviewContainer.classList.remove('hidden');

        // Path logo Excel
        excelLogo.src = "/image/bulkUpload-file-logo/logo-excel.png";
        textPages.innerHTML = "XLSX";
    } else {
        alert('Please upload a valid Excel file.');
    }
}

function formatFileSize(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1048576) return (bytes / 1024).toFixed(2) + ' KB';
    return (bytes / 1048576).toFixed(2) + ' MB';
}

function truncateText(text, maxLength) {
    return text.length > maxLength ? text.substring(0, maxLength) + "..." : text;
}
