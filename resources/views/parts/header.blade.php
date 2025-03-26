<header id="header">
    <div class="container">
        <div class="row-flex">
            <div class="header-bar-icon">
                <i class="ri-menu-line"></i>
            </div>
            <div class="header-logo">
                <a href="/"><img src="{{ asset('frontend/asset/images/logo.png') }}" alt="Torano"></a>
            </div>
            <div class="header-logo-mobile">
                <img src="{{ asset('frontend/asset/images/logo.png') }}" alt="">
            </div>
            <div class="header-nav">
                <nav>
                    <ul>
                        <li><a href="">SẢN PHẨM</a></li>
                        <li><a href="">MẶC HÀNG NGÀY</a></li>
                        <li><a href="">ĐỒ THỂ THAO</a></li>
                        <li><a href="">SẢN XUẤT RIÊNG</a></li>
                    </ul>
                </nav>
            </div>
            <div class="header-search">
                <form action="{{ route('search') }}" method="get">
                    <input type="text" name="search" placeholder="Tìm kiếm" value="{{ request()->input('search') }}">
                    <button type="submit"><i class="ri-search-line"></i></button>
                </form>
            </div>
            <div>
                @if(session()->has('user'))
                    <span>Xin chào, {{ session('user')['name'] }}</span>
                    <form action="{{ route('logout') }}" method="post" style="display:inline;">
                        @csrf
                        <button type="submit" style="background:none;border:none;color:#007bff;cursor:pointer;">Logout</button>
                    </form>
                @else
                    <a href="/login">Login</a>
                    |
                    <a href="/register">Register</a>
                @endif
            </div>
            <div class="header-cart">
                <a href="/cart"><i class="ri-shopping-cart-line" number="0"></i></a>

            </div>
        </div>
    </div>
</header>