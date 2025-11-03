@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-center align-items-center py-5">
    <div class="card shadow-lg rounded-4 w-75" style="background-color:#4a3730; color:#f5f5f5; border:1px solid #6b4c3b;">

        {{-- Header --}}
        <div class="card-header text-center" style="background-color:#db770cff; border-bottom:1px solid #8b5a44;">
            <h4 class="fw-bold mb-0">💰 Expense Management</h4>
        </div>

        {{-- Body --}}
        <div class="card-body">
            {{-- Flash Message --}}
            @if(Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Add Expense Form --}}
            <form action="{{ route('admin.expenses.store') }}" method="POST" class="mb-4">
                @csrf
                <div class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <input type="text" name="description" class="form-control" placeholder="Expense description"
                            style="border:1px solid #6b4c3b; background-color:#fff; color:#3e2f2f;" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" step="0.01" name="amount" class="form-control" placeholder="Amount ($)"
                            style="border:1px solid #6b4c3b; background-color:#fff; color:#3e2f2f;" required>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-warning w-100 fw-bold text-dark">Add Expense</button>
                    </div>
                </div>
            </form>

            {{-- Expense Table --}}
            <div class="table-responsive">
                <table class="table align-middle table-hover text-center" style="color:#f5f5f5; border:1px solid #6b4c3b;">
                    <thead style="background-color:#6b4c3b;">
                        <tr>
                            <th>#</th>
                            <th>Description</th>
                            <th>Amount ($)</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($expenses as $expense)
                            <tr style="border-bottom:1px solid #5a3d30;">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $expense->description }}</td>
                                <td>${{ number_format($expense->amount, 2) }}</td>
                                <td>{{ \Carbon\Carbon::parse($expense->created_at)->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center">No expense recorded yet 💼</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer Buttons --}}
            <div class="d-flex justify-content-between mt-4">
                 <div class="mb-4">
                <a href="javascript:history.back()" class="btn btn-outline-light fw-bold">
                    <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
                </a>
            </div>
                <button class="btn btn-success fw-bold">
                    <i class="bi bi-file-earmark-excel"></i> Download Excel
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
