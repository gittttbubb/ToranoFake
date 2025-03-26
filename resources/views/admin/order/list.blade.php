@extends('admin.main')
@section('content')
<div class="admin-content-main-content-order-list">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Địa chỉ</th>
                <th>Ghi chú</th>
                <th>Chi tiết</th>
                <th>Ngày</th>
                <!-- <th>Trạng thái</th> -->
                <th>Tùy biến</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
            <tr>
                <td>{{ $order -> id }}</td>
                <td>{{ $order -> name }}</td>
                <td>{{ $order -> phone }}</td>
                <td>{{ $order -> email }}</td>
                <td>{{ $order -> address }}, {{ $order -> city }}</td>
                <td>{{ $order -> note }}</td>
                <td>
                    <a class="edit-class" href="/admin/order/detail/{{ $order -> order_detail }}">Chi tiết</a>
                </td>
                <td>{{ $order -> created_at }}</td>
                <!-- <td><a class="non_confirm-order" href="">Chưa xác nhận</a></td> -->
                <!-- <td>
                        <a class="delete-class" href="">Xóa</a>
                    </td> -->
                <td>
                    <form action="{{ route('order.delete', $order->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-class">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach


        </tbody>
    </table>
</div>
@endsection