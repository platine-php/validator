<?php

declare(strict_types=1);

namespace Platine\Test\Validator\Rule;

use Platine\Validator\Validator;
use Platine\Validator\Rule\AlphaNumeric;
use Platine\Dev\PlatineTestCase;

/**
 * AlphaNumeric validation rule class tests
 *
 * @group core
 * @group validator
 * @group rule
 */
class AlphaNumericTest extends PlatineTestCase
{
    /**
     * test Validate method
     *
     * @dataProvider validationDataProvider
     *
     * @param  mixed $value
     * @param  mixed $expectedResult
     * @return void
     */
    public function testValidate($value, $expectedResult): void
    {
        $field = 'name';

        $validator = $this->getMockInstance(Validator::class, [
            'getLabel' => $field,
            'translate' => $field,
        ]);



        $o = new AlphaNumeric();

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
            array('29$', false),
            array('#$', false),
            array('acb@34', false),
            array('167-a34b', false),
            array('167_34b', false),
            array('abc', true),
            array('éîû', true),
            array('a34b', true),
            array('', true),
        );
    }
}
