function previewPDF(event, target) {
    var file = event.target.files[0];

    // Ambil elemen DOM
    var textOutput = document.getElementById('textPreview-' + target);
    var textSize = document.getElementById('textSize-' + target);
    var textPages = document.getElementById('textPages-' + target);
    var textCircle = document.getElementById('textCircle-' + target);
    var pdfPreviewContainer = document.getElementById('pdfPreviewContainer-' + target);
    var pdfLogo = document.getElementById('pdfLogo-' + target);
    var fileArrowUp = document.getElementById('fileArrowUp-' + target);

    // === CASE: USER CANCEL / HAPUS FILE ===
    if (!file) {
        textOutput.innerHTML = '';
        textSize.innerHTML = '';
        textPages.innerHTML = '';
        textCircle.innerHTML = '';
        pdfLogo.src = '';
        pdfLogo.classList.add("hidden");

        fileArrowUp.style.display = 'block';
        pdfPreviewContainer.style.display = 'flex'; // tetap tampil wrapper
        return;
    }

    // === RESET ICON DAN TAMPILAN ===
    fileArrowUp.style.display = 'none';
    pdfLogo.classList.remove("hidden");

    // Update informasi file
    textOutput.innerHTML = truncateText(file.name, 20);
    textSize.innerHTML = formatFileSize(file.size);
    textCircle.innerHTML = "<i class='fas fa-circle text-gray-500'></i>";

    var extension = file.name.split('.').pop().toLowerCase();

    if (extension === 'pdf') {
        var reader = new FileReader();
        reader.onload = function () {
            var pdfData = new Uint8Array(reader.result);
            pdfjsLib.getDocument(pdfData).promise.then(function (pdf) {
                textPages.innerHTML = pdf.numPages + " " + (pdf.numPages > 1 ? 'Pages' : 'Page');
                pdfLogo.src = '/image/bulkUpload-file-logo/logo-pdf.png';
            });
        };
        reader.readAsArrayBuffer(file);

    } else {
        alert('Please upload a valid PDF file.');
        // reset input supaya bisa pilih ulang
        event.target.value = '';
        previewPDF(event, target);
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