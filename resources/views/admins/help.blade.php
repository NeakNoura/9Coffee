@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm border-0 rounded-4 help-main" style="background-color: #3e2f2f; color: #f5f5f5;">
        <div class="card-header" style="background-color: #db770cff; color: #fff;">
            <a href="{{ route('admins.dashboard') }}" class="btn btn-light mt-3" style="color:#3e2f2f;">
                <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
            </a>
            <h4 class="mb-0 text-center">☕ Help Center</h4>
        </div>
        <div class="card-body">

            <p class="intro-text text-center" style="color:#f5f5f5; font-size:1rem; margin-bottom:25px;">
                Welcome to the Help Center! Explore the sections below to manage your Coffee Shop Admin Dashboard efficiently.
            </p>

            <div class="row g-3 help-cards">
                <div class="col-md-4">
                    <div class="card text-white" style="background-color:#5a3d30; box-shadow:0 5px 20px rgba(0,0,0,0.2);">
                        <div class="card-body">
                            <div class="help-icon" style="font-size:2rem;">📊</div>
                            <h5>Dashboard</h5>
                            <ul>
                                <li>👁️ Daily Views: Track website visitors.</li>
                                <li>💰 Sales: Monitor completed transactions.</li>
                                <li>💬 Comments: Customer feedback messages.</li>
                                <li>🤑 Earnings: Total revenue from all orders.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card text-white" style="background-color:#5a3d30; box-shadow:0 5px 20px rgba(0,0,0,0.2);">
                        <div class="card-body">
                            <div class="help-icon" style="font-size:2rem;">👥</div>
                            <h5>Customers</h5>
                            <ul>
                                <li>View details: name, country, order history.</li>
                                <li>Search and filter by name/location.</li>
                                <li>Update or delete customer info.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card text-white" style="background-color:#5a3d30; box-shadow:0 5px 20px rgba(0,0,0,0.2);">
                        <div class="card-body">
                            <div class="help-icon" style="font-size:2rem;">🛒</div>
                            <h5>Orders</h5>
                            <ul>
                                <li>View order info, payment & delivery status.</li>
                                <li>Update status: Pending, Delivered, In Progress, Return.</li>
                                <li>Mark payments as "Paid" or "Due".</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card text-white" style="background-color:#5a3d30; box-shadow:0 5px 20px rgba(0,0,0,0.2);">
                        <div class="card-body">
                            <div class="help-icon" style="font-size:2rem;">✉️</div>
                            <h5>Messages</h5>
                            <ul>
                                <li>Respond promptly to improve satisfaction.</li>
                                <li>Resolve order issues or feedback efficiently.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card text-white" style="background-color:#5a3d30; box-shadow:0 5px 20px rgba(0,0,0,0.2);">
                        <div class="card-body">
                            <div class="help-icon" style="font-size:2rem;">⚙️</div>
                            <h5>Settings</h5>
                            <ul>
                                <li>Update name, profile picture, contact info.</li>
                                <li>Change preferences like language & theme.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card text-white" style="background-color:#5a3d30; box-shadow:0 5px 20px rgba(0,0,0,0.2);">
                        <div class="card-body">
                            <div class="help-icon" style="font-size:2rem;">🔒</div>
                            <h5>Password</h5>
                            <ul>
                                <li>Use strong passwords with symbols & numbers.</li>
                                <li>Change immediately if you suspect compromise.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card text-white" style="background-color:#5a3d30; box-shadow:0 5px 20px rgba(0,0,0,0.2);">
                        <div class="card-body">
                            <div class="help-icon" style="font-size:2rem;">🚪</div>
                            <h5>Sign Out</h5>
                            <ul>
                                <li>Prevent unauthorized access.</li>
                                <li>End session properly.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <p class="support-text text-center mt-4" style="color:#f5f5f5;">
                Need more help? Contact <a href="mailto:support@coffeeshop.com" style="color:#fff; text-decoration:underline;">support@coffeeshop.com</a>
            </p>
        </div>
    </div>
</div>
@endsection
