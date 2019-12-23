@extends('layout.main')

@section('content')

    <h1>Home</h1>

    <p>View boards</p>

    <ul>
        @foreach($boards as $board)
            <li><a href="/boards/{{ $board->id }}">{{ $board->name }}</a></li>
        @endforeach
    </ul>

    <a class="font-bold py-2 px-4 rounded bg-blue-500 text-white hover:bg-blue-700" href="/boards/create">Create New Board</a>
@endsection
