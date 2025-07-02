// // Jalankan ketika seluruh dokumen HTML telah dimuat
// document.addEventListener('DOMContentLoaded', () => {
//     // Ambil semua elemen yang memiliki class "editor" (biasanya <textarea>)
//     const editors = document.querySelectorAll('.editor');

//     // Loop setiap editor dan inisialisasi CKEditor pada masing-masing
//     editors.forEach((textarea, index) => {
//         ClassicEditor
//             .create(textarea, {
//                 // Tambahkan plugin custom untuk upload image dengan base64 (tanpa server)
//                 extraPlugins: [ MyCustomBase64UploadAdapterPlugin ],
//                 toolbar: {
//                     // Toolbar tidak akan dikelompokkan meskipun penuh
//                     shouldNotGroupWhenFull: true,
//                 },
//             })
//     });
// });


// // Plugin custom untuk upload gambar menggunakan base64 (tidak dikirim ke server)
// function MyCustomBase64UploadAdapterPlugin(editor) {
//     // Ambil plugin bawaan CKEditor untuk menangani file upload
//     editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
//         // Return objek upload adapter
//         return {
//             // Fungsi upload dipanggil saat gambar dimasukkan ke editor
//             upload: () => {
//                 // Ambil file dari loader (misalnya file gambar)
//                 return loader.file.then(file => {
//                     return new Promise((resolve, reject) => {
//                         const reader = new FileReader();

//                         // Baca file sebagai data URL (base64)
//                         reader.readAsDataURL(file);

//                         // Jika berhasil, resolve hasil ke CKEditor (dengan format { default: URL })
//                         reader.onload = () => resolve({ default: reader.result });

//                         // Jika gagal, reject dengan error
//                         reader.onerror = error => reject(error);
//                     });
//                 });
//             }
//         };
//     };
// }

document.addEventListener('DOMContentLoaded', () => {
    const editorContainer = document.getElementById('editor-container');
    const uploadUrl = editorContainer.getAttribute('data-upload-url');
    const deleteUrl = editorContainer.getAttribute('data-delete-url');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let previousImageUrlsMap = {};

    const editors = document.querySelectorAll('.editor');

    editors.forEach((textarea, index) => {
        ClassicEditor
            .create(textarea, {
                ckfinder: {
                    uploadUrl: uploadUrl
                },
                toolbar: {
                    items: [
                        'undo', 'redo',
                        '|',
                        'heading',
                        '|',
                        'fontFamily', 'fontSize', 'fontColor', 'fontBackgroundColor',
                        '|',
                        'bold', 'italic', 'strikethrough', 'highlight', 'subscript', 'superscript',
                        '|',
                        'link', 'uploadImage', 'blockQuote', 'codeBlock',
                        '|',
                        'mathType', 'chemType',
                        '|',
                        'alignment',
                        '|',
                        'bulletedList', 'numberedList', 'outdent', 'indent'
                    ],
                    shouldNotGroupWhenFull: true
                },
            })
            .then(editor => {
                previousImageUrlsMap[index] = [];

                editor.model.document.on('change:data', () => {
                    const currentContent = editor.getData();

                    const imageUrls = Array.from(currentContent.matchAll(/<img[^>]+src="([^">]+)"/g))
                        .map(match => match[1]);

                    const removedImages = previousImageUrlsMap[index].filter(url => !imageUrls.includes(url));

                    removedImages.forEach(url => {
                        fetch(deleteUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({ imageUrl: url })
                        })
                        .then(response => response.json())
                        .then(data => console.log('Gambar berhasil dihapus:', data))
                        .catch(error => console.error('Error saat menghapus gambar:', error));
                    });

                    previousImageUrlsMap[index] = imageUrls;
                });
            })
            .catch(error => console.error('Error CKEditor:', error));
    });
});
