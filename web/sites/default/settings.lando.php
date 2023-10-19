<?php
/**
 * @file
 * Lando settings.
 */

// Configure the database if on Lando
// @todo: eventually we want to remove this in favor of Lando directly
// spoofing the needed PLATFORM_* envvars.
if (isset($_SERVER['LANDO'])) {

  // Set the database credentials.
  $databases['default']['default'] = array (
    'database' => 'drupal9',
    'username' => 'drupal9',
    'password' => 'drupal9',
    'prefix' => '',
    'host' => 'database',
    'port' => '',
    'namespace' => 'Drupal\\mysql\\Driver\\Database\\mysql',
    'driver' => 'mysql',
    'autoload' => 'core/modules/mysql/src/Driver/Database/mysql/',
  );

  // And a bogus hashsalt for now.
  $settings['hash_salt'] = json_encode($databases);

}
