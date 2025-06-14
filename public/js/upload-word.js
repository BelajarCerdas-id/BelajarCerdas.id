function previewWord(event, target) {
    var file = event.target.files[0];

    if (file && file.name.endsWith('.docx')) {
        var textOutput = document.getElementById('textPreview-' + target);
        var textSize = document.getElementById('textSize-' + target);
        var textPages = document.getElementById('textPages-' + target);
        var textCircle = document.getElementById('textCircle-' + target);
        var wordPreviewContainer = document.getElementById('wordPreviewContainer-' + target);
        var wordLogo = document.getElementById('logo-' + target);

        textOutput.innerHTML = truncateText(file.name, 20);
        textSize.innerHTML = formatFileSize(file.size);
        textCircle.innerHTML = "<i class='fas fa-circle text-gray-500'></i>";
        wordPreviewContainer.classList.remove('hidden');

        // Path logo word
        wordLogo.src = "/image/bulkUpload-file-logo/logo-word.png";
        textPages.innerHTML = "DOCX";
    } else {
        alert('Please upload a valid word file.');
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
