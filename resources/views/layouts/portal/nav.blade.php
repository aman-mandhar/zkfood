<header>
        <!-- Header Start -->
       <div class="header-area">
            <div class="main-header ">
                <div class="header-top black-bg d-none d-md-block">
                   <div class="container">
                       <div class="col-xl-12">
                            <div class="row d-flex justify-content-between align-items-center">
                                <div class="header-info-left">
                                    <ul>
                                        <li>{{ \Carbon\Carbon::now()->format('l, j F Y') }}</li>
                                    </ul>
                                </div>
                                <div class="header-info-right">
                                    <ul class="header-social">
                                        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                        <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                       <li> <a href="#"><i class="fab fa-pinterest-p"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                       </div>
                   </div>
                </div>
                <div class="header-mid d-none d-md-flex">
                    <div class="container header-mid__inner">
                        <div class="row d-flex align-items-center">
                            <div class="brand col-md-3">
                                <a href="index.html" class="brand__kv">
                                    <img src="{{ asset('images/kv_logo.png') }}" height="90" alt="ZK News badge">
                                </a>
                            </div>
                            <div class="header-banner--ad col-md-9 text-right" aria-hidden="true">
                                <!-- placeholder for banner / ad -->
                            </div>
                        </div>
                    </div>
                </div>
               <div class="header-bottom header-sticky">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-xl-10 col-lg-10 col-md-12 header-flex">
                                <!-- sticky -->
                                <div class="sticky-logo col-md-4">
                                    <a href="index.html"><img src="{{ asset('images/kv_logo.png') }}" height="50" alt="KV badge"></a>
                                </div>
                                <!-- Main-menu -->
                                <div class="main-menu d-none d-md-block col-md-8">
                                    <nav>
                                        <ul id="navigation">
                                            <li><a href="{{ route('welcome') }}">Home</a></li>
                                            <li><a href="#">Latest News</a>
                                                <ul class="submenu">
                                                    <li><a href="#">1</a></li>
                                                    <li><a href="#">2</a></li>
                                                </ul>
                                            </li>
                                            <li><a href="#">About</a></li>
                                            <li><a href="#">Classifieds</a>
                                                <ul class="submenu">
                                                    <li><a href="{{ url('/sikh/search') }}">Vacancies</a></li>
                                                    <li><a href="{{ url('/jobs/search') }}">job Candidates </a></li>
                                                    <li><a href="{{ url('/business-directories') }}">Business Directory</a></li>
                                                    <li><a href="{{ url('/matrimonial') }}">Matrimonials</a></li>
                                                </ul>
                                            </li>
                                            @auth
                                                @php
                                                    switch(Auth::user()->user_role_id){
                                                        case 1: $route = route('admin.dashboard'); break;
                                                        case 11: $route = route('promoter.dashboard'); break;
                                                        case 2: $route = route('guest.dashboard'); break;
                                                    }
                                                @endphp
                                                <li><a href="#">My Account</a>
                                                    <ul class="submenu">
                                                        <li><a href="{{ route('dashboard.all') }}">Dashboard</a></li>
                                                        <li><a href="{{ route('profile.view', Auth::user()->id) }}">Profile</a></li>
                                                        <li><a href="{{ route('edit.profile') }}">Edit Profile</a></li>
                                                        <li><a href="{{ route('change.password.form') }}">Change Password</a></li>
                                                        <li><form method="POST" action="{{ route('logout') }}">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item">Logout</button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </li>
                                            @else
                                                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                                                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Signup</a></li>
                                            @endauth
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-4">
                                <div class="header-right-btn f-right d-none d-lg-block">
                                    <i class="fas fa-search special-tag"></i>
                                    <div class="search-box">
                                        <form action="#">
                                            <input type="text" placeholder="Search">

                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Mobile Menu -->
                            <div class="col-12">
                                <div class="mobile_menu d-block d-md-none"></div>
                            </div>

                        </div>
                    </div>
               </div>
            </div>
       </div>
        <!-- Header End -->
    </header>
