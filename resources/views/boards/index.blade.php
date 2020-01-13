@extends('layout.main')

@section('content')

    <h1>Home</h1>

    <p>View boards</p>

    <ul>
        @foreach($boards as $board)
            <li><a href="/boards/{{ $board->id }}">{{ $board->name }}</a></li>
        @endforeach
    </ul>

    <a href="/boards/create">Create New Board</a>
@endsection
