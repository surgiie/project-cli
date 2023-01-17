<table style="box-double">
    <thead>
        <tr class="flex">
            @foreach($statuses as $status)
                <th align="center" class="text-green">
                    {{ $status }}
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        <tr border="1">
            <td class="mb-1">
{{ format_table_cell("Lorem ipsum dolor sit, amet consectetur adipisicing elit. Doloremque, nisi, quaerat natus error quos fugit nulla ea quas aperia cupiditate id non quisquam porro fugiat ex veniam eum dicta delectus.", $wrap)}} 

ID: 1|Tags: Urgent 

            </td>
    </tbody>
    <tfoot>
        <tr>
            <td align="center" colspan="3">
                Board: Name
            </td>
        </tr>
    </tfoot>
</table>
