<x-admin-layout>
<body>
    <x-dashboard-navbar route="/dashboard/comments"/>

    <section class="dashboard">
        <form action="{{ route('comments.update', $comment->id) }}" id="edit_comment" method="POST">
            @csrf
            @method('PATCH')
            <div class="welcome-2">Edytuj komentarz</div>
            <div class="body_form">
                <label>Imię i/lub Nazwisko</label>
                <input type="text" name="name" autocomplete="off" value="{{ $comment->name }}">
                <label>Tekst</label>
                <textarea name="body">{{ $comment->body }}</textarea>
                <input type="submit" value="Edytuj">
            </div>
        </form>
    </section>
</x-admin-layout>