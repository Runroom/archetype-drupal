<?php

namespace Drupal\hipartners_general\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * An example controller.
 */
class StyleguideController extends ControllerBase {

	/**
	 * {@inheritdoc}
	 */
	public function show() {
		return [
			'#theme' => 'styleguide'
		];
	}

}