<?php

declare(strict_types=1);

namespace Platine\Test\Validator\Rule;

use Platine\Validator\Validator;
use Platine\Validator\Rule\Alpha;
use Platine\PlatineTestCase;

/**
 * Alpha validation rule class tests
 *
 * @group core
 * @group validator
 * @group rule
 */
class AlphaTest extends PlatineTestCase
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

        $validator = $this->getMockBuilder(Validator::class)
                ->getMock();

        $validator->expects($this->any())
                ->method('getLabel')
                ->with($field)
                ->will($this->returnValue($field));



        $o = new Alpha();

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
            array('29', false),
            array('qbc1', false),
            array('a-b', false),
            array('167-a34b', false),
            array('167_34b', false),
            array('abc', true),
            array('éîû', true),
            array('ab', true),
            array('a b', true),
            array('', true),
        );
    }
}
