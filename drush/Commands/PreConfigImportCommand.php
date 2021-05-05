<?php

declare(strict_types=1);

namespace Drush\Commands;

class PreConfigImportCommand extends DrushCommands
{
    /** @hook pre-command config:import */
    public function setUuid(): void
    {
        $staticUuidIsSet = \Drupal::state()->get('static_uuid_is_set');

        if (!$staticUuidIsSet) {
            $configFactory = \Drupal::configFactory();
            $configFactory->getEditable('system.site')->set('uuid', '60d59ddd-0588-4020-9dae-405d5aa61f10')->save();

            $this->output()->writeln('Setting the correct UUID for this project: done.');

            \Drupal::state()->set('static_uuid_is_set', 1);
        }
    }
}
