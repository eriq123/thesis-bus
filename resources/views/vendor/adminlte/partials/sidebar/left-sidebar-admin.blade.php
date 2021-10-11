<li class="nav-item">
    <a href="{{ route('buses.index') }}" class="nav-link">
        <i class="fas fa-bus"></i>
        <p class="ml-1">Bus</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('buses.routes.index') }}" class="nav-link">
        <i class="fas fa-route"></i>
        <p class="ml-1">Bus Routes</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('buses.schedules.index') }}" class="nav-link">
        <i class="far fa-calendar-alt"></i>
        <p class="ml-1">Bus Schedules</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('buses.bookings.index') }}" class="nav-link">
        <i class="fas fa-book-open"></i>
        <p class="ml-1">Bus Bookings</p>
    </a>
</li>
<li>
    <hr>
</li>
<li class="nav-item">
    <a href="{{route('roles.index')}}" class="nav-link">
        <i class="fas fa-users-cog"></i>
        <p class="ml-1">Roles</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{route('users.index')}}" class="nav-link">
        <i class="fas fa-users"></i>
        <p class="ml-1">Users</p>
    </a>
</li>
