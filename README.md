# project-cli

![Tests](https://github.com/surgiie/project-cli/actions/workflows/tests.yml/badge.svg)

A simple php kanban style todo cli.


## Install

- `composer global require surgiie/project-cli`
-  `sqlite` and the `sqlite3` extension should be installed as the cli stores all data within a `sqlite` database.

**Note** - The cli relies on the `pctnl` extension which is not available on linux, but windows is still supported, install with the `--ignore-platform-reqs` if using windows.
## Create New Board

To create a new board, run: `project new:board <board-name>`

This will create the following directory: `~/.project/boards/<board-name>`

This will also create the following sqlite database file: `~/.projects/boards/<board-name>/database`.


## Set/Select Board

To set the default board the cli will interact with, run: `project select <board-name>`.

This allows the cli to know which board to interact with when running commands. 


**Note** This will be done automatically on your first board creation.

## Create Task Statuses

Next, create statuses for your board tasks and to use as headers the board listing, for example a common set of statuses you may create:

```bash
project new:status "Todo"
project new:status "Doing"
project new:status "Done"
```

**Tip** For best render experience, consider keeping this to 3-5 statuses or however many statuses your screen/terminal size can render, 3-4 is best and too many would eventually cause rendering issues for the board.

**Note**  You should create these order you wish to display them in board from left to right as the order of your board columns will be by `created_at` by default.

## Create Task Tags

Next, optionally create tags for your board tasks, for example, you may like to tag tasks by urgency:

```bash
project new:tag "Urgent"
project new:tag "Not Urgent"
```

## Create Tasks

Lastly, you can create tasks:

`project new:task "Some task description" --status="Todo" --tag="Urgent"`

## Edit Tasks
You can overwrite existing data and update a task as follows: 

`project edit:task <new-description> --id="<task-id>" --status="<new-status>" --tag="<new-tag>" --due-date="<new-due-date>`
### Move Tasks
To "move" an task, simply update the status field:

`project edit:task --id="1" --status="Doing"`

### Create Task Description With Terminal Editor
If you prefer to type up the description of the task in a terminal editor instead of terminal, use the `--editor` flag to open a tmp file to type the description, by default that will be vim:

`project new:task --editor --status="Todo" --tag="Urgent"`

Once you close vim, youll go back to the command process and your task will be created.


**Note** If you prefer to use a different terminal based editor you can set the binary for another terminal based editor using the `TERMINAL_EDITOR` preference as documented below. Only terminal based editors are supported since editors like vscode, sublime, etc do not run in the same terminal process as the command call, youll be prompted to enter the description in the terminal if you attempt to set this to a non-terminal based editor.

### Assign Due Dates:

`project new:task "Some task description" --due-date="tomorrow"`

**Note** Any string value that is parsable by the [Carbon](https://github.com/briannesbitt/Carbon) library can be used here.


### CLI Preferences:

You may customize certain functionality or output using preferences stored in your `sqlite` database, for example to specify the order of the columns of your board:

`project set "STATUS_ORDER" "Todo, Doing, Done"

The cli will consume these preferences as set by the values stored in this table. 

Below is a list of options that can be currently set:


| Name   | Description   |  Example  |  Default |
|---|---|---|---|
| `STATUS_ORDER`   | The order of the statuses shown on the board.  |  `project set STATUS_ORDER "Todo,Doing,Done"` | N/A |
| `TERMINAL_EDITOR`   | The terminal editor to use for creating task content via `--editor` option.  |  `project set TERMINAL_EDITOR "nano"` | `vim` |
| `DATE_TIMEZONE`   | The timezone to use for dates shown on the boards/task detail. Must be a valid list returned from php's [timezone_identifiers_list](https://www.php.net/manual/en/function.timezone-identifiers-list.php)  |  `project set DATE_TIMEZONE "America/New_York"` | `America/Indiana/Indianapolis` |

