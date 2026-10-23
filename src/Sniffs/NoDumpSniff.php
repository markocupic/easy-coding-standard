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
    private const VAR_DUMPER_CLASS = 'VarDumper';
    private const IGNORED_PREVIOUS_TOKENS = [
        T_DOUBLE_COLON,
        T_OBJECT_OPERATOR,
        T_NULLSAFE_OBJECT_OPERATOR,
        T_FUNCTION,
        T_NEW,
    ];
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

        // Ignore method calls, declarations and instantiations, e.g. Yaml::dump(), $obj->dump(), $obj?->dump(), function dump() or new Dump()
        $prevPtr = $phpcsFile->findPrevious(T_WHITESPACE, $stackPtr - 1, null, true);

        if ($prevPtr !== false && \in_array($tokens[$prevPtr]['code'], self::IGNORED_PREVIOUS_TOKENS, true)) {
            // VarDumper::dump() is a debug call as well
            if (!$this->isVarDumperCall($phpcsFile, $prevPtr)) {
                return;
            }
        }

        $phpcsFile->addError(self::ERROR_MESSAGE, $stackPtr, self::ERROR_CODE);
    }

    private function isVarDumperCall(File $phpcsFile, int $prevPtr): bool
    {
        $tokens = $phpcsFile->getTokens();

        if ($tokens[$prevPtr]['code'] !== T_DOUBLE_COLON) {
            return false;
        }

        $classPtr = $phpcsFile->findPrevious(T_WHITESPACE, $prevPtr - 1, null, true);

        if ($classPtr === false) {
            return false;
        }

        // Covers VarDumper::dump(), \VarDumper::dump() and Symfony\Component\VarDumper\VarDumper::dump()
        $className = ltrim((string) $tokens[$classPtr]['content'], '\\');
        $className = substr($className, (int) strrpos('\\'.$className, '\\'));

        return \strcasecmp($className, self::VAR_DUMPER_CLASS) === 0;
    }
}
