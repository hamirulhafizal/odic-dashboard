<table>
    <thead>
    <tr>
        <th>Product ID</th>
        <th>Product Name</th>
        <th>Item Description</th>
        <th>Vendor Name</th>
        <th>Price</th>
        <th>Quantity</th>
    </tr>
    </thead>
    <tbody>

@foreach ($data as $user)
<tr>
    <td>{{ $user['id'] }}</td>
    <td>{{ $user['name'] }}</td>
    <td>{{ $user['detail'] }}</td>
    @if($user['booth_id'] == 1)
    <td>Vendor 1</td>
    @elseif($user['booth_id'] == 2)
    <td>Vendor 2</td>
    @elseif($user['booth_id'] == 3)
    <td>Vendor 3</td>
    @elseif($user['booth_id'] == 4)
    <td>Vendor 4</td>
    @elseif($user['booth_id'] == 5)
    <td>Vendor 5</td>
    @elseif($user['booth_id'] == 6)
    <td>Vendor 6</td>
    @elseif($user['booth_id'] == 7)
    <td>Vendor 7</td>
    @elseif($user['booth_id'] == 8)
    <td>Vendor 8</td>
    @elseif($user['booth_id'] == 9)
    <td>Vendor 9</td>
    @elseif($user['booth_id'] == 1001)
    <td>Masterclass 1</td>
    @elseif($user['booth_id'] == 1002)
    <td>Masterclass 2</td>
    @elseif($user['booth_id'] == 1003)
    <td>Masterclass 3</td>
    @elseif($user['booth_id'] == 1004)
    <td>Masterclass 4</td>
    @elseif($user['booth_id'] == 1005)
    <td>Masterclass 5</td>
    @elseif($user['booth_id'] == 1006)
    <td>Masterclass 6</td>
    @elseif($user['booth_id'] == 1007)
    <td>Masterclass 7</td>
    @elseif($user['booth_id'] == 1008)
    <td>Masterclass 8</td>
    @elseif($user['booth_id'] == 1009)
    <td>Masterclass 9</td>
    @endif
    <td>{{ $user['price'] }}</td>
    <td>{{ $user['quantity'] }}</td>
</tr>
@endforeach
    </tbody>
</table>
