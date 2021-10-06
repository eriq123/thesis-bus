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

                @if(Auth::user()->role_id == 1)
                {{-- Admin --}}
                @include('adminlte::partials.sidebar.left-sidebar-admin')
                @elseif(Auth::user()->role_id == 2)
                {{-- Driver --}}
                @include('adminlte::partials.sidebar.left-sidebar-driver')
                @elseif(Auth::user()->role_id == 3)
                {{-- Conductor --}}
                @include('adminlte::partials.sidebar.left-sidebar-conductor')
                @elseif(Auth::user()->role_id == 4)
                {{-- Passenger --}}
                @include('adminlte::partials.sidebar.left-sidebar-passenger')
                @endif
                <li>
                    <hr>
                </li>
                <li class="nav-item">
                    <form id="#">
                        @csrf
                        <a href="#"
                            class="nav-link">
                            <i class="fa fa-user-circle"></i>
                            <p class="ml-1">Update My Information</p>
                        </a>
                    </form>

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
            </ul>
            
                <!-- <div class="copyright">
                    &copy; BTIT Class of 2020 - 2019 <p> MARIANNE CLAIRE ARIÑO</p>
                    <p> ELISHA FELIPE </p> <p> LESTER SORIANO </p>
                </div>
                <div class="version">
                    <b>Version: </b> 1.0.0
                </div> -->
        
        
        </nav>
        
    </div>

</aside>
