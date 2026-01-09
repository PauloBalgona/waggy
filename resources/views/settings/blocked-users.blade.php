@extends('navbar.nav1')
@section('title', 'Blocked Users - Waggy')

@section('content')
<div style="max-width: 900px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #252938; border-radius: 12px; padding: 24px;">
        <h2 style="color: white; margin-top: 0; margin-bottom: 24px; font-size: 24px; font-weight: 600;">
            Blocked Users
        </h2>

        @if(session('success'))
            <div style="background-color: #10b981; color: white; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background-color: #ef4444; color: white; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('error') }}
            </div>
        @endif

        <div id="blocked-users-container">
            @forelse($blockedUsers as $block)
                <div data-blocked-user-id="{{ $block->blockedUser->id }}" style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background-color: #1e2230; border-radius: 8px; margin-bottom: 12px; border: 1px solid #3d4557;">
                    <div style="display: flex; align-items: center; gap: 12px; flex: 1;">
                        <img src="{{ $block->blockedUser->avatar ? asset('storage/' . $block->blockedUser->avatar) : asset('assets/usericon.png') }}"
                             style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #3d4557;">
                        <div>
                            <p style="color: white; margin: 0; font-weight: 500;">
                                {{ $block->blockedUser->pet_name }}
                            </p>
                            <p style="color: #8b95a5; margin: 0; font-size: 0.875rem;">
                                {{ $block->blockedUser->pet_breed }}
                            </p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('user.unblock', $block->blockedUser->id) }}" style="margin: 0; padding: 0; position: relative; z-index: 10;">
                        @csrf
                        <button type="submit" 
                                class="unblock-btn"
                                data-user-id="{{ $block->blockedUser->id }}"
                                style="background-color: #3b82f6; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-size: 0.875rem; font-weight: 500; transition: background-color 0.2s; white-space: nowrap; position: relative; z-index: 10;"
                                onmouseover="this.style.backgroundColor='#2563eb'"
                                onmouseout="this.style.backgroundColor='#3b82f6'">
                            Unblock
                        </button>
                    </form>
                </div>
            @empty
                <div id="no-blocked-users" style="text-align: center; padding: 40px 20px; color: #8b95a5;">
                    <p style="margin: 0;">You haven't blocked any users yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const unblockForms = document.querySelectorAll('form[action*="unblock"]');
    console.log('Found ' + unblockForms.length + ' unblock forms');
    
    unblockForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const button = this.querySelector('button[type="submit"]');
            const userId = button.getAttribute('data-user-id');
            
            console.log('Form submitted for user:', userId);
            
            if (!confirm('Are you sure you want to unblock this user?')) {
                return;
            }
            
            // Disable button
            button.disabled = true;
            const originalText = button.textContent;
            button.textContent = 'Unblocking...';
            button.style.opacity = '0.6';
            
            // Send AJAX request
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Remove the blocked user card
                    const userCard = document.querySelector(`[data-blocked-user-id="${userId}"]`);
                    if (userCard) {
                        userCard.style.transition = 'opacity 0.3s ease-out';
                        userCard.style.opacity = '0';
                        setTimeout(() => {
                            userCard.remove();
                            
                            // Check if there are no more blocked users
                            const container = document.getElementById('blocked-users-container');
                            const remainingUsers = container.querySelectorAll('[data-blocked-user-id]');
                            if (remainingUsers.length === 0) {
                                container.innerHTML = '<div id="no-blocked-users" style="text-align: center; padding: 40px 20px; color: #8b95a5;"><p style="margin: 0;">You haven\'t blocked any users yet.</p></div>';
                            }
                        }, 300);
                    }
                    
                    showNotification(data.message || 'User unblocked successfully!', 'success');
                } else {
                    showNotification('Failed to unblock user', 'error');
                    button.disabled = false;
                    button.textContent = originalText;
                    button.style.opacity = '1';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while unblocking the user', 'error');
                button.disabled = false;
                button.textContent = originalText;
                button.style.opacity = '1';
            });
        });
    });
});

function showNotification(message, type) {
    const notificationDiv = document.createElement('div');
    notificationDiv.style.cssText = `
        background-color: ${type === 'success' ? '#10b981' : '#ef4444'};
        color: white;
        padding: 12px 16px;
        border-radius: 8px;
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        animation: slideIn 0.3s ease-out;
        font-weight: 500;
    `;
    notificationDiv.textContent = message;
    document.body.appendChild(notificationDiv);
    
    setTimeout(() => {
        notificationDiv.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => notificationDiv.remove(), 300);
    }, 3000);
}
</script>

<style>
@keyframes slideIn {
    from {
        transform: translateX(400px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(400px);
        opacity: 0;
    }
}
</style>

@endsection

