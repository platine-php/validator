<?php

declare(strict_types=1);

namespace Platine\Test\Validator\Rule;

use Platine\Validator\Validator;
use Platine\Validator\Rule\Regex;
use Platine\Dev\PlatineTestCase;

/**
 * Regex validation rule class tests
 *
 * @group core
 * @group validator
 * @group rule
 */
class RegexTest extends PlatineTestCase
{
    /**
     * test Validate method
     *
     * @dataProvider validationDataProvider
     *
     * @param  mixed $param
     * @param  mixed $value
     * @param  mixed $expectedResult
     * @return void
     */
    public function testValidate($param, $value, $expectedResult): void
    {
        $field = 'name';


        $validator = $this->getMockInstance(Validator::class, [
            'getLabel' => $field,
            'translate' => $field,
        ]);



        $o = new Regex($param);

        $this->assertEquals($expectedResult, $o->validate($field, $value, $validator));

        //ERROR MESSAGE
        $this->assertNotEmpty($o->getErrorMessage($field, $value, $validator));
    }

    /**
     * Data provider for "testValidate"
     * @return array
     */
    public function validationDataProvider(): array
    {
        return array(
            array('/^([a-b])$/', 'ab3', false),
            array('/^([a-b])$/', 'AB', false),
            array('/^([a-b0-9])$/', 'ab 3', false),
            array('/^([a-b])$/', 'a', true),
            array('/^([a-b0-8]+)$/', 'a6', true),
        );
    }
}
