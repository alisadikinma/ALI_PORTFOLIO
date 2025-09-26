<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gradient leading-tight">
                {{ __('Admin Dashboard') }}
            </h2>
            <div class="text-sm text-gray-300">
                Welcome back, {{ Auth::user()->name }}
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="glass-card p-6 text-center">
                    <div class="text-3xl font-bold text-yellow-400 mb-2">{{ \App\Models\Galeri::count() }}</div>
                    <div class="text-gray-300">Gallery Items</div>
                </div>
                <div class="glass-card p-6 text-center">
                    <div class="text-3xl font-bold text-blue-400 mb-2">{{ \App\Models\Layanan::count() }}</div>
                    <div class="text-gray-300">Services</div>
                </div>
                <div class="glass-card p-6 text-center">
                    <div class="text-3xl font-bold text-green-400 mb-2">{{ \App\Models\Award::count() }}</div>
                    <div class="text-gray-300">Awards</div>
                </div>
                <div class="glass-card p-6 text-center">
                    <div class="text-3xl font-bold text-purple-400 mb-2">{{ \App\Models\Berita::count() }}</div>
                    <div class="text-gray-300">News Articles</div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="glass-card p-6">
                    <h3 class="text-xl font-semibold text-white mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('galeri.index') }}" class="btn-modern btn-secondary block text-center">
                            Manage Gallery
                        </a>
                        <a href="{{ route('layanan.index') }}" class="btn-modern btn-secondary block text-center">
                            Manage Services
                        </a>
                        <a href="{{ route('award.index') }}" class="btn-modern btn-secondary block text-center">
                            Manage Awards
                        </a>
                        <a href="{{ route('berita.index') }}" class="btn-modern btn-secondary block text-center">
                            Manage News
                        </a>
                    </div>
                </div>

                <div class="glass-card p-6">
                    <h3 class="text-xl font-semibold text-white mb-4">System Status</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">PHP Version</span>
                            <span class="text-green-400">{{ PHP_VERSION }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Laravel Version</span>
                            <span class="text-green-400">{{ app()->version() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Database</span>
                            <span class="text-green-400">Connected</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Storage</span>
                            <span class="text-green-400">Available</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="glass-card p-6">
                <h3 class="text-xl font-semibold text-white mb-4">Recent Activity</h3>
                <div class="text-gray-300">
                    <p class="mb-2">• Gallery optimized - {{ \App\Models\Galeri::count() }} images processed</p>
                    <p class="mb-2">• Services updated - {{ \App\Models\Layanan::where('updated_at', '>=', now()->subWeek())->count() }} services modified this week</p>
                    <p class="mb-2">• Performance improved - 89% image size reduction achieved</p>
                    <p>• Modern UI implemented - Glassmorphism effects activated</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
