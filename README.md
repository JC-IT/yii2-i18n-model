# Helpers to work with model translations for Yii2

[![codecov](https://codecov.io/gh/jc-it/yii2-i18n-model/branch/master/graph/badge.svg)](https://codecov.io/gh/jc-it/yii2-i18n-model)
[![Continous integration](https://github.com/jc-it/yii2-i18n-model/actions/workflows/ci.yaml/badge.svg)](https://github.com/jc-it/yii2-i18n-model/actions/workflows/ci.yaml)
![Packagist Total Downloads](https://img.shields.io/packagist/dt/jc-it/yii2-i18n-model)
![Packagist Monthly Downloads](https://img.shields.io/packagist/dm/jc-it/yii2-i18n-model)
![GitHub tag (latest by date)](https://img.shields.io/github/v/tag/jc-it/yii2-i18n-model)
![Packagist Version](https://img.shields.io/packagist/v/jc-it/yii2-i18n-model)

This extension provides a package that implements some traits and behaviors to work with model attribute translations.

```bash
$ composer require jc-it/yii2-i18n-model
```

or add

```
"jc-it/yii2-i18n-model": "^<latest version>"
```

to the `require` section of your `composer.json` file.

## Configuration
### Active Record model
Add behavior to you AR model and make sure it has an `i18n` attribute:
```php
public function behaviors(): array
{
    return [
        \JCIT\i18n\behaviors\I18nBehavior::class => [
            'class' => \JCIT\i18n\behaviors\I18nBehavior::class,
            'attributes' => [
                '<attribute>',
            ],       
        ]       
    ]; 
}
```

In the rules, just define the rules as you would normally do:
```php
public function rules(): array
{
    return [
        [['<attribute>'], \yii\validators\RequiredValidator::class],
        [['<attribute>'], \yii\validators\StringValidator::class],
    ]; 
}
```

In order to set properties or save it first set the locale and then save:
```php
$model = new ClassWithI18nBehavior();
$model->locale = 'en-US';
$model-><attribute> = 'value en';
$model->save();

$model->locale = 'nl-NL';
$model-><attribute> = 'value nl';
$model->save();
```

Fetching values is done automatically to the locale set to the model:
```php
$model = ClassWithI18nBehavior::findOne();
$model->locale = 'en-US';
echo $model-><attribute>; // 'value en'

$model->locale = 'nl-NL';
echo $model-><attribute>; // 'value nl'
```

### Form model
Add the trait to the form model:
```php
class FormModel extends \yii\base\Model
{
    use \JCIT\i18n\traits\models\I18nTrait;
}
```

On rendering the form attributes, the attribute name should be like: `i18n[<locale>][<attribute>]`.

## TODO
- Fix PHPStan, re-add to `captainhook.json`
  - ```      
    {
        "action": "vendor/bin/phpstan",
        "options": [],
        "conditions": []
    },
    ```
- Add tests

## Credits
- [Joey Claessen](https://github.com/joester89)

## License

The MIT License (MIT). Please see [LICENSE](https://github.com/jc-it/yii2-i18n-model/blob/master/LICENSE) for more information.
