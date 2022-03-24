<?php

declare(strict_types=1);

namespace Drupal\contact_form\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * @ContentEntityType(
 *   id = "lead",
 *   label = @Translation("Lead"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\contact_form\LeadListBuilder",
 *     "views_data" = "Drupal\contact_form\Entity\LeadViewsData",
 *     "access" = "Drupal\contact_form\LeadAccessControlHandler",
 *     "form" = {
 *       "default" = "Drupal\contact_form\Form\LeadForm",
 *       "add" = "Drupal\contact_form\Form\LeadForm",
 *       "edit" = "Drupal\contact_form\Form\LeadForm",
 *       "delete" = "Drupal\contact_form\Form\LeadDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider"
 *     }
 *   },
 *   base_table = "lead",
 *   translatable = FALSE,
 *   admin_permission = "administer lead entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status"
 *   },
 *   links = {
 *     "canonical" = "/admin/content/lead/{lead}",
 *     "add-form" = "/admin/content/lead/add",
 *     "edit-form" = "/admin/content/lead/{lead}/edit",
 *     "delete-form" = "/admin/content/lead/{lead}/delete",
 *     "collection" = "/admin/content/lead"
 *   },
 *   field_ui_base_route = "entity.lead.settings"
 * )
 */
final class Lead extends ContentEntityBase implements LeadInterface
{
    use EntityChangedTrait;

    public const NAME = 'name';
    public const CREATED = 'created';
    public const CHANGED = 'changed';

    public function getName(): string
    {
        return $this->get(self::NAME)->getString();
    }

    public function setName(string $name): self
    {
        $this->set(self::NAME, $name);

        return $this;
    }

    public function getCreatedTime(): int
    {
        return (int) $this->get(self::CREATED)->getString();
    }

    public function setCreatedTime(int $timestamp): self
    {
        $this->set(self::CREATED, $timestamp);

        return $this;
    }

    public static function baseFieldDefinitions(EntityTypeInterface $entityType): array
    {
        $fields = parent::baseFieldDefinitions($entityType);

        $fields[self::NAME] = BaseFieldDefinition::create('string')
            ->setLabel((string) new TranslatableMarkup('Name'))
            ->setDescription((string) new TranslatableMarkup('The name of the Lead entity.'))
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

        $fields[self::CREATED] = BaseFieldDefinition::create('created')
            ->setLabel((string) new TranslatableMarkup('Created'))
            ->setDescription((string) new TranslatableMarkup('The time that the entity was created.'));

        $fields[self::CHANGED] = BaseFieldDefinition::create('changed')
            ->setLabel((string) new TranslatableMarkup('Changed'))
            ->setDescription((string) new TranslatableMarkup('The time that the entity was last edited.'));

        return $fields;
    }
}
