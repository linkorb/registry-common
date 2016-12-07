<?php

namespace Test\Registry\Common;

use PHPUnit_Framework_TestCase;

use Registry\Common\DottedPropertyTrait;

class DottedPropertyTraitTest extends PHPUnit_Framework_TestCase
{
    public function testFlatten()
    {
        $mock = $this->getMockForTrait(DottedPropertyTrait::class);

        $data = array(
            'foo.bar' => '1234',
            'foo' => array(
                'bar' => array(
                    'duck' => '2345',
                ),
            ),
        );

        $this->assertSame(
            array(
                'foo.bar' => '1234',
                'foo.bar.duck' => '2345',
            ),
            $mock->flatten($data)
        );
    }

    public function testUnflatten()
    {
        $mock = $this->getMockForTrait(DottedPropertyTrait::class);

        $data = array(
            'foo.bar' => '1234',
            'foo.bar.duck' => '2345',
        );

        $this->assertSame(
            array(
                'foo' => array(
                    'bar' => array(
                        0 => '1234',
                        'duck' => '2345',
                    ),
                ),
            ),
            $mock->unflatten($data)
        );
    }
}
