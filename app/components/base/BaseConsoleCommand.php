<?php

/**
 * Base class for console command
 */
abstract class BaseConsoleCommand extends CConsoleCommand
{

    /**
     * Output text to console
     *
     * @param string $text
     */
    public function e($text = '')
    {
        echo $text . "\r\n";
    }
}
