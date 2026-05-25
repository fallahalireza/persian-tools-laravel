<?php

namespace FallahAlireza\PersianTools\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

abstract class BaseRule implements ValidationRule
{
    /**
     * Create rule instance from validator string parameters.
     * Subclasses should override this when they accept parameters.
     *
     * @param  array<int, string>  $parameters
     */
    public static function fromParameters(array $parameters): static
    {
        // @phpstan-ignore new.static
        return new static();
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $this->passes($attribute, $value)) {
            $fail($this->message($attribute));
        }
    }

    /**
     * Check if the value passes the rule.
     */
    abstract public function passes(string $attribute, mixed $value): bool;

    /**
     * Get the validation error message.
     */
    public function message(string $attribute = ''): string
    {
        $key = $this->translationKey();

        return trans("persian-tools::validation.{$key}", ['attribute' => $attribute]);
    }

    /**
     * Translation key for the rule (defaults to snake_case class name).
     */
    protected function translationKey(): string
    {
        $class = class_basename(static::class);

        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $class) ?? $class);
    }
}
