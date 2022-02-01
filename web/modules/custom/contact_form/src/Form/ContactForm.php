<?php

declare(strict_types=1);

namespace Drupal\contact_form\Form;

use Drupal\contact_form\Entity\Lead;
use Drupal\Core\Entity\EntityFieldManager;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\Messenger;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContactForm extends FormBase
{
    protected $messenger;
    private EntityFieldManager $entityFieldManager;

    final public function __construct(
        Messenger $messenger,
        EntityFieldManager $entityFieldManager
    ) {
        $this->messenger = $messenger;
        $this->entityFieldManager = $entityFieldManager;
    }

    public static function create(ContainerInterface $container): self
    {
        return new static(
            $container->get('messenger'),
            $container->get('entity_field.manager')
        );
    }

    public function getFormId(): string
    {
        return 'contact_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state): array
    {
        $form['name'] = [
            '#type' => 'textfield',
            '#title' => new TranslatableMarkup('Name'),
            '#required' => true,
        ];

        $form['email'] = [
            '#type' => 'email',
            '#title' => new TranslatableMarkup('Email'),
            '#required' => true,
        ];

        $form['phone'] = [
            '#type' => 'tel',
            '#title' => new TranslatableMarkup('Phone'),
            '#required' => true,
        ];

        $form['subject'] = [
            '#type' => 'select',
            '#title' => new TranslatableMarkup('Subject'),
            '#empty_option' => new TranslatableMarkup('Pick one'),
            '#required' => true,
            '#required_error' => new TranslatableMarkup('Select one subject'),
            '#options' => $this->getOptions('field_subject'),
        ];

        $otherCondition = [
            ':input[name="subject"]' => ['value' => 'specific'],
        ];

        $form['other_subject'] = [
            '#type' => 'textfield',
            '#title' => new TranslatableMarkup('Specific subject'),
            '#states' => [
                'required' => $otherCondition,
                'enabled' => $otherCondition,
                'visible' => $otherCondition,
            ],
        ];

        $form['type'] = [
            '#type' => 'radios',
            '#title' => new TranslatableMarkup('Type'),
            '#required' => true,
            '#options' => $this->getOptions('field_type'),
        ];

        $form['preferences'] = [
            '#type' => 'checkboxes',
            '#title' => new TranslatableMarkup('Preferences'),
            '#options' => $this->getOptions('field_preferences'),
        ];

        $form['comment'] = [
            '#type' => 'textarea',
            '#title' => new TranslatableMarkup('Comment'),
            '#attributes' => [
                'placeholder' => new TranslatableMarkup('Write your comment'),
            ],
            '#required' => true,
        ];

        $form['newsletter'] = [
            '#type' => 'checkbox',
            '#title' => new TranslatableMarkup('Newsletter'),
        ];

        $form['privacy_policy'] = [
            '#type' => 'checkbox',
            '#title' => new TranslatableMarkup('contact_form.privacy_policy', [
                '@link' => Url::fromRoute('entity.node.canonical', ['node' => 2])->toString(),
            ]),
            '#required' => true,
        ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => new TranslatableMarkup('Send'),
        ];

        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state): void
    {
        $lead = Lead::create();

        $lead->setName($form['name']['#value']);

        $lead->set('field_email', $form['email']['#value']);
        $lead->set('field_phone', $form['phone']['#value']);
        $lead->set('field_subject', $form['subject']['#value']);
        $lead->set('field_other_subject', $form['other_subject']['#value']);
        $lead->set('field_type', $form['type']['#value']);
        $lead->set('field_preferences', array_values($form['preferences']['#value']));
        $lead->set('field_comment', $form['comment']['#value']);
        $lead->set('field_newsletter', $form['newsletter']['#value']);
        $lead->set('field_privacy_policy', $form['privacy_policy']['#value']);

        $lead->save();

        $this->messenger->addStatus(new TranslatableMarkup('Lead saved succesfully'));
    }

    protected function getOptions(string $field): array
    {
        $fields = $this->entityFieldManager->getFieldDefinitions('lead', 'lead');
        $fieldDefinition = $fields[$field];

        return $fieldDefinition->getSetting('allowed_values');
    }
}
