<?php

declare(strict_types=1);

/*
 * This file is part of Easy Coding Standard.
 *
 * (c) Marko Cupic <m.cupic@gmx.ch>
 * @license LGPL-3.0-or-later
 * For full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/easy-coding-standard
 */

namespace Markocupic\EasyCodingStandard\Sniffs;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

final class NoExitOrDieSniff implements Sniff
{
    private const ERROR_CODE = 'ExitDetected';
    private const ERROR_MESSAGE = 'Found exit() or die() usage.';

    public function register(): array
    {
        // Listen for the language construct token (covers both exit and die)
        return [T_EXIT];
    }

    public function process(File $phpcsFile, $stackPtr): void
    {
        // Any T_EXIT is a violation; no need to check for parentheses
        $phpcsFile->addError(self::ERROR_MESSAGE, $stackPtr, self::ERROR_CODE);
    }
}