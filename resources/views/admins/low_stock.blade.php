@extends('layouts.admin')

@section('content')
<div class="container py-5">

    {{-- Back Button --}}
    <div class="mb-4">
        <a href="{{ route('admins.dashboard') }}" class="btn btn-outline-dark fw-bold">
            <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
        </a>
    </div>

    {{-- Low Stock Card --}}
    <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background-color:#f8f9fa;">
        {{-- Header --}}
        <div class="card-header py-3 text-white fw-bold" style="background-color:#db770c;">
            <h4 class="mb-0">
                ⚠️ Low Stock Products
            </h4>
        </div>

        {{-- Table --}}
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center mb-0">
                    <thead style="background-color:#6b4c3b; color:#fff;">
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $counter = 1; @endphp
                        @forelse ($lowStockProducts as $product)
                            <tr>
                                <td class="fw-semibold text-dark">{{ $counter }}</td>
                                <td class="fw-semibold text-dark">{{ $product->name }}</td>
                                <td>
                                    <span class="badge rounded-pill bg-danger px-3 py-2">
                                        {{ $product->quantity }}
                                    </span>
                                </td>
                            </tr>
                            @php $counter++; @endphp
                        @empty
                            <tr>
                                <td colspan="3" class="text-muted py-4">
                                    ✅ All products are well stocked 🎉
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
