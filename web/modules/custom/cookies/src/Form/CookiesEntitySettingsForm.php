<?php

namespace Drupal\cookies\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class CookiesEntitySettingsForm extends FormBase
{
    public function getFormId()
    {
        return 'cookiesentity_settings';
    }

    public function submitForm(array &$form, FormStateInterface $formState)
    {
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $form['cookiesentity_settings']['#markup'] = 'Settings form for Cookies entity entities. Manage field settings here.';

        return $form;
    }
}
