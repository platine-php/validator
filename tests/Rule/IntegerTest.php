<?php

declare(strict_types=1);

namespace Platine\Test\Validator\Rule;

use Platine\Validator\Validator;
use Platine\Validator\Rule\Integer;
use Platine\Dev\PlatineTestCase;

/**
 * Integer validation rule class tests
 *
 * @group core
 * @group validator
 * @group rule
 */
class IntegerTest extends PlatineTestCase
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



        $o = new Integer();

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
            array('1.8', false),
            array('q2', false),
            array('2006.m', false),
            array('tnh', false),
            array('1111', true),
            array(0, true),
            array('1', true),
            array(2006, true),
            array(127, true),
            array(56, true),
        );
    }
}
