import { Controller } from '@hotwired/stimulus';
import IMask from 'imask';

/**
 * NumberMask Stimulus Controller
 * 
 * Applies number formatting masks to input fields using IMask library.
 * Configurable via data attributes from SettingService values.
 */
export default class extends Controller<HTMLInputElement> {
    static values = {
        decimals: Number,
        decimalSeparator: String,
        thousandsSeparator: String,
        min: Number,
        max: Number,
        scale: Number
    };

    declare readonly decimalsValue: number;
    declare readonly decimalSeparatorValue: string;
    declare readonly thousandsSeparatorValue: string;
    declare readonly minValue: number;
    declare readonly maxValue: number;
    declare readonly scaleValue: number;

    declare readonly hasDecimalsValue: boolean;
    declare readonly hasDecimalSeparatorValue: boolean;
    declare readonly hasThousandsSeparatorValue: boolean;
    declare readonly hasMinValue: boolean;
    declare readonly hasMaxValue: boolean;
    declare readonly hasScaleValue: boolean;

    private mask?: any;

    connect() {
        this.initializeMask();
    }

    disconnect() {
        if (this.mask) {
            this.mask.destroy();
        }
    }

    private initializeMask() {
        const decimals = this.hasDecimalsValue ? this.decimalsValue : 2;
        const decimalSeparator = this.hasDecimalSeparatorValue ? this.decimalSeparatorValue : '.';
        const thousandsSeparator = this.hasThousandsSeparatorValue ? this.thousandsSeparatorValue : ',';
        const scale = this.hasScaleValue ? this.scaleValue : decimals;

        // Configure IMask for number formatting
        const maskOptions: any = {
            mask: Number,
            scale: scale, // Number of decimal places
            signed: false, // Allow negative numbers
            thousandsSeparator: thousandsSeparator,
            padFractionalZeros: false, // Don't pad with zeros
            normalizeZeros: true, // Remove leading zeros
            radix: decimalSeparator, // Decimal separator
            mapToRadix: ['.', ','], // Characters that map to decimal separator
        };

        // Add min/max constraints if provided
        if (this.hasMinValue) {
            maskOptions.min = this.minValue;
        }
        if (this.hasMaxValue) {
            maskOptions.max = this.maxValue;
        }

        // Create and apply the mask
        this.mask = IMask(this.element, maskOptions);

        // Handle form submission - ensure proper number format
        const form = this.element.closest('form');
        if (form) {
            form.addEventListener('submit', this.handleFormSubmit.bind(this));
        }
    }

    private handleFormSubmit(event: Event) {
        if (this.mask) {
            // Convert masked value back to standard number format for form submission
            const unmaskedValue = this.mask.unmaskedValue;
            if (unmaskedValue) {
                // Set the raw numeric value for form submission
                this.element.value = unmaskedValue;
            }
        }
    }

    // Method to get the numeric value
    get numericValue(): number {
        return this.mask ? parseFloat(this.mask.unmaskedValue) || 0 : 0;
    }

    // Method to set value programmatically
    setValue(value: number | string) {
        if (this.mask) {
            this.mask.value = String(value);
        }
    }

    // Method to update mask configuration
    updateMaskConfig(options: any) {
        if (this.mask) {
            this.mask.updateOptions(options);
        }
    }
}