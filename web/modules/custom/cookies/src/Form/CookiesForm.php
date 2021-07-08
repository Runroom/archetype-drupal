<?php

declare(strict_types=1);

namespace Drupal\cookies\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;

final class CookiesForm extends FormBase
{
    public function getFormId(): string
    {
        return 'cookies_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state): array
    {
        $form['mandatory'] = [
            '#type' => 'checkbox',
            '#title' => new TranslatableMarkup('cookies.mandatory_cookies.label'),
            '#attributes' => ['disabled' => true],
            '#default_value' => true,
        ];

        $form['performance'] = [
            '#type' => 'checkbox',
            '#title' => new TranslatableMarkup('cookies.performance_cookies.label'),
            '#attributes' => [
                'class' => ['js-cookies-performance-checkbox'],
            ],
        ];

        $form['targeting'] = [
            '#type' => 'checkbox',
            '#title' => new TranslatableMarkup('cookies.targeting_cookies.label'),
            '#attributes' => [
                'class' => ['js-cookies-targeting-checkbox'],
            ],
        ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => new TranslatableMarkup('cookies.save_settings'),
            '#attributes' => [
                'class' => ['js-cookies-save-preferences'],
            ],
        ];

        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state): void
    {
    }
}
