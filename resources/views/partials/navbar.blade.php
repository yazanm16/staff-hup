<header class="bg-white border-b">

    <div class="flex items-center justify-between px-4 py-3">

        <!-- LEFT -->
        <div class="flex items-center">
            <button class="md:hidden mr-3 text-gray-600">
                <i class="fas fa-bars text-xl"></i>
            </button>

            <h1 class="flex items-center text-blue-600 font-bold text-lg">
                <i class="fas fa-users mr-2"></i>
                EmpManage
            </h1>

            <span class="ml-4 hidden md:inline text-gray-400">
                @yield('page-title', 'Dashboard')
            </span>
        </div>

        <!-- RIGHT -->
        <div class="flex items-center gap-4">

            <span class="hidden md:block text-gray-500 text-sm">
                {{ now()->format('l d F Y') }}
            </span>

            @auth
                <!-- USER INFO -->
                <div class="flex items-center">
                    <div class="mr-3 text-right hidden md:block">
                        <p class="text-sm font-semibold">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                    </div>

                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-blue-600"></i>
                    </div>

                    <!-- LOGOUT -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="ml-3 text-red-600 hover:text-red-700">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            @else
                <!-- LOGIN -->
                <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Login
                </a>
            @endauth

        </div>

    </div>

</header>
