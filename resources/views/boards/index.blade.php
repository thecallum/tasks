@extends('layout.main')

@section('content')

    <section class="section">
        <h1 class="title">Boards</h1>
        <h2 class="subtitle">View boards</h2>

        <div class="list is-hoverable">
            @foreach($boards as $board)
                <a href="/boards/{{ $board->id }}" class="list-item has-background-white has-text-dark">    
                    {{ $board->name }}
                </a>
            @endforeach
        </div>

        <a class="button is-primary" href="/boards/create">Create New Board</a>
    </section>

@endsection
