#!/usr/bin/env bash

FILE=$(mktemp "/tmp/stub.php.XXXXX")
OUTPUT="build/peytz-wizard.phar"
SOURCE="src"

STUB="
<?php
Phar::mapPhar();

spl_autoload_register(function (\$className) {
    if (0 !== strpos(\$className, 'Peytz\\\Wizard\\\')) {
        return false;
    }

    \$file = 'phar://' . __FILE__ . '/' . str_replace('\\\', DIRECTORY_SEPARATOR, \$className) . '.php';

    if (file_exists(\$file)) {
        require \$file;
        return true;
    }

    return false;
});

__HALT_COMPILER();
"

# Generate PHAR file
echo $STUB > $FILE
phar-build --ns --src=$SOURCE --phar=$OUTPUT --stub=$FILE
rm $FILE
