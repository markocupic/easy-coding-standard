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

class NoDumpSniff implements Sniff
{
    public function register()
    {
        return [T_STRING];
    }

    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $token = $tokens[$stackPtr];

        // Ignore the token if it is not "dump"
        if (strtolower($token['content']) !== 'dump') {
            return;
        }

        // Make sure this is actually a dump() function call
        $next = $phpcsFile->findNext(T_WHITESPACE, $stackPtr + 1, null, true);

        if ($tokens[$next]['code'] !== T_OPEN_PARENTHESIS) {
            return;
        }

        // Show warning
        $phpcsFile->addWarning(
            'Find dump() usage!',
            $stackPtr,
            'DumpDetected'
        );
    }
}
