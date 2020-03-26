<?php

namespace Drupal\cookies\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CookiesForm extends FormBase
{
    public static function create(ContainerInterface $container)
    {
        return new static();
    }

    public function getFormId()
    {
        return 'cookies_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state): array
    {
        $form['mandatory'] = [
            '#type' => 'checkbox',
            '#attributes' => ['disabled' => true],
            '#default_value' => true,
        ];

        $form['performance'] = [
            '#type' => 'checkbox',
            '#attributes' => [
                'class' => ['js-cookies-performance-checkbox'],
            ],
            '#default_value' => $this->isCookieActive('performance_cookie', 'true'),
        ];

        $form['targeting'] = [
            '#type' => 'checkbox',
            '#attributes' => [
                'class' => ['js-cookies-targeting-checkbox'],
            ],
            '#default_value' => $this->isCookieActive('targeting_cookie', 'false'),
        ];

        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $formState): void
    {
    }

    protected function isCookieActive(string $cookie, string $defaultValue): bool
    {
        return \Drupal::request()->cookies->get($cookie, $defaultValue) === 'true';
    }
}
