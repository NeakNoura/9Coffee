@extends('layouts.admin')

@section('content')
<style>
.form-control {
  border: 1px solid #ced4da !important;
  border-radius: .25rem !important;
  background-color: #fff !important;
}
</style>

<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-primary rounded-3">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Create New Admin</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('store.admins') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success w-100">Create Admin</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
