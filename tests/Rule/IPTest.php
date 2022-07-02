<?php

declare(strict_types=1);

namespace Platine\Test\Validator\Rule;

use Platine\Validator\Validator;
use Platine\Validator\Rule\IP;
use Platine\Dev\PlatineTestCase;

/**
 * IP validation rule class tests
 *
 * @group core
 * @group validator
 * @group rule
 */
class IPTest extends PlatineTestCase
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



        $o = new IP();

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
            array('1.1.1', false),
            array('q::2', false),
            array('2006::2:m', false),
            array('192.168.0.256', false),
            array('1.1.1.1', true),
            array('0.0.0.0', true),
            array('::1', true),
            array('2006::1', true),
            array('127.0.0.1', true),
            array('255.255.255.255', true),
        );
    }
}
