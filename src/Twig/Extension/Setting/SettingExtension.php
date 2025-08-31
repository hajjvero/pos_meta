<?php

namespace App\Twig\Extension\Setting;

use App\Service\Setting\SettingService;
use Psr\Cache\InvalidArgumentException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SettingExtension extends AbstractExtension
{
    public function __construct(
        private readonly SettingService $settingService,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_setting', $this->getSetting(...)),
            new TwigFunction('has_setting', $this->hasSetting(...)),
        ];
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getSetting(string $key)
    {
        return $this->settingService->getSetting()->get($key);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function hasSetting(string $key): bool
    {
        return $this->settingService->getSetting()->has($key);
    }
}
