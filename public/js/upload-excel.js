function previewExcel(event, target) {
    var file = event.target.files[0];

    if (file && file.name.endsWith('.xlsx')) {
        var textOutput = document.getElementById('textPreview-' + target);
        var textSize = document.getElementById('textSize-' + target);
        var textPages = document.getElementById('textPages-' + target);
        var textCircle = document.getElementById('textCircle-' + target);
        var excelPreviewContainer = document.getElementById('excelPreviewContainer-' + target);
        var excelLogo = document.getElementById('pdfLogo-' + target);

        textOutput.innerHTML = truncateText(file.name, 20);
        textSize.innerHTML = formatFileSize(file.size);
        textCircle.innerHTML = "<i class='fas fa-circle text-gray-500'></i>";
        excelPreviewContainer.classList.remove('hidden');

        // Path logo Excel
        excelLogo.src = "/image/logo-excel.png";
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
