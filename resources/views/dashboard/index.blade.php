<x-admin-layout>
    <x-dashboard-navbar />
    
    <section class="dashboard">
        <img src="{{ asset('images/moon.jpg') }}" id="dashboard__image">
        <p class="welcome">Witaj w Panelu Administracyjnym!</p>
        <p class="name_profile">{{ Auth::User()->firstname . ' ' . Auth::User()->lastname }}</p>
        <div class="actions_home">
            <div class="connected">
                @can('post-create')
                    <a href="{{ route('posts.create') }}" class="button">
                        <i class="fa-solid fa-plus"></i>
                        <p>Dodaj post</p>
                    </a>
                @endcan
                @can('post-list')
                    <a href="{{ route('posts.index') }}" class="button">
                        <i class="fa-solid fa-newspaper"></i>
                        <p>Przeglądaj posty</p>
                    </a>
                @endcan
            </div>
            <div class="connected">
                @can('user-create')
                    <a href="/dashboard/users/create" class="button">
                        <i class="fa-solid fa-user-plus"></i>
                        <p>Dodaj użytkownika</p>
                    </a>
                @endcan
                @can('user-list')
                    <a href="/dashboard/users" class="button">
                        <i class="fa-solid fa-user-gear"></i>
                        <p>Zarządzaj użytkownikami</p>
                    </a>
                @endcan
            </div>
            @can('comment-list')
                <a href="{{ route('comments.index') }}" class="button">
                    <i class="fa-solid fa-comments"></i>
                    <p>Przeglądaj komentarze</p>
                </a>
            @endcan
            <div class="connected">
                @can('role-create')
                    <a href="/dashboard/roles/create" class="button">
                        <i class="fa-solid fa-wrench"></i>
                        <p>Dodaj role</p>
                    </a>
                @endcan
                @can('role-list')
                    <a href="/dashboard/roles" class="button">
                        <i class="fa-solid fa-toolbox"></i>
                        <p>Przeglądaj role</p>
                    </a>
                </div>
                @endcan
        </div>
    </section>
</x-admin-layout>