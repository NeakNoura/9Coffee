@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-5">
    </div>
<a href="{{ route('admins.dashboard') }}" class="btn btn-light mt-3" style="color:#3e2f2f;">Back to Dashboard</a>
            </div>
    <div class="card shadow-sm border-0 rounded-4" style="background-color:#3e2f2f; color:#f5f5f5;">
        <div class="card-header" style="background-color:#db770cff; color:#fff;">
            <h4 class="mb-0">⚠️ Low Stock Products</h4>
        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered" style="color:#f5f5f5;">
                <thead style="background-color:#5a3d30;">
                    <tr class="text-center">
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse ($lowStockProducts as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>
                                <span class="badge bg-danger">{{ $product->quantity }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3">All products are well stocked 🎉</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
