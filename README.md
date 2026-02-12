# EasyCodingStandard configurations

This package includes some custom sniffers for Markocupic projects..

## Installation

Add the package to your Contao installation via Composer:

```bash
composer require markocupic/easy-coding-standard --dev
```

## Usage

Create a file named `ecs.php` in the root directory of your project.

```php
<?php

declare(strict_types=1);

use SetList\SetList;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withSets([SetList::MARKOCUPIC])
    // Adjust the configuration according to your needs.
;
```

Then run the script like this:

```bash
vendor/bin/ecs check
```

## What's inside?

The package contains the following custom fixers:

| Class     | Description |
|-----------|-------------|
| --empty-- | --empty--   |

The package contains the following custom sniffs:

| Class                                            | Description                                |
|--------------------------------------------------|--------------------------------------------|
| [`NoDumpSniff`](src/Sniffs/NoDumpSniff.php)      | Prevents using dump() statements.          |
| [`NoExitOrDieSniff`](src/Sniffs/NoExitSniff.php) | Prevents using exit() or die() statements. |
| [`NoMailSniff`](src/Sniffs/NoMailSniff.php)      | Prevents using mail() statements.          |

## License

Markocupic Easy Coding Standard is licensed under the terms of the LGPLv3.
