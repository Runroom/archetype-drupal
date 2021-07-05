<?php

declare(strict_types=1);

namespace Drupal\cookies\Form;

use Drupal\cookies\Controller\CookiesController;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

final class CookiesEntityForm extends ContentEntityForm
{
    public function save(array $form, FormStateInterface $formState): int
    {
        $entity = $this->entity;

        $status = parent::save($form, $formState);

        switch ($status) {
            case SAVED_NEW:
                $this->messenger()->addMessage($this->t('Created the %label Cookies entity.', [
                    '%label' => $entity->label(),
                ]));

                break;

            default:
                $this->messenger()->addMessage($this->t('Saved the %label Cookies entity.', [
                    '%label' => $entity->label(),
                ]));
        }

        Cache::invalidateTags(CookiesController::COOKIES_CACHE_TAGS);

        $formState->setRedirect('entity.cookies_entity.canonical', ['cookies_entity' => $entity->id()]);

        return $status;
    }
}
