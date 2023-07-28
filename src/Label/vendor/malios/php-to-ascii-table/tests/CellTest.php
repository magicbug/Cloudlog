<?php

namespace AsciiTable\Test;

use AsciiTable\Cell;
use PHPUnit\Framework\TestCase;

class CellTest extends TestCase
{
    public function testCellWithDifferentTypesOfValues()
    {
        $cell = new Cell('age', '21');
        $this->assertEquals('21', $cell->getValue());
        $this->assertEquals(2, $cell->getWidth());

        $cell->setValue(123);
        $this->assertEquals('123', $cell->getValue());
        $this->assertEquals(3, $cell->getWidth());

        $ageObject = new class (2008) {
            private $year;

            function __construct(int $year)
            {
                $this->year = $year;
            }

            function __toString()
            {
                return strval(2017 - $this->year);
            }
        };

        $cell->setValue($ageObject);
        $this->assertEquals(1, $cell->getWidth());

        $floatCell = new Cell('price', 100.00);
        $this->assertEquals("100.00", $floatCell->getValue());

        $floatCell->setValue(99.99);
        $this->assertEquals("99.99", $floatCell->getValue());
    }

    public function testCellWidthWithMultiByteCharacters()
    {
        $cell = new Cell('name', 'Иван');
        $this->assertEquals(4, $cell->getWidth());

        $cell = new Cell('message', 'Hello, 世界'); // 世界 - this string is with width 4
        $this->assertEquals(11, $cell->getWidth());
    }
}
