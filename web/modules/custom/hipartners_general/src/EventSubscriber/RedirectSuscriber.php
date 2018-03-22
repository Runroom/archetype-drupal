<?php

namespace Drupal\hipartners_general\EventSubscriber;

use Drupal\Core\Session\AccountInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Drupal\language\ConfigurableLanguageManager;
/**
 * Class RedirectSuscriber.
 *
 * @package Drupal\hipartners_general
 */
class RedirectSuscriber implements EventSubscriberInterface {

  private $accountService;
  private $languageManager;

  /**
   * Constructor.
   */
  public function __construct(
    AccountInterface $accountService,
    ConfigurableLanguageManager $language_manager
  ) {
    $this->accountService = $accountService;
    $this->languageManager = $language_manager;
  }

  /**
   * {@inheritdoc}
   */
  static function getSubscribedEvents() {
    $events[KernelEvents::RESPONSE][] = ['redirectContent'];
    return $events;
  }

  /**
   * Redirect request for every content type without detail page.
   *
   * @param RedirectResponse $event
   */
  public function redirectContent(FilterResponseEvent $event) {
    $request = $event->getRequest();

    // Redirect all the anonymous users that want to access to certain detail pages (nodes).
    if (!empty($request->attributes->get('node'))) {

      if ((
        $request->attributes->get('node')->getType() === 'regulatory_announcement' ||
        $request->attributes->get('node')->getType() === 'annual_report' ||
        $request->attributes->get('node')->getType() === 'annual_governance_report' ||
        $request->attributes->get('node')->getType() === 'annual_remuneration_report' ||
        $request->attributes->get('node')->getType() === 'presentation' ) &&
        !$this->accountService->isAuthenticated()
      ) {
        $this->redirectHome($event);
      }
    }

    // Redirect all the anonymous users that want to access to styleguide.
    if (
      $request->attributes->get('_route') == 'hipartners_general.styleguide' &&
      !$this->accountService->isAuthenticated()
    ) {
      $this->redirectHome($event);
    }
  }

	/**
	 * Redirect to Home Page
	 */
  private function redirectHome($event) {
    $langId = $this->languageManager->getCurrentLanguage()->getId();
    $response = new RedirectResponse('/' . $langId);
    $event->setResponse($response);
  }

}
