<?php

declare(strict_types=1);

namespace Drupal\cookies\Form;

use Drupal\cookies\Controller\CookiesController;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;

final class CookiesEntityForm extends ContentEntityForm
{
    public function save(array $form, FormStateInterface $form_state): int
    {
        $entity = $this->entity;

        $status = parent::save($form, $form_state);

        switch ($status) {
            case SAVED_NEW:
                $this->messenger()->addMessage(new TranslatableMarkup('Created the %label Cookies entity.', [
                    '%label' => $entity->label(),
                ]));

                break;

            default:
                $this->messenger()->addMessage(new TranslatableMarkup('Saved the %label Cookies entity.', [
                    '%label' => $entity->label(),
                ]));
        }

        Cache::invalidateTags(CookiesController::COOKIES_CACHE_TAGS);

        $form_state->setRedirect('entity.cookies_entity.canonical', ['cookies_entity' => $entity->id()]);

        return $status;
    }
}
