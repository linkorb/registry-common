<?php

namespace Test\Registry\Common;

use PHPUnit_Framework_TestCase;

use Registry\Common\DottedPropertyTrait;

require_once __DIR__ . '/../../src/Common/DottedPropertyTrait.php';

class DottedPropertyTraitMakeFlatTest extends PHPUnit_Framework_TestCase
{
    public function testEmptyPropertiesStayEmpty()
    {
        $this->assertSame(array(), $this->flatten(array()));
    }

    public function testNestedArrayFlattensToDottedKey()
    {
        $this->assertSame(
            array('foo.bar' => 'baz'),
            $this->flatten(array('foo' => array('bar' => 'baz')))
        );
    }

    public function testFlattenedKeyAlreadyInResultIsRenumbered()
    {
        $this->assertSame(
            array(
                'foo.bar.0' => 'first',
                'foo.bar.1' => 'second',
            ),
            $this->flatten(array(
                'foo.bar' => 'first',
                'foo' => array('bar' => 'second'),
            ))
        );
    }

    public function testFlattenedKeyAlreadyInConflictsGetsNextSuffix()
    {
        // Prefix 0 is falsy, so the first value is stored as foo.bar.
        // The second arrival renumbers that pair; the third uses the counter.
        $this->assertSame(
            array(
                'foo.bar.0' => 'first',
                'foo.bar.1' => 'second',
                'foo.bar.2' => 'third',
            ),
            $this->flatten(array(
                0 => array('foo' => array('bar' => 'first')),
                'foo.bar' => 'second',
                'foo' => array('bar' => 'third'),
            ))
        );
    }

    private function flatten($properties)
    {
        $subject = $this->getMockForTrait(DottedPropertyTrait::class);

        return $subject->flatten($properties);
    }
}
