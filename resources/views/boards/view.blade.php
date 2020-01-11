@extends('layout.main')

@section('javascript')

    <script src="/js/app.js"></script>

@endsection

@section('content')
    <h1>{{ $board->name }}</h1>

    <div class="mb-4">
        <a class="font-bold py-2 px-4 rounded bg-blue-500 text-white hover:bg-blue-700" href="/boards/{{ $board->id }}/edit">Edit Board</a>
    </div>

    <div class="mb-4">
        <form method="POST" action="/boards/{{ $board->id }}">
            @method('DELETE')
            @csrf

            <button type="submit" class="font-bold py-2 px-4 rounded bg-red-500 text-white hover:bg-red-700">Delete Board</button>
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
