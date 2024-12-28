document.addEventListener('DOMContentLoaded', () => {
    const uploadUrl = document.getElementById('editor-container').getAttribute('data-upload-url');
    const deleteUrl = document.getElementById('editor-container').getAttribute('data-delete-url');
    let previousImageUrlsMap = {};

    // Cari semua elemen dengan class "editor"
    const editors = document.querySelectorAll('.editor');

    // Loop melalui semua elemen dan inisialisasi CKEditor
    editors.forEach((textarea, index) => {
        ClassicEditor
            .create(textarea, {
                ckfinder: {
                    uploadUrl: uploadUrl, // Endpoint untuk upload gambar
                },
                // toolbar: {
                //     items: [
                //         'undo', 'redo',
                //         '|',
                //         'heading',
                //         '|',
                //         'fontfamily', 'fontsize', 'fontColor', 'fontBackgroundColor',
                //         '|',
                //         'bold', 'italic', 'strikethrough', 'subscript', 'superscript', 'code',
                //         '|',
                //         'link', 'uploadImage', 'blockQuote', 'codeBlock',
                //         '|',
                //         'alignment',
                //         '|',
                //         'bulletedList', 'numberedList', 'todoList', 'outdent', 'indent'
                //     ],
                //     shouldNotGroupWhenFull: true // ini true agar ketika lebar editor sudah tidak sesuai maka toolbar akan dibungkus ke dalam baris baru (simple nya responsif)
                // }
                toolbar: {
                    shouldNotGroupWhenFull: true
                }
            })
            .then(editor => {
                const editorInstance = editor;

                // Inisialisasi array untuk menyimpan URL gambar per editor
                previousImageUrlsMap[index] = [];

                // Pantau perubahan konten editor
                editorInstance.model.document.on('change:data', () => {
                    const currentContent = editorInstance.getData();
                    const imageUrls = Array.from(currentContent.matchAll(/<img[^>]+src="([^">]+)"/g))
                        .map(match => match[1]);

                    // Cari URL gambar yang dihapus
                    const removedImages = previousImageUrlsMap[index].filter(url => !imageUrls.includes(url));

                    // Kirim request untuk menghapus gambar yang dihapus
                    removedImages.forEach(url => {
                        console.log('Menghapus gambar:', url);
                        fetch(deleteUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                imageUrl: url
                            })
                        })
                            .then(response => response.json())
                            .then(data => console.log('Gambar berhasil dihapus:', data))
                            .catch(error => console.error('Error saat menghapus gambar:', error));
                    });

                    // Perbarui URL gambar sebelumnya untuk editor ini
                    previousImageUrlsMap[index] = imageUrls;
                });
            })
            .catch(error => console.error('Error pada CKEditor:', error));
    });
});
