    <header class="bg-indigo-600 flex justify-between items-center px-6 h-16 text-white">
        <div> 
            <div class="text-4xl font-light">Tasks</div>
        </div>


        <nav>
            @auth
                <a class="hover:underline" href="{{ url('/home') }}">Home</a>
            @else
                <a class="hover:underline" href="{{ route('login') }}">Login</a>
                <a class="hover:underline" href="{{ route('register') }}">Register</a>
            @endauth
    
            </nav>
    </header>