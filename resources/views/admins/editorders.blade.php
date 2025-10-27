@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-5 d-inline ">Edit Order</h5>
                <p>Current Status is <b> {{ $order->status}}</b></p>
                <form enctype="multipart/form-data" action="{{ route('update.orders',$order->id)}}" method="POST">
                    @csrf
                  <div class="form-outline mb-4 mt-4">
                    <select name="status" class="form-select form-control" aria-label="">
                        <option selected>Choose Status</option>
                        <option value="Proccessing">Proccessing</option>
                        <option value="Delivered">Delivered</option>
                        <option value="Pending">Pending</option>
                    </select>
                    </div>
                    <br>
                        <button type="submit" name="submit" class="btn btn-success mb-4 text-center">Update </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection