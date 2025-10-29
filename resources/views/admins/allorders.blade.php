@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-5"> {{-- full width --}}
    <div class="card shadow-sm border-0 rounded-4 w-100" style="background-color: #3e2f2f; color: #f5f5f5;">
        <div class="card-header" style="background-color: #db770cff; color: #fff;">
            <h4 class="mb-0">Orders List</h4>
        </div>
        <div class="card-body">

            {{-- Flash Messages --}}
            @if(Session::has('update'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('update') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(Session::has('delete'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ Session::get('delete') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Orders Table --}}
            <div class="table-responsive w-100">
                <table class="table align-middle mb-0" style="color:#f5f5f5; min-width:100%; border:1px solid #6b4c3b;">
                    <thead style="background-color: #5a3d30;">
                        <tr class="text-center">
                            <th>#</th>
                            <th>Name</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Change Status</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allOrders as $order)
                        <tr class="text-center" style="border-bottom:1px solid #6b4c3b;">
                            <th scope="row">{{ $order->id }}</th>
                            <td>{{ $order->first_name }}</td>
                            <td>{{ $order->product_id }}</td>
                            <td>${{ number_format($order->price,2) }}</td>
                            <td>{{ $order->order_created_at ?? $order->created_at }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'Pending' => 'warning',
                                        'Delivered' => 'success',
                                        'Cancelled' => 'danger'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td>
                            <button
                                type="button"
                                class="btn btn-sm btn-info rounded-pill btn-edit-status"
                                data-id="{{ $order->id }}"
                                data-status="{{ $order->status }}">
                                Change Status
                            </button>
                                </td>
                                 <td>
                                <form action="{{ route('delete.orders', $order->id)}}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    class="btn btn-sm btn-danger rounded-pill btn-delete"
                                    data-name="{{ $order->address }}"
                                    data-price="{{ number_format($order->price, 2) }}"
                                    data-id="{{ $order->id }}">
                                    Delete
                                </button>
                            </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    {{-- Total Price --}}
                    <tfoot style="background-color:#5a3d30; color:#fff;">
                        <tr>
                            <td colspan="7" class="text-end"><strong>Total Price:</strong></td>
                            <td>${{ number_format($allOrders->sum('price'),2) }}</td>
                            <td colspan="4"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- ✅ Only ONE Delete All Button --}}
            <form action="{{ route('delete.all.orders') }}" method="POST" class="mt-3" onsubmit="return confirm('Are you sure you want to delete all orders?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash3-fill"></i> Delete All Orders
                </button>
            </form>

            <a href="{{ route('admins.dashboard') }}" class="btn btn-light mt-3" style="color:#3e2f2f;">
                <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
            </a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/all-allorder.js') }}"></script>



@endsection
