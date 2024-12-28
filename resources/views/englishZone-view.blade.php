<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script> {{-- script pdf.js --}}
<style>
    /* Optional: Styling for the PDF viewer */
    #pdf-container {
        height: 800px;
        overflow: auto;
    }

    canvas {
        height: auto;
    }
</style>
<div id="pdf-container"></div>

<script>
    var url = '{{ $fileUrl }}'; // URL yang dihasilkan oleh Laravel untuk file PDF

    pdfjsLib.getDocument(url).promise.then(function(pdf) {
        var viewer = document.getElementById('pdf-container');
        var numPages = pdf.numPages;

        // Loop untuk merender semua halaman PDF
        for (var pageNum = 1; pageNum <= numPages; pageNum++) {
            pdf.getPage(pageNum).then(function(page) {
                var scale = 1.5;
                var viewport = page.getViewport({
                    scale: scale
                });

                var canvas = document.createElement('canvas');
                viewer.appendChild(canvas);
                var context = canvas.getContext('2d');

                canvas.height = viewport.height;
                canvas.width = viewport.width;

                page.render({
                    canvasContext: context,
                    viewport: viewport
                });
            });
        }
    });
</script>

{{-- <iframe src="https://docs.google.com/viewer?url={{ $fileUrl }}&embedded=true" width="600" height="500"></iframe> --}}
