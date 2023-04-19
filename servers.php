<?php

declare(strict_types=1);

namespace Deployer;

host('drupal.runroom.dev')
    ->setDeployPath('~/drupal.runroom.dev')
    // labelling with "stage" => "staging" will reset database and load doctrine fixtures
    ->setLabels(['stage' => 'staging'])
    ->setRemoteUser(getenv('DEPLOYER_USER'));
