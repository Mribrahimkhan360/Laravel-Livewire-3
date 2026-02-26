<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">

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

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" id="rolePermissionDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-gear me-0"></i>  Role & Permission
                </a>
                <ul class="dropdown-menu" aria-labelledby="rolePermissionDropdown">
                    <li>
                        <a class="dropdown-item" href="{{ route('roles.index') }}">Roles</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('permissions.index') }}">Permissions</a>
                    </li>
                </ul>
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

    <!-- Page Content -->
    <div class="flex-grow-1" style="margin-left: 220px;">
        <nav class="navbar navbar-light bg-light border-bottom">
            <div class="container-fluid">
                <p></p>
                <h5 class="ms-3 mb-0">Role List</h5>
            </div>
        </nav>

        <div class="table-container p-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Role List</h2>
                <a href="{{ route('roles.create') }}" class="btn btn-primary">Add Role</a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="thead-dark">
                    <tr>
                        <th>Name</th>
                        <th>Guard Name</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td>{{ $role->guard_name }}</td>
                            <td class="text-center">
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this?')">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
@livewireScripts

</body>
</html>
