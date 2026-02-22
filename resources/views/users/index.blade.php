<!doctype html>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="">
</head>
<body>
<!-- Sidebar -->
<div class="bg-dark text-white p-3 position-fixed top-0 start-0 vh-100"
     style="width: 220px;">

    <h4 class="text-center mb-4">Dashboard</h4>

    <ul class="nav nav-pills flex-column mb-auto" style="margin-left: 18px">

        <li class="nav-item">
            <a href="/dashboard" class="nav-link text-white">
                Dashboard
            </a>
        </li>

        <li>
            <a href="{{route('product')}}" class="nav-link text-white">
                Product
            </a>
        </li>

        <li>
            <a href="{{route('users.index')}}" class="nav-link text-white">
                User
            </a>
        </li>

        <li>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link text-white border-0 bg-transparent">
                    <i class="bi bi-gear me-0"></i> Logout
                </button>
            </form>
        </li>
    </ul>
</div>
<div class="flex-grow-1" style="margin-left: 220px;">
    <nav class="navbar navbar-light bg-light border-bottom">
        <div class="container-fluid">
            <p></p>
            <h5 class="ms-3 mb-0">User List</h5>
        </div>
    </nav>
    <livewire:user :users="$users" />
</div>
@livewireScripts
</body>
</html>



