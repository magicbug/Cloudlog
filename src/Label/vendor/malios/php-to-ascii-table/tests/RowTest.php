<?php

namespace AsciiTable\Test;

use AsciiTable\Cell;
use AsciiTable\Row;
use PHPUnit\Framework\TestCase;

class RowTest extends TestCase
{
    public function testCellNotDuplicated()
    {
        $row = new Row();
        $row->addCell(new Cell('name', 'John'));
        $row->addCell(new Cell('name', 'Rick'));

        $this->assertCount(1, $row->getCells());

        $cell = $row->getCells()->get('name');

        $this->assertEquals($cell->getColumnName(), 'name');
        $this->assertEquals($cell->getValue(), 'Rick');
    }
}
