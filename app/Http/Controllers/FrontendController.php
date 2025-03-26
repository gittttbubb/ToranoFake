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
        // $cart = Session::get('cart');
        // $product_id = array_keys($cart);
        // $products = product::whereIn('id',$product_id)->get();
        // return view('cart',[
        //     'title' => 'Giỏ hàng',
        //     'products' => $products,
        // ]);
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
        $order =  new order();
        $order->name = $request->input('name');
        $order->phone = $request->input('phone');
        $order->email = $request->input('email');
        $order->city = $request->input('city');
        $order->district = $request->input('district');
        $order->ward = $request->input('ward');
        $order->address = $request->input('address');
        $order->note = $request->input('note');
        $order_detail = json_encode($request->input('product_id')); //lấy key và value trong mảng từ input
        $order->order_detail = $order_detail;
        $order->token = $token;
        $order->save();
        Session::forget('cart'); //có thể thay flush bằng forget
        $mailifor = $order->email;
        $nameifor = $order->name;
        $Mail = Mail::to($mailifor)->send(new TestMail($nameifor));
        // Notification::send($order, new EmailNotification($order));
        $adminEmail = 'nguyenthangg203@gmail.com'; // Địa chỉ email của quản trị viên
        Notification::route('mail', $adminEmail)->notify(new EmailNotification($order));
        return redirect('/order/confirm')->with('order_name', $order->name);
    }
    public function search(Request $request)
    {
        $query = $request->input('search');
        $results = product::where('name', 'LIKE', "%{$query}%")->get(); // Tìm kiếm sản phẩm theo tên

        return view('search', compact('results'));
    }
}
