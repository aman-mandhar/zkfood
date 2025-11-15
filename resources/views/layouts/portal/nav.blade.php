<style>
    /* Sticky navbar container */
    .navbar-area {
        position: sticky;
        top: 0;
        z-index: 1030;
        width: 100%;
        background-color: #ffffff;
        transition: background-color 0.25s ease, box-shadow 0.25s ease, transform 0.25s ease;
    }

    /* Slide-down animation on load */
    .navbar-animate {
        animation: navbarSlideDown 0.5s ease-out;
    }

    @keyframes navbarSlideDown {
        from {
            opacity: 0;
            transform: translateY(-25px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* When user scrolls down slightly */
    .navbar-area.nav-scrolled {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    }

    /* Hide on scroll down */
    .navbar-area.nav-hidden {
        transform: translateY(-100%);
    }

    /* Modern nav-links */
    .navbar .nav-link {
        font-weight: 500;
        padding: 0.5rem 0.9rem;
        position: relative;
        transition: color 0.2s ease;
    }

    .navbar .nav-link::after {
        content: "";
        position: absolute;
        left: 0.9rem;
        right: 0.9rem;
        bottom: 0.2rem;
        height: 2px;
        transform: scaleX(0);
        transform-origin: center;
        transition: transform 0.2s ease;
        background-color: #fd7e14;
    }

    .navbar .nav-link:hover::after,
    .navbar .nav-link.active::after {
        transform: scaleX(1);
    }
</style>

<!-- Navbar Start -->
<header class="navbar-area">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 navbar-animate">

            <!-- Logo -->
            <a href="{{ url('/') }}" class="navbar-brand p-0">
                <img src="{{ asset('portal/assets/img/logo/logo.png') }}"
                     alt="Logo" style="width: 85px; height: 85px;">
            </a>

            <!-- Mobile Toggler -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="fa fa-bars"></span>
            </button>

            <!-- Menu Items -->
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center mb-2 mb-lg-0">

                    <li class="nav-item">
                        <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Home</a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/about') }}" class="nav-link {{ request()->is('about') ? 'active' : '' }}">About</a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/service') }}" class="nav-link {{ request()->is('service') ? 'active' : '' }}">Service</a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/menu') }}" class="nav-link {{ request()->is('menu') ? 'active' : '' }}">Menu</a>
                    </li>

                    <!-- Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            Pages
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ url('/booking') }}">Booking</a></li>
                            <li><a class="dropdown-item" href="{{ url('/team') }}">Our Team</a></li>
                            <li><a class="dropdown-item" href="{{ url('/testimonial') }}">Testimonial</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/contact') }}" class="nav-link {{ request()->is('contact') ? 'active' : '' }}">Contact</a>
                    </li>

                    <!-- CTA Button -->
                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                        <a href="#" class="btn btn-primary py-2 px-4">Book A Table</a>
                    </li>

                </ul>
            </div>

        </nav>
    </div>
</header>
<!-- Navbar End -->

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const header = document.querySelector('.navbar-area');
        if (!header) return;

        let lastScrollTop = 0;
        const offsetToShowShadow = 60;    // shadow after 60px scroll
        const offsetToHideOnDown = 150;   // hide only after 150px scroll

        window.addEventListener('scroll', function () {
            const currentScroll = window.pageYOffset || document.documentElement.scrollTop;

            // Add shadow
            if (currentScroll > offsetToShowShadow) {
                header.classList.add('nav-scrolled');
            } else {
                header.classList.remove('nav-scrolled');
            }

            // Hide on scroll down, show on scroll up
            if (currentScroll > lastScrollTop && currentScroll > offsetToHideOnDown) {
                header.classList.add('nav-hidden');    // hiding...
            } else {
                header.classList.remove('nav-hidden'); // showing...
            }

            lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
        });
    });
</script>
