<?php

declare(strict_types=1);

namespace Drupal\contact_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class LeadSettingsForm extends FormBase
{
    public function getFormId(): string
    {
        return 'lead_settings';
    }

    public function submitForm(array &$form, FormStateInterface $form_state): void
    {
    }

    public function buildForm(array $form, FormStateInterface $form_state): array
    {
        $form['lead_settings']['#markup'] = 'Settings form for Lead entities. Manage field settings here.';

        return $form;
    }
}
