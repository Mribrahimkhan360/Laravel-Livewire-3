<?php

use Livewire\Component;

new class extends Component {
    public $users;

    public function mount($users)
    {
        $this->users = $users;
    }
};
?>

<div class="table-container p-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Users List</h2>
        <a href="{{ route('users.create') }}" class="btn btn-primary">Add Users</a>

    </div>
    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif
    <div class="table-responsive">
        @if($users->count() > 0)
            <table class="table table-striped table-hover align-middle">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Flag</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge
                                {{ $user->flag == 'admin' ? 'bg-danger' :
                                   ($user->flag == 'custom_user' ? 'bg-primary' : 'bg-secondary') }}">
                                {{ ucfirst(str_replace('_',' ',$user->flag)) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                  style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{--                            <div class="d-flex justify-content-center mt-4">--}}
            {{--                                {{ $users->links() }}--}}
            {{--                            </div>--}}
        @else
            <p>No products found.</p>
        @endif
    </div>
</div>
