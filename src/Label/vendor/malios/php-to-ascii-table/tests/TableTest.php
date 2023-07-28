<?php

namespace AsciiTable\Test;

use AsciiTable\Cell;
use AsciiTable\Row;
use AsciiTable\Table;
use Ds\Set;
use PHPUnit\Framework\TestCase;

class TableTest extends TestCase
{
    public function testSetVisibleColumns()
    {
        $table = new Table();
        $table->setVisibleColumns(['name', 'age', 'location', 'sex']);

        $set = new Set();
        $set->add('name');
        $set->add('age');
        $set->add('location');
        $set->add('sex');

        self::assertEquals(0, $set->diff($table->getVisibleColumns())->count());
        self::assertEquals(0, $table->getVisibleColumns()->diff($set)->count());
    }

    public function testGetVisibleColumnsWhenNotSet()
    {
        $row = new Row();
        $row->addCell(new Cell('name', 'Catherine'));
        $row->addCell(new Cell('sex', 'female'));
        $row->addCell(new Cell('height', 168));

        $table = new Table();
        $table->addRow($row);

        $this->assertSame(['name', 'sex', 'height'], $table->getVisibleColumns()->toArray());
    }

    public function testGetAllColumns()
    {
        $row = new Row();
        $row->addCell(new Cell('name', 'Bill'));
        $row->addCell(new Cell('age', 21));

        $row2 = new Row();
        $row2->addCell(new Cell('name', 'John'));
        $row2->addCell(new Cell('sex', 'male'));

        $row3 = new Row();
        $row3->addCell(new Cell('name', 'Catherine'));
        $row3->addCell(new Cell('sex', 'female'));
        $row3->addCell(new Cell('height', 168));

        $table = new Table();
        $table->addRow($row);
        $table->addRow($row2);
        $table->addRow($row3);

        $this->assertSame(['name', 'age', 'sex', 'height'], $table->getAllColumns()->toArray());
    }

    public function testColumnWidth()
    {
        $row = new Row();
        $row->addCell(new Cell('name', 'Jon'));
        $row->addCell(new Cell('age', 21));

        $row2 = new Row();
        $row2->addCell(new Cell('name', 'John'));
        $row2->addCell(new Cell('age', 32));

        $row3 = new Row();
        $row3->addCell(new Cell('name', 'Johnathan'));
        $row3->addCell(new Cell('age', 23));

        $table = new Table();

        $table->addRow($row);
        $table->addRow($row2);
        $table->addRow($row3);

        $this->assertEquals(3, $table->getColumnWidth('age'));
        $this->assertEquals(9, $table->getColumnWidth('name'));
    }
}
