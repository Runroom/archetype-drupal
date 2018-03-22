<?php

namespace Drupal\hipartners_general\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\hipartners_general\BlockListService;
use Symfony\Component\DependencyInjection\ContainerInterface;
/**
 * Provides a 'GeneralMeetingsBlock' block.
 *
 * @Block(
 *  id = "quarterly_results_block",
 *  admin_label = @Translation("Quarterly Results Block"),
 * )
 */
class QuarterlyResultsBLock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var BlockListService
   */
  protected $blockListService;
  /**
   * Constructs a new GeneralMeetingsBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param string $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    BlockListService $block_service
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->blockListService = $block_service;
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
      $container->get('hipartners_general.block_list')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $this->blockListService->setNodeType('quarterly_results');
    $node = $this->blockListService->result();
    $list = $this->blockListService->buildselect($node);

    return array(
      '#theme' => 'generalMeetingsBlock',
      '#title' => 'Quarterly Results Block',
      '#list' => $list,
      '#node' => $node,
      '#cache' => array(
        'max-age' => 0,
      ),
    );
  }

}
