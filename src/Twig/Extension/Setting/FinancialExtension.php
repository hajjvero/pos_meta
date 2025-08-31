<?php

namespace App\Twig\Extension\Setting;

use App\Service\Setting\SettingService;
use Psr\Cache\InvalidArgumentException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FinancialExtension extends AbstractExtension
{
    public function __construct(
        private readonly SettingService $settingService,
    ) {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('number_format', $this->numberFormat(...)),
            new TwigFilter('price_format', $this->priceFormat(...)),
        ];
    }

    /**
     * @throws InvalidArgumentException
     */
    public function numberFormat(float|string $number): string
    {
        $setting = $this->settingService->getSetting();

        $decimals = $setting->get('decimals');
        $decimal_separator = $setting->get('decimal_separator');
        $thousands_separator = $setting->get('thousands_separator');

        return number_format($number, $decimals, $decimal_separator, $thousands_separator);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function priceFormat(float|string $price): string
    {
        $setting = $this->settingService->getSetting();

        $currency = $setting->get('currency');
        $currency_position = $setting->get('currency_position');

        return sprintf($currency_position === 'before' ? '%s %s' : '%2$s %1$s', $currency, $this->numberFormat($price));
    }
}
