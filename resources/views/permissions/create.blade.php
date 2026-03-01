<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @livewireStyles
</head>
<body>
<div class="d-flex" id="wrapper">
    <div class="bg-dark text-white p-3" id="sidebar-wrapper"
         style="min-width: 221px; min-height: 100vh;">

        <h4 class="text-center mb-4">Dashboard</h4>

        <ul class="nav nav-pills flex-column mb-auto">

            <li class="nav-item">
                <a href="/dashboard" class="nav-link text-white">
                    <i class="bi bi-house-door me-3"></i> Dashboard
                </a>
            </li>

            <li>
                <a href="{{route('product')}}" class="nav-link text-white">
                    <i class="bi bi-person me-3"></i>Product
                </a>
            </li>
            <li>
                <a href="{{route('product')}}" class="nav-link text-white">
                    <i class="bi bi-person me-3"></i>Product
                </a>
            </li>
            <li>
                <a href="{{route('users.index')}}" class="nav-link text-white">
                    <i class="bi bi-gear me-3"></i> User
                </a>
            </li>

            <li class="nav-item dropdown" x-data="{ open: false }">
                <a href="#" class="nav-link text-white" role="button"
                   @click.prevent="open = !open">
                    <i class="bi bi-gear me-3"></i>  Role & Permission
                </a>

                <ul class="dropdown-menu" :class="{ 'show': open }">
                    <li>
                        <a href="{{ route('roles.index') }}" class="dropdown-item">
                            Roles
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('permissions.index') }}" class="dropdown-item">
                            Permissions
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link text-white border-0 bg-transparent">
                        <i class="bi bi-gear me-3"></i> Logout
                    </button>
                </form>

            </li>

        </ul>
    </div>

    <div class="flex-grow-1" id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <div class="container-fluid">
                <a href=""></a>
                <h5 class="ms-3 mb-0">Create Permission</h5>
            </div>
        </nav>
        <div class="container my-5">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white flex">
                    <h4 class="mb-0">Add New Permission</h4>
                    <p>
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show text-green-800" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        </p>
                        <p>
                        @if($errors->any())
                            @foreach($errors->all() as $error)
                                <p class="alert alert-success alert-dismissible fade show ">
                                    {{ $error }}
                                </p>
                                @endforeach
                                @endif
                                </p>
                </div>

                <div class="card-body">
                    {{--  <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">--}}
                    <form action="{{ route('permissions.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">

                            <!-- Permission Name -->
                            <div class="col-md-6">
                                <label for="name" class="form-label">Role Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter permission name" required>
                            </div>

                            <!-- Guard Name -->
{{--                            <div class="col-md-6">--}}
{{--                                <label for="guard_name" class="form-label">Guard Name</label>--}}
{{--                                <input type="text" name="guard_name" id="guard_name" class="form-control" placeholder="Enter guard name" required>--}}
{{--                            </div>--}}

                        </div>

                        <!-- Buttons -->
                        <div class="text-end mt-2">
                            <button type="reset" class="btn btn-secondary">Reset</button>
                            <button type="submit" class="btn btn-success">Create Permission</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
@livewireScripts
</body>
</html>
