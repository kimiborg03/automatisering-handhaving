{{-- users table for the users.blade.php management table--}}
@foreach($users as $user)
<tr>
    <td>
        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal-{{ $user->id }}">
            <i class="bi bi-pencil-square"></i>
        </button>
    </td>
    <td>{{ $user->name }}</td>
    <td>{{ $user->username }}</td>
    <td>{{ $user->email }}</td>
    <td>
        @php
            $group = $groups->firstWhere('id', $user->group_id);
        @endphp
        {{ $group ? $group->name : '-' }}
    </td>
    <td>{{ $user->role }}</td>
    <td>{{ $user->access }}</td>
</tr>

@include('admin.modals')
@endforeach
