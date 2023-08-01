<?php declare(strict_types=1);

namespace AsciiTable;

use Ds\Map;
use Ds\Set;

class Table implements TableInterface
{
    /**
     * @var RowInterface[]
     */
    private $rows = [];

    /**
     * @var Set
     */
    private $visibleColumns;

    /**
     * @var Set
     */
    private $allColumns;

    /**
     * @var Map
     */
    private $biggestValues;

    public function __construct()
    {
        $this->visibleColumns = new Set();
        $this->allColumns = new Set();
        $this->biggestValues = new Map();
    }

    /**
     * {@inheritdoc}
     */
    public function addRow(RowInterface $row)
    {
        foreach ($row->getCells() as $cell) {
            $columnName = $cell->getColumnName();

            $this->allColumns->add($columnName);

            $width = $cell->getWidth();
            if ($this->biggestValues->hasKey($columnName)) {
                if ($width > $this->biggestValues->get($columnName)) {
                    $this->biggestValues->put($columnName, $width);
                }
            } else {
                $this->biggestValues->put($columnName, $width);
            }
        }

        array_push($this->rows, $row);
    }

    /**
     * {@inheritdoc}
     */
    public function getRows() : array
    {
        return $this->rows;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty() : bool
    {
        return empty($this->rows);
    }

    /**
     * {@inheritdoc}
     */
    public function setVisibleColumns(array $columnNames)
    {
        $this->visibleColumns->clear();
        $this->visibleColumns->allocate(count($columnNames));
        $this->visibleColumns->add(...$columnNames);
    }

    /**
     * {@inheritdoc}
     */
    public function getVisibleColumns() : Set
    {
        if ($this->visibleColumns->isEmpty()) {
            return $this->getAllColumns();
        }

        return $this->visibleColumns;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllColumns() : Set
    {
        return $this->allColumns;
    }

    /**
     * {@inheritdoc}
     */
    public function getColumnWidth(string $columnName) : int
    {
        $width = 0;
        if ($this->biggestValues->hasKey($columnName)) {
            $width = $this->biggestValues->get($columnName);
        }

        $visibleColumns = $this->getVisibleColumns();
        if ($visibleColumns->contains($columnName) && mb_strwidth($columnName) > $width) {
            $width = mb_strwidth($columnName);
        }

        return $width;
    }
}
