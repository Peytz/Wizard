<?php

use Composer\Autoload\ClassLoader;

if (!@include __DIR__ . '/../vendor/autoload.php') {
    die(<<<'EOT'
You must set up the project dependencies, run the following commands:
wget http://getcomposer.org/composer.phar
php composer.phar install
EOT
    );
}

$loader = new ClassLoader();
$loader->add('Acme', __DIR__ . '/Peytz/Test/Wizard/Fixtures');
$loader->register();
