@extends('admin.layout')
@section('admin-title', 'Manage Admins')

@section('admin-content')
    <style>
        .admin-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }
        
        .admin-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }
        
        .form-group {
            margin-bottom: 16px;
        }
        
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #1e3a8a;
            margin-bottom: 8px;
        }
        
        .form-input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #1e3a8a;
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        }
        
        .btn-create {
            width: 100%;
            padding: 11px 16px;
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 8px;
        }
        
        .btn-create:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
        }
        
        .admin-item {
            background-color: #f9fafb;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 12px;
            border: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }
        
        .admin-item:hover {
            background-color: #f3f4f6;
            border-color: #d1d5db;
        }
        
        .admin-info {
            flex: 1;
        }
        
        .admin-name {
            font-weight: 700;
            color: #1f2937;
            margin: 0;
            font-size: 15px;
        }
        
        .admin-email {
            color: #6b7280;
            font-size: 13px;
            margin: 6px 0 0 0;
        }
        
        .admin-badge {
            display: inline-block;
            background-color: #dbeafe;
            color: #0369a1;
            padding: 5px 12px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
        }
        
        .btn-delete {
            background: #fff;
            border: 1.5px solid #ef4444;
            color: #ef4444;
            padding: 8px 14px;
            font-size: 13px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }
        
        .btn-delete:hover {
            background-color: #fee2e2;
            border-color: #dc2626;
            color: #dc2626;
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 16px;
            font-size: 14px;
        }
        
        .alert-success {
            background-color: #dcfce7;
            border: 1px solid #86efac;
            color: #166534;
        }
        
        .alert-error {
            background-color: #fee2e2;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }
        
        .empty-state {
            text-align: center;
            padding: 32px 20px;
            color: #9ca3af;
        }
        
        .info-box {
            background: linear-gradient(135deg, #dbeafe 0%, #e0f2fe 100%);
            border: 1px solid #7dd3fc;
            border-radius: 8px;
            padding: 20px;
            margin-top: 24px;
        }
        
        .info-title {
            color: #0369a1;
            margin-bottom: 12px;
            font-weight: 700;
            font-size: 15px;
        }
        
        .info-list {
            color: #0369a1;
            margin: 0;
            padding-left: 20px;
            font-size: 14px;
        }
        
        .info-list li {
            margin-bottom: 6px;
            line-height: 1.5;
        }
        
        .section-title {
            color: #1e3a8a;
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .badge-count {
            background-color: #1e3a8a;
            color: #fff;
            padding: 3px 9px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 700;
        }
    </style>

    <div class="row g-4">
        <!-- Add New Admin -->
        <div class="col-lg-5">
            <div class="admin-card" style="padding: 28px;">
                <h5 class="section-title">
                    <i class="bi bi-person-plus-fill" style="color: #1e3a8a; font-size: 20px;"></i>
                    Create New Admin
                </h5>

                @if($errors->any())
                <div class="alert alert-error">
                    <strong><i class="bi bi-exclamation-circle"></i> Validation Error:</strong>
                    <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                </div>
                @endif

                <form action="{{ route('admin.settings.admins.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label"><i class="bi bi-person"></i> Full Name</label>
                        <input type="text" name="name" required class="form-input" value="{{ old('name') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="bi bi-envelope"></i> Email Address</label>
                        <input type="email" name="email" required class="form-input" value="{{ old('email') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="bi bi-lock"></i> Password</label>
                        <input type="password" name="password" required class="form-input">
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="bi bi-lock-check"></i> Confirm Password</label>
                        <input type="password" name="password_confirmation" required class="form-input">
                    </div>

                    <button type="submit" class="btn-create">
                        <i class="bi bi-plus-circle"></i> Create Admin Account
                    </button>
                </form>
            </div>
        </div>

        <!-- Admin Accounts List -->
        <div class="col-lg-7">
            <div class="admin-card" style="padding: 28px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h5 class="section-title" style="margin: 0;">
                        <i class="bi bi-shield-check" style="color: #1e3a8a; font-size: 20px;"></i>
                        Admin Accounts
                        <span class="badge-count">{{ count($admins) }}</span>
                    </h5>
                </div>

                @if(session('success') && !session('error'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-error">
                    <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                </div>
                @endif

                @if(count($admins) > 0)
                    @foreach($admins as $admin)
                    <div class="admin-item">
                        <div class="admin-info">
                            <p class="admin-name">{{ $admin->name }}</p>
                            <p class="admin-email">{{ $admin->email }}</p>
                            @if($admin->id === auth()->user()->id)
                            <span class="admin-badge">
                                <i class="bi bi-check-circle"></i> Current User
                            </span>
                            @elseif($admin->isSuperAdmin())
                            <span class="admin-badge" style="background-color: #fef08a; color: #854d0e;">
                                <i class="bi bi-crown"></i> Super Admin
                            </span>
                            @else
                            <span class="admin-badge" style="background-color: #d1fae5; color: #065f46;">
                                <i class="bi bi-person-check"></i> Admin
                            </span>
                            @endif
                        </div>
                        
                        @if($admin->id !== auth()->user()->id && count($admins) > 1)
                        <form action="{{ route('admin.settings.admins.delete', $admin->id) }}" method="POST" style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" onclick="return confirm('Are you sure you want to delete this admin? This action cannot be undone.');">
                                <i class="bi bi-trash2"></i> Delete
                            </button>
                        </form>
                        @endif
                        @if($admin->id === auth()->user()->id || count($admins) <= 1)
                        <span style="color: #9ca3af; font-size: 12px; font-weight: 600;">
                            <i class="bi bi-lock"></i>
                            @if(count($admins) <= 1)
                                Last Admin
                            @else
                                Cannot Delete
                            @endif
                        </span>
                        @endif
                    </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <i class="bi bi-inbox" style="font-size: 32px; display: block; margin-bottom: 10px;"></i>
                        <p>No admin accounts created yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Info Box -->
    <div class="info-box">
        <h6 class="info-title"><i class="bi bi-info-circle"></i> Admin Management Information</h6>
        <ul class="info-list">
            <li><strong>Super Admin:</strong> First admin created - has full control over admin accounts</li>
            <li><strong>Admin:</strong> Regular admin - can manage platform but cannot create/delete admins</li>
            <li>At least one admin account must always exist</li>
            <li>You cannot delete your own account from this page</li>
            <li>Only super admins can create or delete admin accounts</li>
        </ul>
    </div>
@endsection
