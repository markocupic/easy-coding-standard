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

final class NoMailSniff implements Sniff
{
    private const FUNCTION_NAME = 'mail';
    private const ERROR_CODE = 'MailDetected';
    private const IGNORED_PREVIOUS_TOKENS = [
        T_DOUBLE_COLON,
        T_OBJECT_OPERATOR,
        T_NULLSAFE_OBJECT_OPERATOR,
        T_FUNCTION,
        T_NEW,
    ];
    private const ERROR_MESSAGE = 'Found mail() usage.';

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

        // Ignore method calls, declarations and instantiations, e.g. $mailer->mail(), Email::mail(), function mail() or new Mail()
        $prevPtr = $phpcsFile->findPrevious(T_WHITESPACE, $stackPtr - 1, null, true);

        if ($prevPtr !== false && \in_array($tokens[$prevPtr]['code'], self::IGNORED_PREVIOUS_TOKENS, true)) {
            return;
        }

        $phpcsFile->addError(self::ERROR_MESSAGE, $stackPtr, self::ERROR_CODE);
    }
}