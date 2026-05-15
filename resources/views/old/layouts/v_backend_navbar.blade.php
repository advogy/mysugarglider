<nav class="navbar navbar-expand navbar-light navbar-top">
    <div class="container-fluid">
        <a href="#" class="burger-btn d-block">
            <i class="bi bi-justify fs-3"></i>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-lg-0">

                <li class="nav-item dropdown me-3">
                    <a class="nav-link active dropdown-toggle text-gray-600" href="#" data-bs-toggle="dropdown"
                        data-bs-display="static" aria-expanded="false">
                        <i class="bi bi-bell bi-sub fs-4"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown"
                        aria-labelledby="dropdownMenuButton">
                        <li class="dropdown-header">
                            <h6>Notifications</h6>
                        </li>
                        <li>
                            <p class="text-center py-2 mb-0">
                                <a href="#">No notification</a>
                            </p>
                        </li>
                    </ul>
                </li>
            </ul>
            <div class="dropdown">
                <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="user-menu d-flex">
                        <div class="user-name text-end me-3">
                            <h6 class="mb-0 text-gray-600">{{ Auth::user()->name }}</h6>
                            <p class="mb-0 text-sm text-gray-600">{{ __('text.welcome') }}</p>
                        </div>
                        <div class="user-img d-flex align-items-center">
                            <div class="avatar avatar-md">
                                @if (Auth::user()->avatar)
                                    <img src="{{ asset('/upload/avatars/' . Auth::user()->avatar) }} " />
                                @else
                                    <img src="{{ asset('/assets/images/no-image.png') }}" height="150"
                                        class="avatar avatar-xl" />
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton"
                    style="min-width: 11rem">
                    <li>
                        <h6 class="dropdown-header">Hello, {{ Auth::user()->name }}</h6>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('profile') }}">
                            <i class="icon-mid bi bi-person me-2"></i>
                            {{ __('text.profile') }}
                        </a>
                    </li>

                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-navbar').submit();">
                            <i class="icon-mid bi bi-box-arrow-left me-2"></i>
                            {{ __('text.logout') }}
                        </a>
                        <form id="logout-form-navbar" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
