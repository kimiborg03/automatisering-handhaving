<nav>
    <ul>
        <li><a href="{{ url('/') }}">Home</a></li>
        <li><a href="{{ route('register') }}">Registreren</a></li>
        <li><a href="{{ route('login') }}">Inloggen</a></li>
    </ul>
</nav>

<style>
    nav {
        background-color: #007bff;
        padding: 10px;
    }

    nav ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: space-around;
    }

    nav ul li {
        display: inline;
    }

    nav ul li a {
        color: white;
        text-decoration: none;
        font-weight: bold;
    }

    nav ul li a:hover {
        text-decoration: underline;
    }
</style>