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
 *  @file DateAfter.php
 *
 *  Date must be after the given one
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
 * @class DateAfter
 * @package Platine\Validator\Rule
 */
class DateAfter implements RuleInterface
{
    /**
     * Constructor
     * @param string $date the date format to compare against
     * @param bool $include whether the given date is included or not
     */
    public function __construct(
        protected string $date,
        protected bool $include = false
    ) {
    }

    /**
     * {@inheritdoc}
     * @see RuleInterface
     */
    public function validate(string $field, mixed $value, Validator $validator): bool
    {
        if ($this->include) {
            return strtotime((string) $value) >= strtotime($this->date);
        }

        return strtotime((string) $value) > strtotime($this->date);
    }

    /**
     * {@inheritdoc}
     * @see RuleInterface
     */
    public function getErrorMessage(string $field, mixed $value, Validator $validator): string
    {
        if ($this->include) {
            return $validator->translate(
                '%s must be after or equal to the date [%s]!',
                $validator->getLabel($field),
                $this->date
            );
        }

        return $validator->translate(
            '%s must be after the date [%s]!',
            $validator->getLabel($field),
            $this->date
        );
    }
}
