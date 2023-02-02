<table>
    <thead>
    <tr>
        <th>Email</th>
        <th>Date Time</th>
    </tr>
    </thead>
    <tbody>

@foreach ($data as $user)
<tr>
    <td>{{ $user['email'] }}</td>
    <td>{{ $user['updated_at'] ?? 'N/A' }}</td>
</tr>
@endforeach
    </tbody>
</table>
