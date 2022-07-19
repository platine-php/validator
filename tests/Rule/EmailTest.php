<?php

declare(strict_types=1);

namespace Platine\Test\Validator\Rule;

use Platine\Validator\Validator;
use Platine\Validator\Rule\Email;
use Platine\Dev\PlatineTestCase;

/**
 * Email validation rule class tests
 *
 * @group core
 * @group validator
 * @group rule
 */
class EmailTest extends PlatineTestCase
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



        $o = new Email();

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
            array('e', false),
            array('e@', false),
            array('e@.', false),
            array('e@.com', false),
            array('.@e.v', false),
            array('gghhghg@gm@il.com', false),
            array('eamil@domain.com', true),
            array('e@f.c', true),
            array('', true),
        );
    }
}
