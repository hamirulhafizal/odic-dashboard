<table>
    <thead>
    <tr>
        <th>Order ID</th>
        <th>Ref No.</th>
        <th>Date Time</th>
        <th>Email</th>
        <th>Delivery Address</th>
        <th>Postal Code</th>
        <th>Amount</th>
        <th>Payment Method</th>
        <th>Contact Number</th>
        <th>Payment Status</th>
        <th>Remark</th>
    </tr>
    </thead>
    <tbody>

@foreach ($data as $user)
<tr>
    <td>{{ $user['id'] }}</td>
    <td>{{ $user['session_id'] }}</td>
    <td>{{ $user['created_at']}}</td>
    <td>{{ $user['email'] }}</td>
    <td>{{ $user['address_1'] }}, {{ $user['level'] }}-{{ $user['unit'] }}, {{ $user['country'] }}</td>
    <td>{{ $user['postcode'] }}</td>
    <td>{{ $user['total'] }}</td>

    @if($user['payment_method_id'] == 1)
        <td>Stripe</td>
    @elseif ($user['payment_method_id'] == 2)
        <td>eNET</td>
    @else
        <td>-</td>
    @endif

    <td>{{ $user['phone'] }}</td>

    @if($user['status_id'] == 1)
        <td>Open</td>
    @elseif ($user['status_id'] == 2)
        <td>Successful</td>
    @elseif ($user['status_id'] == 3)
        <td>Declined</td>
    @elseif ($user['status_id'] == 4)
        <td>Cancelled</td>
    @elseif ($user['status_id'] == 5)
        <td>Expired</td>
    @else
        <td>-</td>
    @endif

    <td>{{ $user['remark'] }}</td>
</tr>
@endforeach
    </tbody>
</table>

