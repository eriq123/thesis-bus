<aside class="main-sidebar {{ config('adminlte.classes_sidebar', 'sidebar-dark-primary elevation-4') }}">

    {{-- Sidebar brand logo --}}
    @if(config('adminlte.logo_img_xl'))
    @include('adminlte::partials.common.brand-logo-xl')
    @else
    @include('adminlte::partials.common.brand-logo-xs')
    @endif

    {{-- Sidebar menu --}}
    <div class="sidebar">
        <nav class="pt-2">
            <ul class="nav nav-pills nav-sidebar flex-column {{ config('adminlte.classes_sidebar_nav', '') }}"
                data-widget="treeview" role="menu" @if(config('adminlte.sidebar_nav_animation_speed') !=300)
                data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}" @endif
                @if(!config('adminlte.sidebar_nav_accordion')) data-accordion="false" @endif>

                {{-- Configured sidebar links --}}
                {{-- @each('adminlte::partials.sidebar.menu-item', $adminlte->menu('sidebar'), 'item') --}}
                @if(Auth::user()->role_id==1)
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
                <li>
                
                    <hr>
                </li>
                <li class="nav-item">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <a href="javascript:void()" onclick="document.getElementById('logout-form').submit();"
                            class="nav-link">
                            <i class="fas fa-power-off"></i>
                            <p class="ml-1">Logout</p>
                        </a>
                    </form>
                </li>
                @else
                <li class="nav-item">
                <a href="#" class="nav-link">
                <i class="fas fa-book-open"></i>
                        <p class="ml-1">Book a Bus</p>
                    </a>
                </li>
                <li>
                
                    <hr>
                </li>
                <li class="nav-item">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <a href="javascript:void()" onclick="document.getElementById('logout-form').submit();"
                            class="nav-link">
                            <i class="fas fa-power-off"></i>
                            <p class="ml-1">Logout</p>
                        </a>
                    </form>
                </li>
                
                @endif
            </ul>
        </nav>
    </div>

</aside>
