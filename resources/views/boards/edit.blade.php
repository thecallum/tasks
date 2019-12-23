@extends('layout.main')

@section('content')
    <h1>Edit Board</h1>

    <div class="w-full ">
        <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" action="/boards/{{ $board->id }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                    Name
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="name" type="text" placeholder="Board Name" value={{ $board->name }}>
            </div>

            <div class="mb-4">
                <button type="submit" class="font-bold py-2 px-4 rounded bg-blue-500 text-white hover:bg-blue-700" href="/boards/create">Update Board</button>
            </div>

            @if ($errors)
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            @endif
        </form>
    </div>

@endsection