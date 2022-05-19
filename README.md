# CommandLib

**By using CommandLib you will be able to add better commands!**

## Information
**NOTE:** Before creating any commands you must Register the BaseCommandManager!

```php
if(!BaseCommandManager::isRegistered()){
    BaseCommandManager::register($this);
}
```

## Command Example
```php

class YourClass extends BaseCommand
{

    public function __construct()
    {
        parent::__construct("Command.", "Command Description.");

        $this->addParameter(Parameter::create("test", EnumType::STRING(), null, true), 0);
    }
    
    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
    
    }
}
```
