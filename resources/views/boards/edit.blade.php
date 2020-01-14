@extends('layout.main')

@section('content')
    <section class="section">
        <h1 class="title">Edit Board</h1>

        <form action="/boards/{{ $board->id }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="field">
                <label class="label">Name</label>
                <div class="control">
                    <input class="input" name="name" type="text" placeholder="Board Name" value="{{ $board->name }}">
                </div>
            </div>

            <div class="control">
                <button class="button is-primary" type="submit">Update Board</button>
            </div>

            @include('components.error')

        </form>
    </section>
@endsection