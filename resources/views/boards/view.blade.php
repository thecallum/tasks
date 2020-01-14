@extends('layout.main')

@section('javascript')

    <script src="/js/app.js"></script>

@endsection

@section('content')
    <section class="section">

    <div class="level">
        <div class="level-left">
            <div class="level-item">
                <h1 class="title">{{ $board->name }}</h1>
            </div>
        </div>

        <div class="level-right">
            <div class="level-item"><a class="button" href="/boards/{{ $board->id }}/edit">Edit</a></div>
            <div class="level-item">
                <form method="POST" action="/boards/{{ $board->id }}">
                    @method('DELETE')
                    @csrf

                    <button class="button is-danger" type="submit">Delete</button>
                </form>
            </div>
        </div>
    </div>
    </section>

    <section class="section">
    
        <div id="app">
            <Board
                :list-data="{{ $tasks }}"
                :card-data="{{ $cards }}"
                board-id="{{ $board->id }}"
            ></Board>
        </div>

    </section>

    

@endsection
