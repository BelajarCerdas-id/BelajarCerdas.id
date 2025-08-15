function previewPDF(event, target) {

    var file = event.target.files[0];
    var reader = new FileReader();

    if (file && file.type === 'application/pdf') {
        var textOutput = document.getElementById('textPreview-' + target);
        var textSize = document.getElementById('textSize-' + target);
        var textPages = document.getElementById('textPages-' + target);
        var textCircle = document.getElementById('textCircle-' + target);
        var pdfPreviewContainer = document.getElementById('pdfPreviewContainer-' + target);
        var pdfLogo = document.getElementById('pdfLogo-' + target);
        textOutput.innerHTML = truncateText(file.name, 20);
        textSize.innerHTML = formatFileSize(file.size);
        textCircle.innerHTML = "<i class='fas fa-circle text-gray-500'></i>";
        pdfPreviewContainer.style.display = 'flex';
        reader.onload = function() {
            var pdfData = new Uint8Array(reader.result);
            pdfjsLib.getDocument(pdfData).promise.then(function (pdf) {
                textPages.innerHTML = pdf.numPages + " " + (pdf.numPages > 1 ? 'Pages' : 'Page');
                pdf.getPage(1).then(function(page) {
                    var scale = 0.3;
                    var viewport = page.getViewport({ scale: scale });
                    var canvas = document.createElement('canvas');
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;
                    page.render({ canvasContext: context, viewport: viewport }).promise.then(function() {
                        pdfLogo.src = 'image/logo-pdf.png';
                    });
                });
            });
        };
        reader.readAsArrayBuffer(file);
    } else {
        alert('Please upload a valid PDF file.');
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
