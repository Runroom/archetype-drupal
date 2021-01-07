<?php

declare(strict_types=1);

namespace Drupal\contact_form\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * @ingroup contact_form
 *
 * @ContentEntityType(
 *   id = "lead",
 *   label = @Translation("Lead"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\contact_form\LeadListBuilder",
 *     "views_data" = "Drupal\contact_form\Entity\LeadViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\contact_form\Form\LeadForm",
 *       "add" = "Drupal\contact_form\Form\LeadForm",
 *       "edit" = "Drupal\contact_form\Form\LeadForm",
 *       "delete" = "Drupal\contact_form\Form\LeadDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\contact_form\LeadHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\contact_form\LeadAccessControlHandler",
 *   },
 *   base_table = "lead",
 *   translatable = FALSE,
 *   admin_permission = "administer lead entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/lead/{lead}",
 *     "add-form" = "/admin/structure/lead/add",
 *     "edit-form" = "/admin/structure/lead/{lead}/edit",
 *     "delete-form" = "/admin/structure/lead/{lead}/delete",
 *     "collection" = "/admin/structure/lead",
 *   },
 *   field_ui_base_route = "lead.settings"
 * )
 */
class Lead extends ContentEntityBase implements LeadInterface
{
    use EntityChangedTrait;

    public function getName(): ?string
    {
        return $this->get('name')->value;
    }

    public function setName(?string $name): LeadInterface
    {
        $this->set('name', $name);

        return $this;
    }

    public function getCreatedTime(): ?int
    {
        return (int) $this->get('created')->value;
    }

    public function setCreatedTime(?int $timestamp): LeadInterface
    {
        $this->set('created', $timestamp);

        return $this;
    }

    public static function baseFieldDefinitions(EntityTypeInterface $entityType): array
    {
        $fields = parent::baseFieldDefinitions($entityType);

        $fields['name'] = BaseFieldDefinition::create('string')
            ->setLabel((string) t('Name'))
            ->setDescription((string) t('The name of the Lead entity.'))
            ->setSettings([
                'max_length' => 50,
                'text_processing' => 0,
            ])
            ->setDefaultValue('')
            ->setDisplayOptions('view', [
                'label' => 'above',
                'type' => 'string',
                'weight' => -4,
            ])
            ->setDisplayOptions('form', [
                'type' => 'string_textfield',
                'weight' => -4,
            ])
            ->setDisplayConfigurable('form', true)
            ->setDisplayConfigurable('view', true)
            ->setRequired(true);

        $fields['created'] = BaseFieldDefinition::create('created')
            ->setLabel((string) t('Created'))
            ->setDescription((string) t('The time that the entity was created.'));

        $fields['changed'] = BaseFieldDefinition::create('changed')
            ->setLabel((string) t('Changed'))
            ->setDescription((string) t('The time that the entity was last edited.'));

        return $fields;
    }
}
