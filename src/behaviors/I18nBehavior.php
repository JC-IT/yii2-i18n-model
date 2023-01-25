<?php

declare(strict_types=1);

namespace JCIT\i18n\behaviors;

use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\base\Model;

class I18nBehavior extends Behavior
{
    public array $attributes = [];
    public string $locale;
    public string $translationAttribute = 'i18n';

    public function __get($name): mixed
    {
        if (in_array($name, $this->attributes)) {
            return $this->owner->{$this->translationAttribute}[$this->locale][$name] ?? null;
        }

        return parent::__get($name);
    }

    public function __set($name, $value): void
    {
        if (in_array($name, $this->attributes)) {
            $i18n = $this->owner->{$this->translationAttribute};
            $i18n[$this->locale][$name] = $value;
            $this->owner->{$this->translationAttribute} = $i18n;
            return;
        }

        parent::__set($name, $value);
    }

    public function attach($owner)
    {
        if (!isset($this->locale)) {
            $this->locale = \Yii::$app->language;
        }

        if (!$owner instanceof Model) {
            throw new InvalidConfigException(self::class . ' can only be attached to models.');
        }

        parent::attach($owner);
    }

    public function canGetProperty($name, $checkVars = true): bool
    {
        return in_array($name, $this->attributes) || parent::canGetProperty($name, $checkVars);
    }

    public function canSetProperty($name, $checkVars = true): bool
    {
        return in_array($name, $this->attributes) || parent::canSetProperty($name, $checkVars);
    }
}
