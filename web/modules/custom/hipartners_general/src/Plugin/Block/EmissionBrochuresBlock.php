<?php

namespace Drupal\hipartners_general\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Locale\CountryManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Entity\EntityFormBuilder;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Render\Renderer;
use Drupal\user\PrivateTempStoreFactory;

/**
 * Provides a 'EmissionBrochuresBlock' block.
 *
 * @Block(
 *  id = "emission_brochures_block",
 *  admin_label = @Translation("Emission brochures block"),
 * )
 */
class EmissionBrochuresBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\Core\Locale\CountryManager
   */
  protected $countryManager;

  /**
   * @var \Drupal\Core\Entity\EntityFormBuilder
   */
  protected $entityFormBuilder;

  /**
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * @var \Drupal\user\PrivateTempStoreFactory
   */
  protected $tempStore;

  /**
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  const DISALLOW_COUNTRIES = ['US','CA', 'AU', 'ZA', 'JP'];
//  const SPECIAL_COUNTRIES = ['AF','AS'];
  const DISALLOW = 0;
  const ALLOW = 1;
//  const SPECIAL = 2;

  /**
   * EmissionBrochuresBlock constructor.
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\Core\Locale\CountryManager $country_manager
   */
  public function __construct(
        array $configuration,
        $plugin_id,
        $plugin_definition,
        CountryManager $country_manager,
        EntityFormBuilder $entity_form_builder,
        EntityTypeManager $entity_type_manager,
        Renderer $renderer,
        PrivateTempStoreFactory $temp_store_factory
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->countryManager = $country_manager;
    $this->entityFormBuilder = $entity_form_builder;
    $this->entityTypeManager = $entity_type_manager;
    $this->renderer = $renderer;
    $this->tempStore = $temp_store_factory->get('hipartners_general');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('country_manager'),
      $container->get('entity.form_builder'),
      $container->get('entity_type.manager'),
      $container->get('renderer'),
      $container->get('user.private_tempstore')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    // SELECTS
    $list_country = $this->countryManager->getList();
    $residence_select = [];
    $physically_select = [];

    foreach ($list_country as $key => $country) {
      $residence_select[$key] = array($country->render(),$this::ALLOW);
      $physically_select[$key] = array($country->render(),$this::ALLOW);
    }

    // Residence
    foreach ($residence_select as $key => $item) {
      $country_code = $key;

      foreach ($this::DISALLOW_COUNTRIES as $disallow) {
        if ($country_code == $disallow) {
          $residence_select[$country_code][1] = $this::DISALLOW;
        }
      }

//      foreach ($this::SPECIAL_COUNTRIES as $special) {
//        if ($country_code == $special) {
//          $list_select[$country_code][1] = $this::SPECIAL;
//        }
//      }
    }

    // Physically
    foreach ($physically_select as $key => $item) {
      $country_code = $key;

      foreach ($this::DISALLOW_COUNTRIES as $disallow) {
        if ($country_code == $disallow) {
          $physically_select[$country_code][1] = $this::DISALLOW;
        }
      }
    }

    // SPECIAL FORM
    $message = \Drupal::entityTypeManager()
      ->getStorage('contact_message')
      ->create(array(
        'contact_form' => 'qib_form',
      ));
    $form = $this->entityFormBuilder->getForm($message);

    // CONTENT
    $view_name = 'folletos_de_emision';
    $view = views_embed_view($view_name);
    $content = $this->renderer->render($view);

    return array(
      '#theme' => 'emissionBrochuresBlock',
      '#title' => 'General Meetings Block',
      '#residence' => $residence_select,
      '#physically' => $physically_select,
      '#form' => $form,
      '#content' => $content,
      '#cache' => array(
        'max-age' => 0,
      ),
    );
  }

}
