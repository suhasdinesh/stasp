<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-users"></i> Authentication</a>
    <ul class="nav-dropdown-items">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-user"></i> <span>Users</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon la la-id-badge"></i> <span>Roles</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i class="nav-icon la la-key"></i> <span>Permissions</span></a></li>
    </ul>
</li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('student') }}'><i class='nav-icon la la-question'></i> Students</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('teacher') }}'><i class='nav-icon la la-question'></i> Teachers</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('classes') }}'><i class='nav-icon la la-question'></i> Classes</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('course') }}'><i class='nav-icon la la-question'></i> Courses</a></li>

<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-users"></i>Fees</a>
    <ul class="nav-dropdown-items">
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('fee-type') }}'><i class='nav-icon la la-file-invoice'></i>Fee Types</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('fees') }}'><i class='nav-icon la la-question'></i>Fees</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('fee-student') }}'><i class='nav-icon la la-question'></i>Fee Students</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('transaction') }}'><i class='nav-icon la la-question'></i>Transactions</a></li>
    </ul>
</li>