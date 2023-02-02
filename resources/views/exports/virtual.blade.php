<table>
    <thead>
    <tr>
        <th>Email</th>
        <th>Date</th>
        <th>Total Carts Items</th>
    </tr>
    </thead>
    <tbody>

@foreach ($data as $user)
<tr>
    <td>{{ $user['email'] }}</td>
    <td>{{$user['created_at'] }}</td>
    <td>{{ $user['total_item'] }}</td>
</tr>
@endforeach
    </tbody>
</table>
