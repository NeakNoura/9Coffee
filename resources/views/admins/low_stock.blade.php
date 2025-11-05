@extends('layouts.admin')

@section('content')
<div class="container py-5">

   {{-- Back Button --}}
   <div class="mb-4">
       <a href="javascript:history.back()" class="btn btn-outline-light fw-bold">
           <i class="bi bi-arrow-left-circle"></i> Back to dashboard
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
                           <td class="fw-semibold text-white">{{ $counter }}</td>
                           <td class="fw-semibold text-white">{{ $product->name }}</td>
                           <td>
                               <span class="badge rounded-pill bg-danger px-3 py-2" id="qty-{{ $product->id }}">
                                   {{ $product->quantity }}
                               </span>
                               <button
                                   class="btn btn-sm btn-outline-success ms-2 btn-add-quantity"
                                   data-id="{{ $product->id }}"
                                   data-name="{{ $product->name }}">
                                   + Add
                               </button>
                           </td>
                       </tr>
                       @php $counter++; @endphp
                       @empty
                       <tr>
                           <td colspan="3" class="text-white py-4">
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

<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/low_stock.js') }}"></script>
@endsection
