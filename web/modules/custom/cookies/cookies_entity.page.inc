<?php

use Drupal\Core\Render\Element;

function template_preprocess_cookies_entity(array &$variables)
{
    $cookies_entity = $variables['elements']['#cookies_entity'];

    foreach (Element::children($variables['elements']) as $key) {
        $variables['content'][$key] = $variables['elements'][$key];
    }
}
