<?php
if (!function_exists('escapeString')) {
    function escapeString($conn, $string)
    {
        return mysqli_real_escape_string($conn, $string);
    }
}
