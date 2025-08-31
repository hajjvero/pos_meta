<?php

namespace App\Dto\Setting;

use App\Entity\Option\Option;

class Setting
{
    /**
     * @param Option[] $options
     */
    public function __construct(array $options)
    {
        foreach ($options as $option) {
            $key = $this->convertKeyToProperty($option->getOptionKey());
            $this->{$key} = $this->convertValue($option->getOptionValue());
        }
    }

    private function convertKeyToProperty(string $key): string
    {
        return str_replace('_', '', lcfirst(ucwords($key, '_')));
    }

    private function convertValue(?string $value): mixed
    {
        if ($value === null) {
            return null;
        }

        // Try to convert to appropriate type
        if (is_numeric($value)) {
            return str_contains($value, '.') ? (float) $value : (int) $value;
        }

        if (in_array(strtolower($value), ['true', 'false'])) {
            return strtolower($value) === 'true';
        }

        return $value;
    }

    public function get(string $key): mixed
    {
        $property = $this->convertKeyToProperty($key);
        return $this->{$property} ?? null;
    }

    public function has(string $key): bool
    {
        $property = $this->convertKeyToProperty($key);
        return property_exists($this, $property);
    }
}
