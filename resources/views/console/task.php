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
        
Tags: {{ $task->tags ? $task->tags : 'None' }}
Due Date: {{ $task->due_date ? to_local_datetime($task->due_date)->format('m/d/Y h:i A'): 'None'}}
Created At: {{ $task->due_date ? to_local_datetime($task->created_at)->format('m/d/Y h:i A'): 'None'}}
Updated At: {{ $task->due_date ? to_local_datetime($task->updated_at)->format('m/d/Y h:i A'): 'None'}}
{{ str_repeat('-', $wordWrap) }}

Title: {{ $task->title ?: 'None' }} 

Description: {{ $task->description }} 

    </td>
</tr>

    </tbody>
    <tfoot>
        <tr>
            <td align="center">
                Board: {{ $boardName }}
            </td>
        </tr>
    </tfoot>
</table>
