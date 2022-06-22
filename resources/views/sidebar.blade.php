<!--  BEGIN SIDEBAR  -->
<div class="sidebar-wrapper sidebar-theme">
    <nav id="sidebar">
        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">
            <li class="menu ">
                <a class="dropdown-toggle" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg><span>Log Out</span>
                    </div>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
            @if(auth()->user()->role_id == 2)
            <li class="nav-item theme-text">
                <a href="{{ route('user.vkontakte-sync') }}" class="nav-link"> Vkontakte </a>
            </li>
            @endif
            <li class="nav-item theme-text">
                <a href="{{ route('user.telegram-sync') }}" class="nav-link"> Telegram </a>
            </li>
        </ul>
    </nav>
</div>
<!--  END SIDEBAR  -->
