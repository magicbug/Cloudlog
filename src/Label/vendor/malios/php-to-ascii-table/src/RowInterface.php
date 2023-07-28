<?php

namespace AsciiTable;

use Ds\Collection;

interface RowInterface
{
    /**
     * Add single cell to the row
     *
     * @param CellInterface $cell
     */
    public function addCell(CellInterface $cell);

    /**
     * Add multiple cells to row
     *
     * @param CellInterface ...$cells
     */
    public function addCells(CellInterface ...$cells);

    /**
     * Get single cell by name
     *
     * @param $columnName
     * @return CellInterface
     */
    public function getCell($columnName) : CellInterface;

    /**
     * Check if the row has a cell cell for given column
     *
     * @param $columnName
     * @return bool
     */
    public function hasCell($columnName) : bool;

    /**
     * Get all cells
     *
     * @return Collection
     */
    public function getCells() : Collection;
}
