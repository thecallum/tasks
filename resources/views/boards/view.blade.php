@extends('layout.main')

@section('javascript')

    <script src="/js/app.js"></script>

@endsection

@section('content')
    <h1>{{ $board->name }}</h1>

    <div>
        <a href="/boards/{{ $board->id }}/edit">Edit Board</a>
    </div>

    <div>
        <form method="POST" action="/boards/{{ $board->id }}">
            @method('DELETE')
            @csrf

            <button type="submit">Delete Board</button>
        </form>
    </div>

    <div id="app">
        <Board
            :list-data="{{ $tasks }}"
            :card-data="{{ $cards }}"
            board-id="{{ $board->id }}"
        ></Board>
    </div>

@endsection
