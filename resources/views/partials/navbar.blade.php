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
                <div class="relative">

                    <button onclick="document.getElementById('userMenu').classList.toggle('hidden')"
                        class="flex items-center focus:outline-none">

                        <div class="mr-3 text-right hidden md:block">
                            <p class="text-sm font-semibold">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->getRoleNames()->first() }}</p>
                        </div>

                        <div class="w-8 h-8 rounded-full overflow-hidden bg-blue-100 flex items-center justify-center">
                            @if (auth()->user()->photo && file_exists(public_path('storage/' . auth()->user()->photo->path)))
                                <img class="w-full h-full object-cover"
                                    src="{{ asset('storage/' . auth()->user()->photo->path) }}">
                            @else
                                <i class="fas fa-user text-blue-600"></i>
                            @endif
                        </div>
                    </button>

                    <!-- DROPDOWN -->
                    <div id="userMenu" class="hidden absolute right-0 mt-2 w-44 bg-white rounded-lg shadow-lg border z-50">

                        <a href="{{ route('profile.edit') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user mr-2"></i> Profile
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>

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
