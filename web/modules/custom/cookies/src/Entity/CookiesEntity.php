<?php

declare(strict_types=1);

namespace Drupal\cookies\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * @ContentEntityType(
 *   id = "cookies_entity",
 *   label = @Translation("Cookies entity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\cookies\CookiesEntityListBuilder",
 *     "views_data" = "Drupal\cookies\Entity\CookiesEntityViewsData",
 *     "access" = "Drupal\cookies\CookiesEntityAccessControlHandler",
 *     "form" = {
 *       "default" = "Drupal\cookies\Form\CookiesEntityForm",
 *       "add" = "Drupal\cookies\Form\CookiesEntityForm",
 *       "edit" = "Drupal\cookies\Form\CookiesEntityForm",
 *       "delete" = "Drupal\cookies\Form\CookiesEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider"
 *     }
 *   },
 *   base_table = "cookies_entity",
 *   translatable = TRUE,
 *   admin_permission = "administer cookies entity entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode"
 *   },
 *   links = {
 *     "add-form" = "/admin/content/cookies_entity/add",
 *     "canonical" = "/cookies_entity/{cookies_entity}",
 *     "edit-form" = "/admin/content/cookies_entity/{cookies_entity}/edit",
 *     "delete-form" = "/admin/content/cookies_entity/{cookies_entity}/delete",
 *     "collection" = "/admin/content/cookies_entity"
 *   },
 *   field_ui_base_route = "entity.cookies_entity.settings"
 * )
 */
class CookiesEntity extends ContentEntityBase implements CookiesEntityInterface
{
    use EntityChangedTrait;
    use EntityPublishedTrait;

    public const NAME = 'name';
    public const CREATED = 'created';
    public const CHANGED = 'changed';
    public const FIELD_INTRODUCTION = 'field_introduction';

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
            ->setDescription((string) new TranslatableMarkup('The name of the Cookies entity entity.'))
            ->setSettings([
                'max_length' => 50,
                'text_processing' => 0,
            ])
            ->setDefaultValue('')
            ->setTranslatable(true)
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
