<?php

declare(strict_types=1);

/*
 * This file is part of Easy Coding Standard.
 *
 * (c) Marko Cupic <m.cupic@gmx.ch>
 * @license LGPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/easy-coding-standard
 */

use Markocupic\EasyCodingStandard\Sniffs\NoDumpSniff;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withSets([
        //__DIR__.'/set/psr12.php',
        //__DIR__.'/set/symfony.php',
    ])
    ->withRules([
       NoDumpSniff::class,
    ])
    //->withConfiguredRule(BlankLineBeforeStatementFixer::class, ['statements' => ['do', 'for', 'foreach', 'return', 'switch', 'throw', 'try', 'while']])
   ;
