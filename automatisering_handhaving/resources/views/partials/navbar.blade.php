<nav>
    <ul>
        <li class="profile-dropdown">
            <a href="#" class="profile-icon">
                <i class="bi bi-person-circle" style="color: black; font-size: 40px;"></i>
            </a>
            <ul class="profile-menu">
                <li><a href="{{ url('/account') }}">Account</a></li>
                <li><a href="{{ url('/admin') }}">Admin</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-button">Log uit</button>
                    </form>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/tallandlogo.png') }}" alt="Talland Logo" style="height: 40px;">
            </a>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle">Wedstrijden</a>
            <ul class="dropdown-menu">
                <li><a href="{{ url('') }}">AZ Alkmaar</a></li>
                <li><a href="{{ url('') }}">AZ Vrouwen</a></li>
                <li><a href="{{ url('') }}">Jong AZ</a></li>
                <li><a href="{{ url('') }}">Overige</a></li>
            </ul>
        </li>
    </ul>
</nav>


<style>
    nav {
        background-color: #ffffff;
        color: white;
        padding: 10px;
        width: 100%;

    }
    /* list styling */
    nav ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: space-between;
        align-items: center
    }
    
    nav ul li {
        position: relative;
    }

    nav ul li a {
        display: block;
    }

    nav ul li a:hover {
        text-decoration: underline;
    }
/* dropdown styling for categories */
    .dropdown {
        margin-right: 10px
    }
    .dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        background-color: #ffffff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        list-style: none;
        padding: 10px 0;
        margin: 0;
        z-index: 1000;
    }

    .dropdown-toggle {
    background-color: white;
    color: black;
    padding: 10px 20px;
    border: 2px solid black;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s; 
}

.dropdown-toggle:hover {
    background-color: black;
    color: white;
}
    .dropdown-menu li {
        padding: 0;
    }

    .dropdown-menu li a {
        padding: 10px 24px;
        color: black;
        text-decoration: none;
        display: block;
    }

    .dropdown-menu li a:hover {
        background-color: #f4f4f4;
    }

     /* Profile dropdown menu */
     .profile-dropdown {
        position: relative;
    }

    .profile-menu {
        display: none;
        position: absolute;
        top: 50px;
        background-color: #f4f4f4;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        list-style: none;
        padding: 10px 0;
        margin: 0;
        border-radius: 5px;
        z-index: 1000;
        width: 150px
    }

    .profile-menu li {
        padding: 0;
    }

    .profile-menu li a {
        padding: 10px 20px;
        color: black;
        text-decoration: none;
        display: block;
        transition: background-color 0.3s;
    }

    .profile-menu li a:hover {
        background-color: #e0e0e0;
    }
/* styling for the logout button */
    .logout-button {
        background-color: red;
        color: white;
        border: none;
        padding: 10px 20px;
        width: 100%;
        text-align: left;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .logout-button:hover {
        background-color: darkred;
    }

/* Show dropdown menu when the parent <li> has the active class */
.profile-dropdown.active .profile-menu,
.dropdown.active .dropdown-menu {
    display: block;
}
</style>

<script>
    // Handle dropdown menus (both category and profile dropdowns)
    document.addEventListener('DOMContentLoaded', function () {
        // Select all dropdown toggles
        const dropdownToggles = document.querySelectorAll('.dropdown-toggle, .profile-icon');

        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', function (e) {
                e.preventDefault();

                // Get the parent dropdown container
                const dropdownContainer = this.closest('li');

                // Toggle the active class to show/hide the dropdown menu
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