<?php

namespace App\EventSubscriber\Setting;

use App\Service\Setting\SettingService;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class SettingSubscriber implements EventSubscriberInterface
{
    private bool $settingsLoaded = false;

    public function __construct(
        private readonly SettingService $settingService
    )
    {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 0],
        ];
    }

    /**
     * @throws InvalidArgumentException
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest() || $this->settingsLoaded) {
            return;
        }

        // Preload settings into cache on first request
        $setting = $this->settingService->getSetting();
        $this->settingsLoaded = true;

        // update default locale
        $event->getRequest()->setLocale($setting->get('language'));
    }
}
