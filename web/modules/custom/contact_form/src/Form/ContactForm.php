<?php

namespace Drupal\contact_form\Form;

use Drupal\contact_form\Entity\Lead;
use Drupal\Core\Entity\EntityFieldManager;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\Messenger;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContactForm extends FormBase
{
    protected $messenger;
    protected $entityFieldManager;

    final public function __construct(
        Messenger $messenger,
        EntityFieldManager $entityFieldManager
    ) {
        $this->messenger = $messenger;
        $this->entityFieldManager = $entityFieldManager;
    }

    public static function create(ContainerInterface $container)
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
            '#title' => $this->t('Name'),
            '#required' => true,
        ];

        $form['email'] = [
            '#type' => 'email',
            '#title' => $this->t('Email'),
            '#required' => true,
        ];

        $form['phone'] = [
            '#type' => 'tel',
            '#title' => $this->t('Phone'),
            '#required' => true,
        ];

        $form['subject'] = [
            '#type' => 'select',
            '#title' => $this->t('Subject'),
            '#empty_option' => t('Pick one'),
            '#required' => true,
            '#required_error' => t('Select one subject'),
            '#options' => $this->getOptions('field_subject'),
        ];

        $otherCondition = [
            ':input[name="subject"]' => ['value' => 'specific'],
        ];

        $form['other_subject'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Specific subject'),
            '#states' => [
                'required' => $otherCondition,
                'enabled' => $otherCondition,
                'visible' => $otherCondition,
            ],
        ];

        $form['type'] = [
            '#type' => 'radios',
            '#title' => $this->t('Type'),
            '#required' => true,
            '#options' => $this->getOptions('field_type'),
        ];

        $form['preferences'] = [
            '#type' => 'checkboxes',
            '#title' => $this->t('Preferences'),
            '#options' => $this->getOptions('field_preferences'),
        ];

        $form['comment'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Comment'),
            '#attributes' => [
                'placeholder' => t('Write your comment'),
            ],
            '#required' => true,
        ];

        $form['newsletter'] = [
            '#type' => 'checkbox',
            '#title' => $this->t('Newsletter'),
        ];

        $form['privacy_policy'] = [
            '#type' => 'checkbox',
            '#title' => $this->t('contact_form.privacy_policy', [
                '@link' => Url::fromRoute('entity.node.canonical', ['node' => 1])->toString(),
            ]),
            '#required' => true,
        ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Send'),
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
        $lead->set('field_preferences', \array_values($form['preferences']['#value']));
        $lead->set('field_comment', $form['comment']['#value']);
        $lead->set('field_newsletter', $form['newsletter']['#value']);
        $lead->set('field_privacy_policy', $form['privacy_policy']['#value']);

        $lead->save();

        $this->messenger->addStatus($this->t('Lead saved succesfully'));
    }

    protected function getOptions(string $field): array
    {
        $fields = $this->entityFieldManager->getFieldDefinitions('lead', 'lead');
        $fieldDefinition = $fields[$field];

        return $fieldDefinition->getSetting('allowed_values');
    }
}
