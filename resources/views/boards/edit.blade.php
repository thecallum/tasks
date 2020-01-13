@extends('layout.main')

@section('content')
    <h1>Edit Board</h1>

    <div>
        <form action="/boards/{{ $board->id }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label for="username">
                    Name
                </label>
                <input name="name" type="text" placeholder="Board Name" value={{ $board->name }}>
            </div>

            <div class="mb-4">
                <button type="submit" href="/boards/create">Update Board</button>
            </div>

            @if ($errors)
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            @endif
        </form>
    </div>

@endsection