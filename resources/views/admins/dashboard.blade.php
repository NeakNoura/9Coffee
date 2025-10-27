@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4 px-4">
    <div class="card shadow-sm border-0 rounded-4" style="background-color: #3e2f2f; color: #f5f5f5; min-height:60vh;">
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #db770cff; color: #fff;">
            <h4 class="mb-0">Recent Orders</h4>
            <a href="{{ route('all.orders') }}" class="btn btn-light btn-sm" style="color:#3e2f2f;">
                View All
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive mt-3" style="max-height:50vh; overflow-y:auto;">
                <table class="table table-hover align-middle text-white mb-0" style="border:1px solid #6b4c3b;">
                    <thead style="background-color: #5a3d30;" class="text-center sticky-top">
                        <tr>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Payment</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @forelse($recentOrders as $order)
                        <tr style="border-bottom:1px solid #6b4c3b;">
                            <td>{{ $order->product_name }}</td>
                            <td>${{ $order->price }}</td>
                            <td>{{ $order->payment_status }}</td>
                            <td>
                                <span class="badge bg-{{ $order->status == 'Pending' ? 'warning' : ($order->status == 'Completed' ? 'success' : 'secondary') }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">
                                No recent orders
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

{{-- Custom CSS for dashboard tables --}}
<style>
    html, body {
        height: 100%;
        background-color: #2e2424;
    }

    .table-responsive {
        scrollbar-width: thin;
        scrollbar-color: #db770c #3e2f2f;
    }

    .table-responsive::-webkit-scrollbar {
        width: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #3e2f2f;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background-color: #db770c;
        border-radius: 4px;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(219, 119, 12, 0.2);
    }

    .card-header h4, .btn {
        font-weight: 500;
    }

    .badge {
        font-size: 0.85rem;
        padding: 0.35em 0.6em;
    }

    @media (max-width: 1200px) {
        .container-fluid {
            padding: 1rem;
        }
    }
</style>
@endsection
