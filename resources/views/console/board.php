<table style="box-double">
    <thead>
        <tr>
            @foreach($statuses as $status)
                <th align="center" class="text-green w-auto">
                    {{ $status }}
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
@foreach($rows as $row)
<tr border="1">
    @foreach($row as $task)

    <td class="mb-1">
        
    @if(empty($task))
        @continue
    @endif

ID: {{$task->id}}   

Title: 

{{ $task->title ?: 'None' }} 

Description: 

{{ $task->description }} 


    </td>

    @endforeach
</tr>
@endforeach


    </tbody>
    <tfoot>
        <tr>
            <td align="center" colspan="3" class="text-green">
                Board: {{ $boardName }}
            </td>
        </tr>
    </tfoot>
</table>
