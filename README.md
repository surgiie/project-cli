# project-cli
A simple PHP Kanban-style to-do CLI.

![Tests](https://github.com/surgiie/project-cli/actions/workflows/tests.yml/badge.svg)
## Installation

* Ensure that `sqlite` and the `sqlite3` & `pctnl` extension are installed.

* Run `composer global require surgiie/project-cli`. Run with `--ignore-platform-reqs` if using windows.


## Creating a New Board
To create a new board, run: project new:board `<board-name>`

This will create the following directory: `~/.project/boards/<board-name>`

This will also create the following sqlite database file: `~/.projects/boards/<board-name>/database`.

## Setting/Selecting a Board
To set the default board that the CLI will interact with, run: `project select <board-name>`

This allows the CLI to know which board to interact with when running commands.

Note: This will be done automatically on your first board creation.

## Creating Task Statuses
Create statuses for your board tasks and use them as headers in the board listing. For example, a common set of statuses you may create:

```bash
project new:status "Todo"
project new:status "Doing"
project new:status "Done"
```

**Tip**: For the best render experience, consider keeping this to 3-5 statuses or however many statuses your screen/terminal size can render. 3-4 is best, and too many may cause rendering issues for the board.

**Note**: Create these in the order you wish to display them on the board from left to right as the order of your board columns will be by created_at by default.

## Creating Task Tags
Optionally, you can create tags for your board tasks. For example, you may want to tag tasks by urgency:

```bash
project new:tag "Urgent"
project new:tag "Not Urgent"
```

## Creating Tasks

You can create tasks by running the `new:task` command:

`project new:task "Some task description" --status="Todo" --tag="Urgent" --due-date="2 weeks"`

**Note** Any string value that is parsable by the [Carbon](https://github.com/briannesbitt/Carbon) library can be used here.

## Editing Tasks

You can edit existing tasks with `edit:task` command:

`project edit:task <new-description> --id="<task-id>" --status="<new-status>" --tag="<new-tag>" --due-date="<new-due-date>"`

## Moving Tasks
To "move" a task, simply update the status field:

`project edit:task --id="1" --status="Doing"`

## Creating Task Description with Terminal Editor
If you prefer to type up the description of the task in a terminal editor instead of the command line, use the --editor flag to open a temporary file to type the description. By default, this will be in Vim:

`project new:task --editor --status="Todo" --tag="Urgent"`

Once you close vim, you will return to the command process and your task will be created.

**Note**: If you prefer to use a different terminal-based editor, you can set the binary for another terminal-based editor using the TERMINAL_EDITOR preference as documented below. Only terminal-based editors are supported, since editors like VSCode, Sublime, etc. do not run in the same terminal process as the command call. If you attempt to set this to a non-terminal based editor, you will be prompted to enter the description in the terminal.


## Show board
You may show you board with the `show:board` command, you'll see something like this example:

![project-cli kanban board](https://github.com/surgiie/project-cli/blob/master/board-example.png?raw=true)

## CLI Preferences:

You can customize certain functionality or output using preferences stored in your sqlite database. For example, to specify the order of the columns of your board:

`project set "STATUS_ORDER" "Todo, Doing, Done"`

The CLI will consume these preferences as set by the values stored in this table.

Below is a list of options that can currently be set:

| Name   | Description   |  Example  |  Default |
|---|---|---|---|
| `STATUS_ORDER`   | The order of the statuses shown on the board.  |  `project set STATUS_ORDER "Todo,Doing,Done"` | N/A |
| `TERMINAL_EDITOR`   | The terminal editor to use for creating task content via `--editor` option.  |  `project set TERMINAL_EDITOR "nano"` | `vim` |
| `DATE_TIMEZONE`   | The timezone to use for dates shown on the boards/task detail. Must be a valid list returned from php's [timezone_identifiers_list](https://www.php.net/manual/en/function.timezone-identifiers-list.php)  |  `project set DATE_TIMEZONE "America/New_York"` | `America/Indiana/Indianapolis` |

