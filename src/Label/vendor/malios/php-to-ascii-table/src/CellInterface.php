<?php

namespace AsciiTable;

interface CellInterface
{
    /**
     * Get value of the cell
     *
     * @return string
     */
    public function getValue() : string;

    /**
     * Set value of the cell.
     *
     * @param mixed $value
     */
    public function setValue($value);

    /**
     * Get the name of the column that the cell belongs to
     *
     * @return string
     */
    public function getColumnName() : string;

    /**
     * Get the alignment of the cell
     *
     * @return int
     */
    public function getAlign(): int;

    /**
     * Set the name of the column that the cell belongs to
     *
     * @param string $columnName
     */
    public function setColumnName(string $columnName);

    /**
     * Get the width (string length) of the cell
     *
     * @return int
     */
    public function getWidth() : int;
}
