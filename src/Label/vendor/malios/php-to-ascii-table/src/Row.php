<?php declare(strict_types=1);

namespace AsciiTable;

use Ds\Map;
use Ds\Collection;

class Row implements RowInterface
{
    /**
     * @var Map
     */
    private $cells;

    public function __construct()
    {
        $this->cells = new Map();
    }

    /**
     * {@inheritdoc}
     */
    public function addCell(CellInterface $cell)
    {
        $this->cells->put($cell->getColumnName(), $cell);
    }

    /**
     * {@inheritdoc}
     */
    public function addCells(CellInterface ...$cells)
    {
        foreach ($cells as $cell) {
            $this->addCell($cell);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCell($columnName) : CellInterface
    {
        return $this->cells->get($columnName);
    }

    /**
     * {@inheritdoc}
     */
    public function hasCell($columnName) : bool
    {
        return $this->cells->hasKey($columnName);
    }

    /**
     * {@inheritdoc}
     */
    public function getCells() : Collection
    {
        return $this->cells;
    }
}
