@extends('main')
@section('content')
<section class="product-detail p-to-top">
    <form action="/cart/add" method="post">
        <div class="container">
            <div class="row-flex row-flex-product-detail">
                <p>Sản phẩm</p><i class="ri-arrow-right-line"></i>
                <p>{{ $product -> name }}</p>
            </div>
            <div class="row-grid">
                <div class="product-detail-left">
                    <img class="main-image" src="{{ asset($product -> image) }}" alt="">
                    <div class="product-images-items">
                        @php
                        $product_images = explode('*', $product -> images);
                        @endphp
                        @foreach ($product_images as $key => $product_img)
                        @if ($key == 0)
                        <img class="active" src="{{asset($product_img)}}" alt="">
                        @else
                        <img src="{{asset($product_img)}}" alt="">
                        @endif
                        @endforeach
                    </div>
                </div>
                <div class="product-detail-right">
                    <div class="product-detail-right-infor">
                        <h1>{{ $product -> name }}</h1>
                        <span>{{ $product -> material }}</span>
                        <div class="product-item-price">
                            <p>{{ number_format ($product -> price_sale) }}<sup>đ</sup> <span>{{ number_format ($product -> price_nomal) }}<sup>đ</sup></span></p>
                        </div>
                    </div>

                    <div class="product-detail-right-des">
                        <h2>Đặc điểm nổi bật</h2>
                        {!!$product -> description!!}
                    </div>
                    <div class="product-detail-right-quantity">
                        <h2>Số lượng</h2>
                        <div class="product-detail-right-quantity-input">
                            <i class="ri-subtract-line"></i>
                            <input onkeydown="return false" class="quantity-input" name="product_qty" type="number" value="1">
                            <input type="hidden" value="{{ $product -> id }}" name="product_id">
                            <i class="ri-add-line"></i>
                        </div>
                    </div>
                    <div class="product-detail-right-addcart">
                        <button type="submit" class="main-btn">Thêm vào giỏ hàng</button>
                    </div>
                </div>
            </div>
            <div class="row-flex">
                <div class="product-detail-content">
                    <h2>Chi tiết sản phẩm</h2>
                    {!!$product -> content!!}
                </div>
            </div>
        </div>
        
        @csrf
    </form>
</section>
@endsection