@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-5">
    </div>
                <a href="{{ route('admins.dashboard') }}" class="btn btn-light mt-3" style="color:#3e2f2f;">Back to Dashboard</a>
            </div>
    <div class="card shadow-sm border-0 rounded-4" style="background-color:#3e2f2f; color:#f5f5f5;">
        <div class="card-header" style="background-color:#db770cff; color:#fff;">
            <h4 class="mb-0">💰 Expense Management</h4>
        </div>
        <div class="card-body">
            {{-- Add Expense Form --}}
            <form action="{{ route('admin.expenses.store') }}" method="POST" class="mb-4">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" name="description" class="form-control" placeholder="Expense description" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="amount" class="form-control" placeholder="Amount ($)" required>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-success w-100">Add Expense</button>
                    </div>
                </div>
            </form>

            {{-- Expense Table --}}
            <table class="table table-bordered table-striped table-hover" style="color:#f5f5f5;">
                <thead style="background-color:#5a3d30;">
                    <tr class="text-center">
                        <th>#</th>
                        <th>Description</th>
                        <th>Amount ($)</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse ($expenses as $expense)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $expense->description }}</td>
                            <td>${{ number_format($expense->amount, 2) }}</td>
                            <td>{{ $expense->created_at }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4">No expense recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
