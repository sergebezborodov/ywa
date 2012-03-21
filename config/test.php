<?php
/**
 * Config for unit test
 */

return array(
    'components' => array(
        'fixture' => array(
            'class' => 'system.test.CDbFixtureManager',
        ),
        'db'=> require 'local/database-test.php'
    ),
);
