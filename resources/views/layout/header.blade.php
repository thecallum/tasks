    <header>
        <div> 
            <div>Tasks</div>
        </div>


        <nav>
            @guest

                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>

            @else

                <a href="/boards">Boards</a>

                <a href="{{ route('logout') }}"
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