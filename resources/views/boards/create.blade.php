@extends('layout.main')

@section('content')
    <section class="section">
        <h1 class="title">Create a new Board</h1>

        <form action="/boards" method="POST">
            @csrf

            <div class="field">
                <label class="label">Name</label>
                <div class="control">
                    <input class="input" name="name" type="text" placeholder="Board Name">
                </div>
            </div>

            <div class="control">
                <button class="button is-primary" type="submit">Create New Board</button>
            </div>

            @include('components.error')

        </form>

    </section>
@endsection
