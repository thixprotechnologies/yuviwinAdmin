<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <a href="{{route('admin.profile')}}" class="nav-link">
                <div class="nav-profile-image">
                    <img src="{{ asset('assets/images/faces/face1.jpg') }}" alt="profile">
                    <span class="login-status online"></span>
                    <!--change to offline or busy as needed-->
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">{{ Auth::guard('admin')->user()->name }}</span>
                    <span class="text-secondary text-small">{{ Auth::guard('admin')->user()->email }}</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
        </li>
    <!-- Agent Section -->
        @if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->role == 3)
        <li class="nav-item {{ menuActive('admin.home') }}">
            <a class="nav-link" href="{{ route('admin.home') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ menuActive('admin.users.add') }}">
            <a class="nav-link" href="{{ route('admin.users.add') }}">
                <span class="menu-title">Add User</span>
                <i class="mdi menu-icon mdi-account-plus"></i>
            </a>
        </li>
        <li class="nav-item {{ menuActive('admin.users') }}">
            <a class="nav-link" href="{{ route('admin.users') }}">
                <span class="menu-title">Users</span>
                <i class="mdi menu-icon mdi-account-multiple"></i>
            </a>
        </li>
        <!-- Accountent Section -->
       @elseif(Auth::guard('admin')->check() && Auth::guard('admin')->user()->role == 4)
       <li class="nav-item {{ menuActive('admin.home') }}">
            <a class="nav-link" href="{{ route('admin.home') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ menuActive('admin.recharge') }}">
            <a class="nav-link" href="{{ route('admin.recharge') }}">
                <span class="menu-title">Recharges</span>
                <i class="mdi menu-icon mdi-wallet"></i>
            </a>
        </li>
        <li class="nav-item {{ menuActive('admin.withdraw') }}">
            <a class="nav-link" href="{{ route('admin.withdraw') }}">
                <span class="menu-title">Withdrawals</span>
                <i class="mdi menu-icon mdi-wallet"></i>
            </a>
        </li>
        <!-- Default fallback code -->
       @else
        <li class="nav-item {{ menuActive('admin.home') }}">
            <a class="nav-link" href="{{ route('admin.home') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ menuActive('admin.agent.add') }}">
            <a class="nav-link" href="{{ route('admin.agent.add') }}">
                <span class="menu-title">Add Agent/Accountent</span>
                <i class="mdi menu-icon mdi-account-plus"></i>
            </a>
        </li>
        <li class="nav-item {{ menuActive('admin.agents') }}">
            <a class="nav-link" href="{{ route('admin.agents') }}">
                <span class="menu-title">Agents List</span>
                <i class="mdi menu-icon mdi-account-multiple"></i>
            </a>
        </li>
        <li class="nav-item {{ menuActive('admin.accountent') }}">
            <a class="nav-link" href="{{ route('admin.accountent') }}">
                <span class="menu-title">Accountent List</span>
                <i class="mdi menu-icon mdi-account-multiple"></i>
            </a>
        </li>
        <li class="nav-item {{ menuActive('admin.users.add') }}">
            <a class="nav-link" href="{{ route('admin.users.add') }}">
                <span class="menu-title">Add User</span>
                <i class="mdi menu-icon mdi-account-plus"></i>
            </a>
        </li>
        <li class="nav-item {{ menuActive('admin.users') }}">
            <a class="nav-link" href="{{ route('admin.users') }}">
                <span class="menu-title">Users</span>
                <i class="mdi menu-icon mdi-account-multiple"></i>
            </a>
        </li>
        <li class="nav-item {{ menuActive('admin.bonusplans.*') }}">
            <a class="nav-link" href="{{ route('admin.bonusplans.index') }}">
                <span class="menu-title">Task List</span>
                <i class="mdi menu-icon mdi-cash"></i>
            </a>
        </li>
        <li class="nav-item {{ menuActive('admin.recharge') }}">
            <a class="nav-link" href="{{ route('admin.recharge') }}">
                <span class="menu-title">Recharges</span>
                <i class="mdi menu-icon mdi-wallet"></i>
            </a>
        </li>
        <li class="nav-item {{ menuActive('admin.withdraw') }}">
            <a class="nav-link" href="{{ route('admin.withdraw') }}">
                <span class="menu-title">Withdrawals</span>
                <i class="mdi menu-icon mdi-wallet"></i>
            </a>
        </li>

        <li class="nav-item {{ menuActive('admin.invites') }}">
            <a class="nav-link" href="{{ route('admin.invites') }}">
                <span class="menu-title">Invites Details</span>
                <i class="mdi menu-icon mdi-account-network"></i>
            </a>
        </li>
        <li class="nav-item {{ menuActive('admin.prediction.*') }}">
            <a class="nav-link" href="{{ route('admin.prediction') }}">
                <span class="menu-title">Next Prediction</span>
                <i class="mdi menu-icon mdi-gamepad-variant"></i>
            </a>
        </li>
        <li class="nav-item {{ menuActive('admin.upi') }}">
            <a class="nav-link" href="{{ route('admin.upi') }}">
                <span class="menu-title">Upi Details</span>
                <i class="mdi menu-icon mdi-cash"></i>
            </a>
        </li>
        <li class="nav-item {{ menuActive('admin.ranks') }}">
            <a class="nav-link" href="{{ route('admin.ranks.index') }}">
                <span class="menu-title">Ranks List</span>
                <i class="mdi menu-icon mdi-cash"></i>
            </a>
        </li>
         <li class="nav-item {{ menuActive('giftcode.add') }}">
            <a class="nav-link" href="{{ route('admin.giftcode.add') }}">
                <span class="menu-title">Giftcode Add</span>
                <i class="mdi menu-icon mdi-cash"></i>
            </a>
        </li>
        <li class="nav-item {{ menuActive('admin.gameRecord.index') }}">
            <a class="nav-link" href="{{ route('admin.gameRecord.index') }}">
                <span class="menu-title">Game Record List</span>
                <i class="mdi menu-icon mdi-cash"></i>
            </a>
        </li>
        <li class="nav-item {{ menuActive('admin.complains') }}">
            <a class="nav-link" href="{{ route('admin.complains.index') }}">
                <span class="menu-title">Complains List</span>
                <i class="mdi menu-icon mdi-cash"></i>
            </a>
        </li>
        @endif

    </ul>
</nav>
