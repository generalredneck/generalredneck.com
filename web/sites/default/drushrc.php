<?php

/**
 * @file
 * Drush RC File.
 */

$options['shell-aliases'] = [];
$options['shell-aliases']['fresh'] = '!( ' . implode(" ) && \n ( ", [
  "echo '\nImporting reference database...'",
  "gunzip < ../reference/general-redneck.sql.gz | sed '/INSERT INTO `cache.*` VALUES/d' | drush sql-cli",
]) . " )";
$options['shell-aliases']['local'] = "!( " . implode(" ) && \n ( ", [
  "echo '\nUpdating database...'",
  "drush updatedb -y",
  "echo '\nApplying pending entity schema updates...'",
  "drush entity-updates -y",
]) . " )";
$options['shell-aliases']['live'] = "!( " . implode(" ) && \n ( ", [
  "echo '\nUpdating database...'",
  "drush updatedb -y",
  "echo '\nApplying pending entity schema updates...'",
  "drush entity-updates -y",
]) . " )";
