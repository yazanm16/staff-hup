<aside class="hidden md:flex flex-col w-64 bg-white border-r">

    <div class="p-6 text-lg font-bold text-blue-600">
        <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
            <i class="fas fa-users mr-1"></i> EmpManage
        </x-responsive-nav-link>
    </div>

    <nav class="flex-1 px-4 space-y-2">


        @role('admin')
            <x-responsive-nav-link href="{{ route('dashboard.admin') }}" :active="request()->routeIs('dashboard.admin')">
                <i class="fas fa-chart-line mr-3"></i> Dashboard
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('employees.index') }}" :active="request()->routeIs('employees.*')">
                <i class="fas fa-user-tie mr-3"></i> Employees
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('departments.index') }}" :active="request()->routeIs('departments.*')">
                <i class="fas fa-building mr-3"></i> Departments
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('tasks.index') }}" :active="request()->routeIs('tasks.*')">
                <i class="fas fa-tasks mr-3"></i> Tasks
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('attendances.reports') }}" :active="request()->routeIs('attendances.reports')">
                <i class="fas fa-clipboard-list mr-3"></i> Attendance
            </x-responsive-nav-link>
        @endrole
        @can('role.manage')
            <x-responsive-nav-link href="{{ route('roles.index') }}" :active="request()->routeIs('roles.*')">
                <i class="fas fa-user-gear mr-3"></i> Roles
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('permissions.index') }}" :active="request()->routeIs('permissions.*')">
                <i class="fas fa-shield-alt mr-3"></i> Permissions
            </x-responsive-nav-link>
        @endcan
        @role('employee')
            <x-responsive-nav-link href="{{ route('dashboard.employee') }}" :active="request()->routeIs('dashboard.employee')">
                <i class="fas fa-chart-bar mr-3"></i> Dashboard
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('attendances.index') }}" :active="request()->routeIs('attendances.*')">
                <i class="fas fa-clock mr-3"></i> Attendance
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('tasks.my') }}" :active="request()->routeIs('tasks.*')">
                <i class="fas fa-clipboard-check mr-3"></i> My Tasks
            </x-responsive-nav-link>
        @endrole

    </nav>

</aside>


<style>
    .menu-item {
        display: flex;
        align-items: center;
        padding: 10px 14px;
        color: #374151;
        border-radius: 8px;
        transition: 0.2s;
    }

    .menu-item:hover {
        background: #eff6ff;
        color: #2563eb;
    }
</style>
