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
 *  @file Regex.php
 *
 *  Field value must match the regex pattern
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
 * @class Regex
 * @package Platine\Validator\Rule
 */
class Regex implements RuleInterface
{
    /**
     * Constructor
     * @param string $regex the regex pattern
     */
    public function __construct(protected string $regex)
    {
        $this->regex = $regex;
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

        return (bool) preg_match($this->regex, (string)$value);
    }

    /**
     * {@inheritdoc}
     * @see RuleInterface
     */
    public function getErrorMessage(string $field, mixed $value, Validator $validator): string
    {
        return $validator->translate(
            '%s does not use the correct format!',
            $validator->getLabel($field)
        );
    }
}
