# project-cli
![Tests](https://github.com/surgiie/project-cli/actions/workflows/tests.yml/badge.svg)

A simple php kanban style todo cli built with [termwind](https://github.com/nunomaduro/termwind).
## Install

- `composer global require surgiie/project-cli`
-  `sqlite` and the `sqlite3` extension should be installed as the cli stores all data within a `sqlite` database.

## Create New Board

To create a new board, run: `project new:board <board-name>`

This will create the following directory: `~/.project/boards/<board-name>`

This will also create the following sqlite database file: `~/.projects/boards/<board-name>/database`.


## Set/Select Board

Once you a board for the cli to work with, set the default board with `project select <board-name>`.

This allows the cli to know which board to interact with when running commands. 


**Note** This will be done automatically on your first board creation. Use to switch between boards in future calls.

## Create Task Statuses

Next, create statuses for your board tasks and to use as headers the board listing, for example a common set of statuses you may create:

```bash
project new:status "Todo"
project new:status "Doing"
project new:status "Done"
```

Consider keeping this limitted to 3-5 statuses or however many statuses your screen/terminal size can render without impacting the board render from breaking to new lines.

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

## Create Task Description With Vim
If you prefer to type up the description of the task in vim instead of terminal, use the `--editor` flag to open a tmp file to type the description in a vim session:

`project new:task --editor --status="Todo" --tag="Urgent"`

Once you close vim, youll go back to the command process and your task will be created.


**Note** If you prefer to use a different terminal based editor you can set the binary for another terminal based editor using the `PROJECT_CLI_EDITOR` environment variable, e.g `export PROJECT_CLI_EDITOR=nano`. Only terminal based editors are supported since editors like vscode, sublime, etc do not run in the same terminal process as the command call, youll be prompted to enter the description in the terminal if you attempt to set this
env to a non-terminal based editor since those terminal binaries would not hold up the command call process.

### Assign Due Dates:

`project new:task "Some task description" --due-date="tomorrow"`

**Note** Any string value that is parsable by the [Carbon](https://github.com/briannesbitt/Carbon) library can be used here.

