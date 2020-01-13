<header>
  <nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
      <a class="navbar-item" href="/">
        <img src="https://bulma.io/images/bulma-logo.png" width="112" height="28">
      </a>
      <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
      </a>
    </div>
    <div id="navbarBasicExample" class="navbar-menu">
      <div class="navbar-start">
        @auth
        <a class="navbar-item" href="/boards/">
        Boards
        </a>
        @endauth
      </div>
      <div class="navbar-end">
        <div class="navbar-item">
          <div class="buttons">

            @auth

              <a class="button is-light" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                Logout
              </a>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
              </form>

            @else

              <a class="button is-primary" href="{{ route('register') }}">
                <strong>Sign up</strong>
              </a>
              <a class="button is-light" href="{{ route('login') }}"> 
                Log in
              </a>

            @endauth

          </div>
        </div>
      </div>
    </div>
  </nav>
</header>
