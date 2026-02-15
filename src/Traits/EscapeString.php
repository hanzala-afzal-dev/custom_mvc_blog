<?php

namespace Traits;

trait EscapeString
{
    /**
     * @param string $value
     * @return string
     */
    public static function html(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}