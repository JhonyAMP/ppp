<div class="sticky top-0 z-10 before:backdrop-blur-md">
    <header
        class="sticky top-0 before:absolute before:inset-0 before:backdrop-blur-md max-lg:before:shadow-lg max-lg:before:bg-white/90 dark:max-lg:before:bg-gray-800/90 before:-z-10 z-30 {{ $variant === 'v2' || $variant === 'v3' ? 'before:bg-white after:absolute after:h-px after:inset-x-0 after:top-full after:bg-gray-200 dark:after:bg-gray-700/60 after:-z-10' : 'max-lg:shadow-sm lg:before:bg-white/90 dark:lg:before:bg-gray-900/90 lg:before:shadow-lg' }}">
        <div class="px-4">
            <div class="flex items-center justify-between h-16">

                <!-- Header: Left side -->
                <div class="flex gap-3 items-center">

                    <!-- Expand / collapse button -->
                    <div class="hidden lg:block 2xl:hidden relative">

                        <button class="text-gray-400 hover:text-gray-600 transition-colors" @click="sidebarExpanded = !sidebarExpanded">
                            <span class="sr-only">Expand / collapse sidebar</span>
                            <i class="fa-solid fa-bars fa-lg"></i>
                        </button>

                    </div>

                    <!-- Hamburger button -->
                    <button class="text-gray-500 hover:text-gray-600 dark:hover:text-gray-400 lg:hidden"
                        @click.stop="sidebarOpen = !sidebarOpen" aria-controls="sidebar" :aria-expanded="sidebarOpen">
                        <span class="sr-only">Open sidebar</span>
                        <i class="fa-solid fa-bars fa-lg"></i>
                    </button>

                    <div class="flex items-center text-sm">
                        PRACTICAS PRE PROFESIONALES
                    </div>

                </div>

                <!-- Header: Right side -->
                <div class="flex items-center gap-2">

                    <!-- Notifications button -->
                    <x-dropdown-notifications align="right" />

                    <!-- Dark mode toggle -->
                    {{-- <x-theme-toggle /> --}}

                    <!-- Divider -->
                    <hr class="w-px h-6 bg-gray-200 dark:bg-gray-700/60 border-none" />

                    <!-- User button -->
                    <x-dropdown-profile align="right" />

                </div>

            </div>
        </div>
    </header>
</div>
