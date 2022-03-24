<?php

declare(strict_types=1);

namespace Drupal\cookies\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

final class CookiesEntitySettingsForm extends FormBase
{
    public function getFormId(): string
    {
        return 'cookiesentity_settings';
    }

    public function submitForm(array &$form, FormStateInterface $form_state): void
    {
    }

    public function buildForm(array $form, FormStateInterface $form_state): array
    {
        $form['cookiesentity_settings']['#markup'] = 'Settings form for Cookies entity entities. Manage field settings here.';

        return $form;
    }
}
