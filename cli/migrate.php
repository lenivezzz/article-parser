<?php
declare(strict_types=1);

use db\Migration;

require __DIR__ . '/../bootstrap.php';

$migration = new Migration($capsule::schema());

if (isset($argv[1]) && $argv[1] === 'rollback' && $migration->rollback()) {
    echo 'Database cleaned';
} elseif ($migration->up()) {
    echo 'Database installed';
} else {
    echo 'Database is up to date';
}
echo PHP_EOL;
