<?php

namespace Drupal\cookies\Form;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\cookies\Controller\CookiesController;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class CookiesEntityForm extends ContentEntityForm
{
    protected $account;

    public function __construct(
        EntityRepositoryInterface $entityRepository,
        EntityTypeBundleInfoInterface $entityTypeBundleInfo = null,
        TimeInterface $time = null,
        AccountProxyInterface $account
    ) {
        parent::__construct($entityRepository, $entityTypeBundleInfo, $time);

        $this->account = $account;
    }

    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('entity.repository'),
            $container->get('entity_type.bundle.info'),
            $container->get('datetime.time'),
            $container->get('current_user')
        );
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $form = parent::buildForm($form, $form_state);

        return $form;
    }

    public function save(array $form, FormStateInterface $formState)
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
