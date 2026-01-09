<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Dog;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Show Admin Login Form
     */
    public function showAdminLogin()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle Admin Login
     */
    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials) && Auth::user()->is_admin) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        if (Auth::check() && !Auth::user()->is_admin) {
            Auth::logout();
        }

        return back()->withErrors([
            'email' => 'Invalid admin credentials.',
        ])->onlyInput('email');
    }

    /**
     * Show Admin Registration Form
     */
    public function showAdminRegister()
    {
        // Only allow if user is logged in as admin OR no admin exists yet
        $adminExists = User::where('is_admin', true)->exists();
        if ($adminExists) {
            // Only admins can register new admins
            if (!auth()->check() || !auth()->user()->is_admin) {
                return redirect()->route('admin.login')->with('error', 'Admin registration is restricted.');
            }
        }

        return view('admin.auth.register');
    }

    /**
     * Handle Admin Registration
     */
    public function registerAdmin(Request $request)
    {
        // Check if any admin exists - if yes, only logged-in admins can register
        $adminExists = User::where('is_admin', true)->exists();
        if ($adminExists) {
            if (!auth()->check() || !auth()->user()->is_admin) {
                return redirect()->route('admin.login')->with('error', 'Admin registration is restricted.');
            }
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // First admin is super_admin, others are regular admin
        $isSuperAdmin = !$adminExists;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => true,
            'admin_role' => $isSuperAdmin ? 'super_admin' : 'admin',
            'email_verified_at' => now(),
        ]);

        // If this is the first admin, login automatically
        // Otherwise, just redirect back to login
        if ($isSuperAdmin) {
            Auth::login($user);
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('admin.login')->with('success', 'Admin user created successfully. Please login.');
        }
    }

    /**
     * Handle Admin Logout
     */
    public function adminLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    /**
     * Show Admin Dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::where('is_admin', false)->count(),
            'total_posts' => Post::count(),
            'total_dogs' => User::where('is_admin', false)->whereNotNull('pet_name')->count(),
            'verified_certificates' => User::where('is_admin', false)->where('certificate_verified', true)->count(),
            'pending_certificates' => User::where('is_admin', false)->where('certificate_verified', false)->whereNotNull('certificate_path')->whereNull('certificate_rejected_at')->count(),
            'rejected_certificates' => User::where('is_admin', false)->whereNotNull('certificate_rejected_at')->count(),
            'online_users' => User::where('is_online', true)->count(),
        ];

        $recent_users = User::where('is_admin', false)->orderBy('created_at', 'desc')->limit(10)->get();
        $recent_posts = Post::with('user')->orderBy('created_at', 'desc')->limit(10)->get();
        $pending_certificates = User::where('certificate_verified', false)->whereNotNull('certificate_path')->whereNull('certificate_rejected_at')->limit(5)->get();
        $rejected_certificates = User::whereNotNull('certificate_rejected_at')->limit(5)->get();

        // Chart Data - User Registration Trend (last 6 weeks)
        $registrationData = [];
        $registrationLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $startDate = now()->subWeeks($i)->startOfWeek();
            $endDate = now()->subWeeks($i)->endOfWeek();
            $count = User::where('is_admin', false)->whereBetween('created_at', [$startDate, $endDate])->count();
            $registrationData[] = $count;
            $registrationLabels[] = $startDate->format('M d');
        }

        // Chart Data - Certificate Verification Distribution
        $verifiedCount = User::where('is_admin', false)->where('certificate_verified', true)->count();
        $pendingCount = User::where('is_admin', false)->where('certificate_verified', false)->whereNotNull('certificate_path')->whereNull('certificate_rejected_at')->count();
        $rejectedCount = User::where('is_admin', false)->whereNotNull('certificate_rejected_at')->count();

        $chartData = [
            'registration' => [
                'labels' => $registrationLabels,
                'data' => $registrationData
            ],
            'verification' => [
                'verified' => $verifiedCount,
                'pending' => $pendingCount,
                'rejected' => $rejectedCount
            ]
        ];

        return view('admin.dashboard', compact('stats', 'recent_users', 'recent_posts', 'pending_certificates', 'rejected_certificates', 'chartData'));
    }

    /**
     * Show all users
     */
    public function users(Request $request)
    {
        $search = $request->query('search');
        $query = User::where('is_admin', false);

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('pet_name', 'like', "%{$search}%");
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.users.index', compact('users', 'search'));
    }

    /**
     * Show user details
     */
    public function showUser($id)
    {
        $user = User::findOrFail($id);
        $posts = $user->posts()->paginate(10);
        $messages_sent = $user->sentMessages()->count();
        $messages_received = $user->receivedMessages()->count();

        return view('admin.users.show', compact('user', 'posts', 'messages_sent', 'messages_received'));
    }

    /**
     * Edit user
     */
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'pet_name' => 'nullable|string|max:255',
            'pet_breed' => 'nullable|string|max:255',
            'pet_age' => 'nullable|integer',
            'pet_gender' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'certificate_verified' => 'boolean',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User updated successfully');
    }

    /**
     * Delete user
     */
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', 'User deleted successfully');
    }

    /**
     * Show all posts
     */
    public function posts(Request $request)
    {
        $search = $request->query('search');
        $query = Post::with('user');

        if ($search) {
            $query->where('message', 'like', "%{$search}%")
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('email', 'like', "%{$search}%");
                });
        }

        $posts = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.posts.index', compact('posts', 'search'));
    }

    /**
     * Show post details
     */
    public function showPost($id)
    {
        $post = Post::with('user')->findOrFail($id);
        $likes = $post->likes()->paginate(10);

        return view('admin.posts.show', compact('post', 'likes'));
    }

    /**
     * Delete post
     */
    public function destroyPost($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return redirect()->route('admin.posts')
            ->with('success', 'Post deleted successfully');
    }

    /**
     * Show all dogs
     */
    public function dogs(Request $request)
    {
        $search = $request->query('search');
        $query = User::where('is_admin', false);

        if ($search) {
            $query->where('pet_name', 'like', "%{$search}%")
                ->orWhere('pet_breed', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        $dogs = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.dogs.index', compact('dogs', 'search'));
    }

    /**
     * Show dog details
     */
    public function showDog($id)
    {
        $user = User::findOrFail($id);
        $photos = collect();

        return view('admin.dogs.show', compact('user', 'photos'));
    }

    /**
     * Show all certificates
     */
    public function certificates(Request $request)
    {
        $status = $request->query('status', 'pending');
        $query = User::where('is_admin', false);

        if ($status === 'verified') {
            $query->where('certificate_verified', true);
        } elseif ($status === 'rejected') {
            $query->whereNotNull('certificate_rejected_at');
        } else {
            // Show only pending (not verified and not rejected)
            $query->where('certificate_verified', false)->whereNotNull('certificate_path')->whereNull('certificate_rejected_at');
        }

        $certificates = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.certificates.index', compact('certificates', 'status'));
    }

    /**
     * Show certificate details
     */
    public function showCertificate($id)
    {
        $user = User::findOrFail($id);

        return view('admin.certificates.show', compact('user'));
    }

    /**
     * Verify certificate
     */
    public function verifyCertificate($id)
    {
        $user = User::findOrFail($id);
        
        $user->update([
            'certificate_verified' => true,
        ]);

        return redirect()->route('admin.certificates')
            ->with('success', 'Certificate verified successfully');
    }

    /**
     * Reject certificate
     */
    public function rejectCertificate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'certificate_verified' => false,
            'certificate_rejected_at' => now(),
        ]);

        return redirect()->route('admin.certificates')
            ->with('success', 'Certificate rejected successfully');
    }

    /**
     * Show admin settings
     */
    public function settings()
    {
        return view('admin.settings');
    }

    /**
     * Manage Admin Accounts
     */
    public function manageAdmins()
    {
        // Only super admin can manage admins
        if (!auth()->user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Only Super Admin can manage admin accounts.');
        }

        $admins = User::where('is_admin', true)->get();
        return view('admin.settings-admins', ['admins' => $admins]);
    }

    /**
     * Create New Admin Account
     */
    public function createAdmin(Request $request)
    {
        // Only super admin can create admins
        if (!auth()->user()->isSuperAdmin()) {
            return back()->with('error', 'Only Super Admin can create admin accounts.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => true,
            'admin_role' => 'admin',
            'email_verified_at' => now(),
        ]);

        return back()->with('success', 'Admin account created successfully!');
    }

    /**
     * Delete Admin Account
     */
    public function deleteAdmin($id)
    {
        // Only super admin can delete admins
        if (!auth()->user()->isSuperAdmin()) {
            return back()->with('error', 'Only Super Admin can delete admin accounts.');
        }

        $admin = User::findOrFail($id);

        // Prevent deleting the last admin
        $adminCount = User::where('is_admin', true)->count();
        if ($adminCount <= 1) {
            return back()->with('error', 'Cannot delete the last admin account!');
        }

        // Prevent deleting yourself
        if ($admin->id === auth()->user()->id) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        $admin->delete();
        return back()->with('success', 'Admin account deleted successfully!');
    }

    /**
     * Show activity logs
     */
    public function logs(Request $request)
    {
        // You can implement activity logging later
        return view('admin.logs');
    }
}