@extends('layouts.admin')

@section('content')
<div class="container text-center mt-5">
    <h4>Scan this QR to Pay</h4>
    <div id="qrcode"></div>
    <p>Order Ref: {{ $orderRef }}</p>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    new QRCode(document.getElementById("qrcode"), {
        text: "{{ $qrData }}",
        width: 200,
        height: 200
    });
</script>
@endsection
