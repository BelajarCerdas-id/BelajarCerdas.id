// show upload image tanya (for student)
function previewImage(event) {
    var file = event.target.files[0];
    var reader = new FileReader();
    reader.onload = function() {
        var output = document.getElementById('imagePreview');
        var textOutput = document.getElementById('textPreview');
        output.innerHTML = '<img src="' + reader.result +
            '" alt="Image Preview" class="w-full h-full object-cover">';
        textOutput.innerHTML = 'Klik, pastikan gambar tidak blur!';
    };
        reader.readAsDataURL(file);
    }

// popup result upload image
function openModal() {
    var imgSrc = document.querySelector('#imagePreview img').src;
    var modalImage = document.getElementById('modalImage');
    modalImage.src = imgSrc;
    document.getElementById('my_modal_1').showModal();
}

function closeModal() {
    document.getElementById('my_modal_1').close();
}
