<table>
    <thead>
    <tr>
        <th>Delivery Date</th>
        <th>Product ID</th>
        <th>Product Name</th>
        <th>Quantity</th>
        <th>Customer Name</th>
        <th>Contact Number</th>
        <th>Delivery Address</th>
        <th>Customer Email</th>
        <th>Remarks</th>
        <th>Order ID</th>
        <th>Order Date</th>
        <th>Vendor Name</th>
    </tr>
    </thead>
    <tbody>

@foreach ($data as $order)

<tr>
    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$order['delivery_date'])->format('Y-m-d')}}</td>
    <td>{{ $order['product_id']}}</td>
    <td>{{ $order['name']}}</td>
    <td>{{ $order['quantity']}}</td>
    <td>{{ $order['customer']}}</td>
    <td>{{ $order['phone']}}</td>
    <td>{{ $order['address_1']}}, {{ $order['level']}} - {{ $order['unit']}}, {{ $order['country']}} Postal Code -{{ $order['postcode']}}</td>
    <td>{{ $order['email']}}</td>
    <td>{{ $order['remark']}}</td>
    <td>{{ $order['order_id']}}</td>
    <td>{{ $order['created_at'] }}</td>
    @if ($order['booth_id'] == '1' ||  $order['booth_id'] == '5'||  $order['booth_id'] == '6' ||  $order['booth_id'] == '7'|| $order['booth_id'] == '8' ||  $order['booth_id'] == '9')
        <td>Food Culture</td>
    @elseif ($order['booth_id'] == '2')
        <td>Phytor</td>
    @elseif ($order['booth_id'] == '3')
        <td>Ollie</td>
    @elseif ($order['booth_id'] == '4')
        <td>Chateau</td>
    @elseif ($order['booth_id'] == '1001')
        <td>Masterclass 1</td>
    @elseif ($order['booth_id'] == '1002')
        <td>Masterclass 2</td>
    @elseif ($order['booth_id'] == '1003')
        <td>Masterclass 3</td>
    @elseif ($order['booth_id'] == '1004')
        <td>Masterclass 4</td>
    @elseif ($order['booth_id'] == '1005')
        <td>Masterclass 5</td>
    @elseif ($order['booth_id'] == '1006')
        <td>Masterclass 6</td>
    @elseif ($order['booth_id'] == '1007')
        <td>Masterclass 7</td>
    @elseif ($order['booth_id'] == '1008')
        <td>Masterclass 8</td>
    @elseif ($order['booth_id'] == '1009')
        <td>Masterclass 9</td>
    @endif


@endforeach
    </tbody>
</table>

