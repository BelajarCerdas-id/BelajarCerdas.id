<x-script></x-script>

<h2>Daftar Komentar</h2>

<ul id="comments-list">
    @foreach ($comments as $comment)
        <li>{{ $comment->message }}</li>
    @endforeach
</ul>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        window.Echo.channel('comments')
            .listen('.comment.created', (e) => {
                // console.log('✅ Komentar diterima dari broadcast:', e);
                const li = document.createElement('li');
                li.textContent = e.comment.message;
                document.getElementById('comments-list').appendChild(li);
            });
    });
</script>
