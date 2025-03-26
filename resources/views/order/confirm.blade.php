@extends('main')
@section('content')
<section class="order-confirm p-to-top">
    <div class="container">
        <div class="row-flex row-flex-product-detail">
            <p>Xác nhận đơn hàng: <span style="font-weight: bold;">{{ session('order_name') }}</span></p>
        </div>
        <div class="row-flex">
            <div class="order-confirm-content">
                <p>Đơn hàng của bạn đã được gửi <span style="font-weight: bold;">Thành công</span>!<br>
                    <span style="font-size: small;">Vui lòng check <span style="font-style: italic;"> <a href="https://mail.google.com/mail/u/0/#inbox">Email</a></span> đã đăng ký để xác nhận đơn hàng.</span>
                </p>
                <br>
                <!-- <button class="main-btn">Tiếp tục mua hàng</button> -->
                <a href="/" class="main-btn">Tiếp tục mua hàng</a>
            </div>
        </div>
    </div>
</section>
@endsection