<table>
    <thead>
    <tr>
        <th>Delivery Slot ID</th>
        <th>Product Name</th>
        <th>Delivery Date</th>
        <th>Delivery Slot</th>
        <th>Delivery Fee</th>
        <th>Delivery Availability</th>
    </tr>
    </thead>
    <tbody>

@foreach ($data as $schedule)
<tr>
    <td>{{ $schedule['id'] }}</td>
    <td>{{ $schedule['product_name']}}</td>
    <td>{{ $schedule['delivery_date'] }}</td>
    <td>{{ $schedule['slot'] }}</td>
    <td>{{ $schedule['delivery_fee'] }}</td>
    <td>{{ $schedule['available_delivery'] }}</td>
</tr>
@endforeach
    </tbody>
</table>

