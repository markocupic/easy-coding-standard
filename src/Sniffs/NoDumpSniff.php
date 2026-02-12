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

final class NoDumpSniff implements Sniff
{
    private const FUNCTION_NAME = 'dump';
    private const ERROR_CODE = 'DumpDetected';
    private const ERROR_MESSAGE = 'Found dump() usage.';

    public function register()
    {
        return [T_STRING];
    }

    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $token = $tokens[$stackPtr];

        if (\strcasecmp((string)$token['content'], self::FUNCTION_NAME) !== 0) {
            return;
        }

        $nextPtr = $phpcsFile->findNext(T_WHITESPACE, $stackPtr + 1, null, true);
        if ($nextPtr === false) {
            return;
        }

        if ($tokens[$nextPtr]['code'] !== T_OPEN_PARENTHESIS) {
            return;
        }

        $phpcsFile->addError(self::ERROR_MESSAGE, $stackPtr, self::ERROR_CODE);
    }
}