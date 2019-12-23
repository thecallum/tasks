@extends('layout.main')

@section('content')
    <h1>{{ $board->name }}</h1>

    <div class="mb-4">
        <a class="font-bold py-2 px-4 rounded bg-blue-500 text-white hover:bg-blue-700" href="/boards/{{ $board->id }}/edit">Edit Board</a>
    </div>

    <div class="mb-4">
        <form method="POST" action="/boards/2">
            @method('DELETE')
            @csrf

            <button type="submit" class="font-bold py-2 px-4 rounded bg-red-500 text-white hover:bg-red-700">Delete Board</button>
        </form>
    </div>

@endsection
