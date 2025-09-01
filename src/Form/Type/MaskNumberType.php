<?php

namespace App\Form\Type;

use App\Dto\Setting\Setting;
use App\Service\Setting\SettingService;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * MaskNumberType
 *
 * Custom form type that extends TextType to provide number masking functionality
 * using IMask library via Stimulus controller. Automatically integrates with
 * SettingService to get formatting configuration.
 *
 * @author Hamza Hajjaji <hajjvero@gmail.com>
 */
class MaskNumberType extends AbstractType
{
    private Setting $setting;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(private readonly SettingService $settingService)
    {
        $this->setting = $this->settingService->getSetting();
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        parent::buildView($view, $form, $options);

        // Get formatting settings from SettingService
        $decimals = $options['decimals'] ?? $this->setting->get('decimals');
        $decimalSeparator = $options['decimal_separator'] ?? $this->setting->get('decimal_separator');
        $thousandsSeparator = $options['thousands_separator'] ?? $this->setting->get('thousands_separator');

        // Set up Stimulus controller attributes
        $view->vars['attr'] = array_merge($view->vars['attr'], [
            'data-controller' => 'number-mask',
            'data-number-mask-decimals-value' => $decimals,
            'data-number-mask-decimal-separator-value' => $decimalSeparator,
            'data-number-mask-thousands-separator-value' => $thousandsSeparator,
        ]);

        // Add scale value if provided
        if (isset($options['scale'])) {
            $view->vars['attr']['data-number-mask-scale-value'] = $options['scale'];
        }

        // Add min/max values if provided
        if (isset($options['min'])) {
            $view->vars['attr']['data-number-mask-min-value'] = $options['min'];
        }

        if (isset($options['max'])) {
            $view->vars['attr']['data-number-mask-max-value'] = $options['max'];
        }

        // Ensure input type is text for proper masking
        $view->vars['type'] = 'text';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'decimals' => null, // Will use SettingService default if null
            'decimal_separator' => null, // Will use SettingService default if null
            'thousands_separator' => null, // Will use SettingService default if null
            'scale' => null, // Number of decimal places (overrides decimals for this field)
            'min' => null, // Minimum value
            'max' => null, // Maximum value
        ]);

        $resolver->setAllowedTypes('decimals', ['null', 'int']);
        $resolver->setAllowedTypes('decimal_separator', ['null', 'string']);
        $resolver->setAllowedTypes('thousands_separator', ['null', 'string']);
        $resolver->setAllowedTypes('scale', ['null', 'int']);
        $resolver->setAllowedTypes('min', ['null', 'int', 'float']);
        $resolver->setAllowedTypes('max', ['null', 'int', 'float']);
    }

    public function getParent(): string
    {
        return TextType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'mask_number';
    }
}
