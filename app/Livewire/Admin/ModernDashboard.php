<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Project;
use App\Models\Contact;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * PHASE 3: MODERN ADMIN DASHBOARD COMPONENT
 * Real-time dashboard with advanced analytics
 */
class ModernDashboard extends Component
{
    public $chartPeriod = '30d';
    public $refreshInterval = 30; // seconds

    // Computed properties for real-time updates
    public function getTotalProjectsProperty()
    {
        return Cache::remember('dashboard.total_projects', 300, function () {
            return Project::count();
        });
    }

    public function getActiveProjectsProperty()
    {
        return Cache::remember('dashboard.active_projects', 300, function () {
            return Project::where('status_project', 'active')->count();
        });
    }

    public function getTotalViewsProperty()
    {
        return Cache::remember('dashboard.total_views', 300, function () {
            // You can integrate with Google Analytics or your own tracking
            return rand(25000, 50000); // Placeholder
        });
    }

    public function getTodayViewsProperty()
    {
        return Cache::remember('dashboard.today_views', 60, function () {
            return rand(150, 300); // Placeholder
        });
    }

    public function getWeekViewsProperty()
    {
        return Cache::remember('dashboard.week_views', 300, function () {
            return rand(1500, 3000); // Placeholder
        });
    }

    public function getTotalMessagesProperty()
    {
        return Cache::remember('dashboard.total_messages', 300, function () {
            return Contact::count();
        });
    }

    public function getUnreadMessagesProperty()
    {
        return Cache::remember('dashboard.unread_messages', 60, function () {
            return Contact::where('is_read', false)->count();
        });
    }

    public function getResponseRateProperty()
    {
        return Cache::remember('dashboard.response_rate', 300, function () {
            $total = Contact::count();
            $responded = Contact::where('responded_at', '!=', null)->count();
            return $total > 0 ? round(($responded / $total) * 100) : 100;
        });
    }

    public function getTotalFollowersProperty()
    {
        return Cache::remember('dashboard.total_followers', 300, function () {
            $setting = Setting::first();
            return $setting ? (int) str_replace(['K', 'k', '+'], '', $setting->followers_count ?? '54') : 54;
        });
    }

    public function getProjectsGrowthProperty()
    {
        return Cache::remember('dashboard.projects_growth', 300, function () {
            $currentMonth = Project::whereMonth('created_at', now()->month)->count();
            $lastMonth = Project::whereMonth('created_at', now()->subMonth()->month)->count();

            if ($lastMonth == 0) return 100;
            return round((($currentMonth - $lastMonth) / $lastMonth) * 100);
        });
    }

    public function getViewsGrowthProperty()
    {
        return Cache::remember('dashboard.views_growth', 300, function () {
            // Placeholder for analytics growth calculation
            return rand(15, 45);
        });
    }

    public function getFollowersGrowthProperty()
    {
        return Cache::remember('dashboard.followers_growth', 300, function () {
            // Placeholder for social media growth calculation
            return rand(8, 25);
        });
    }

    public function getRecentActivitiesProperty()
    {
        return Cache::remember('dashboard.recent_activities', 60, function () {
            $activities = [];

            // Get recent projects
            $recentProjects = Project::latest()->take(3)->get();
            foreach ($recentProjects as $project) {
                $activities[] = [
                    'icon' => 'fas fa-plus',
                    'color' => 'neon-green',
                    'message' => "New project '{$project->nama_project}' was created",
                    'time' => $project->created_at->diffForHumans(),
                    'created_at' => $project->created_at
                ];
            }

            // Get recent messages
            $recentMessages = Contact::latest()->take(3)->get();
            foreach ($recentMessages as $message) {
                $activities[] = [
                    'icon' => 'fas fa-envelope',
                    'color' => 'cyber-pink',
                    'message' => "New message from {$message->full_name}",
                    'time' => $message->created_at->diffForHumans(),
                    'created_at' => $message->created_at
                ];
            }

            // Sort by creation time and take latest 5
            usort($activities, function ($a, $b) {
                return $b['created_at'] <=> $a['created_at'];
            });

            return array_slice($activities, 0, 5);
        });
    }

    public function getChartDataProperty()
    {
        return Cache::remember("dashboard.chart_data.{$this->chartPeriod}", 300, function () {
            $days = $this->chartPeriod === '7d' ? 7 : ($this->chartPeriod === '30d' ? 30 : 90);

            $labels = [];
            $viewsData = [];
            $projectsData = [];

            for ($i = $days - 1; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $labels[] = $date->format('M j');

                // Simulate data - replace with real analytics
                $viewsData[] = rand(50, 200);
                $projectsData[] = rand(0, 3);
            }

            return [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Views',
                        'data' => $viewsData,
                        'borderColor' => '#8b5cf6',
                        'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
                        'fill' => true,
                        'tension' => 0.4
                    ],
                    [
                        'label' => 'Projects',
                        'data' => $projectsData,
                        'borderColor' => '#ec4899',
                        'backgroundColor' => 'rgba(236, 72, 153, 0.1)',
                        'fill' => true,
                        'tension' => 0.4
                    ]
                ]
            ];
        });
    }

    public function mount()
    {
        // Initialize dashboard
        $this->refreshData();
    }

    public function refreshData()
    {
        // Clear dashboard caches
        $cacheKeys = [
            'dashboard.total_projects',
            'dashboard.active_projects',
            'dashboard.total_views',
            'dashboard.today_views',
            'dashboard.week_views',
            'dashboard.total_messages',
            'dashboard.unread_messages',
            'dashboard.response_rate',
            'dashboard.total_followers',
            'dashboard.projects_growth',
            'dashboard.views_growth',
            'dashboard.followers_growth',
            'dashboard.recent_activities',
            "dashboard.chart_data.{$this->chartPeriod}"
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        // Emit event for real-time updates
        $this->dispatch('dashboardRefreshed');

        session()->flash('notification', 'Dashboard data updated successfully!');
    }

    public function setChartPeriod($period)
    {
        $this->chartPeriod = $period;

        // Clear chart cache
        Cache::forget("dashboard.chart_data.{$period}");

        // Emit chart update event
        $this->dispatch('chartDataUpdated', $this->chartData);
    }

    public function getListeners()
    {
        return [
            'refreshDashboard' => 'refreshData',
            'newMessage' => 'handleNewMessage',
            'projectUpdated' => 'handleProjectUpdate'
        ];
    }

    public function handleNewMessage()
    {
        // Clear message-related caches
        Cache::forget('dashboard.total_messages');
        Cache::forget('dashboard.unread_messages');
        Cache::forget('dashboard.recent_activities');

        $this->dispatch('dashboardRefreshed');
    }

    public function handleProjectUpdate()
    {
        // Clear project-related caches
        Cache::forget('dashboard.total_projects');
        Cache::forget('dashboard.active_projects');
        Cache::forget('dashboard.projects_growth');
        Cache::forget('dashboard.recent_activities');

        $this->dispatch('dashboardRefreshed');
    }

    public function render()
    {
        return view('livewire.admin.modern-dashboard')
            ->layout('layouts.admin-enhanced');
    }
}