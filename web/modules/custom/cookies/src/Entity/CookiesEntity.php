<?php

declare(strict_types=1);

namespace Drupal\cookies\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * @ContentEntityType(
 *   id = "cookies_entity",
 *   label = @Translation("Cookies entity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\cookies\CookiesEntityListBuilder",
 *     "views_data" = "Drupal\cookies\Entity\CookiesEntityViewsData",
 *     "translation" = "Drupal\cookies\CookiesEntityTranslationHandler",
 *     "form" = {
 *       "default" = "Drupal\cookies\Form\CookiesEntityForm",
 *       "add" = "Drupal\cookies\Form\CookiesEntityForm",
 *       "edit" = "Drupal\cookies\Form\CookiesEntityForm",
 *       "delete" = "Drupal\cookies\Form\CookiesEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\cookies\CookiesEntityHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\cookies\CookiesEntityAccessControlHandler",
 *   },
 *   base_table = "cookies_entity",
 *   translatable = TRUE,
 *   admin_permission = "administer cookies entity entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/cookies_entity/{cookies_entity}",
 *     "add-form" = "/admin/structure/cookies_entity/add",
 *     "edit-form" = "/admin/structure/cookies_entity/{cookies_entity}/edit",
 *     "delete-form" = "/admin/structure/cookies_entity/{cookies_entity}/delete",
 *     "collection" = "/admin/structure/cookies_entity",
 *   },
 *   field_ui_base_route = "cookies_entity.settings"
 * )
 */
class CookiesEntity extends ContentEntityBase implements CookiesEntityInterface
{
    use EntityChangedTrait;
    use EntityPublishedTrait;

    public function getName()
    {
        return $this->get('name')->value;
    }

    public function setName($name)
    {
        $this->set('name', $name);

        return $this;
    }

    public function getCreatedTime()
    {
        return $this->get('created')->value;
    }

    public function setCreatedTime($timestamp)
    {
        $this->set('created', $timestamp);

        return $this;
    }

    public static function baseFieldDefinitions(EntityTypeInterface $entity_type)
    {
        $fields = parent::baseFieldDefinitions($entity_type);

        $fields['name'] = BaseFieldDefinition::create('string')
            ->setLabel((string) t('Name'))
            ->setDescription((string) t('The name of the Cookies entity entity.'))
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

        $fields['created'] = BaseFieldDefinition::create('created')
            ->setLabel((string) t('Created'))
            ->setDescription((string) t('The time that the entity was created.'));

        $fields['changed'] = BaseFieldDefinition::create('changed')
            ->setLabel((string) t('Changed'))
            ->setDescription((string) t('The time that the entity was last edited.'));

        return $fields;
    }
}
