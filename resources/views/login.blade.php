@extends('main')
@section('content')
<section class=" p-to-top">
    <form action="login_user" method="post">
        <div class="container login">
            <div class="">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                @if ($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
            </div>
            <div class="">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                @if ($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
            </div>
            <div class="">
                <button type="submit" class="btn-login">Đăng nhập</button>
            </div>
            <a href="/" class="return-home">Trang chủ</a>
            <a href="/register" class="register-link">Bạn chưa có tài khoản? Đăng ký</a>
        </div>
        @csrf
    </form>
</section>
@endsection