<?php

if (! function_exists('get_selected_board_name')) {
    /**Get the name of the current set default board.*/
    function get_selected_board_name()
    {
        $defaultFile = project_path('default-board');

        if (! is_file($defaultFile)) {
            return false;
        }

        $defaultBoard = trim(file_get_contents($defaultFile));

        if (! is_dir(project_path("boards/$defaultBoard"))) {
            return false;
        }

        return $defaultBoard;
    }
}

if (! function_exists('project_path')) {
    /**Create a path relavent to the .project directory.*/
    function project_path(string $path = '')
    {
        $basePath = getenv('PROJECT_CLI_BASE_PATH');
        if ($basePath) {
            $base = rtrim($basePath, '/').'/';
        } else {
            $user = get_current_user();
            $base = rtrim("/home/$user", '/').'/.project/';
        }

        $path = trim($path, '/');

        return rtrim($base.$path, '/');
    }
}

if (! function_exists('format_table_cell')) {
    function format_table_cell($value, $wrap)
    {
        return wordwrap(preg_replace('!\s+!', ' ', $value), $wrap);
    }
}
