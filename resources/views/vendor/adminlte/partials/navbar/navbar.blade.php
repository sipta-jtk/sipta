@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

<nav class="main-header navbar
    {{ config('adminlte.classes_topnav_nav', 'navbar-expand') }}
    {{ config('adminlte.classes_topnav', 'navbar-white navbar-light') }}">

    {{-- Navbar left --}}
    <ul class="navbar-nav">
        @include('adminlte::partials.navbar.menu-item-left-sidebar-toggler')
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-left'), 'item')
        @yield('content_top_nav_left')
    </ul>

    {{-- Navbar right --}}
    <ul class="navbar-nav ml-auto">
        @yield('content_top_nav_right')
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-right'), 'item')

        {{-- Icon lonceng --}}
        <li class="nav-item dropdown">
            <a class="nav-link" href="#" id="notificationBell">
                <i class="fas fa-bell"></i>
                <span class="badge badge-danger navbar-badge" id="notificationCount"></span>
            </a>
        </li>

        {{-- User Menu --}}
        @if(Auth::user())
            @if(config('adminlte.usermenu_enabled'))
                @include('adminlte::partials.navbar.menu-item-dropdown-user-menu')
            @else
                @include('adminlte::partials.navbar.menu-item-logout-link')
            @endif
        @endif

        {{-- Right sidebar --}}
        @if($layoutHelper->isRightSidebarEnabled())
            @include('adminlte::partials.navbar.menu-item-right-sidebar-toggler')
        @endif
    </ul>

    {{-- Modal Include --}}
    @include('NotificationAndReminder.views.modals.log-modal')
    @include('NotificationAndReminder.views.modals.preferences-modal')

</nav>
