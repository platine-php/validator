<?php

/**
 * Platine Validator
 *
 * Platine Validator is a simple, extensible validation library with support for filtering
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2020 Platine Validator
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/**
 *  @file Password.php
 *
 *  Validate the password strength
 *
 *  @package    Platine\Validator\Rule
 *  @author Platine Developers Team
 *  @copyright  Copyright (c) 2020
 *  @license    http://opensource.org/licenses/MIT  MIT License
 *  @link   https://www.platine-php.com
 *  @version 1.0.0
 *  @filesource
 */

declare(strict_types=1);

namespace Platine\Validator\Rule;

use Platine\Validator\RuleInterface;
use Platine\Validator\Validator;

/**
 * @class Password
 * @package Platine\Validator\Rule
 */
class Password implements RuleInterface
{
    /*
     * The error type list
     */
    public const ERROR_TYPE_LENGTH = 1;
    public const ERROR_TYPE_UPPERCASE = 2;
    public const ERROR_TYPE_LOWERCASE = 3;
    public const ERROR_TYPE_NUMBER = 4;
    public const ERROR_TYPE_SPECIAL_CHAR = 5;

    /**
     * The password strength rules
     * @var array<string, bool|int>
     */
    protected array $rules = [];

    /**
     * The error type
     * @var int
     */
    protected int $errorType = self::ERROR_TYPE_LENGTH;

    /**
     * Constructor
     * @param array<string, bool|int> $rules
     */
    public function __construct(array $rules = [])
    {
        $this->rules = array_merge([
            'length' => 5,
            'uppercase' => false,
            'lowercase' => false,
            'number' => false,
            'special_chars' => false,
           ], $rules);
    }

    /**
     * {@inheritdoc}
     * @see RuleInterface
     */
    public function validate(string $field, mixed $value, Validator $validator): bool
    {
        if (empty($value)) {
            return true;
        }

        $rules = $this->rules;
        if (strlen($value) < $rules['length']) {
            $this->errorType = self::ERROR_TYPE_LENGTH;

            return false;
        }

        if ($rules['uppercase'] && ((bool)preg_match('~[A-Z]~', $value)) === false) {
            $this->errorType = self::ERROR_TYPE_UPPERCASE;

            return false;
        }

        if ($rules['lowercase'] && ((bool)preg_match('~[a-z]~', $value)) === false) {
            $this->errorType = self::ERROR_TYPE_LOWERCASE;

            return false;
        }

        if ($rules['number'] && ((bool)preg_match('~[0-9]~', $value)) === false) {
            $this->errorType = self::ERROR_TYPE_NUMBER;

            return false;
        }

        if ($rules['special_chars'] && ((bool)preg_match('~[^\w]~', $value)) === false) {
            $this->errorType = self::ERROR_TYPE_SPECIAL_CHAR;

            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     * @see RuleInterface
     */
    public function getErrorMessage(string $field, mixed $value, Validator $validator): string
    {
        if ($this->errorType === self::ERROR_TYPE_LENGTH) {
            return $validator->translate(
                '%s must contain at least %d characters!',
                $validator->getLabel($field),
                $this->rules['length']
            );
        }

        $errorMaps = [
            self::ERROR_TYPE_LOWERCASE => $validator->translate(
                '%s should include at least one lower case!',
                $validator->getLabel($field)
            ),
            self::ERROR_TYPE_UPPERCASE => $validator->translate(
                '%s should include at least one upper case!',
                $validator->getLabel($field)
            ),
            self::ERROR_TYPE_NUMBER => $validator->translate(
                '%s should include at least one number!',
                $validator->getLabel($field)
            ),
            self::ERROR_TYPE_SPECIAL_CHAR => $validator->translate(
                '%s should include at least one special character!',
                $validator->getLabel($field)
            ),
        ];

        return $errorMaps[$this->errorType] ?? '';
    }
}
