    <header class="bg-indigo-600 flex justify-between items-center px-6 h-16 text-white">
        <div> 
            <div class="text-4xl font-light">Tasks</div>
        </div>


        <nav>
            @guest

                <a class="hover:underline" href="{{ route('login') }}">Login</a>
                <a class="hover:underline" href="{{ route('register') }}">Register</a>

            @else

                <a class="hover:underline" href="/boards">Boards</a>

                <a class="hover:underline" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    Logout
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @endguest


             
    
            </nav>
    </header>