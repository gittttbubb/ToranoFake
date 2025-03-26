<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\order;
use App\Models\product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function list_order(){
        $orders = order::all();
        return view('admin.order.list', [
            'orders' => $orders,
            'title' => 'Danh sách đơn hàng'
        ]);
    }
    public function detail_order(Request $request){
        $order_detail = json_decode($request->order_detail, true);
        $product_id = array_keys($order_detail);
        $products = product::whereIn('id', $product_id)->get();
        return view('admin.order.detail',[
            'products' => $products,
            'order_detail' => $order_detail,
            'title' => 'Chi tiết đơn hàng'
        ]);
    }
    public function delete_order($id)
    {
        $order = Order::find($id);

        if ($order) {
            $order->delete();
            return redirect()->back()->with('success', 'Đơn hàng đã được xóa thành công.');
        }

        return redirect()->back()->with('error', 'Đơn hàng không tồn tại.');
    }
}
