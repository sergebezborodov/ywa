<?php

/**
 * Базовый класс для консольных команд
 */
abstract class BaseConsoleCommand extends CConsoleCommand {

    /**
     * Вывод текста в консоль
     *
     * @param string $text
     */
    public function e($text = '') {
        echo $text . "\r\n";
    }
}
