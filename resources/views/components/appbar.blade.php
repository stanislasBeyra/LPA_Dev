<nav id="main-navbar" class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
    <!-- Container wrapper -->
    <div class="container-fluid">
        <!-- Toggle button -->
        <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Brand -->
        <a class="navbar-brand" href="/">
            <span class="orange pop">LP</span><span class="pop">A</span>
        </a>


        <div class="dropdown navbar-nav ms-auto d-flex flex-row">
            <div
                id="dropdownMenuButton"
                data-mdb-dropdown-init
                data-mdb-ripple-init
                aria-expanded="false">
                <i class="fas fa-user-circle text-primary fa-2x"></i>
            </div>

            <ul class="dropdown-menu p-3" aria-labelledby="dropdownMenuButton">
                <li class="d-flex align-items-center mb-2">
                    <i class="fas fa-user-circle fa-2x me-2"></i>
                    <div>
                        <span class="d-block fw-bold">{{ auth()->user()->username }}</span>
                        <small>
                            @php
                            switch(auth()->user()->role) {
                            case 1:
                            echo 'admin';
                            break;
                            case 2: 
                            echo 'client';
                            break;
                            case 3:
                            echo 'vendor';
                            break;
                            default:
                            echo 'inconnu';
                            }
                            @endphp
                        </small>
                    </div>
                </li>
                <hr>
                <li>
                    <a class="dropdown-item d-flex align-items-center" href="{{ route('content.page', ['page' => 'user-profile']) }}">
                        <i class="fas fa-user me-2"></i> Profile
                    </a>
                </li>
                <li>
                    <!-- Formulaire de déconnexion -->
                    <form action="{{ route('logout') }}" method="POST" style="display: none;" id="logoutForm">
                        @csrf
                    </form>

                    <a class="dropdown-item d-flex align-items-center" href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </li>

            </ul>
        </div>




    </div>
</nav>