<table>
    <thead>
    <tr>
        <th>Slot ID</th>
        <th>Username</th>
        <th>status</th>
        <th>2%</th>
    </tr>
    </thead>
    <tbody>

@foreach ($data as $investment)
<tr>
    <td>{{ $investment->id }}</td>
    <td>{{ $investment->username }}</td>
    <td>{{ $investment->status }}</td>
    <td>{{ $investment->total_direct_sales }}</td>
</tr>
@endforeach
    </tbody>
</table>
