<a href="{{ route('post.show', $post->slug) }}" class="read_post">
<div class="post">
    <img src="{{ asset($post->image_path) }}" alt="{{ $post->title }}">
    <div class="read"><i class="fa-solid fa-angles-right"></i>Przeczytaj</div>
    <div class="body">
        @if ($post->category)
            <div class="category" style="background: {{ $post->category->backgroundColor }}CC; color: {{ $post->category->textColor }}">{{ $post->category->name }}</div>
        @endif
        <p class="title">{{ $post->title }}</p>
        <div class="user">
            <img src="{{ asset($post->user->image_path) }}" alt="user">
            <p><span class="name">{{ $post->user->firstname . ' ' . $post->user->lastname }}</span><br><span class="date"> {{ \Carbon\Carbon::parse($post->created_at)->translatedFormat('d F, Y') }}</span></p>
        </div>
        <p class="short_body">{{ $post->excerpt }}</p>
    </div>
</div>
</a>
