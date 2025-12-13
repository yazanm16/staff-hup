<aside class="hidden md:flex flex-col w-64 bg-white border-r">

    <div class="p-6 text-lg font-bold text-blue-600">
        <i class="fas fa-users mr-1"></i> EmpManage
    </div>

    <nav class="flex-1 px-4 space-y-2">

        @if (auth()->check() && auth()->user()->role === 'admin')
            <a href="{{ route('dashboard') }}" class="menu-item">
                <i class="fas fa-chart-line mr-3"></i> Dashboard
            </a>

            <a href="{{ route('employees.index') }}" class="menu-item">
                <i class="fas fa-user-tie mr-3"></i> Employees
            </a>
            <a href="{{ route('departments.index') }}" class="menu-item">
                <i class="fas fa-building mr-3"></i> Departments

                <a href="{{ route('tasks.index') }}" class="menu-item">
                    <i class="fas fa-tasks mr-3"></i> Tasks
                </a>

                <a href="#" class="menu-item">
                    <i class="fas fa-clipboard-list mr-3"></i> Attendance
                </a>
            @elseif(auth()->check())
                <a href="{{ route('dashboard') }}" class="menu-item">
                    <i class="fas fa-chart-bar mr-3"></i> Dashboard
                </a>

                <a href="#" class="menu-item">
                    <i class="fas fa-clock mr-3"></i> Attendance
                </a>

                <a href="{{ route('tasks.index') }}" class="menu-item">
                    <i class="fas fa-clipboard-check mr-3"></i> My Tasks
                </a>
        @endif

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
