@extends('main')
@section('content')
<section class="hot-products p-to-top">
    <div class="container">
        <div class="row-grid">
            <h3>Kết quả tìm kiếm:</h3>
        </div>
        @if(isset($results))
            @if(count($results) > 0)
                <div class="row-grid row-grid-hot-products">
                    @foreach($results as $result)
                    <div class="hot-product-item">
                        <a href="/product/{{ $result -> id }}"><img src="{{ asset($result -> image) }}" alt=""></a>
                        <p><a href="/product/{{ $result -> id }}">{{ $result -> name }}</a></p>
                        <span>{{ $result -> material }}</span>
                        <div class="product-item-price">
                            <p>{{ number_format($result -> price_sale) }} <sup>đ</sup> <span>{{ number_format($result -> price_nomal) }}<sup>đ</sup></span></p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <p>Không tìm thấy kết quả nào.</p>
            @endif
        @endif
    </div>
</section>
@endsection