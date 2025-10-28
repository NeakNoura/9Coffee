@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-5">
    <div class="card shadow-sm border-0 rounded-4 w-100" style="background-color: #3e2f2f; color: #f5f5f5;">
        <div class="card-header" style="background-color: #db770c; color: #fff;">
            <h4 class="mb-0">🧾 Raw Material Stock</h4>
        </div>
        <div class="card-body">

            {{-- Flash Messages --}}
            @if(Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Stock Table --}}
            <div class="table-responsive">
                <table class="table align-middle mb-0" style="color:#f5f5f5; min-width:100%; border:1px solid #6b4c3b;">
                    <thead style="background-color: #5a3d30;" class="text-center">
                        <tr>
                            <th>#</th>
                            <th>Raw Material</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Status</th>
                            <th>Update</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                      @foreach($rawMaterials as $material)
<tr>
    <td>{{ $material->id }}</td>
    <td>{{ $material->name }}</td>
    <td>{{ $material->quantity }}</td>
    <td>{{ $material->unit }}</td>
    <td>
        <span class="badge {{ $material->quantity < 5 ? 'bg-danger' : 'bg-success' }}">
            {{ $material->quantity < 5 ? 'Low' : 'OK' }}
        </span>
    </td>
    <td>
        <form action="{{ route('admin.raw-material.update', $material->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="number" name="quantity" value="{{ $material->quantity }}" min="0" class="form-control" style="width:80px; display:inline-block;">
            <button type="submit" class="btn btn-sm btn-primary">Update</button>
        </form>
    </td>
</tr>
@endforeach


                    </tbody>
                </table>
            </div>

            <a href="{{ route('admins.dashboard') }}" class="btn btn-light mt-3" style="color:#3e2f2f;">
                <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
            </a>

        </div>
    </div>
</div>
@endsection
