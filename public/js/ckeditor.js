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
                fontSize: {
                    options: [ 'tiny', 'small', 'default', 'big', 'huge' ],
                    supportAllValues: true
                },
                fontColor: {
                    supportAllValues: true
                },
                highlight: {
                    options: [
                        { model: 'yellowMarker', class: 'marker-yellow', title: 'Yellow', color: 'var(--ck-highlight-marker-yellow)', type: 'marker' },
                        { model: 'greenMarker', class: 'marker-green', title: 'Green', color: 'var(--ck-highlight-marker-green)', type: 'marker' },
                        { model: 'pinkMarker', class: 'marker-pink', title: 'Pink', color: '#ffc0cb', type: 'marker' },
                        { model: 'blueMarker', class: 'marker-blue', title: 'Blue', color: '#add8e6', type: 'marker' }
                    ]
                },
                htmlSupport: {
                    allow: [
                        {
                            name: /.*/,
                            attributes: true,
                            classes: true,
                            styles: true
                        }
                    ]
                }
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
