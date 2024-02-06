# psr-logger (PSR-3)

Simple implementation of [PSR-3 (Logger Interface)](https://www.php-fig.org/psr/psr-3/).

## Requirements

The following versions of PHP are supported by this version.

* PHP ^8.1

# Usage

## Handler

All handlers implement the _Jd297\Psr\Logger\Handler\HandlerInterface_.

### FileHandler

The _FileHandler_ logs to a file that is specified in the first argument.

```php
use Jd297\Psr\Logger\Handler\FileHandler;

$fileHandler = new FileHandler('[projectdir]/log/dev.log')];
```

## Logger

Instantiate a _Logger_ with a Clock that implements _Psr\Clock\ClockInterface_ and an array of handlers that implement _Jd297\Psr\Logger\Handler\HandlerInterface_.

```php
use Jd297\Psr\Clock\SystemClock;
use Jd297\Psr\Logger\Logger;
use Psr\Log\LogLevel;

$logger = new Logger(new SystemClock(), [$fileHandler]);

$logger->log(LogLevel::INFO, 'User "{username}" created with email "{email}".', [
    'username' => 'john',
    'email' => 'john.doe@local.local',
]);
```

## Composer

### Scripts

Reformat code with [PHP CS Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer).
``` bash
$ composer reformat
```

Test source code with [PHPUnit](https://github.com/sebastianbergmann/phpunit).
``` bash
$ composer unit
```

Analyse files with [PHPStan](https://github.com/phpstan/phpstan) (Level 9).
``` bash
$ composer analyse
```

## License

The BSD 2-Clause "Simplified" License (BSD-2-Clause). Please see [License File](https://github.com/jd297/psr-logger/blob/master/LICENSE) for more information.
