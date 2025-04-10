
@extends('main')

@section('content')
<section class="order-confirm p-to-top">
    <div class="container">
        @if (session('success'))
            <h1 style="color: green;">{{ session('success') }}</h1>
            <p>Mã đơn hàng: {{ session('order_id') }}</p>
            <p>Tên khách hàng: {{ session('order_name') }}</p>
        @elseif (session('error'))
            <h1 style="color: red;">{{ session('error') }}</h1>
        @endif
        <br>
        <a href="/" class="main-btn">Quay về trang chủ</a>
    </div>
</section>
@endsection