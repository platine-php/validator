<?php

declare(strict_types=1);

namespace Platine\Test\Validator\Rule;

use Platine\Validator\Validator;
use Platine\Validator\Rule\URL;
use Platine\Dev\PlatineTestCase;

/**
 * URL validation rule class tests
 *
 * @group core
 * @group validator
 * @group rule
 */
class URLTest extends PlatineTestCase
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



        $o = new URL();

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
            array('foo.com', false),
            array('www.foo.bar', false),
            array('http://localhost', true),
            array('http://foo.bar', true),
            array('ftp://myhost.com', true),
            array('ftp://user@pass/host.com', true),
            array('ftp://user@pass:231/host.com', true),
        );
    }
}
