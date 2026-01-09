@extends('admin.layout')
@section('admin-title', 'Dashboard')

@section('admin-content')
    <!-- Stats Row -->
    <div class="row g-4 mb-5">
        <div class="col-md-2">
            <div class="stat-card" style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; text-align: center; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                <div style="font-size: 32px; color: #1e3a8a; font-weight: bold;">{{ $stats['total_users'] }}</div>
                <div style="color: #666; font-size: 13px; margin-top: 10px;">Total Users</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card" style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; text-align: center; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                <div style="font-size: 32px; color: #059669; font-weight: bold;">{{ $stats['online_users'] }}</div>
                <div style="color: #666; font-size: 13px; margin-top: 10px;">Online Now</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card" style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; text-align: center; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                <div style="font-size: 32px; color: #d97706; font-weight: bold;">{{ $stats['total_posts'] }}</div>
                <div style="color: #666; font-size: 13px; margin-top: 10px;">Total Posts</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card" style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; text-align: center; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                <div style="font-size: 32px; color: #8b5cf6; font-weight: bold;">{{ $stats['total_dogs'] }}</div>
                <div style="color: #666; font-size: 13px; margin-top: 10px;">Total Dogs</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card" style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; text-align: center; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                <div style="font-size: 32px; color: #059669; font-weight: bold;">{{ $stats['verified_certificates'] }}</div>
                <div style="color: #666; font-size: 13px; margin-top: 10px;">Verified</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card" style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; text-align: center; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                <div style="font-size: 32px; color: #dc2626; font-weight: bold;">{{ $stats['pending_certificates'] }}</div>
                <div style="color: #666; font-size: 13px; margin-top: 10px;">Pending</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card" style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; text-align: center; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                <div style="font-size: 32px; color: #991b1b; font-weight: bold;">{{ $stats['rejected_certificates'] }}</div>
                <div style="color: #666; font-size: 13px; margin-top: 10px;">Rejected</div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-5">
        <!-- User Registration Chart -->
        <div class="col-lg-6">
            <div style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                <h5 style="color: #1e3a8a; margin-bottom: 20px; font-weight: 600;">User Registration Trend</h5>
                <canvas id="registrationChart" style="max-height: 300px;"></canvas>
            </div>
        </div>

        <!-- Verification Status Pie Chart -->
        <div class="col-lg-6">
            <div style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                <h5 style="color: #1e3a8a; margin-bottom: 20px; font-weight: 600;">Verification Status</h5>
                <canvas id="verificationChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Users -->
        <div class="col-lg-6">
            <div style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                <h5 style="color: #1e3a8a; margin-bottom: 20px; font-weight: 600;">Recent Users</h5>
                <div style="max-height: 400px; overflow-y: auto;">
                    @forelse($recent_users as $user)
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom" style="border-color: #e5e7eb;">
                            <div>
                                <h6 style="color: #333; margin-bottom: 5px; font-weight: 600;">{{ $user->pet_name ?? 'No Pet' }}</h6>
                                <small style="color: #999;">{{ $user->email }}</small>
                            </div>
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm" style="background-color: #1e3a8a; color: #fff; border: none; font-size: 12px; padding: 6px 12px; border-radius: 4px; text-decoration: none;">View</a>
                        </div>
                    @empty
                        <p style="color: #999; text-align: center; padding: 20px;">No users yet</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Posts -->
        <div class="col-lg-6">
            <div style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                <h5 style="color: #1e3a8a; margin-bottom: 20px; font-weight: 600;">Recent Posts</h5>
                <div style="max-height: 400px; overflow-y: auto;">
                    @forelse($recent_posts as $post)
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom" style="border-color: #e5e7eb;">
                            <div>
                                <h6 style="color: #333; margin-bottom: 5px; font-weight: 600;">{{ $post->user->pet_name ?? 'Unknown' }}</h6>
                                <small style="color: #999;">{{ Str::limit($post->message, 50) }}</small>
                            </div>
                            <a href="{{ route('admin.posts.show', $post->id) }}" class="btn btn-sm" style="background-color: #1e3a8a; color: #fff; border: none; font-size: 12px; padding: 6px 12px; border-radius: 4px; text-decoration: none;">View</a>
                        </div>
                    @empty
                        <p style="color: #999; text-align: center; padding: 20px;">No posts yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-4">
        <!-- Pending Certificates -->
        <div class="col-12">
            <div style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 style="color: #1e3a8a; margin: 0; font-weight: 600;">Pending Certificates</h5>
                    <a href="{{ route('admin.certificates') }}" class="btn btn-sm" style="background-color: #1e3a8a; color: #fff; border: none; font-size: 12px; padding: 6px 12px; border-radius: 4px; text-decoration: none;">View All</a>
                </div>
                <div style="max-height: 300px; overflow-y: auto;">
                    @forelse($pending_certificates as $user)
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom" style="border-color: #e5e7eb;">
                            <div>
                                <h6 style="color: #333; margin-bottom: 5px; font-weight: 600;">{{ $user->pet_name ?? 'No Pet' }}</h6>
                                <small style="color: #999;">{{ $user->email }}</small>
                            </div>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('admin.certificates.show', $user->id) }}" class="btn btn-sm" style="background-color: #d97706; color: #fff; border: none; font-size: 12px; padding: 6px 12px; border-radius: 4px; text-decoration: none;">Review</a>
                                <form action="{{ route('admin.certificates.verify', $user->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm" style="background-color: #059669; color: #fff; border: none; font-size: 12px; padding: 6px 12px; border-radius: 4px; cursor: pointer;" onclick="return confirm('Verify this certificate?')">Verify</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p style="color: #999; text-align: center; padding: 20px;">No pending certificates</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-4">
        <!-- Rejected Certificates -->
        <div class="col-12">
            <div style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 style="color: #991b1b; margin: 0; font-weight: 600;">Rejected Certificates</h5>
                    <a href="{{ route('admin.certificates') }}" class="btn btn-sm" style="background-color: #1e3a8a; color: #fff; border: none; font-size: 12px; padding: 6px 12px; border-radius: 4px; text-decoration: none;">View All</a>
                </div>
                <div style="max-height: 300px; overflow-y: auto;">
                    @forelse($rejected_certificates as $user)
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom" style="border-color: #e5e7eb;">
                            <div>
                                <h6 style="color: #333; margin-bottom: 5px; font-weight: 600;">{{ $user->pet_name ?? 'No Pet' }}</h6>
                                <small style="color: #999;">{{ $user->email }}</small>
                            </div>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('admin.certificates.show', $user->id) }}" class="btn btn-sm" style="background-color: #d97706; color: #fff; border: none; font-size: 12px; padding: 6px 12px; border-radius: 4px; text-decoration: none;">Review</a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="background-color: #dc2626; color: #fff; border: none; font-size: 12px; padding: 6px 12px; border-radius: 4px; cursor: pointer;" onclick="return confirm('Delete this rejected certificate?')">Delete</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p style="color: #999; text-align: center; padding: 20px;">No rejected certificates</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // User Registration Chart - Real Data from Database
        const registrationCtx = document.getElementById('registrationChart').getContext('2d');
        new Chart(registrationCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['registration']['labels']) !!},
                datasets: [{
                    label: 'New Users',
                    data: {!! json_encode($chartData['registration']['data']) !!},
                    borderColor: '#1e3a8a',
                    backgroundColor: 'rgba(30, 58, 138, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#1e3a8a',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: '#666',
                            font: { size: 12 }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#666' },
                        grid: { color: '#f0f0f0' }
                    },
                    x: {
                        ticks: { color: '#666' },
                        grid: { color: '#f0f0f0' }
                    }
                }
            }
        });

        // Verification Status Pie Chart - Real Data from Database
        const verificationCtx = document.getElementById('verificationChart').getContext('2d');
        new Chart(verificationCtx, {
            type: 'doughnut',
            data: {
                labels: ['Verified', 'Pending', 'Rejected'],
                datasets: [{
                    data: [{{ $chartData['verification']['verified'] }}, {{ $chartData['verification']['pending'] }}, {{ $chartData['verification']['rejected'] }}],
                    backgroundColor: [
                        '#059669',
                        '#f59e0b',
                        '#dc2626'
                    ],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#666',
                            font: { size: 12 },
                            padding: 15
                        }
                    }
                }
            }
        });
    </script>
@endsection
