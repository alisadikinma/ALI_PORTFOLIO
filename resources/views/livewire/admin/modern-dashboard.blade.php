{{--
PHASE 3: MODERN ADMIN DASHBOARD COMPONENT
Real-time stats with Livewire 3.0 integration
--}}

<div class="modern-admin-dashboard">
    {{-- Dashboard Header --}}
    <div class="dashboard-header mb-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div>
                <h1 class="text-4xl font-bold text-gradient-hero mb-2">
                    Welcome back, {{ Auth::user()->name ?? 'Admin' }}! 👋
                </h1>
                <p class="text-xl text-gray-300">
                    Here's what's happening with your portfolio today
                </p>
            </div>

            <div class="flex items-center gap-4">
                {{-- Real-time indicator --}}
                <div class="flex items-center gap-2 px-4 py-2 glass-card rounded-full">
                    <div class="w-2 h-2 bg-neon-green rounded-full animate-pulse-glow"></div>
                    <span class="text-sm text-gray-300">Live Dashboard</span>
                </div>

                {{-- Quick actions --}}
                <button wire:click="refreshData"
                        class="btn-cyber btn-sm"
                        wire:loading.class="opacity-50"
                        wire:loading.attr="disabled">
                    <i class="fas fa-sync-alt" wire:loading.class="animate-spin"></i>
                    <span wire:loading.remove>Refresh</span>
                    <span wire:loading>Updating...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Key Metrics Grid --}}
    <div class="metrics-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Projects Metric --}}
        <div class="metric-card card-modern p-6 hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div class="metric-icon p-3 bg-gradient-to-r from-electric-purple to-cyber-pink rounded-xl">
                    <i class="fas fa-project-diagram text-white text-xl"></i>
                </div>
                <div class="metric-trend text-right">
                    <span class="text-neon-green text-sm font-medium">
                        +{{ $this->projectsGrowth }}%
                    </span>
                </div>
            </div>

            <div class="metric-content">
                <h3 class="text-3xl font-bold text-white mb-1"
                    data-counter="{{ $totalProjects }}"
                    data-counter-suffix="">
                    {{ $totalProjects }}
                </h3>
                <p class="text-gray-400 text-sm">Total Projects</p>
            </div>

            <div class="metric-footer mt-4 pt-4 border-t border-gray-700">
                <span class="text-xs text-gray-500">
                    {{ $activeProjects }} active • {{ $totalProjects - $activeProjects }} completed
                </span>
            </div>
        </div>

        {{-- Views Metric --}}
        <div class="metric-card card-modern p-6 hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div class="metric-icon p-3 bg-gradient-to-r from-neon-green to-aurora-blue rounded-xl">
                    <i class="fas fa-eye text-white text-xl"></i>
                </div>
                <div class="metric-trend text-right">
                    <span class="text-neon-green text-sm font-medium">
                        +{{ $this->viewsGrowth }}%
                    </span>
                </div>
            </div>

            <div class="metric-content">
                <h3 class="text-3xl font-bold text-white mb-1"
                    data-counter="{{ $totalViews }}"
                    data-counter-suffix="">
                    {{ number_format($totalViews) }}
                </h3>
                <p class="text-gray-400 text-sm">Portfolio Views</p>
            </div>

            <div class="metric-footer mt-4 pt-4 border-t border-gray-700">
                <span class="text-xs text-gray-500">
                    {{ $todayViews }} today • {{ $weekViews }} this week
                </span>
            </div>
        </div>

        {{-- Contact Messages --}}
        <div class="metric-card card-modern p-6 hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div class="metric-icon p-3 bg-gradient-to-r from-neon-yellow to-sunset-orange rounded-xl">
                    <i class="fas fa-envelope text-white text-xl"></i>
                </div>
                <div class="metric-trend text-right">
                    @if($unreadMessages > 0)
                        <span class="text-cyber-pink text-sm font-medium animate-pulse-glow">
                            {{ $unreadMessages }} new
                        </span>
                    @endif
                </div>
            </div>

            <div class="metric-content">
                <h3 class="text-3xl font-bold text-white mb-1"
                    data-counter="{{ $totalMessages }}"
                    data-counter-suffix="">
                    {{ $totalMessages }}
                </h3>
                <p class="text-gray-400 text-sm">Messages Received</p>
            </div>

            <div class="metric-footer mt-4 pt-4 border-t border-gray-700">
                <span class="text-xs text-gray-500">
                    {{ $responseRate }}% response rate
                </span>
            </div>
        </div>

        {{-- Social Followers --}}
        <div class="metric-card card-modern p-6 hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div class="metric-icon p-3 bg-gradient-to-r from-cyber-pink to-electric-purple rounded-xl">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
                <div class="metric-trend text-right">
                    <span class="text-neon-green text-sm font-medium">
                        +{{ $this->followersGrowth }}%
                    </span>
                </div>
            </div>

            <div class="metric-content">
                <h3 class="text-3xl font-bold text-white mb-1"
                    data-counter="{{ $totalFollowers }}"
                    data-counter-suffix="K+">
                    {{ $totalFollowers }}K+
                </h3>
                <p class="text-gray-400 text-sm">Social Followers</p>
            </div>

            <div class="metric-footer mt-4 pt-4 border-t border-gray-700">
                <span class="text-xs text-gray-500">
                    Across all platforms
                </span>
            </div>
        </div>
    </div>

    {{-- Chart and Activity Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        {{-- Analytics Chart --}}
        <div class="lg:col-span-2">
            <div class="card-modern p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-white">
                        Portfolio Analytics
                    </h3>
                    <div class="flex items-center gap-2">
                        <button class="btn-sm px-3 py-1 {{ $chartPeriod === '7d' ? 'btn-cyber' : 'glass-card' }}"
                                wire:click="setChartPeriod('7d')">
                            7D
                        </button>
                        <button class="btn-sm px-3 py-1 {{ $chartPeriod === '30d' ? 'btn-cyber' : 'glass-card' }}"
                                wire:click="setChartPeriod('30d')">
                            30D
                        </button>
                        <button class="btn-sm px-3 py-1 {{ $chartPeriod === '90d' ? 'btn-cyber' : 'glass-card' }}"
                                wire:click="setChartPeriod('90d')">
                            90D
                        </button>
                    </div>
                </div>

                {{-- Chart placeholder - integrate with Chart.js --}}
                <div class="chart-container h-64 glass-card rounded-lg p-4">
                    <canvas id="analyticsChart" width="100%" height="100%"></canvas>
                </div>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="card-modern p-6">
            <h3 class="text-xl font-semibold text-white mb-6">
                Recent Activity
            </h3>

            <div class="activity-feed space-y-4">
                @foreach($recentActivities as $activity)
                <div class="activity-item flex items-start gap-3 p-3 glass-card rounded-lg hover-lift">
                    <div class="activity-icon p-2 bg-{{ $activity['color'] }} rounded-lg">
                        <i class="{{ $activity['icon'] }} text-white text-sm"></i>
                    </div>
                    <div class="activity-content flex-1">
                        <p class="text-sm text-white">{{ $activity['message'] }}</p>
                        <span class="text-xs text-gray-400">{{ $activity['time'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>

            <a href="#" class="inline-flex items-center gap-2 text-electric-purple hover:text-cyber-pink mt-4 text-sm font-medium">
                View all activity
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>

    {{-- Quick Actions Grid --}}
    <div class="quick-actions grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Add New Project --}}
        <div class="action-card card-modern p-6 hover-lift cursor-pointer"
             wire:click="$emit('showCreateProjectModal')">
            <div class="action-icon p-4 bg-gradient-to-r from-neon-green to-aurora-blue rounded-xl mb-4 mx-auto w-fit">
                <i class="fas fa-plus text-white text-2xl"></i>
            </div>
            <h4 class="text-lg font-semibold text-white text-center mb-2">New Project</h4>
            <p class="text-gray-400 text-sm text-center">Add a new portfolio project</p>
        </div>

        {{-- Manage Content --}}
        <a href="{{ route('admin.content') }}" class="action-card card-modern p-6 hover-lift">
            <div class="action-icon p-4 bg-gradient-to-r from-electric-purple to-cyber-pink rounded-xl mb-4 mx-auto w-fit">
                <i class="fas fa-edit text-white text-2xl"></i>
            </div>
            <h4 class="text-lg font-semibold text-white text-center mb-2">Edit Content</h4>
            <p class="text-gray-400 text-sm text-center">Update portfolio sections</p>
        </a>

        {{-- View Messages --}}
        <a href="{{ route('admin.messages') }}" class="action-card card-modern p-6 hover-lift relative">
            @if($unreadMessages > 0)
                <div class="absolute -top-2 -right-2 w-6 h-6 bg-cyber-pink rounded-full flex items-center justify-center">
                    <span class="text-white text-xs font-bold">{{ $unreadMessages }}</span>
                </div>
            @endif

            <div class="action-icon p-4 bg-gradient-to-r from-neon-yellow to-sunset-orange rounded-xl mb-4 mx-auto w-fit">
                <i class="fas fa-comments text-white text-2xl"></i>
            </div>
            <h4 class="text-lg font-semibold text-white text-center mb-2">Messages</h4>
            <p class="text-gray-400 text-sm text-center">View contact messages</p>
        </a>

        {{-- Analytics --}}
        <a href="{{ route('admin.analytics') }}" class="action-card card-modern p-6 hover-lift">
            <div class="action-icon p-4 bg-gradient-to-r from-aurora-blue to-electric-purple rounded-xl mb-4 mx-auto w-fit">
                <i class="fas fa-chart-line text-white text-2xl"></i>
            </div>
            <h4 class="text-lg font-semibold text-white text-center mb-2">Analytics</h4>
            <p class="text-gray-400 text-sm text-center">Detailed performance stats</p>
        </a>
    </div>

    {{-- Real-time notifications --}}
    @if(session()->has('notification'))
        <div class="fixed bottom-6 right-6 z-50">
            <div class="notification glass-card p-4 rounded-lg shadow-glow-green animate-slide-in-right">
                <div class="flex items-center gap-3">
                    <i class="fas fa-check-circle text-neon-green text-xl"></i>
                    <span class="text-white">{{ session('notification') }}</span>
                </div>
            </div>
        </div>
    @endif
</div>

{{-- Enhanced Styles --}}
<style>
    .modern-admin-dashboard {
        padding: 2rem;
        background: linear-gradient(135deg, var(--dark-surface) 0%, var(--card-surface) 100%);
        min-height: 100vh;
    }

    .dashboard-header {
        position: relative;
        overflow: hidden;
    }

    .dashboard-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(139, 92, 246, 0.05) 0%, rgba(236, 72, 153, 0.05) 100%);
        border-radius: 1rem;
        z-index: -1;
    }

    .metric-card {
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .metric-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, var(--electric-purple) 0%, var(--cyber-pink) 50%, var(--aurora-blue) 100%);
    }

    .metric-icon {
        background: var(--gradient-cyber) !important;
        box-shadow: 0 0 20px rgba(139, 92, 246, 0.3);
    }

    .action-card {
        text-align: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        color: inherit;
    }

    .action-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }

    .activity-item {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .activity-item:hover {
        transform: translateX(8px);
        background: rgba(255, 255, 255, 0.05);
    }

    .chart-container canvas {
        filter: drop-shadow(0 0 10px rgba(139, 92, 246, 0.2));
    }

    .notification {
        animation: slideInRight 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .modern-admin-dashboard {
            padding: 1rem;
        }

        .metrics-grid {
            grid-template-columns: 1fr;
        }

        .dashboard-header h1 {
            font-size: 2rem;
        }
    }
</style>

{{-- JavaScript for Chart.js integration --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize analytics chart
    const ctx = document.getElementById('analyticsChart');
    if (ctx) {
        const chart = new Chart(ctx, {
            type: 'line',
            data: @json($chartData),
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: '#ffffff'
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        },
                        ticks: {
                            color: '#94a3b8'
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        },
                        ticks: {
                            color: '#94a3b8'
                        }
                    }
                }
            }
        });

        // Update chart when period changes
        Livewire.on('chartDataUpdated', (data) => {
            chart.data = data;
            chart.update();
        });
    }

    // Initialize counter animations
    const counters = document.querySelectorAll('[data-counter]');
    counters.forEach(counter => {
        const target = parseInt(counter.dataset.counter);
        const suffix = counter.dataset.counterSuffix || '';
        let current = 0;
        const increment = target / 50;

        const updateCounter = () => {
            if (current < target) {
                current += increment;
                counter.textContent = Math.floor(current) + suffix;
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target + suffix;
            }
        };

        updateCounter();
    });
});
</script>