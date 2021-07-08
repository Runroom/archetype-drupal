<?php

declare(strict_types=1);

namespace Drupal\contact_form\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;

class LeadForm extends ContentEntityForm
{
    public function save(array $form, FormStateInterface $formState): int
    {
        $entity = $this->entity;

        $status = parent::save($form, $formState);

        switch ($status) {
            case SAVED_NEW:
                $this->messenger()->addMessage(new TranslatableMarkup('Created the %label Lead.', [
                    '%label' => $entity->label(),
                ]));

                break;
            default:
                $this->messenger()->addMessage(new TranslatableMarkup('Saved the %label Lead.', [
                    '%label' => $entity->label(),
                ]));
        }

        $formState->setRedirect('entity.lead.canonical', ['lead' => $entity->id()]);

        return $status;
    }
}
