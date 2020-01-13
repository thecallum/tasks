@extends('layout.main')

@section('content')
    <h1>Create a new Board</h1>

    <div>
        <formaction="/boards" method="POST">
            @csrf

            <div>
                <label for="username">
                    Name
                </label>
                <input name="name" type="text" placeholder="Board Name">
            </div>

            <div>
                <button type="submit" href="/boards/create">Create New Board</button>
            </div>

            @if ($errors)
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            @endif
        </form>
    </div>

@endsection
