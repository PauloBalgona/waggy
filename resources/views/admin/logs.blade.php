@extends('admin.layout')
@section('admin-title', 'Activity Logs')

@section('admin-content')
    <div style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
        <div style="padding: 12px; background-color: #dbeafe; border: 1px solid #7dd3fc; border-radius: 4px; color: #0369a1;">
            <strong>Info:</strong> Activity logging can be integrated here. This feature will track all admin actions and important events in the system.
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <h5 style="color: #1e3a8a; margin-bottom: 20px; font-weight: 600;">Planned Log Features</h5>
                <ul style="color: #666; line-height: 2; margin: 0; padding-left: 20px;">
                    <li>User login/logout activities</li>
                    <li>Post creation and deletion</li>
                    <li>Admin actions and changes</li>
                    <li>Certificate verifications</li>
                    <li>System events</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h5 style="color: #1e3a8a; margin-bottom: 20px; font-weight: 600;">Filter Options</h5>
                <form>
                    <select class="form-select mb-3" style="border: 1px solid #d1d5db; border-radius: 4px; padding: 8px 12px; color: #333; background: #fff;">
                        <option>All Events</option>
                        <option>User Activities</option>
                        <option>Admin Actions</option>
                        <option>System Events</option>
                    </select>
                    <input type="date" class="form-control" style="border: 1px solid #d1d5db; border-radius: 4px; padding: 8px 12px; color: #333; background: #fff;">
                </form>
            </div>
        </div>

        <hr style="border-color: #e5e7eb; margin: 30px 0;">

        <div style="background-color: #f9fafb; padding: 20px; border-radius: 8px; border: 1px solid #e5e7eb; text-align: center;">
            <p style="color: #999; margin: 0;">Activity logs will appear here once logging is implemented.</p>
        </div>
    </div>
@endsection
