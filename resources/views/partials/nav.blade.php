<div class="row">
    <div class="col">
        <nav class="navbar">
            <div class="container-fluid">
                <a class="navbar-brand text-wrap text-primary" href="/">
                    {{ config('app.name') }}
                </a>

                @auth
                    <div class="d-flex align-items-center">
                        <p class="m-0 mx-3">
                            Hello {{ auth()->user()->name ?? 'User' }}!
                        </p>

                        @if (auth()->user()->role_id == 2)
                            <a href="{{ route('product.index') }}" class="btn btn-outline-primary mx-1">
                                Manage Menu
                            </a>
                        @endif

                        <a role="button" href="{{ route('logout') }}"
                            class="text-wrap text-primary ml-auto text-capitalize text-decoration-none btn btn-outline-primary">
                            Sign out
                        </a>
                    </div>
                @endauth
            </div>
        </nav>

    </div>
</div>
