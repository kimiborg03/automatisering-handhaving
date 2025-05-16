<head>
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
</head>

<nav>
    <ul>
        {{-- profile dropdown menu --}}
        <li class="profile-dropdown">
            <a href="#" class="profile-icon">
                <i class="bi bi-person-circle" style="color: black; font-size: 40px;"></i>
            </a>
            <ul class="profile-menu">
        @if(Auth::check())
            <li><a href="{{ url('/account') }}">Account</a></li>
            <li><a href="{{ url('/admin') }}">Admin</a></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-button">Log uit</button>
                </form>
            </li>
        @else
            <li>
                <a href="{{ route('login') }}" class="login-button">Inloggen</a>
            </li>
        @endif
            </ul>
        </li>
        {{-- talland logo --}}
        <li>
            <a href="{{ url('/home') }}">
                <img src="{{ asset('images/tallandlogovoetbal.png') }}" alt="Talland Logo" class="talland-logo">
            </a>
        </li>
        {{-- category dropdown menu --}}
        <li class="dropdown">
            <a href="#" class="dropdown-toggle">Wedstrijden</a>
            <ul class="dropdown-menu">
                <li><a href="{{ route('category.show', 'AZ-Alkmaar') }}">AZ-Alkmaar</a></li>
                <li><a href="{{ route('category.show', 'AZ-Vrouwen') }}">AZ-Vrouwen</a></li>
                <li><a href="{{ route('category.show', 'Jong-AZ') }}">Jong-AZ</a></li>
                <li><a href="{{ route('category.show', 'Overige') }}">Overige</a></li>
            </ul>
        </li>
    </ul>
</nav>




<script>
    // Handle dropdown menus
    document.addEventListener('DOMContentLoaded', function () {
        // Select dropdown toggles for the icon and the categories
        const dropdownToggles = document.querySelectorAll('.dropdown-toggle, .profile-icon');

        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', function (e) {
                e.preventDefault();

                // Get the parent dropdown container
                const dropdownContainer = this.closest('li');

                // Toggle the active class to show or hide the dropdown menu
                dropdownContainer.classList.toggle('active');

                // Close other open dropdowns
                document.querySelectorAll('li.active').forEach(activeDropdown => {
                    if (activeDropdown !== dropdownContainer) {
                        activeDropdown.classList.remove('active');
                    }
                });
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function (e) {
            document.querySelectorAll('li.active').forEach(activeDropdown => {
                if (!activeDropdown.contains(e.target)) {
                    activeDropdown.classList.remove('active');
                }
            });
        });
    });
</script>