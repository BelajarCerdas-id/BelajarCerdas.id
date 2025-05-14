<x-script></x-script>
<h2>Form Komentar</h2>

<form id="comment-form">
    <input type="text" id="message" placeholder="Tulis komentar...">
    <button type="submit">Kirim</button>
</form>

<script>
    document.getElementById('comment-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const message = document.getElementById('message').value;

        fetch("{{ route('comments.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                message
            })
        }).then(() => {
            document.getElementById('message').value = '';
            alert('Komentar dikirim!');
        });
    });
</script>
