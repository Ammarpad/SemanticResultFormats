<?php

require_once( __DIR__ . '/evilMediaWikiBootstrap.php' );

echo exec( 'composer update' ) . "\n";

require_once( __DIR__ . '/../vendor/autoload.php' );