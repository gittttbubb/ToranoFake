@extends('main')
@section('content')
<section class=" p-to-top">
    <form action="create_user" method="post">
        <div class="container register">
            <div>
                <label for="name">Usename:</label>
                <input type="text" id="name" name="name" required>
                @if ($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                @if ($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                @if ($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
            </div>
            <div>
                <label for="password_confirmation">Confirm Password:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
                @if ($errors->has('password_confirmation'))
                    <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                @endif
            </div>
            <div >
                <button type="submit" class="btn-register">Đăng ký</button>
            </div>
            <a href="/" class="return-home">Trang chủ</a>
            <a href="/login" class="login-link">Bạn đã có tài khoản? Đăng nhập</a>
        </div>
        @csrf
    </form>
</section>
@endsection