<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\order;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EmailNotification;


class FrontendController extends Controller
{
    public function index()
    {
        $products = product::all();
        return view('home', [
            'products' => $products
        ]);
    }
    public function show_product(Request $request)
    {
        $product = product::find($request->id);
        return view('product', [
            'title' => 'Chi tiết sản phẩm',
            'product' => $product
        ]);
    }
    //cart
    public function add_cart(Request $request)
    {
        $product_id = $request->product_id;
        $product_qty = $request->product_qty;
        if (is_null(Session::get('cart'))) {
            Session::put('cart', [
                $product_id => $product_qty,
            ]);
            return redirect('/cart');
        } else {
            $cart = Session::get('cart');
            if (Arr::exists($cart, $product_id)) { //check  product_id có trong mảng cart
                $cart[$product_id] += $product_qty;
                Session::put('cart', $cart);
                return redirect('/cart');
            } else {
                $cart[$product_id] = $product_qty;
                Session::put('cart', $cart);
                return redirect('/cart');
            }
        }
    }
    public function show_cart()
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return view('cart', [
                'title' => 'Giỏ hàng',
                'products' => collect([]), // Trả về một collection rỗng nếu giỏ hàng trống
            ]);
        }

        $product_id = array_keys($cart);
        $products = Product::whereIn('id', $product_id)->get();

        return view('cart', [
            'title' => 'Giỏ hàng',
            'products' => $products,
        ]);
    }
    public function delete_cart(Request $request)
    {
        $product_id = $request->id;
        $cart = Session::get('cart');
        unset($cart[$product_id]);
        Session::put('cart', $cart);
        return redirect('/cart');
    }
    public function update_cart(Request $request)
    {
        $cart = $request->product_id;
        Session::put('cart', $cart);
        return redirect('/cart');
    }
    
    public function send_cart(Request $request)
    {
        $token = Str::random(12);
        $order = new order();
        $order->name = $request->input('name');
        $order->phone = $request->input('phone');
        $order->email = $request->input('email');
        $order->city = $request->input('city');
        $order->district = $request->input('district');
        $order->ward = $request->input('ward');
        $order->address = $request->input('address');
        $order->note = $request->input('note');
        $order_detail = json_encode($request->input('product_id')); // Lấy key và value trong mảng từ input
        $order->order_detail = $order_detail;
        $order->token = $token;
        $order->save();

        // Lưu thông tin giỏ hàng vào session để sử dụng cho VNPay
        Session::put('order_id', $order->id);
        Session::put('order_email', $order->email);
        Session::put('order_name', $order->name);

        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl =  "http://127.0.0.1:8000/vnpay/return";
        $vnp_TmnCode = "262XSFHX"; //Mã website tại VNPAY 
        $vnp_HashSecret = "MMZXWISZNUUUNKGOZQPCPASLLTHYGMTB"; //Chuỗi bí mật
        $vnp_TxnRef = time(); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = "Thanh toán đơn hàng #" . $order->id;
        $vnp_OrderType = '250000';
        $vnp_Amount = $request->input('total_amount') * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = '';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

        ksort($inputData);
        $query = "";
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            $hashdata .= urlencode($key) . "=" . urlencode($value) . '&';
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        $hashdata = rtrim($hashdata, '&');
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url = $vnp_Url . "?" . $query . "vnp_SecureHash=" . $vnpSecureHash;

        // Chuyển hướng đến VNPay
        return redirect($vnp_Url);
    }

    public function vnpay_return(Request $request)
    {
        $vnp_HashSecret = "MMZXWISZNUUUNKGOZQPCPASLLTHYGMTB";
        $inputData = $request->all();
        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $hashData = "";
        foreach ($inputData as $key => $value) {
            $hashData .= urlencode($key) . '=' . urlencode($value) . '&';
        }
        $hashData = rtrim($hashData, '&');
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
    
        if ($secureHash === $vnp_SecureHash && $request->vnp_ResponseCode == '00') {
            // Lấy thông tin đơn hàng từ session
            $order_id = Session::get('order_id');
            $order_email = Session::get('order_email');
            $order_name = Session::get('order_name');
    
            // Gửi email xác nhận
            Mail::to($order_email)->send(new TestMail($order_name));
    
            // Gửi thông báo cho admin  
            $adminEmail = 'nguyenthangg203@gmail.com';
            $order = order::find($order_id);
            Notification::route('mail', $adminEmail)->notify(new EmailNotification($order));
    
            // Xóa giỏ hàng
            Session::forget('cart');
            Session::forget('order_id');
            Session::forget('order_email');
            Session::forget('order_name');
    
            return redirect()->route('views.order.success')->with([
                'success' => 'Thanh toán thành công!',
                'order_id' => $order_id,
                'order_name' => $order_name,
            ]);
        }
    
        return redirect()->route('views.order.success')->with('error', 'Thanh toán không thành công!');
    }
    public function search(Request $request)
    {
        $query = $request->input('search');
        $results = product::where('name', 'LIKE', "%{$query}%")->get(); // Tìm kiếm sản phẩm theo tên

        return view('search', compact('results'));
    }
}
