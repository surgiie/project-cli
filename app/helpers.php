<?php

if (!function_exists("get_selected_board_name")) {
    /**Get the name of the default set board.*/
    function get_selected_board_name()
    {
        $defaultFile = project_path("default-board");
        if (!is_file($defaultFile)) {
            return false;
        }
        return trim(file_get_contents($defaultFile));
    }
}

if (!function_exists('project_path')) {
    
    /**Create a path relavent to the .project directory.*/
    function project_path(string $path = '')
    {
        $basePath = getenv("PROJECT_CLI_BASE_PATH");
        if ($basePath) {
            $base = rtrim($basePath, '/') . '/';
        } else {
            $user = get_current_user();
            $base = rtrim("/home/$user", '/') . '/.project/';
        }

        $path = trim($path, '/');

        return rtrim($base . $path, '/');
    }
}
