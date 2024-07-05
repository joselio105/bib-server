<?php

namespace plugse\server\core\app\validation;

use plugse\server\core\app\entities\Entity;
use plugse\server\core\helpers\File;

class Validations
{

    private const ExceptionsNamespace = 'plugse\\server\\core\\app\\validation\\exceptions\\';

    public static function validate(Entity $entity)
    {
        $validationSchemas = $entity->getValidation();
        $attributes = $entity->getAttributes();
        
        $lengthVAlidations = [
            ValidationTypes::MUST_HAVE_LENGTH_EQUALS_TO->value,
            ValidationTypes::MUST_HAVE_LENGTH_GREATHER_THAN->value,
            ValidationTypes::MUST_HAVE_LENGTH_SMALLER_THAN->value,
        ];

        foreach ($validationSchemas as $name => $schemas) {
            for ($i = 0; $i < count($schemas); $i++) {
                $schema = $schemas[$i]->value;
                
                if (
                    in_array($schema, $lengthVAlidations) and
                    !is_int($schema)
                ) {
                    self::$schema($attributes, $name, $schemas[$i + 1]);
                    $i++;
                } elseif (
                    !in_array($schema, $lengthVAlidations) and
                    !is_int($schema)
                ) {
                    self::$schema($attributes, $name);
                }
            }
        }
    }

    public static function getValidations(string $entityName): array
    {
        $entityName = ucfirst(strtolower($entityName));
        $validationsFile = "src/app/validations/{$entityName}Validation.php";

        try {
            return File::getFileData($validationsFile);
        } catch (\Throwable $th) {
            return [];
        }
    }

    public static function isId(string $field): bool
    {
        return substr($field, -2) === 'Id';
    }

    public static function isBoolean(string $field): bool
    {
        return substr($field, 0, 2) === 'is';
    }

    public static function isRequired(array $attributes, string $name): void
    {
        $exceptionName = self::ExceptionsNamespace . ucfirst(ValidationTypes::IS_REQUIRED->value) . 'Error';

        if (!key_exists($name, $attributes) or is_null($attributes[$name])) {
            throw new $exceptionName($name);
        }
    }

    public static function mustBeBool(array $attributes, string $name): void
    {
        $exceptionName = self::ExceptionsNamespace . ucfirst(ValidationTypes::MUST_BE_BOOL->value) . 'Error';

        if (key_exists($name, $attributes) and !is_bool($attributes[$name]) and !is_null($attributes[$name])) {
            throw new $exceptionName($name);
        }
    }

    public static function mustBeInt(array $attributes, string $name): void
    {
        if (!key_exists($name, $attributes)) {
            return;
        }

        $exceptionName = self::ExceptionsNamespace . ucfirst(ValidationTypes::MUST_BE_INT->value) . 'Error';

        $isInt = preg_match('/\d+/', $attributes[$name]) !== false;
        if (!$isInt) {
            throw new $exceptionName($name);
        }
    }

    public static function mustBeString(array $attributes, string $name): void
    {
        $exceptionName = self::ExceptionsNamespace . ucfirst(ValidationTypes::MUST_BE_STRING->value) . 'Error';

        if (key_exists($name, $attributes) and (!is_string($attributes[$name])) and !is_null($attributes[$name])) {
            throw new $exceptionName($name);
        }
    }

    public static function mustBePassword(array $attributes, $name): void
    {
        self::mustHaveLengthGreatherThan($attributes, $name, 8);
        self::mustHaveNumbers($attributes, $name);
        self::mustHaveSpecialChars($attributes, $name);
        self::mustHaveLowerCaseChars($attributes, $name);
        self::mustHaveUpperCaseChars($attributes, $name);
    }

    public static function mustBePhone(array $attributes, string $name): void
    {
        if (!key_exists($name, $attributes)) {
            return;
        }

        $exceptionName = self::ExceptionsNamespace . ucfirst(ValidationTypes::MUST_BE_PHONE->value) . 'Error';
        $value = $attributes[$name];

        $matches = [];
        if (is_string($value)) {
            $pattern = "/\+?(\d{2,3})?\s?(\d{2,3})?\s?(\d{4,5})\s?\-?(\d{4})$/";
            preg_match($pattern, $value, $matches);
        }

        if (empty($matches)) {
            throw new $exceptionName("{$name} - {$value}");
        }
    }

    public static function mustBeEmail(array $attributes, string $name): void
    {
        if (!key_exists($name, $attributes)) {
            return;
        }

        $exceptionName = self::ExceptionsNamespace . ucfirst(ValidationTypes::MUST_BE_EMAIL->value) . 'Error';
        $value = $attributes[$name];

        $matches = [];
        if (is_string($value)) {
            $pattern = "/(.+)\@{1}(.+\..+)/";
            preg_match($pattern, $value, $matches);
        }

        if (empty($matches)) {
            throw new $exceptionName("{$name} - {$value}");
        }
    }

    public static function mustBeUrl(array $attributes, string $name): void
    {
        if (!key_exists($name, $attributes)) {
            return;
        }

        $exceptionName = self::ExceptionsNamespace . ucfirst(ValidationTypes::MUST_BE_URL->value) . 'Error';
        $value = $attributes[$name];

        $matches = [];
        if (is_string($value)) {
            $pattern = '/[https|http]:\\/\\/(?:www\\.)?[-a-zA-Z0-9@:%._\\+~#=]{1,256}\\.[a-zA-Z0-9()]{1,6}\\b(?:[-a-zA-Z0-9()@:%_\\+.~#?&\\/=]*)/';
            preg_match($pattern, $value, $matches);
        }

        if (empty($matches)) {
            throw new $exceptionName("{$name} - {$value}");
        }
    }

    public static function mustBeUrlLattes(array $attributes, string $name): void
    {
        if (!key_exists($name, $attributes)) {
            return;
        }

        $exceptionName = self::ExceptionsNamespace . ucfirst(ValidationTypes::MUST_BE_URL_LATTES->value) . 'Error';
        $value = $attributes[$name];

        $matches = [];
        if (is_string($value)) {
            $pattern = "/(http\:\/\/lattes.cnpq.br\/{1})(\d{16})/";
            preg_match($pattern, $value, $matches);
        }

        if (empty($matches)) {
            throw new $exceptionName("{$name} - {$value}");
        }
    }

    public static function mustBeUrlRepoufsc(array $attributes, string $name): void
    {
        if (!key_exists($name, $attributes)) {
            return;
        }

        $exceptionName = self::ExceptionsNamespace . ucfirst(ValidationTypes::MUST_BE_URL_REPOUFSC->value) . 'Error';
        $value = $attributes[$name];

        $matches = [];
        if (is_string($value)) {
            $pattern = "/(https\:\/\/repositorio.ufsc.br\/handle\/(\d{9})\/{1})\(d{6})\/";
            preg_match($pattern, $value, $matches);
        }

        if (empty($matches)) {
            throw new $exceptionName("{$name} - {$value}");
        }
    }

    public static function mustBeDatetime(array $attributes, string $name): void
    {
        if (!key_exists($name, $attributes)) {
            return;
        }

        $exceptionName = self::ExceptionsNamespace . ucfirst(ValidationTypes::MUST_BE_DATETIME->value) . 'Error';
        $value = $attributes[$name];

        $matches = [];
        if (is_string($value)) {
            $pattern = "/(1|2{1})(\d{3})\-{1}(0|1{1})(\d{1})\-{1}(\d{2})\s\d{2}:\d{2}:\d{2}/";
            preg_match($pattern, $value, $matches);
        }

        if (empty($matches)) {
            throw new $exceptionName("{$name} - {$value}");
        }
    }

    public static function mustBeDate(array $attributes, string $name): void
    {
        if (!key_exists($name, $attributes)) {
            return;
        }

        $exceptionName = self::ExceptionsNamespace . ucfirst(ValidationTypes::MUST_BE_DATE->value) . 'Error';
        $value = $attributes[$name];

        $matches = [];
        if (is_string($value)) {
            $pattern = "/(1|2{1})(\d{3})\-{1}(0|1{1})(\d{1})\-{1}(\d{2})/";
            preg_match($pattern, $value, $matches);
        }

        if (empty($matches)) {
            throw new $exceptionName("{$name} - {$value}");
        }
    }

    public static function mustBeHour(array $attributes, string $name): void
    {
        if (!key_exists($name, $attributes)) {
            return;
        }

        $exceptionName = self::ExceptionsNamespace . ucfirst(ValidationTypes::MUST_BE_HOUR->value) . 'Error';
        $value = $attributes[$name];

        $matches = [];
        if (is_string($value)) {
            $pattern = "/\d{2}:\d{2}:\d{2}/";
            preg_match($pattern, $value, $matches);
        }

        if (empty($matches)) {
            throw new $exceptionName("{$name} - {$value}");
        }
    }

    public static function mustBeSemester(array $attributes, string $name): void
    {
        if (!key_exists($name, $attributes)) {
            return;
        }

        $exceptionName = self::ExceptionsNamespace . ucfirst(ValidationTypes::MUST_BE_SEMESTER->value) . 'Error';
        $value = $attributes[$name];

        $matches = [];
        if (is_string($value)) {
            $pattern = "/(1|2{1})(\d{3})\-{1}(1|2{1})/";
            preg_match($pattern, $value, $matches);
        }

        if (empty($matches)) {
            throw new $exceptionName("{$name} - {$value}");
        }
    }

    public static function mustBeAuthor(string $name, string $value): void
    {
        $exceptionName = self::ExceptionsNamespace . ucfirst(ValidationTypes::MUST_BE_AUTHORS->value) . 'Error';

        $matches = [];
        if (is_string($value)) {
            $pattern = "/^([A-Z]{1}[\w|\s]+), ([\w|\s]+)/";
            preg_match($pattern, $value, $matches);
        }

        if (empty($matches)) {
            throw new $exceptionName("{$name} - {$value}");
        }
    }

    public static function mustBeAuthors(array $attributes, string $name): void
    {
        if (!key_exists($name, $attributes)) {
            return;
        }
        $values = explode('; ', $attributes[$name]);

        foreach($values as $value){
            self::mustBeAuthor($name, $value);
        }        
    }

    public static function mustBeCutter(array $attributes, string $name): void
    {
        if (!key_exists($name, $attributes)) {
            return;
        }

        $exceptionName = self::ExceptionsNamespace . ucfirst(ValidationTypes::MUST_BE_CUTTER->value) . 'Error';
        $value = $attributes[$name];

        $matches = [];
        if (is_string($value)) {
            $pattern = "/[A-Z]\d{2,4}[a-z]?/";
            preg_match($pattern, $value, $matches);
        }

        if (empty($matches)) {
            throw new $exceptionName("{$name} - {$value}");
        }   
    }

    public static function mustBeRegistration(array $attributes, string $name)
    {
        if (!key_exists($name, $attributes)) {
            return;
        }

        $exceptionName = self::ExceptionsNamespace . ucfirst(ValidationTypes::MUST_BE_REGISTRATION->value) . 'Error';
        $value = $attributes[$name];

        $matches = [];
        if (is_string($value)) {
            $pattern = "/bib\.\d{4}\.\d{1,3}/";
            preg_match($pattern, $value, $matches);
        }

        if (empty($matches)) {
            throw new $exceptionName("{$name} - {$value}");
        }
    }

    public static function mustHaveLengthEqualsTo(array $attributes, string $name, int $length): void
    {
        $exceptionName = self::ExceptionsNamespace . ucfirst(ValidationTypes::MUST_HAVE_LENGTH_EQUALS_TO->value) . 'Error';

        if (!key_exists($name, $attributes)) {
            return;
        }

        $value = $attributes[$name];

        if (strlen($value) != $length) {
            throw new $exceptionName("{$name} - {$value}", $length);
        }
    }

    public static function mustHaveLengthGreatherThan(array $attributes, string $name, int $length): void
    {
        $exceptionName = self::ExceptionsNamespace . ucfirst(ValidationTypes::MUST_HAVE_LENGTH_GREATHER_THAN->value) . 'Error';

        if (!key_exists($name, $attributes)) {
            return;
        }

        $value = $attributes[$name];

        if (strlen($value) < $length) {
            throw new $exceptionName("{$name} - {$value}", $length);
        }
    }

    public static function mustHaveLengthSmallerThan(array $attributes, string $name, int $length): void
    {
        $exceptionName = self::ExceptionsNamespace . ucfirst(ValidationTypes::MUST_HAVE_LENGTH_SMALLER_THAN->value) . 'Error';

        if (!key_exists($name, $attributes)) {
            return;
        }

        $value = $attributes[$name];

        if (strlen($value) > $length) {
            throw new $exceptionName("{$name} - {$value}", $length);
        }
    }

    public static function mustHaveLowerCaseChars(array $attributes, string $name): void
    {
        $exceptionName = self::ExceptionsNamespace . ucfirst(ValidationTypes::MUST_HAVE_LOWER_CASE_CHARS->value) . 'Error';

        if (!key_exists($name, $attributes)) {
            return;
        }

        $value = $attributes[$name];

        if (preg_match('/[a-z]+/', $value) != 1) {
            throw new $exceptionName("{$name} - {$value}");
        }
    }

    public static function mustHaveUpperCaseChars(array $attributes, string $name): void
    {
        $exceptionName = self::ExceptionsNamespace . ucfirst(ValidationTypes::MUST_HAVE_UPPER_CASE_CHARS->value) . 'Error';

        if (!key_exists($name, $attributes)) {
            return;
        }

        $value = $attributes[$name];

        if (preg_match('/[A-Z]+/', $value) != 1) {
            throw new $exceptionName("{$name} - {$value}");
        }
    }

    public static function mustHaveNumbers(array $attributes, string $name): void
    {
        $exceptionName = self::ExceptionsNamespace . ucfirst(ValidationTypes::MUST_HAVE_NUMBERS->value) . 'Error';

        if (!key_exists($name, $attributes)) {
            return;
        }

        $value = $attributes[$name];

        if (preg_match('/[0-9]+/', $value) != 1) {
            throw new $exceptionName("{$name} - {$value}");
        }
    }

    public static function mustHaveSpecialChars(array $attributes, string $name): void
    {
        $exceptionName = self::ExceptionsNamespace . ucfirst(ValidationTypes::MUST_HAVE_SPECIAL_CHARS->value) . 'Error';

        if (!key_exists($name, $attributes)) {
            return;
        }

        $value = $attributes[$name];

        if (
            preg_match("/[\!\@\#\$\%\&\*\(\)\[\]\{\}\,\.\;\:\\\?\/\|]+/", $value) != 1
        ) {
            throw new $exceptionName("{$name} - {$value}");
        }
    }
}
