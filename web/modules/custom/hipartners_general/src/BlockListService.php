<?php

namespace Drupal\hipartners_general;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Entity\Query\QueryFactory;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\language\ConfigurableLanguageManager;
use Drupal\Core\Path\CurrentPathStack;
use Drupal\Core\Path\AliasManager;
use \Drupal\Core\Url;

/**
 * Class BlockListService.
 */
class BlockListService {

  const DEFAULT_GETVALUE = 't';
  const DEFAULT_TYPE = 'general_meetings';
  /**
   * Drupal\Core\Entity\EntityTypeManager definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;
  /**
   * Drupal\Core\Entity\Query\QueryFactory definition.
   *
   * @var \Drupal\Core\Entity\Query\QueryFactory
   */
  protected $entityQuery;
  /**
   * Symfony\Component\HttpFoundation\RequestStack definition.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;
  /**
   * Drupal\language\ConfigurableLanguageManager definition.
   *
   * @var \Drupal\language\ConfigurableLanguageManager
   */
  protected $languageManager;
  /**
   * Drupal\Core\Path\CurrentPathStack definition.
   *
   * @var \Drupal\Core\Path\CurrentPathStack
   */
  protected $pathCurrent;
  /**
   * Drupal\Core\Path\AliasManager definition.
   *
   * @var \Drupal\Core\Path\AliasManager
   */
  protected $pathAliasManager;
  /**
   * @var string
   */
  protected $currentLanguage;

  /**
   * @var string
   */
  protected $nodeType;

  /**
   * Constructs a new BlockListService object.
   */
  public function __construct(EntityTypeManager $entity_type_manager, QueryFactory $entity_query, RequestStack $request_stack, ConfigurableLanguageManager $language_manager, CurrentPathStack $path_current, AliasManager $path_alias_manager) {
    $this->entityTypeManager = $entity_type_manager;
    $this->entityQuery = $entity_query;
    $this->requestStack = $request_stack;
    $this->languageManager = $language_manager;
    $this->pathCurrent = $path_current;
    $this->pathAliasManager = $path_alias_manager;
    $this->currentLanguage = $this->languageManager->getCurrentLanguage()->getId();
    $this->nodeType = self::DEFAULT_TYPE;
  }

  public function setNodeType($node_type) {
    $this->nodeType = $node_type;
  }

  public function buildselect($loaded_node) {
    $nodes = $this->getAllNodesObject();
    $list = [];
    $current_path = $this->pathCurrent->getPath();
    $current_alias = $this->pathAliasManager->getAliasByPath($current_path);
    if ($nodes) {
      foreach ($nodes as $node) {

        if ($node->hasTranslation($this->currentLanguage)) {
          $node = $node->getTranslation($this->currentLanguage);
        }

        $node_id = $node->id();
        $node_title = $node->get('title')->getValue()[0]['value'];
        $node_alias = $this->pathAliasManager->getAliasByPath('/node/' . $node_id);
        $node_alias = trim($node_alias, '/');
        $list[$node_title]['url'] = $current_alias . '?' . self::DEFAULT_GETVALUE . '=' . $node_alias;
        $list[$node_title]['title'] = $node_title;
        $list[$node_title]['active'] = FALSE;

        if (!empty($loaded_node)) {
          if ($node_id === $loaded_node['#node']->id()) {
            $list[$node_title]['active'] = TRUE;
          }
        }

      }
    }

    return $list;
  }

  /**
   * @return array|bool
   */
  public function result() {
    $request = $this->requestStack->getCurrentRequest();
    $alias_param = $request->get('t');
    $alias = $this->pathAliasManager->getPathByAlias('/' . $alias_param);
    $node_id = '';

    if (Url::fromUri("internal:" . $alias)->isRouted()) {
      $params = Url::fromUri("internal:" . $alias)->getRouteParameters();
      if (!empty($params)) {
        $entity_type = key($params);
        $node_id = $params[$entity_type];
      }
    }
    $allIds = $this->getAllNodesId();
    $lastId = reset($allIds);

    if (empty($node_id) && $lastId) {
      $node = $this->getNodeById($lastId, $lastId);
    }
    elseif ($node_id && $lastId) {
      $node = $this->getNodeById($node_id, $lastId);
    }
    else {
      $node = FALSE;
    }

    return $node;
  }

  /**
   * @return array|int
   */
  private function getAllNodesId() {
    $query = $this->entityQuery->get('node');
    $query->condition('type', $this->nodeType);
    $query->sort('created', 'ASC');
    return $query->execute();
  }

  /**
   * @return \Drupal\Core\Entity\EntityInterface[]|bool
   */
  private function getAllNodesObject() {
    $entity_storage = $this->entityTypeManager->getStorage('node');
    $meetings = $this->getAllNodesId();

    if (!empty($meetings)) {
      return $entity_storage->loadMultiple($meetings);
    }

    return FALSE;
  }

  /**
   * @param $id
   * @return array|bool
   */
  private function getNodeById($id, $lastId) {
    $entity_storage = $this->entityTypeManager->getStorage('node');
    $viewBuilder = $this->entityTypeManager->getViewBuilder('node');
    $node = $entity_storage->load($id);

    if (!isset($node)) {
      $node = $entity_storage->load($lastId);
      if (!isset($node)) {
        return FALSE;
      }
    }
    return $viewBuilder->view($node, 'full');
  }
}
