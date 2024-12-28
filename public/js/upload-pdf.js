// Show upload PDF as thumbnail
// Fungsi untuk menampilkan PDF thumbnail dan informasi setelah file dipilih
function previewPDF(event) {
    var file = event.target.files[0];
    var reader = new FileReader();

    // Jika file dan tipe file adalah PDF
    if (file && file.type === 'application/pdf') {
        var fileName = file.name;
        var fileSize = file.size;
        var textOutput = document.getElementById('textPreview');
        var textOutput2 = document.getElementById('textSize');
        var textOutput3 = document.getElementById('textPages');
        var textOutput4 = document.getElementById('textCircle');
        var pdfPreviewContainer = document.getElementById('pdfPreviewContainer');
        var pdfThumbnail = document.getElementById('pdfThumbnail');
        var pdfLogo = document.getElementById('pdfLogo');  // Mengakses elemen logo PDF

        // Menampilkan nama file PDF di bawah gambar
        textOutput.innerHTML = truncateText(fileName, 54);
        textOutput2.innerHTML = formatFileSize(fileSize);
        textOutput4.innerHTML = "<i class='fas fa-circle text-[5px] text-gray-500'></i>";

        // Menampilkan kotak putih (pdfPreviewContainer) setelah file dipilih
        pdfPreviewContainer.style.display = 'flex';  // Menampilkan kotak putih

        reader.onload = function() {
            var pdfData = new Uint8Array(reader.result);

            // Menggunakan PDF.js untuk merender halaman pertama PDF sebagai thumbnail
            pdfjsLib.getDocument(pdfData).promise.then(function (pdf) {
                // Menampilkan jumlah halaman
                var totalPages = pdf.numPages;
                textOutput3.innerHTML = totalPages + " " + Pages(totalPages);

                pdf.getPage(1).then(function(page) {
                    var scale = 0.3;  // Skala untuk thumbnail
                    var viewport = page.getViewport({ scale: scale });

                    // Membuat elemen canvas sementara untuk rendering thumbnail
                    var canvas = document.createElement('canvas');
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render halaman pertama ke dalam canvas
                    page.render({
                        canvasContext: context,
                        viewport: viewport
                    }).promise.then(function() {
                        // Mengubah canvas menjadi data URL (gambar) dan menampilkannya
                        pdfLogo.src = 'image/logo-pdf.png'; // Menampilkan logo PDF
                        pdfThumbnail.src = canvas.toDataURL(); // Menampilkan gambar thumbnail PDF
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
    if (bytes < 1024) {
        return bytes + ' B'; // Jika ukuran file kurang dari 1 KB, tampilkan dalam byte
    } else if (bytes < 1048576) {
        return (bytes / 1024).toFixed(2) + ' KB'; // Jika ukuran file kurang dari 1 MB, tampilkan dalam KB
    } else if (bytes < 1073741824) {
        return (bytes / 1048576).toFixed(2) + ' MB'; // Jika ukuran file kurang dari 1 GB, tampilkan dalam MB
    } else {
        return (bytes / 1073741824).toFixed(2) + ' GB'; // Jika ukuran file lebih dari 1 GB, tampilkan dalam GB
    }
}

function Pages(totalPages) {
    if (totalPages > 1) {
        return 'Pages';
    } else if (totalPages == 1) {
        return 'Page';
    }
}

function truncateText(text, maxLength) {
    // Memeriksa apakah panjang teks lebih besar dari maxLength
    if (text.length > maxLength) {
        // Memotong teks dan menambahkan "..."
        return text.substring(0, maxLength) + "...";
    } else {
        return text;  // Jika panjang teks tidak melebihi maxLength, kembalikan teks asli
    }
}



// Popup result upload PDF (no change needed)
function openModal() {
    var imgSrc = document.querySelector('#pdfThumbnail').src;
    var modalImage = document.getElementById('modalImage');
    modalImage.src = imgSrc;
    document.getElementById('imageModal').showModal();
}

function closeModal() {
    document.getElementById('imageModal').close();
}
