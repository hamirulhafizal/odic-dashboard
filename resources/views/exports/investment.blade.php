<table>
    <thead>
    <tr>
        <th>Investment ID</th>
        <th>Username</th>
        <th>Amount</th>
        <th>Roi</th>
        <th>Slot</th>
        <th>Roi Amount</th>
        <th>Receipt</th>
        <th>Status</th>
        <th>Registered Data</th>
    </tr>
    </thead>
    <tbody>

@foreach ($data as $investment)
<tr>
    <td>{{ $investment['id'] }}</td>
    <td>{{ $investment['username'] }}</td>
    <td>{{ $investment['amount'] }}</td>
    <td>{{ $investment['roi'] }}</td>
    <td>{{ $investment['slot'] }}</td>
    <td>{{ $investment['roi_amount'] }}</td>
    <td>{{ URL::asset('investment/'.$investment['receipt']) }}</td>
    <td>{{ $investment['status'] }}</td>
    <td>{{ $investment['created_at'] ?? 'N/A' }}</td>
</tr>
@endforeach
    </tbody>
</table>
