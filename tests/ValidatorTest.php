<?php

declare(strict_types=1);

namespace Platine\Test\Validator;

use Platine\Dev\PlatineTestCase;
use Platine\Lang\Lang;
use Platine\Validator\Rule\NotEmpty;
use Platine\Validator\Validator;

/**
 * Validator class tests
 *
 * @group core
 * @group validator
 */
class ValidatorTest extends PlatineTestCase
{
    public function testConstructor(): void
    {
        $lang = $this->getMockInstance(Lang::class, []);
        $v = new Validator($lang);

        $this->assertInstanceOf(Lang::class, $v->getLang());
        $this->assertEquals($lang, $v->getLang());
    }

    public function testSetGetData(): void
    {
        $lang = $this->getMockInstance(Lang::class, []);
        $data = $this->getDefaultValidationData();

        $v = new Validator($lang);

        $this->assertEmpty($v->getData());

        $v->setData($data);
        $this->assertNotEmpty($v->getData());
        $this->assertCount(3, $v->getData());
        $this->assertEquals(18, $v->getData('age'));
        $this->assertEquals('baz', $v->getData('key_not_exist', 'baz'));
    }

    public function testSetGetLabel(): void
    {
        $lang = $this->getMockInstance(Lang::class, []);
        $v = new Validator($lang);

        $this->assertEquals('foo bar', $v->getLabel('foo_bar'));
        $this->assertEquals('foo bar', $v->getLabel('foo-bar'));

        $v->setLabel('foo', 'my label');
        $this->assertEquals('my label', $v->getLabel('foo'));
    }

    public function testAddFilters(): void
    {
        $lang = $this->getMockInstance(Lang::class, []);
        $v = new Validator($lang);
        $reflection = $this->getPrivateProtectedAttribute(Validator::class, 'filters');

        $this->assertEmpty($reflection->getValue($v));

        $v->addFilter('foo', 'trim');
        $this->assertNotEmpty($reflection->getValue($v));
        $this->assertCount(1, $reflection->getValue($v));

        //using array
        //Success
        $v->addFilters('baz', array('trim'));
        $this->assertCount(2, $reflection->getValue($v));
    }

    public function testAddRules(): void
    {
        $lang = $this->getMockInstance(Lang::class, []);
        $v = new Validator($lang);

        $this->assertEmpty($v->getRules());

        //When Label is not set before
        $v->addRule('foo_bar', new NotEmpty());
        $this->assertNotEmpty($v->getRules());
        $this->assertCount(1, $v->getRules());
        $this->assertEquals('foo bar', $v->getLabel('foo_bar'));

        //When Label is set before
        $v->setLabel('foo', 'My foo');
        $v->addRule('foo', new NotEmpty());
        $this->assertNotEmpty($v->getRules());
        $this->assertCount(2, $v->getRules());
        $this->assertEquals('My foo', $v->getLabel('foo'));

        //using array
        //Success
        $v->addRules('baz', array(new NotEmpty()));
        $this->assertCount(3, $v->getRules());
    }

    public function testGetRules(): void
    {
        $lang = $this->getMockInstance(Lang::class, []);
        $v = new Validator($lang);

        $this->assertEmpty($v->getRules());

        $v->addRule('foo_bar', new NotEmpty());
        $this->assertCount(1, $v->getRules());

        $v->addRule('foo', new NotEmpty());
        $this->assertCount(2, $v->getRules());

        //Using field
        //rule not found
        $this->assertEmpty($v->getRules('not_found_field'));

        //rule found for field
        $this->assertCount(1, $v->getRules('foo'));
    }

    public function testValidate(): void
    {
        $lang = $this->getMockInstance(Lang::class, []);
        $v = new Validator($lang);

        //Success
        $data = $this->getDefaultValidationData();
        $v->addRule('foo', new NotEmpty());
        $this->assertTrue($v->validate($data));
        $this->assertTrue($v->isValid());
        $this->assertEmpty($v->getErrors());

        //Using filter
        $v->reset();
        $v->addRule('foo', new NotEmpty());
        $v->addFilter('foo', 'trim');
        $v->addFilter('baz', 'trim'); //the data does not exist
        $v->setData(array('foo' => ' '));
        $this->assertFalse($v->validate());
        $this->assertFalse($v->isValid());
        $this->assertEmpty($v->getData('foo'));
        $this->assertNotEmpty($v->getErrors());
        $this->assertArrayHasKey('foo', $v->getErrors());

        //When rule is empty
        $v->reset();
        $this->assertTrue($v->validate());
    }

    private function getDefaultValidationData(): array
    {
        return array(
            'name' => 'foobar',
            'foo' => 'bar',
            'age' => 18,
        );
    }
}
