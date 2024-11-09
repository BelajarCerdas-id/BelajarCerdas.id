<x-layout>

</x-layout>

<article class="py-8 max-w-screen-md">
    <h2 class="mb-1 text-3xl tracking-tight font-bold text-gray-900">{{ $post['title'] }}</h2>
    <div class="text-base text-gray-500">
        <a href="">{{ $post['author'] }}</a> | {{ $post->created_at->diffForHumans() }}
    </div>
    <p class="my-4 font-light">
        {{ $post['body'] }}
    </p>
    <a href="/post" class="font-medium text-blue-500">&laquo;Back to article</a>
</article>
