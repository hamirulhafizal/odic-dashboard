<table>
    <thead>
    <tr>
        <th>Email</th>
        <th>Date Time</th>
        <th>Date Time (updated)</th>
        <th>Name</th>
        <th>MC 1</th>
        <th>MC 2</th>
        <th>MC 3</th>
        <th>MC 4</th>
        <th>MC 5</th>
        <th>MC 6</th>
        <th>MC 7</th>
        <th>MC 8</th>
        <th>MC 9</th>
    </tr>
    </thead>
    <tbody>

@foreach ($data as $masterclass)
<tr>
    <td>{{ $masterclass->email }}</td>
    <td>{{ $masterclass->created_at ?? 'N/A' }}</td>
    <td>{{ $masterclass->updated_at ?? 'N/A' }}</td>
    <td>{{ $masterclass->name }}
    @if($masterclass->masterclass_1 == true) <td style="background:#99FF99">Yes</td> @else <td style="background:#ff6666">No</td> @endif
    @if($masterclass->masterclass_2 == true) <td style="background:#99FF99">Yes</td> @else <td style="background:#ff6666">No</td> @endif
    @if($masterclass->masterclass_3 == true) <td style="background:#99FF99">Yes</td> @else <td style="background:#ff6666">No</td> @endif
    @if($masterclass->masterclass_4 == true) <td style="background:#99FF99">Yes</td> @else <td style="background:#ff6666">No</td> @endif
    @if($masterclass->masterclass_5 == true) <td style="background:#99FF99">Yes</td> @else <td style="background:#ff6666">No</td> @endif
    @if($masterclass->masterclass_6 == true) <td style="background:#99FF99">Yes</td> @else <td style="background:#ff6666">No</td> @endif
    @if($masterclass->masterclass_7 == true) <td style="background:#99FF99">Yes</td> @else <td style="background:#ff6666">No</td> @endif
    @if($masterclass->masterclass_8 == true) <td style="background:#99FF99">Yes</td> @else <td style="background:#ff6666">No</td> @endif
    @if($masterclass->masterclass_9 == true) <td style="background:#99FF99">Yes</td> @else <td style="background:#ff6666">No</td> @endif
</tr> 
@endforeach
    </tbody>
</table>
