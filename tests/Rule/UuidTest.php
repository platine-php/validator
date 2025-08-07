<?php

declare(strict_types=1);

namespace Platine\Test\Validator\Rule;

use Platine\Dev\PlatineTestCase;
use Platine\Validator\Rule\Uuid;
use Platine\Validator\Validator;

/**
 * Uuid validation rule class tests
 *
 * @group core
 * @group validator
 * @group rule
 */
class UuidTest extends PlatineTestCase
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



        $o = new Uuid();

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
            array('ab3', false),
            array('AB', false),
            array('ab 3', false),
            array('f7ab0132-7337-11f0-8de9-0242ac120002', true), // v1
            array('ec87e4f1-abb8-4a97-be2b-1ff6d6bf5702', true), // v4
            array('01988267-9aa9-7c88-8ca6-69a5cbf59fe4', true), // v7
            array('', true),
        );
    }
}
