<?php

namespace Drupal\contact_form\Form;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @ingroup contact_form
 */
class LeadForm extends ContentEntityForm
{
    protected $account;

    final public function __construct(
        EntityRepositoryInterface $entityRepository,
        EntityTypeBundleInfoInterface $entityTypeBundleInfo = null,
        TimeInterface $time = null,
        AccountProxyInterface $account
    ) {
        parent::__construct($entityRepository, $entityTypeBundleInfo, $time);

        $this->account = $account;
    }

    public static function create(ContainerInterface $container): self
    {
        return new static(
            $container->get('entity.repository'),
            $container->get('entity_type.bundle.info'),
            $container->get('datetime.time'),
            $container->get('current_user')
        );
    }

    public function buildForm(array $form, FormStateInterface $form_state): array
    {
        $form = parent::buildForm($form, $form_state);

        return $form;
    }

    public function save(array $form, FormStateInterface $form_state): int
    {
        $entity = $this->entity;

        $status = parent::save($form, $form_state);

        switch ($status) {
            case SAVED_NEW:
                $this->messenger()->addMessage($this->t('Created the %label Lead.', [
                    '%label' => $entity->label(),
                ]));

                break;
            default:
                $this->messenger()->addMessage($this->t('Saved the %label Lead.', [
                    '%label' => $entity->label(),
                ]));
        }

        $form_state->setRedirect('entity.lead.canonical', ['lead' => $entity->id()]);

        return $status;
    }
}
