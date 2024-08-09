<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
    <div class="container">
        @auth
            @if (Auth::user()->hasRole('admin'))
                <!-- Si el usuario es admin, redirige al /users -->
                <a class="navbar-brand" href="{{ url('/users') }}">{{ env('APP_NAME') }}</a>
            @else
                <!-- Si el usuario es comprador o no está autenticado, redirige al / -->
                <a class="navbar-brand" href="{{ url('/') }}">{{ env('APP_NAME') }}</a>
            @endif
        @else
            <!-- Si el usuario no está autenticado, redirige al / -->
            <a class="navbar-brand" href="{{ url('/') }}">{{ env('APP_NAME') }}</a>
        @endauth

        {{-- Hamburguesa --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">
                <!-- Puedes agregar enlaces de navegación adicionales aquí -->
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                Inicio de sesión
                            </a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Registro</a>
                        </li>
                    @endif
                @else
                    @role('buyer')
                        {{-- Cart --}}
                        @if(session('cartId'))
                            <a class="dropdown-item" href="{{ route('cart.show') }}">
                                Ir al Carrito
                                <cart-icon />
                            </a>
                        @else
                            <p>No se encontró el carrito.</p>
                        @endif
                    @endrole

                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <img src="{{ Auth::user()->file->route }}" alt="Foto de perfil" class="rounded-circle me-2" width="40" height="40">
                            <span class="fw-bold">{{ Auth::user()->capital_letters['name'] }}</span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                            @role('admin')
                                {{-- User --}}
                                <a class="dropdown-item" href="{{ route('users.index') }}">
                                    Usuarios
                                </a>
                                {{-- Products --}}
                                <a class="dropdown-item" href="{{ route('products.index') }}">
                                    Productos
                                </a>
                                {{-- Category --}}
                                <a class="dropdown-item" href="{{ route('categories.index') }}">
                                    Categorías
                                </a>
                            @endrole

                            {{-- Logout --}}
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Cerrar sesión
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
