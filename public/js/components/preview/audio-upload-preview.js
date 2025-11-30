function previewAudio(event, target) {
    var file = event.target.files[0];

    var textOutput = document.getElementById('textPreview-' + target);
    var textSize = document.getElementById('textSize-' + target);
    var textPages = document.getElementById('textPages-' + target);
    var textCircle = document.getElementById('textCircle-' + target);
    var wordPreviewContainer = document.getElementById('wordPreviewContainer-' + target);
    var wordLogo = document.getElementById('wordLogo-' + target);
    var fileArrowUp = document.getElementById('fileArrowUp-' + target);

    // reset jika tidak ada file
    if (!file) {
        textOutput.textContent = "";
        textSize.textContent = "";
        textPages.textContent = "";
        textCircle.textContent = "";
        wordLogo.innerHTML = "";
        wordPreviewContainer.classList.add("hidden");
        return;
    }

    // tampilkan container
    wordPreviewContainer.classList.remove('hidden');

    // tampilkan nama file
    textOutput.innerHTML = truncateText(file.name, 20);
    textSize.innerHTML = formatFileSize(file.size);
    textCircle.innerHTML = "<i class='fas fa-circle text-gray-500'></i>";
    textPages.innerHTML = "AUDIO";

    // masukkan icon audio
    wordLogo.innerHTML = "<i class='fa-solid fa-file-audio text-blue-600 text-4xl'></i>";
}

function formatFileSize(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1048576) return (bytes / 1024).toFixed(2) + ' KB';
    return (bytes / 1048576).toFixed(2) + ' MB';
}

function truncateText(text, maxLength) {
    return text.length > maxLength ? text.substring(0, maxLength) + "..." : text;
}
