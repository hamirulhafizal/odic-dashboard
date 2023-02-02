<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Registered Data</th>
    </tr>
    </thead>
    <tbody>

@foreach ($data as $user)
<tr>
    <td>{{ $user['name'] }}</td>
    <td>{{ $user['email'] }}</td>
    <td>{{ $user['created_at'] ?? 'N/A' }}</td>
</tr>
@endforeach
    </tbody>
</table>
