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
 *  @file Validator.php
 *
 *  The Validator class used to validate the input based on defined rules
 *
 *  @package    Platine\Validator
 *  @author Platine Developers Team
 *  @copyright  Copyright (c) 2020
 *  @license    http://opensource.org/licenses/MIT  MIT License
 *  @link   http://www.iacademy.cf
 *  @version 1.0.0
 *  @filesource
 */

declare(strict_types=1);

namespace Platine\Validator;

use Platine\Lang\Lang;
use Platine\Validator\Exception\ValidatorException;

/**
 * A Validator contains a set of validation rules and
 * associated metadata for ensuring that a given data set
 * is valid and returned correctly.
 */
class Validator
{

    /**
     * The data to validate
     * @var array<string, mixed>
     */
    protected array $data = [];

    /**
     * The field labels
     * @var array<string, string>
     */
    protected array $labels = [];

    /**
     * The filters to use to filter validation data
     * @var array<string, callable[]>
     */
    protected array $filters = [];

    /**
     * The validate rules
     * @var array<string, RuleInterface[]>
     */
    protected array $rules = [];

    /**
     * The validate errors
     * @var array<string, string>
     */
    protected array $errors = [];

    /**
     * The status of the validation
     * @var bool
     */
    protected bool $valid = false;

    /**
     * The validation language domain to use
     * @var string
     */
    protected string $langDomain;

    /**
     * The language to use
     * @var Lang
     */
    protected Lang $lang;


    /**
     * Create new instance
     * @param Lang $lang
     * @param string $langDomain
     */
    public function __construct(Lang $lang, string $langDomain = 'validators')
    {
        $this->lang = $lang;
        $this->langDomain = $langDomain;

        //Add the domain for the validator
        $this->lang->addDomain($langDomain);

        $this->reset();
    }

    /**
     * Return the language instance
     * @return Lang
     */
    public function getLang(): Lang
    {
        return $this->lang;
    }

    /**
     * Translation a single message
     * @param string $message
     * @param array<int, mixed>|mixed $args
     * @return string
     */
    public function translate(string $message, $args = []): string
    {
        return $this->lang->trd($message, $this->langDomain, $args);
    }

    /**
     * Reset the validation instance
     *
     * @return $this
     */
    public function reset(): self
    {
        $this->rules = [];
        $this->labels = [];
        $this->errors = [];
        $this->filters = [];
        $this->valid = false;
        $this->data = [];

        return $this;
    }

    /**
     * Set the validation data
     * @param array<string, mixed> $data the data to be validated
     *
     * @return $this
     */
    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Return the validation data
     *
     * @param string $field if is set will return only the data for this filed
     * @param mixed $default the default value to return if can not find field value
     * @return mixed
     */
    public function getData(?string $field = null, $default = null)
    {
        if ($field === null) {
            return $this->data;
        }
        return array_key_exists($field, $this->data) ? $this->data[$field] : $default;
    }

    /**
     * Return the validation status
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * Set the Label for a given Field
     * @param string $field
     * @param string $label
     *
     * @return $this
     */
    public function setLabel(string $field, string $label): self
    {
        $this->labels[$field] = $label;

        return $this;
    }

    /**
     * Return the label for a given Field
     * @param string $field
     *
     * @return string the label if none is set will use the humanize value
     * of field
     */
    public function getLabel(string $field): string
    {
        return isset($this->labels[$field])
                ? $this->labels[$field]
                : $this->humanizeFieldName($field);
    }

    /**
     * Add a filter for the given field
     * @param string $field
     * @param callable $filter
     *
     * @return $this
     */
    public function addFilter(string $field, callable $filter): self
    {
        if (!isset($this->filters[$field])) {
            $this->filters[$field] = [];
        }
        $this->filters[$field][] = $filter;

        return $this;
    }

    /**
     * Add a list of filter for the given field
     * @param string $field
     * @param callable[] $filters
     *
     * @return $this
     */
    public function addFilters(string $field, array $filters): self
    {
        foreach ($filters as $filter) {
            if (!is_callable($filter)) {
                throw new ValidatorException('Filter must to be a valid callable');
            }
            $this->addFilter($field, $filter);
        }

        return $this;
    }

    /**
     * Add a rule for the given field
     * @param string $field
     * @param RuleInterface $rule
     *
     * @return $this
     */
    public function addRule(string $field, RuleInterface $rule): self
    {
        if (!isset($this->rules[$field])) {
            $this->rules[$field] = [];
        }
        if (!isset($this->labels[$field])) {
            $this->labels[$field] = $this->humanizeFieldName($field);
        }
        $this->rules[$field][] = $rule;

        return $this;
    }

    /**
     * Add a list of rules for the given field
     * @param string $field
     * @param RuleInterface[] $rules the array of RuleInterface
     *
     * @return $this
     */
    public function addRules(string $field, array $rules): self
    {
        foreach ($rules as $rule) {
            if (!$rule instanceof RuleInterface) {
                throw new ValidatorException(sprintf(
                    'Validation rule must implement [%s]!',
                    RuleInterface::class
                ));
            }
            $this->addRule($field, $rule);
        }

        return $this;
    }

    /**
     * Return all currently defined rules
     * @param string $field if set will return only for this field
     *
     * @return RuleInterface[]|array<string, RuleInterface[]>
     */
    public function getRules(?string $field = null): array
    {
        return $field !== null
                    ? (isset($this->rules[$field]) ? $this->rules[$field] : [])
                    : $this->rules;
    }

    /**
     * Validate the data
     * @param  array<string, mixed>  $data
     * @return bool       the validation status
     */
    public function validate(array $data = []): bool
    {
        if (!empty($data)) {
            $this->data = $data;
        }
        $this->applyFilters();

        $this->errors = $this->validateRules();
        $this->valid = empty($this->errors);

        return $this->valid;
    }

    /**
     * Return the validation errors
     * @return array<string, string> the validation errors
     *
     * @example array(
     *          'field1' => 'message 1',
     *          'field2' => 'message 2',
     * )
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Process to validation of fields rules
     * @return array<string, string> the validation errors
     */
    protected function validateRules(): array
    {
        if (empty($this->rules)) {
            return [];
        }
        $errors = [];

        foreach ($this->rules as $field => $rules) {
            list($result, $error) = $this->validateFieldRules($field, $rules);
            if ($result === false) {
                $errors[$field] = $error;
            }
        }

        return $errors;
    }

    /**
     * Validate the rules for the given field
     * @param  string $field
     * @param  RuleInterface[]  $rules the array of rules
     * @return array<mixed>     array(Status, error)
     */
    protected function validateFieldRules(string $field, array $rules): array
    {
        $value = isset($this->data[$field]) ? $this->data[$field] : null;
        foreach ($rules as $rule) {
            list($result, $error) = $this->validateRule($field, $value, $rule);
            if ($result === false) {
                return [false, $error];
            }
        }

        return [true, null];
    }

    /**
     * Validate single rule for the given field
     * @param  string $field
     * @param  mixed $value
     * @param  RuleInterface  $rule the rule instance to validate
     * @return array<mixed>     array(Status, error)
     */
    protected function validateRule(string $field, $value, RuleInterface $rule): array
    {
        $result = $rule->validate($field, $value, $this);
        if ($result) {
            return [true, null];
        }
        return [false, $rule->getErrorMessage($field, $value, $this)];
    }

    /**
     * Apply any defined filters to the validation data
     * @return array<string, mixed> the filtered data
     */
    protected function applyFilters(): array
    {
        if (empty($this->filters)) {
            return $this->data;
        }

        $data = $this->data;

        foreach ($this->filters as $field => $filters) {
            if (!isset($data[$field])) {
                continue;
            }

            $value = $data[$field];
            foreach ($filters as $filter) {
                $value = call_user_func($filter, $value);
            }

            $data[$field] = $value;
        }
        $this->data = $data;

        return $this->data;
    }

    /**
     * Humanize the given field
     * @param  string $field
     *
     * @return string
     */
    protected function humanizeFieldName(string $field): string
    {
        return str_replace(['-', '_'], ' ', $field);
    }
}
