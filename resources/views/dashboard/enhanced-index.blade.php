@extends('layouts.admin-enhanced')
@section('content')

<!-- Welcome Section -->
<div class="modern-card" style="margin-bottom: 2rem;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
        <div>
            <h1 style="margin: 0; font-size: 2rem; font-weight: 800; color: white;">
                Welcome back, {{ Auth::check() ? Auth::user()->name : 'Admin' }}! 👋
            </h1>
            <p style="margin: 0.5rem 0 0 0; color: rgba(255, 255, 255, 0.8); font-size: 1.125rem;">
                Digital Transformation Consultant Dashboard - {{ \Carbon\Carbon::now()->format('F d, Y') }}
            </p>
        </div>
        <div style="text-align: right;">
            <div style="font-size: 1.5rem; font-weight: 700; color: var(--neon-yellow);">
                {{ \Carbon\Carbon::now()->format('H:i') }}
            </div>
            <div style="font-size: 0.875rem; color: rgba(255, 255, 255, 0.7);">
                {{ \Carbon\Carbon::now()->format('l') }}
            </div>
        </div>
    </div>

    <!-- Quick Stats Bar -->
    <div style="display: flex; gap: 2rem; padding: 1.5rem; background: rgba(255, 255, 255, 0.05); border-radius: 1rem; margin-top: 1.5rem;">
        <div style="text-align: center;">
            <div style="font-size: 1.75rem; font-weight: 800; color: var(--electric-purple);">54K+</div>
            <div style="font-size: 0.875rem; color: rgba(255, 255, 255, 0.7);">Social Followers</div>
        </div>
        <div style="width: 1px; background: rgba(255, 255, 255, 0.1);"></div>
        <div style="text-align: center;">
            <div style="font-size: 1.75rem; font-weight: 800; color: var(--cyber-pink);">16+</div>
            <div style="font-size: 0.875rem; color: rgba(255, 255, 255, 0.7);">Years Experience</div>
        </div>
        <div style="width: 1px; background: rgba(255, 255, 255, 0.1);"></div>
        <div style="text-align: center;">
            <div style="font-size: 1.75rem; font-weight: 800; color: var(--neon-green);">$250K+</div>
            <div style="font-size: 0.875rem; color: rgba(255, 255, 255, 0.7);">Cost Savings</div>
        </div>
        <div style="width: 1px; background: rgba(255, 255, 255, 0.1);"></div>
        <div style="text-align: center;">
            <div style="font-size: 1.75rem; font-weight: 800; color: var(--aurora-blue);">99%</div>
            <div style="font-size: 0.875rem; color: rgba(255, 255, 255, 0.7);">Success Rate</div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="action-buttons">
    <a href="{{ route('project.create') }}" class="btn-modern">
        <i class="fas fa-plus"></i>
        Add New Project
    </a>
    <a href="{{ route('berita.create') }}" class="btn-modern secondary">
        <i class="fas fa-edit"></i>
        Write Article
    </a>
    <a href="{{ route('contacts.index') }}" class="btn-modern secondary">
        <i class="fas fa-envelope"></i>
        Check Messages
    </a>
    <a href="{{ url('/') }}" target="_blank" class="btn-modern secondary">
        <i class="fas fa-external-link-alt"></i>
        View Portfolio
    </a>
</div>

<!-- Main Statistics Grid -->
<div class="stats-grid">
    <!-- Portfolio Projects Card -->
    <div class="stat-card projects">
        <div class="stat-icon-large projects">
            <i class="fas fa-briefcase"></i>
        </div>
        <div class="stat-number">{{ $countProject }}</div>
        <div class="stat-label">Portfolio Projects</div>
        <div class="stat-trend">
            <i class="fas fa-arrow-up"></i>
            <span>Active portfolio showcasing expertise</span>
        </div>
        <div style="margin-top: 1rem;">
            <a href="{{ route('project.index') }}" style="color: var(--electric-purple); text-decoration: none; font-weight: 600; font-size: 0.875rem;">
                Manage Projects →
            </a>
        </div>
    </div>

    <!-- Media Gallery Card -->
    <div class="stat-card gallery">
        <div class="stat-icon-large gallery">
            <i class="fas fa-images"></i>
        </div>
        <div class="stat-number">{{ $countGaleri }}</div>
        <div class="stat-label">Media Assets</div>
        <div class="stat-trend">
            <i class="fas fa-chart-line"></i>
            <span>Visual content for engagement</span>
        </div>
        <div style="margin-top: 1rem;">
            <a href="{{ route('galeri.index') }}" style="color: var(--neon-green); text-decoration: none; font-weight: 600; font-size: 0.875rem;">
                Manage Media →
            </a>
        </div>
    </div>

    <!-- Articles & Insights Card -->
    <div class="stat-card articles">
        <div class="stat-icon-large articles">
            <i class="fas fa-newspaper"></i>
        </div>
        <div class="stat-number">{{ $countBerita }}</div>
        <div class="stat-label">Published Articles</div>
        <div class="stat-trend">
            <i class="fas fa-brain"></i>
            <span>Thought leadership content</span>
        </div>
        <div style="margin-top: 1rem;">
            <a href="{{ route('berita.index') }}" style="color: var(--neon-yellow); text-decoration: none; font-weight: 600; font-size: 0.875rem;">
                Manage Articles →
            </a>
        </div>
    </div>

    <!-- Client Messages Card -->
    <div class="stat-card messages">
        <div class="stat-icon-large messages">
            <i class="fas fa-envelope"></i>
        </div>
        <div class="stat-number">{{ $countPesan }}</div>
        <div class="stat-label">Client Messages</div>
        <div class="stat-trend">
            <i class="fas fa-handshake"></i>
            <span>Business opportunities</span>
        </div>
        <div style="margin-top: 1rem;">
            <a href="{{ route('contacts.index') }}" style="color: #ef4444; text-decoration: none; font-weight: 600; font-size: 0.875rem;">
                View Messages →
            </a>
        </div>
    </div>
</div>

<!-- Content Management Overview -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-top: 2rem;">
    <!-- Recent Messages Table -->
    <div class="modern-table">
        <div class="table-header">
            <h3 class="table-title">Recent Client Messages</h3>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Service Interest</th>
                        <th>Message Preview</th>
                        <th>Date</th>
                        <th>Priority</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($contacts ?? [] as $contact)
                        <tr>
                            <td>
                                <div style="font-weight: 600;">{{ $contact->full_name }}</div>
                                <div style="font-size: 0.875rem; color: rgba(255, 255, 255, 0.7);">{{ $contact->email }}</div>
                            </td>
                            <td>
                                @if($contact->service)
                                    <span class="success-badge">{{ ucfirst(str_replace('-', ' ', $contact->service)) }}</span>
                                @else
                                    <span style="color: rgba(255, 255, 255, 0.5);">General Inquiry</span>
                                @endif
                            </td>
                            <td>
                                <div style="max-width: 300px; overflow: hidden; text-overflow: ellipsis;">
                                    {{ Str::limit($contact->message, 60) }}
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 0.875rem;">{{ $contact->created_at->format('M d, Y') }}</div>
                                <div style="font-size: 0.75rem; color: rgba(255, 255, 255, 0.7);">{{ $contact->created_at->format('H:i') }}</div>
                            </td>
                            <td>
                                @if($contact->created_at->isToday())
                                    <span class="warning-badge">Today</span>
                                @elseif($contact->created_at->isYesterday())
                                    <span class="success-badge">Recent</span>
                                @else
                                    <span style="color: rgba(255, 255, 255, 0.5);">Older</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 3rem; color: rgba(255, 255, 255, 0.5);">
                                <i class="fas fa-inbox fa-3x" style="margin-bottom: 1rem; opacity: 0.3;"></i>
                                <div>No messages yet</div>
                                <div style="font-size: 0.875rem;">Client messages will appear here</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions Panel -->
    <div class="modern-card">
        <h3 style="margin: 0 0 1.5rem 0; font-size: 1.25rem; font-weight: 600; color: white;">
            <i class="fas fa-rocket" style="color: var(--electric-purple); margin-right: 0.5rem;"></i>
            Quick Actions
        </h3>

        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <!-- Website Analytics -->
            <div style="padding: 1rem; background: rgba(255, 255, 255, 0.05); border-radius: 0.75rem; border-left: 3px solid var(--electric-purple);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="font-weight: 600; color: white;">Website Status</span>
                    <span class="success-badge">Live</span>
                </div>
                <div style="font-size: 0.875rem; color: rgba(255, 255, 255, 0.7);">
                    Portfolio accessible and optimized
                </div>
                <a href="{{ url('/') }}" target="_blank" style="color: var(--electric-purple); text-decoration: none; font-size: 0.875rem; font-weight: 600;">
                    View Live Site →
                </a>
            </div>

            <!-- Content Updates -->
            <div style="padding: 1rem; background: rgba(255, 255, 255, 0.05); border-radius: 0.75rem; border-left: 3px solid var(--neon-green);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="font-weight: 600; color: white;">Content Fresh</span>
                    <span class="success-badge">Up to date</span>
                </div>
                <div style="font-size: 0.875rem; color: rgba(255, 255, 255, 0.7);">
                    Last updated {{ \Carbon\Carbon::now()->diffForHumans() }}
                </div>
                <a href="{{ route('berita.create') }}" style="color: var(--neon-green); text-decoration: none; font-size: 0.875rem; font-weight: 600;">
                    Add New Content →
                </a>
            </div>

            <!-- Social Media Reach -->
            <div style="padding: 1rem; background: rgba(255, 255, 255, 0.05); border-radius: 0.75rem; border-left: 3px solid var(--cyber-pink);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="font-weight: 600; color: white;">Social Reach</span>
                    <span style="color: var(--cyber-pink); font-weight: 600;">54K+</span>
                </div>
                <div style="font-size: 0.875rem; color: rgba(255, 255, 255, 0.7);">
                    Growing audience engagement
                </div>
                <a href="{{ route('setting.index') }}" style="color: var(--cyber-pink); text-decoration: none; font-size: 0.875rem; font-weight: 600;">
                    Update Social Links →
                </a>
            </div>

            <!-- Business Performance -->
            <div style="padding: 1rem; background: rgba(255, 255, 255, 0.05); border-radius: 0.75rem; border-left: 3px solid var(--neon-yellow);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="font-weight: 600; color: white;">Business Impact</span>
                    <span style="color: var(--neon-yellow); font-weight: 600;">$250K+</span>
                </div>
                <div style="font-size: 0.875rem; color: rgba(255, 255, 255, 0.7);">
                    Cost savings delivered to clients
                </div>
                <a href="{{ route('project.index') }}" style="color: var(--neon-yellow); text-decoration: none; font-size: 0.875rem; font-weight: 600;">
                    View Case Studies →
                </a>
            </div>
        </div>

        <!-- Professional Brand Status -->
        <div style="margin-top: 2rem; padding: 1.5rem; background: var(--gradient-cyber); border-radius: 1rem;">
            <div style="text-align: center; color: white;">
                <i class="fas fa-shield-alt fa-2x animate-pulse-glow" style="margin-bottom: 1rem;"></i>
                <div style="font-weight: 700; font-size: 1.125rem; margin-bottom: 0.5rem;">
                    Professional Brand Status
                </div>
                <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 1rem;">
                    Established Digital Transformation Authority
                </div>
                <div style="display: flex; justify-content: space-around; font-size: 0.75rem;">
                    <div>
                        <div style="font-weight: 600;">16+ Years</div>
                        <div style="opacity: 0.8;">Experience</div>
                    </div>
                    <div>
                        <div style="font-weight: 600;">99%</div>
                        <div style="opacity: 0.8;">Success Rate</div>
                    </div>
                    <div>
                        <div style="font-weight: 600;">54K+</div>
                        <div style="opacity: 0.8;">Followers</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Business Intelligence Section -->
<div class="modern-card" style="margin-top: 2rem;">
    <h3 style="margin: 0 0 1.5rem 0; font-size: 1.25rem; font-weight: 600; color: white;">
        <i class="fas fa-chart-line" style="color: var(--aurora-blue); margin-right: 0.5rem;"></i>
        Business Intelligence Dashboard
    </h3>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
        <!-- Lead Generation -->
        <div style="text-align: center; padding: 1.5rem; background: rgba(255, 255, 255, 0.05); border-radius: 1rem;">
            <div style="font-size: 2rem; color: var(--electric-purple); margin-bottom: 0.5rem;">
                <i class="fas fa-funnel-dollar"></i>
            </div>
            <div style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 0.25rem;">
                {{ $countPesan ?? 0 }}
            </div>
            <div style="font-size: 0.875rem; color: rgba(255, 255, 255, 0.7);">
                Lead Generation
            </div>
        </div>

        <!-- Content Authority -->
        <div style="text-align: center; padding: 1.5rem; background: rgba(255, 255, 255, 0.05); border-radius: 1rem;">
            <div style="font-size: 2rem; color: var(--neon-yellow); margin-bottom: 0.5rem;">
                <i class="fas fa-lightbulb"></i>
            </div>
            <div style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 0.25rem;">
                {{ $countBerita ?? 0 }}
            </div>
            <div style="font-size: 0.875rem; color: rgba(255, 255, 255, 0.7);">
                Thought Leadership
            </div>
        </div>

        <!-- Portfolio Strength -->
        <div style="text-align: center; padding: 1.5rem; background: rgba(255, 255, 255, 0.05); border-radius: 1rem;">
            <div style="font-size: 2rem; color: var(--neon-green); margin-bottom: 0.5rem;">
                <i class="fas fa-trophy"></i>
            </div>
            <div style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 0.25rem;">
                {{ $countProject ?? 0 }}
            </div>
            <div style="font-size: 0.875rem; color: rgba(255, 255, 255, 0.7);">
                Portfolio Strength
            </div>
        </div>

        <!-- Brand Visibility -->
        <div style="text-align: center; padding: 1.5rem; background: rgba(255, 255, 255, 0.05); border-radius: 1rem;">
            <div style="font-size: 2rem; color: var(--cyber-pink); margin-bottom: 0.5rem;">
                <i class="fas fa-eye"></i>
            </div>
            <div style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 0.25rem;">
                {{ $countGaleri ?? 0 }}
            </div>
            <div style="font-size: 0.875rem; color: rgba(255, 255, 255, 0.7);">
                Brand Visibility
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add real-time clock update
    function updateTime() {
        const timeElements = document.querySelectorAll('[data-time]');
        timeElements.forEach(element => {
            const now = new Date();
            element.textContent = now.toLocaleTimeString('en-US', {
                hour12: false,
                hour: '2-digit',
                minute: '2-digit'
            });
        });
    }

    // Update time every minute
    setInterval(updateTime, 60000);

    // Add hover effects to stat cards
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
            this.style.boxShadow = '0 25px 50px rgba(0, 0, 0, 0.4)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
            this.style.boxShadow = 'none';
        });
    });

    // Add click tracking for analytics
    document.querySelectorAll('a[href]').forEach(link => {
        link.addEventListener('click', function() {
            console.log('Navigation:', this.href);
            // Add analytics tracking here
        });
    });
});
</script>

@endsection