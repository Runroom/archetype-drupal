<?php

namespace Drupal\hipartners_general\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Routing\CurrentRouteMatch;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\views\Views;

/**
 * Provides a 'RelatedHotelsBlock' block.
 *
 * @Block(
 *  id = "related_hotels_block",
 *  admin_label = @Translation("Related hotels block"),
 * )
 */
class RelatedHotelsBlock extends BlockBase implements ContainerFactoryPluginInterface {
  const HOTEL_LIST_NODE = 4;

  protected $entityTypeManager;
  protected $currentRoute;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    EntityTypeManager $entity_type_manager,
    CurrentRouteMatch $current_route
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->currentRoute = $current_route;
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
      $container->get('entity_type.manager'),
      $container->get('current_route_match')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $node = $this->currentRoute->getParameter('node');

    if ($node) {
      $nid = $node->id();
      $siblings = $this->getSiblingNodes($nid);

      return array(
        '#theme' => 'relatedHotelsBlock',
        '#title' => 'Hello',
        '#previous' => isset($siblings['previous']) ? $siblings['previous'] : NULL,
        '#next' => isset($siblings['next']) ? $siblings['next'] : NULL,
        '#cache' => array(
          'max-age' => 0,
        ),
      );
    }
  }

  private function getSiblingNodes($nid) {
    $hotelNode = $this->entityTypeManager->getStorage('node')->load(self::HOTEL_LIST_NODE);
    $hotels = $hotelNode->get('field_hotels')->getValue();

    for ($index = 0; $index < count($hotels); $index++) {
      if ($hotels[$index]['target_id'] == $nid) {
        return $this->getPrevNext($hotels, $index);
      }
    }
  }

  private function getPrevNext($results, $position) {
    $viewBuilder = $this->entityTypeManager->getViewBuilder('node');
    $nodeLoader = $this->entityTypeManager->getStorage('node');

    $previous = $position - 1;
    if ($previous < 0) {
      $previous = count($results) - 1;
    }

    $next = $position + 1;
    if ($next > count($results) - 1) {
      $next = 0;
    }

    $previous = $nodeLoader->load($results[$previous]['target_id']);
    $next = $nodeLoader->load($results[$next]['target_id']);

    return array(
      'previous' => $viewBuilder->view($previous, 'teaser_mini'),
      'next' => $viewBuilder->view($next, 'teaser_mini'),
    );
  }
}
