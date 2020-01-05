@extends('layout.main')

@section('javascript')

    <script src="/js/app.js"></script>

@endsection

@section('content')
    <style>
        .list-col {
            width: 200px;
            margin-left: 15px;
        }
        .list-col.empty > div {
            background: #f8f6f9;
            height: 80px;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .list-col.empty > div::before {
            content: 'Drag here';
        }
        .list-container {
            display: flex;
            margin-left: -15px;
            padding: 15px;
        }
        .list-item {
            display: block;
            padding: 15px;
            cursor: pointer;
        }
        .list-item.first {
            background: rgba(191, 64, 64, 0.2);
            border: 2px solid rgba(191, 64, 64, 0.2);
        }
        .list-item.second {
            background: rgba(128, 191, 64, 0.2);
            border: 2px solid rgba(128, 191, 64, 0.2);
        }
        .list-item.third {
            background: rgba(64, 191, 191, 0.2);
            border: 2px solid rgba(64, 191, 191, 0.2);
        }
        .list-item.fourth {
            background: rgba(128, 64, 191, 0.2);
            border: 2px solid rgba(128, 64, 191, 0.2);
        }

        .ghost {
          
            background-color: pink !important;
        }

    </style>


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

    <p>Create List</p>

    <form method="POST" action="/tasks/{{ $board->id }}">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Name
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="name" type="text" placeholder="List Name">
        </div>

        <button type="submit" class="font-bold py-2 px-4 rounded bg-green-500 text-white hover:bg-green-700">Create List</button>
    </form>

    <p>Lists</p>

    <div class="list">
        <div class="list-container">

        @foreach($tasks as $task)

            <div class="list-item">
              <h3 class="list-title">{{ $task->name }}</h3>

              <ul class="card-list">
                @foreach($cards as $card)
                    @if ($card->task_id === $task->id )
                        <li class="card-item">{{ $card->name }}</li>
                    @endif
                @endforeach
              </ul>

              <div class="card-add">
                <form method="POST" action="/cards/{{ $task->id }}">
                    @csrf 

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Name
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="name" type="text" placeholder="Card Name">
                    </div>

                    <button type="submit" class="card-add-button">Add card</button>
                </form>

              </div>
            </div>

        @endforeach

        <div id="app">

            <Board></Board>

        </div>
              
        </div>
    </div>

@endsection
