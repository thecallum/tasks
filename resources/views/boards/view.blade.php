@extends('layout.main')

@section('javascript')

    <script src="/js/app.js"></script>

@endsection

@section('content')
<section class="section has-background-primary">

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

    <section style="flex-grow:1">
    
        <div id="app" style="height: 100%;">
            <Board
                :list-data="{{ $tasks }}"
                :card-data="{{ $cards }}"
                board-id="{{ $board->id }}"
            ></Board>
        </div>

    </section>





    <!-- <div class="tile is-ancestor">
  <div class="tile is-vertical is-8">
    <div class="tile">
      <div class="tile is-parent is-vertical">
        <article class="tile is-child notification is-primary">
          <p class="title">Vertical...</p>
          <p class="subtitle">Top tile</p>
        </article>
        <article class="tile is-child notification is-warning">
          <p class="title">...tiles</p>
          <p class="subtitle">Bottom tile</p>
        </article>
      </div>
      <div class="tile is-parent">
        <article class="tile is-child notification is-info">
          <p class="title">Middle tile</p>
          <p class="subtitle">With an image</p>
          <figure class="image is-4by3">
            <img src="https://bulma.io/images/placeholders/640x480.png">
          </figure>
        </article>
      </div>
    </div>
    <div class="tile is-parent">
      <article class="tile is-child notification is-danger">
        <p class="title">Wide tile</p>
        <p class="subtitle">Aligned with the right tile</p>
        <div class="content">
        
        </div>
      </article>
    </div>
  </div>
  <div class="tile is-parent">
    <article class="tile is-child notification is-success">
      <div class="content">
        <p class="title">Tall tile</p>
        <p class="subtitle">With even more content</p>
        <div class="content">
         
        </div>
      </div>
    </article>
  </div>
</div> -->
    

@endsection
