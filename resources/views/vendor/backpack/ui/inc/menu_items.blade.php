{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-item title="User Management" icon="la la-users" :link="backpack_url('user')" />
<x-backpack::menu-item title="Job Listing" icon="la la-file" :link="backpack_url('job')" /> 
<x-backpack::menu-item title="Dispute" icon="la la-question" :link="backpack_url('dispute')" /> 
<x-backpack::menu-item title="Reports" icon="la la-key" :link="backpack_url('report')" /> 