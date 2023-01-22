<table style="box-double">
    <thead>
        <tr>
            <th align="center" class="text-green w-auto">
                ID: {{ $task->id }}
            </th>
        </tr>
    </thead>
    <tbody>
<tr border="1">

    <td class="mb-1">

Title: 

{{ $task->title ?: 'None' }} 
{{ str_repeat('-', $wordWrap) }}

Description: 

{{ $task->description }}   

{{ str_repeat('-', $wordWrap) }}

Tags: 

{{ $task->tags ? $task->tags : 'None' }}
{{ str_repeat('-', $wordWrap) }}

Due Date: 
@if($task->due_date)

{{ to_local_datetime($task->due_date, $timezone)->format('m/d/Y h:i A') }}

Past Due: {{ to_local_datetime($task->due_date, $timezone)->isPast() ? "Yes": "No"}}

@else
None
@endif
{{ str_repeat('-', $wordWrap) }}

Created At: 

{{ $task->created_at ? to_local_datetime($task->created_at, $timezone)->format('m/d/Y h:i A'): 'None'}}
{{ str_repeat('-', $wordWrap) }}

Updated At: 

{{ $task->updated_at ? to_local_datetime($task->updated_at, $timezone)->format('m/d/Y h:i A'): 'None'}}

    </td>
</tr>

    </tbody>
    <tfoot>
        <tr>
            <td align="center" class="text-green">
                Board: {{ $boardName }}
            </td>
        </tr>
    </tfoot>
</table>
