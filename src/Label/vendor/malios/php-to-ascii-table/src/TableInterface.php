<?php

namespace AsciiTable;

use Ds\Set;

interface TableInterface
{
    /**
     * Add single row to the table
     *
     * @param RowInterface $row
     */
    public function addRow(RowInterface $row);

    /**
     * Get all rows in the table
     *
     * @return RowInterface[]
     */
    public function getRows() : array;

    /**
     * Check if the table is empty.
     *
     * @return bool
     */
    public function isEmpty() : bool;

    /**
     * Set visible columns
     *
     * @param string[] $columnNames
     */
    public function setVisibleColumns(array $columnNames);

    /**
     * Get visible columns
     *
     * @return Set
     */
    public function getVisibleColumns() : Set;

    /**
     * Get all columns in the table
     *
     * @return Set
     */
    public function getAllColumns() : Set;

    /**
     * Get the width of a column by name
     *
     * @param string $columnName
     * @return int
     */
    public function getColumnWidth(string $columnName) : int;
}
