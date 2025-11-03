@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-5">
      <div class="mb-4">
                <a href="javascript:history.back()" class="btn btn-outline-light fw-bold">
                    <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
                </a>
            </div>
    <div class="card shadow-sm border-0 rounded-4" style="background-color:#3e2f2f; color:#f5f5f5;">
        <div class="card-header" style="background-color:#db770cff; color:#fff;">
            <a href="{{ route('admin.sales.report.download') }}" class="btn btn-success mb-3">
    📥 Download Report
</a>

            <h4 class="mb-0">📈 Sales Report (Last 30 Days)</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover" style="color:#f5f5f5;">
                <thead style="background-color:#5a3d30;">
                    <tr class="text-center">
                        <th>Date</th>
                        <th>Total Orders</th>
                        <th>Total Sales ($)</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse ($sales as $row)
                        <tr>
                            <td>{{ $row->date }}</td>
                            <td>{{ $row->total_orders }}</td>
                            <td>${{ number_format($row->total_sales, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3">No sales data available.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
