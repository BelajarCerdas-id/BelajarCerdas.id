function previewWord(event, target) {
    var file = event.target.files[0];

    var textOutput = document.getElementById('textPreview-' + target);
    var textSize = document.getElementById('textSize-' + target);
    var textPages = document.getElementById('textPages-' + target);
    var textCircle = document.getElementById('textCircle-' + target);
    var wordPreviewContainer = document.getElementById('wordPreviewContainer-' + target);
    var wordLogo = document.getElementById('logo-' + target);

    // Jika tidak ada file (user cancel), reset preview
    if (!file) {
        // reset word preview
        $('#wordPreviewContainer-bulkUpload-word').addClass('hidden');
        $('#textPreview-bulkUpload-word').text('');
        $('#textSize-bulkUpload-word').text('');
        $('#textPages-bulkUpload-word').text('');
        $('#textCircle-bulkUpload-word').html('');
        $('#logo-bulkUpload-word img').attr('src', '').hide();
        return;
    }

    if (file && file.name.endsWith('.docx')) {
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
