<?php

namespace App\DataFixtures;

use App\Entity\Option\Option;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OptionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $options = $this->getDefaultOptions();

        foreach ($options as $key => $value) {
            $this->createOption($manager, $key, $value);
        }

        $manager->flush();
    }

    private function getDefaultOptions(): array
    {
        return [
            // General Settings
            'date_format' => 'YYYY-MM-dd',
            'time_format' => 'HH:mm',
            'time_zone' => 'Africa/Casablanca',
            'language' => 'en',

            // Company Settings
            'company_name' => 'POS Meta',
            'company_email' => '',
            'company_phone' => '',
            'company_fax' => '',
            'company_address' => '',
            'company_logo' => '',
            'company_logo_dark' => '',

            // Financial Settings
            'currency' => 'DH',
            'currency_position' => 'before',
            'decimals' => '2',
            'decimal_separator' => '.',
            'thousands_separator' => ',',

            // Order Settings
            'order_code_format' => 'ORD-{YYYY}-{MM}-{DD}-{ID}',
            'order_default_payment_method' => 'cash',
            'order_is_active_ticket_model' => 'true',
            'order_auto_print_receipt' => 'false',

            // Receipt Settings
            'receipt_header_text' => 'Thank you for your purchase!',
            'receipt_footer_text' => 'Please come again!',
            'receipt_show_logo' => 'true',
            'receipt_paper_size' => '80mm',
            'receipt_show_barcode' => 'true',

            // Product Settings
            'product_is_enable_reference' => 'false',
            'product_is_disable_sku' => 'false',
            'product_is_enable_description' => 'false',
            'product_is_enable_cost_price' => 'false',
            /*'product_enable_stock_tracking' => 'false',
            'product_low_stock_threshold' => '10',*/
            'product_enable_categories' => 'true',
            'product_enable_images' => 'false',
            /*'product_max_images' => '5',*/

            // Customer Settings
            /*'customer_enable_registration' => 'true',
            'customer_require_email' => 'false',
            'customer_require_phone' => 'true',
            'customer_enable_loyalty_points' => 'false',
            'customer_points_per_dollar' => '1',
            'customer_enable_discount' => 'true',*/

            // Inventory Settings
            /*'inventory_enable_alerts' => 'true',
            'inventory_alert_threshold' => '5',
            'inventory_enable_expiry_tracking' => 'false',
            'inventory_auto_deduct_stock' => 'true',*/

            // Notification Settings
            /*'email_notifications' => 'true',
            'sms_notifications' => 'false',
            'low_stock_notifications' => 'true',
            'daily_sales_report' => 'false',
            'weekly_sales_report' => 'true',*/

            // System Settings
            /*'backup_frequency' => 'daily',
            'enable_api' => 'true',
            'api_rate_limit' => '1000',
            'enable_webhook' => 'false',
            'webhook_url' => '',
            'max_file_upload_size' => '10',*/
        ];
    }

    private function createOption(ObjectManager $manager, string $key, string $value): void
    {
        $option = new Option();
        $option->setOptionKey($key);
        $option->setOptionValue($value);
        $manager->persist($option);
    }
}
