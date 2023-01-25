<?php

declare(strict_types=1);

namespace JCIT\i18n\traits\models;

use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\validators\SafeValidator;
use yii\validators\Validator;

trait I18nTrait
{
    public array $i18n = [];

    public function __get($name): mixed
    {
        if (str_starts_with($name, 'i18n[')) {
            $arrayIndex = str_replace(['][', '[', ']', 'i18n.'], ['.', '.', '', ''], $name);
            return ArrayHelper::getValue($this->i18n, $arrayIndex);
        }

        return parent::__get($name);
    }

    public function __set($name, $value): void
    {
        if (str_starts_with($name, 'i18n[')) {
            $arrayIndex = str_replace(['][', '[', ']', 'i18n.'], ['.', '.', '', ''], $name);
            ArrayHelper::setValue($this->i18n, $arrayIndex, $value);
            return;
        }

        parent::__set($name, $value);
    }

    public function getAttributeLabel($attribute): string
    {
        if (str_starts_with($attribute, 'i18n[')) {
            $attributeParts = explode('.', str_replace(['][', '[', ']', 'i18n.'], ['.', '.', '', ''], $attribute));
            return parent::getAttributeLabel($attributeParts[1]);
        }

        return parent::getAttributeLabel($attribute);
    }

    /**
     * Rules that apply to localized attributes, same format as rules()
     * @return array<array|Validator>
     */
    abstract public function i18nRules(): array;

    /**
     * Language options, key is locale value is display name
     * @return array<string, string>
     */
    abstract public function languageOptions(): array;

    /**
     * @return array<array|Validator>
     */
    public function rules(): array
    {
        $result = [
            [['i18n'], SafeValidator::class],
        ];

        foreach ($this->i18nRules() as $rule) {
            $languages = ArrayHelper::remove($rule, 'languages', array_keys($this->languageOptions()));
            if ($rule instanceof Validator) {
                $newAttributes = [];
                foreach ($rule->attributes as $attribute) {
                    foreach ($languages as $language) {
                        $newAttributes[] = 'i18n[' . $language . '][' . $attribute . ']';
                    }
                }
                $rule->attributes = $newAttributes;
            } elseif (is_array($rule) && isset($rule[0], $rule[1])) {
                $newAttributes = [];
                foreach ((array)$rule[0] as $attribute) {
                    foreach ($languages as $language) {
                        $newAttributes[] = 'i18n[' . $language . '][' . $attribute . ']';
                    }
                }
                $rule[0] = $newAttributes;
            } else {
                throw new InvalidConfigException('Invalid validation rule: a rule must specify both attribute names and validator type.');
            }
            $result[] = $rule;
        }

        return $result;
    }
}
