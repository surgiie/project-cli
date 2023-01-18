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
Tags: {{ $task->tags ? $task->tags : 'None' }}
Due Date: {{ $task->due_date ? to_local_datetime($task->due_date)->format('m/d/Y h:i A'): 'None'}}
{{ str_repeat('-', $wordWrap) }}

Title: {{ $task->title ?: 'None' }} 

Description: 
{{ $task->description }} 

    </td>

    @endforeach
</tr>
@endforeach


    </tbody>
    <tfoot>
        <tr>
            <td align="center" colspan="3">
                Board: {{ $boardName }}
            </td>
        </tr>
    </tfoot>
</table>
