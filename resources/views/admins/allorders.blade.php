@extends('layouts.admin')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid mt-5">
    <div class="card shadow-sm border-0 rounded-4 w-100" style="background-color: #3e2f2f; color: #f5f5f5;">

        {{-- Card Header --}}
        <div class="card-header position-relative" style="background-color: #db770cff; color: #fff;">
            <a href="{{ route('admins.dashboard') }}" class="btn btn-light btn-sm position-absolute start-0" style="color:#3e2f2f; left:1rem; top:50%; transform:translateY(-50%);">
                <i class="bi bi-arrow-left-circle"></i> Dashboard
            </a>
            <h4 class="mb-0 text-center">Orders List</h4>
        </div>

        {{-- Card Body --}}
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
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="min-width:100%; border:1px solid #6b4c3b; color:#f5f5f5;">
                    <thead style="background-color: #5a3d30;" class="text-center">
                        <tr>
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
                        @php $counter = 1; @endphp
                        @foreach ($allOrders as $order)
                        <tr class="text-center">
                            <th scope="row">{{ $counter }}</th>
                            <td>{{ $order->first_name }}</td>
                            <td>{{ $order->product->name ?? 'N/A' }}</td>
                            <td>${{ number_format($order->price,2) }}</td>
                            <td>{{ $order->order_created_at ?? $order->created_at }}</td>
                            <td>
                                @php
                                    $statusColors = ['Pending'=>'warning','Paid'=>'success','Cancelled'=>'danger'];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-info rounded-pill btn-edit-status"
                                        data-id="{{ $order->id }}"
                                        data-status="{{ $order->status }}">
                                    Change Status
                                </button>
                            </td>
                            <td>
                                <form action="{{ route('delete.orders', $order->id)}}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger rounded-pill btn-delete"
                                        data-name="{{ $order->address ?? '' }}"
                                        data-price="{{ number_format($order->price, 2) }}"
                                        data-id="{{ $order->id }}">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @php $counter++; @endphp
                        @endforeach
                    </tbody>
                    <tfoot style="background-color:#5a3d30; color:#fff;">
                        <tr>
                            <td colspan="7" class="text-end"><strong>Total Price:</strong></td>
                            <td>${{ number_format($allOrders->sum('price'),2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Actions: Delete + Download --}}
            <div class="d-flex justify-content-start gap-2 mt-3 flex-wrap">
                <form action="{{ route('delete.all.orders') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete all orders?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash3-fill"></i> Delete All Orders
                    </button>
                </form>
                <a href="{{ route('orders.export') }}" class="btn btn-success">
                    <i class="bi bi-download"></i> Download Excel
                </a>
            </div>

        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/all-allorder.js') }}"></script>



@endsection
