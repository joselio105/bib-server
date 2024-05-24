<?php

namespace plugse\server\core\infra\database\connection;

use plugse\server\core\errors\PropertyNotFoundError;

class DbSettings
{
    private array $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
        $this->checkProperties();
    }

    public function get(): array
    {
        return $this->settings;
    }

    private function checkProperties(): void
    {
        $properties = ['host', 'name', 'user', 'password', 'prefix'];

        foreach($properties as $property){
            if(!key_exists($property, $this->settings)){
                throw new PropertyNotFoundError(SECRET_KEY_FILE);
            }
        }
    }
}
