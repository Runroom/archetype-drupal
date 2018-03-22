<?php

namespace Drupal\hipartners_general\TwigExtension;

use Drupal\block\Entity\Block;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class GetBlockById.
 *
 * @package Drupal\hipartners_general\TwigExtension
 */
class GetBlockById extends \Twig_Extension {
  private $entity_type_manager;

  /**
   * Constructor.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entity_type_manager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   * This function must return the name of the extension. It must be unique.
   */
  public function getName() {
    return 'get_block_display_by_id';
  }

  /**
   * In this function we can declare the extension function
   */
  public function getFunctions() {
    return array(
      new \Twig_SimpleFunction('get_block_display_by_id',
        array($this, 'get_block_display_by_id'),
        array('is_safe' => array('html'))
      ),
    );
  }

  /**
   * The php function to load a given block
   */
  public function get_block_display_by_id($block_id) {
    $block = Block::load($block_id);
    return $this->entity_type_manager->getViewBuilder('block')->view($block);
  }
}