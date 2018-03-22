<?php

namespace Drupal\redirect_by_ip\StackMiddleware;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\redirect_by_ip\Services\FreeGeoIpLocatorService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Performs a custom task.
 */
class RedirectByCountry implements HttpKernelInterface {

  // Spain 80.28.210.217
  // Germany 85.181.71.177
  // France 80.215.39.40

  const INTERNATIONAL_LANG = 'en';

  /**
   * The wrapped HTTP kernel.
   *
   * @var \Symfony\Component\HttpKernel\HttpKernelInterface
   */
  protected $httpKernel;
  protected $languageManager;
  protected $configFactory;
  protected $locatorService;
  protected $entityTypeManager;

  /**
   * Creates a HTTP middleware handler.
   *
   * @param \Symfony\Component\HttpKernel\HttpKernelInterface $kernel
   *   The HTTP kernel.
   */
  public function __construct(
    HttpKernelInterface $kernel,
    LanguageManagerInterface $language_manager,
    ConfigFactoryInterface $config_factory,
    FreeGeoIpLocatorService $locator_service,
    EntityTypeManagerInterface $entity_type_manager
  ) {
    $this->locatorService = $locator_service;
    $this->httpKernel = $kernel;
    $this->configFactory = $config_factory;
    $this->languageManager = $language_manager;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = TRUE) {
    $uri = $request->getRequestUri();
    $front_page = $this->configFactory->get('system.site')->get('page.front');

    if ($uri === '/' ||  $uri === $front_page) {

      $clientIp = $request->getClientIp();
      $country = $this->locatorService->locate($clientIp);

      if ($country !== NULL) {
        $lang = $this->getLangToRedirect($country);
        $response = new RedirectResponse("/" . $lang);
        return $response->send();
      }

    }
    return $this->httpKernel->handle($request, $type, $catch);
  }

  public function getLangToRedirect($country) {
    if ($country === 'ES') {
      return 'es';
    }
    else {
      return $this::INTERNATIONAL_LANG;
    }
  }
}