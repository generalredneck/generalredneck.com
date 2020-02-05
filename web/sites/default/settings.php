<?php

/**
 * Load services definition file.
 */
$settings['container_yamls'][] = __DIR__ . '/services.yml';

/**
 * Include the Pantheon-specific settings file.
 *
 * n.b. The settings.pantheon.php file makes some changes
 *      that affect all envrionments that this site
 *      exists in.  Always include this file, even in
 *      a local development environment, to ensure that
 *      the site settings remain consistent.
 */
include __DIR__ . "/settings.pantheon.php";

/**
 * Place the config directory outside of the Drupal root.
 */
$settings['config_sync_directory'] = dirname(DRUPAL_ROOT) . '/config';

/**
 * If there is a local settings file, then include it
 */
$local_settings = __DIR__ . "/settings.local.php";
if (file_exists($local_settings)) {
  include $local_settings;
}


$config['evercurrent.admin_config']['send'] = FALSE;
      $config['evercurrent.admin_config']['send'] = TRUE;
      $config['evercurrent.admin_config']['target_address'] = 'https://live-evercurrent-clone.pantheonsite.io';
      $settings['evercurrent_environment_url'] = 'https://generalredneck.com';
      $settings['evercurrent_environment_token'] = 'b29a72ecacd57fedef0b415821311461';

if (isset($_ENV['PANTHEON_ENVIRONMENT'])) {
  switch($_ENV['PANTHEON_ENVIRONMENT']) {
    case 'live':
      $config['evercurrent.admin_config']['send'] = TRUE;
      $config['evercurrent.admin_config']['target_address'] = 'https://live-evercurrent-clone.pantheonsite.io';
      $settings['evercurrent_environment_url'] = 'https://generalredneck.com';
      $settings['evercurrent_environment_token'] = 'b29a72ecacd57fedef0b415821311461';
      break;
  }
}

/**
 * Always install the 'standard' profile to stop the installer from
 * modifying settings.php.
 *
 * See: tests/installer-features/installer.feature
 */
$settings['install_profile'] = 'standard';
