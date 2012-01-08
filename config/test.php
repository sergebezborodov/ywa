<?php
/**
 * Настройки приложения для тестирования
 */

return array(
    'components' => array(
        'fixture' => array(
            'class' => 'system.test.CDbFixtureManager',
        ),
        'db'=> require 'local/database-test.php'
    ),
);
