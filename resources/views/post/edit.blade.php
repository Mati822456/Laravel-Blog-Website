<x-admin-layout :edit="true">
    @section('scripts')
        <script src="//cdn.quilljs.com/1.3.6/quill.js"></script>
        <link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        @vite(['resources/js/post.js'])
    @endsection

    <x-dashboard-navbar route="/dashboard/posts"/>

    <section class="post__create">
        <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" id="form">
            @csrf
            @method('PATCH')
            <div id="content" data-image-url="{{route('image.store')}}">
            </div>
            <div class="body_form">
                <div class="welcome-2">Edytuj post</div>
                @if(count($errors) > 0)
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
                <label>Obrazek</label>
                <div class="image_upload">
                    <input type="file" name="image" accept="image/*" onchange="loadFile(event)">
                </div>
                <label>Widoczność</label>
                <div class="published">
                    <p>Ustaw widoczność na publiczne</p>
                    <label class="switch">
                        <input type="checkbox" name="is_published" {{ $post->is_published == true ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                </div>
                <label>Nagłówek</label>
                <p class="info title_length">Maksymalnie 255 znaków. <span class='current_title_length'>{{ Str::length($post->title) }}/255</span></p>
                <input type="text" name="title" autocomplete="off" value="{{ $post->title }}">
                <label>Krótki opis</label>
                <p class="info excerpt_length">Maksymalnie 510 znaków. <span class='current_excerpt_length'>{{ Str::length($post->excerpt) }}/510</span></p>
                <textarea name="excerpt">{{ $post->excerpt }}</textarea>
                <label>Tekst</label>
                <div id="editor" name="body">
                    
                </div>
                <textarea name="body" style="display: none" id="hiddenArea">{!! $post->body !!}</textarea>
                <div class="preview_mode" onClick="showPreview();">Podgląd</div>
                <input type="submit" value="Opublikuj">
            </div>
        </form>
    </section>
    <aside class="post__preview">
        <div class="post_container">
            <div class="top">
                <img src="{{ asset($post->image_path) }}" id="output">
                <div class="info">
                    <p class="preview_title">{{ $post->title }}</p>
                    <p class="date">{{ $post->updated_at->format('d.m.Y') }} by {{ $post->user->firstname . ' ' . $post->user->lastname }}</p>
                </div>
            </div>
        </div>
        <div class="post_body">
            {!! $post->body !!}

            <div class="actions">
                <a href=""><i class="fa-solid fa-arrow-left"></i> Powrót do strony głównej</a>
                <a href="">Następny post <i class="fa-solid fa-arrow-right"></i></a>
            </div>

            <div class="exit_preview" onClick="exitPreview();">Do góry <i class="fa-solid fa-arrow-up"></i></div>
        </div>
    </aside>
</x-admin-layout>