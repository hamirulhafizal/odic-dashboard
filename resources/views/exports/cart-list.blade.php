<table>
    <thead>
    <tr>
        <td>Email :</td>
        <td>{{ $email }}</td>
    </tr>
    <tr>
        <th>Product ID</th>
        <th>Email</th>
        <th>Product Name</th>
        <th>Vendor Name</th>
        <th>Unit Price</th>
        <th>Quantity</th>
        <th>Price</th>
    </tr>
    </thead>
    <tbody>

@foreach ($data as $user)
<tr>
    <td>{{ $user['product_id'] }}</td>
    <td>{{ $user['email'] }}</td>
    <td>{{ $user['name'] }}</td>
    @if ($user['booth_id'] == '1'|| $user['booth_id'] == '5'||  $user['booth_id'] == '6' ||  $user['booth_id'] == '7'|| $user['booth_id'] == '8' ||  $user['booth_id'] == '9')
        <td>Food Culture</td>
    @elseif ($user['booth_id'] == '2')
        <td>Phytor</td>
    @elseif ($user['booth_id'] == '3')
        <td>Ollie</td>
    @elseif ($user['booth_id'] == '4')
        <td>Chateau</td>
    @elseif ($user['booth_id'] == '1001')
        <td>Masterclass 1</td>
    @elseif ($user['booth_id'] == '1002')
        <td>Masterclass 2</td>
    @elseif ($user['booth_id'] == '1003')
        <td>Masterclass 3</td>
    @elseif ($user['booth_id'] == '1004')
        <td>Masterclass 4</td>
    @elseif ($user['booth_id'] == '1005')
        <td>Masterclass 5</td>
    @elseif ($user['booth_id'] == '1006')
        <td>Masterclass 6</td>
    @elseif ($user['booth_id'] == '1007')
        <td>Masterclass 7</td>
    @elseif ($user['booth_id'] == '1008')
        <td>Masterclass 8</td>
    @elseif ($user['booth_id'] == '1009')
        <td>Masterclass 9</td>
    @endif
    <td>{{ $user['price'] }}</td>
    <td>{{ $user['quantity'] }}</td>
    <td>{{ $user['price_qty'] }}</td>
</tr>
@endforeach
    </tbody>
</table>
