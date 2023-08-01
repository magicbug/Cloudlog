<?php declare(strict_types=1);

namespace AsciiTable;

use AsciiTable\Exception\BuilderException;
use Ds\Collection;

class Builder
{
    /**
     * @var string
     */
    const CHAR_CELL_SEPARATOR = '│';

    /**
     * @var string
     */
    const CHAR_LINE_SEPARATOR = '─';

    /**
     * @var string
     */
    const CHAR_CELL_PADDING = ' ';

    /**
     * @var string
     */
    const CHAR_JOIN_INNER = '┼';

    /**
     * @var string
     */
    const CHAR_CORNER_TOP_LEFT = '┌';

    /**
     * @var string
     */
    const CHAR_CORNER_TOP_RIGHT = '┐';

    /**
     * @var string
     */
    const CHAR_JOIN_LEFT_INNER = '├';

    /**
     * @var string
     */
    const CHAR_JOIN_RIGHT_INNER = '┤';

    /**
     * @var string
     */
    const CHAR_JOIN_TOP_INNER = '┬';

    /**
     * @var string
     */
    const CHAR_JOIN_BOTTOM_INNER = '┴';

    /**
     * @var string
     */
    const CHAR_CORNER_BOTTOM_LEFT = '└';

    /**
     * @var string
     */
    const CHAR_CORNER_BOTTOM_RIGHT = '┘';

    /**
     * @var Table
     */
    private $table;

    /**
     * @var string|null
     */
    private $title;

    public function __construct()
    {
        $this->table = new Table();
    }

    /**
     * Get the table
     *
     * @return Table
     */
    public function getTable() : Table
    {
        return $this->table;
    }

    /**
     * Add single row.
     * The value passed should be either an array or an JsonSerializable object
     *
     * @param array|\JsonSerializable $rowArrayOrObject
     * @throws BuilderException
     */
    public function addRow($rowArrayOrObject)
    {
        if (is_array($rowArrayOrObject)) {
            $rowArray = $rowArrayOrObject;
        } else if ($rowArrayOrObject instanceof \JsonSerializable) {
            $rowArray = $rowArrayOrObject->jsonSerialize();
        } else {
            throw new BuilderException(sprintf(
                'Row must be either an array or JsonSerializable, %s given instead',
                gettype($rowArrayOrObject)
            ));
        }

        $row = new Row();
        foreach ($rowArray as $columnName => $value) {
            $cell = new Cell($columnName, $value);
            $row->addCell($cell);
        }

        $this->table->addRow($row);
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * Add multiple rows
     *
     * @param array[]|\JsonSerializable[] $rows
     * @return void
     */
    public function addRows(array $rows)
    {
        foreach ($rows as $row) {
            $this->addRow($row);
        }
    }

    /**
     * Show only specific columns of the table
     *
     * @param array $columnNames
     * @return void
     * @throws BuilderException
     */
    public function showColumns(array $columnNames)
    {
        $this->table->setVisibleColumns($columnNames);
    }

    /**
     * Render table and return result string
     *
     * @return string
     * @throws BuilderException
     */
    public function renderTable() : string
    {
        if ($this->table->isEmpty()) throw new BuilderException('Cannot render empty table');

        $visibleColumns = $this->table->getVisibleColumns();

        // border for header and footer
        $borderParts = array_map(function ($columnName) {
            $width = $this->table->getColumnWidth($columnName);
            return str_repeat(self::CHAR_LINE_SEPARATOR, ($width + 2));
        }, $visibleColumns->toArray());

        $borderTop = self::CHAR_CORNER_TOP_LEFT
                . join(self::CHAR_JOIN_TOP_INNER, $borderParts)
                . self::CHAR_CORNER_TOP_RIGHT;
        $borderMiddle = self::CHAR_JOIN_LEFT_INNER
                . join(self::CHAR_JOIN_INNER, $borderParts)
                . self::CHAR_JOIN_RIGHT_INNER;
        $borderBottom = self::CHAR_CORNER_BOTTOM_LEFT
                . join(self::CHAR_JOIN_BOTTOM_INNER, $borderParts)
                . self::CHAR_CORNER_BOTTOM_RIGHT;

        $headerCells = array_map(function ($columnName) {
            return new Cell($columnName, $columnName);
        }, $visibleColumns->toArray());

        $headerRow = new Row();
        $headerRow->addCells(...$headerCells);
        $header = $this->renderRow($headerRow, $visibleColumns);

        $body = '';
        $rows = $this->table->getRows();
        $visibleColumns = $this->table->getVisibleColumns();
        foreach ($rows as $row) {
            $currentLine = $this->renderRow($row, $visibleColumns);
            $body .= $currentLine . PHP_EOL;
        }

        if ($this->title === null) {
            $titleString = '';
        } else {
            $titlePadding = intdiv(max(0, mb_strwidth($borderTop) - mb_strwidth($this->title)), 2);
            $titleString = str_repeat(' ', $titlePadding) . $this->title . PHP_EOL;
        }

        $tableAsString = $titleString . $borderTop . PHP_EOL . $header . PHP_EOL . $borderMiddle . PHP_EOL . $body . $borderBottom;
        return $tableAsString;
    }

    /**
     * Render single row and return string
     *
     * @param RowInterface $row
     * @param Collection $columnNames
     * @return string
     */
    private function renderRow(RowInterface $row, Collection $columnNames)
    {
        $line = self::CHAR_CELL_SEPARATOR;

        // render cells of the row
        foreach ($columnNames as $columnName) {
            $colWidth = $this->table->getColumnWidth($columnName);
            if ($row->hasCell($columnName)) {
                $cell = $row->getCell($columnName);
                $currentCell = $this->renderCell($cell, $colWidth);
            } else {
                $currentCell = $this->renderCell(new Cell($columnName, ''), $colWidth);
            }

            $line .= $currentCell . self::CHAR_CELL_SEPARATOR;
        }

        return $line;
    }

    /**
     * Render cell content with left and right padding depending on the column width
     *
     * @param CellInterface $cell
     * @param int $colWidth
     * @return string
     */
    private function renderCell(CellInterface $cell, int $colWidth) : string
    {
        $filler = str_repeat(self::CHAR_CELL_PADDING, ($colWidth - $cell->getWidth()));
        if ($cell->getAlign() == Cell::ALIGN_LEFT) {
            $content = self::CHAR_CELL_PADDING . $cell->getValue() . $filler . self::CHAR_CELL_PADDING;
        } else {
            $content = self::CHAR_CELL_PADDING . $filler . $cell->getValue() . self::CHAR_CELL_PADDING;
        }
        return $content;
    }
}
