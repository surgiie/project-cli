# project-cli
![Tests](https://github.com/surgiie/project-cli/actions/workflows/tests.yml/badge.svg)

A php kanban style todo cli. 
## Install

- `composer global require surgiie/project-cli`
- The cli relies on `sqlite` database to save all data. Refer to the installation instructions for the `sqlite3` extension.


## Create New Board

To create a new board, run: `project new:board <board-name>`

This will create the following directory: `~/.project/boards/<board-name>`

This will also create the following sqlite database file: `~/.projects/boards/<board-name>/database`.


## Set/Select Board

Once you a board for the cli to work with, set the default board with `project select <board-name>`.

This allows the cli to know which board to interact with when running commands. 


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
project new:tag "urgent"
project new:tag "not-urgent"
```

