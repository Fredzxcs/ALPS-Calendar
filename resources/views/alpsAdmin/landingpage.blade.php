<!-- INSERT ACTUAL ADMIN LANDING PAGE HERE -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Landing Page</title>
</head>
<body>
    <header>
        <h1>Welcome to the Admin Dashboard</h1>
    </header>

    <nav>
        <ul>
            <li><a href="/manage-users">Manage Users</a></li>
            <li><a href="/view-reports">View Reports</a></li>
            <li><a href="{{ route('register') }}">Add User</a></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </li>
        </ul>
    </nav>

    <main>
        <section>
            <h2>Quick Stats</h2>
            <p>Total Users: 150</p>
            <p>Active Sessions: 12</p>
            <p>Pending Tasks: 5</p>
        </section>

        <section>
            <h2>Announcements</h2>
            <p>No new announcements at this time.</p>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Admin Dashboard. All rights reserved.</p>
    </footer>
</body>
</html>
