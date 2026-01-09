@extends('admin.layout')
@section('admin-title', 'Admin Settings')

@section('admin-content')
    <div class="row g-4">
        <!-- System Information -->
        <div class="col-lg-6">
            <div style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                <h5 style="color: #1e3a8a; margin-bottom: 20px; font-weight: 600;"><i class="bi bi-list"></i> System Info</h5>
                <div style="background-color: #f9fafb; padding: 15px; border-radius: 8px; border: 1px solid #e5e7eb;">
                    <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e5e7eb;">
                        <small style="color: #666;">Laravel</small>
                        <span style="color: #333; font-weight: 500;">{{ app()->version() }}</span>
                    </div>

                    <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e5e7eb;">
                        <small style="color: #666;">PHP</small>
                        <span style="color: #333; font-weight: 500;">{{ PHP_VERSION }}</span>
                    </div>

                    <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e5e7eb;">
                        <small style="color: #666;">App</small>
                        <span style="color: #333; font-weight: 500;">Waggy Community</span>
                    </div>

                    <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e5e7eb;">
                        <small style="color: #666;">Environment</small>
                        <span style="color: #333; font-weight: 500;">{{ config('app.env') }}</span>
                    </div>

                    <div style="display: flex; justify-content: space-between; padding: 8px 0;">
                        <small style="color: #666;">Debug</small>
                        <span style="color: #333; font-weight: 500;">{{ config('app.debug') ? '[on]' : '[off]' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Platform Statistics -->
        <div class="col-lg-6">
            <div style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                <h5 style="color: #1e3a8a; margin-bottom: 20px; font-weight: 600;"><i class="bi bi-graph-up"></i> Stats</h5>
                <div class="row g-2">
                    <div class="col-6">
                        <div style="background-color: #dbeafe; padding: 15px; border-radius: 8px; text-align: center; border: 1px solid #7dd3fc;">
                            <small style="color: #0369a1;"><i class="bi bi-people"></i> Users</small>
                            <p style="color: #0369a1; font-size: 28px; font-weight: bold; margin: 0;">{{ \App\Models\User::where('is_admin', false)->count() }}</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="background-color: #d1fae5; padding: 15px; border-radius: 8px; text-align: center; border: 1px solid #6ee7b7;">
                            <small style="color: #047857;"><i class="bi bi-file-text"></i> Posts</small>
                            <p style="color: #047857; font-size: 28px; font-weight: bold; margin: 0;">{{ \App\Models\Post::count() }}</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="background-color: #fce7f3; padding: 15px; border-radius: 8px; text-align: center; border: 1px solid #f472b6;">
                            <small style="color: #be185d;"><i class="bi bi-chat-dots"></i> Messages</small>
                            <p style="color: #be185d; font-size: 28px; font-weight: bold; margin: 0;">{{ \App\Models\Message::count() }}</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="background-color: #fed7aa; padding: 15px; border-radius: 8px; text-align: center; border: 1px solid #fb923c;">
                            <small style="color: #92400e;"><i class="bi bi-paw"></i> Dogs</small>
                            <p style="color: #92400e; font-size: 28px; font-weight: bold; margin: 0;">{{ \App\Models\User::where('is_admin', false)->whereNotNull('pet_name')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Certificate Statistics -->
        <div class="col-lg-6">
            <div style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                <h5 style="color: #1e3a8a; margin-bottom: 20px; font-weight: 600;"><i class="bi bi-award"></i> Certificates</h5>
                <div style="background-color: #f9fafb; padding: 15px; border-radius: 8px; border: 1px solid #e5e7eb;">
                    <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #e5e7eb;">
                        <small style="color: #666;"><i class="bi bi-check-circle"></i> Verified</small>
                        <span style="color: #059669; font-weight: bold; font-size: 18px;">{{ \App\Models\User::where('is_admin', false)->where('certificate_verified', true)->count() }}</span>
                    </div>

                    <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #e5e7eb;">
                        <small style="color: #666;"><i class="bi bi-hourglass-split"></i> Pending</small>
                        <span style="color: #f59e0b; font-weight: bold; font-size: 18px;">{{ \App\Models\User::where('is_admin', false)->where('certificate_verified', false)->whereNotNull('certificate_path')->whereNull('certificate_rejected_at')->count() }}</span>
                    </div>

                    <div style="display: flex; justify-content: space-between; padding: 12px 0;">
                        <small style="color: #666;"><i class="bi bi-x-circle"></i> Rejected</small>
                        <span style="color: #dc2626; font-weight: bold; font-size: 18px;">{{ \App\Models\User::where('is_admin', false)->whereNotNull('certificate_rejected_at')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Activity -->
        <div class="col-lg-6">
            <div style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                <h5 style="color: #1e3a8a; margin-bottom: 20px; font-weight: 600;"><i class="bi bi-activity"></i> Activity</h5>
                <div style="background-color: #f9fafb; padding: 15px; border-radius: 8px; border: 1px solid #e5e7eb;">
                    <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #e5e7eb;">
                        <small style="color: #666;"><i class="bi bi-circle-fill" style="color: #059669;"></i> Online</small>
                        <span style="color: #059669; font-weight: bold;">{{ \App\Models\User::where('is_online', true)->count() }}</span>
                    </div>

                    <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #e5e7eb;">
                        <small style="color: #666;"><i class="bi bi-calendar"></i> This Week</small>
                        <span style="color: #1e3a8a; font-weight: bold;">{{ \App\Models\User::where('last_seen', '>=', now()->subWeek())->count() }}</span>
                    </div>

                    <div style="display: flex; justify-content: space-between; padding: 12px 0;">
                        <small style="color: #666;"><i class="bi bi-pause-circle"></i> Inactive</small>
                        <span style="color: #d97706; font-weight: bold;">{{ \App\Models\User::where('last_seen', '<', now()->subMonth())->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Actions -->
        <div class="col-12">
            <div style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                <h5 style="color: #1e3a8a; margin-bottom: 20px; font-weight: 600;"><i class="bi bi-gear"></i> Actions</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="{{ route('admin.dashboard') }}" class="btn" style="background-color: #1e3a8a; color: #fff; border: none; padding: 12px; border-radius: 4px; width: 100%; font-weight: 500; cursor: pointer; text-decoration: none; display: block; text-align: center;"><i class="bi bi-graph-up"></i> Dashboard</a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.users') }}" class="btn" style="background-color: #059669; color: #fff; border: none; padding: 12px; border-radius: 4px; width: 100%; font-weight: 500; cursor: pointer; text-decoration: none; display: block; text-align: center;"><i class="bi bi-people"></i> Users</a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.certificates') }}" class="btn" style="background-color: #d97706; color: #fff; border: none; padding: 12px; border-radius: 4px; width: 100%; font-weight: 500; cursor: pointer; text-decoration: none; display: block; text-align: center;"><i class="bi bi-award"></i> Certificates</a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.posts') }}" class="btn" style="background-color: #8b5cf6; color: #fff; border: none; padding: 12px; border-radius: 4px; width: 100%; font-weight: 500; cursor: pointer; text-decoration: none; display: block; text-align: center;"><i class="bi bi-file-text"></i> Posts</a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.settings.admins') }}" class="btn" style="background-color: #6366f1; color: #fff; border: none; padding: 12px; border-radius: 4px; width: 100%; font-weight: 500; cursor: pointer; text-decoration: none; display: block; text-align: center;"><i class="bi bi-person-plus"></i> Manage Admins</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Help & Info -->
        <div class="col-12">
            <div style="background-color: #dbeafe; border: 1px solid #7dd3fc; border-radius: 8px; padding: 20px;">
                <h6 style="color: #0369a1; margin-bottom: 10px; font-weight: 600;"><i class="bi bi-info-circle"></i> Admin Guide</h6>
                <ul style="color: #0369a1; margin: 0; padding-left: 20px; font-size: 14px;">
                    <li><i class="bi bi-graph-up"></i> Monitor platform statistics and user activity</li>
                    <li><i class="bi bi-award"></i> Manage certificate verification workflow</li>
                    <li><i class="bi bi-file-text"></i> Review and moderate posts and user content</li>
                    <li><i class="bi bi-trending-up"></i> Track system performance and user engagement</li>
                    <li><i class="bi bi-shield-lock"></i> Ensure platform security and compliance</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
