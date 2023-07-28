<?php
namespace Ds;

use Error;
use OutOfBoundsException;

/**
 * A “last in, first out” or “LIFO” collection that only allows access to the
 * value at the top of the structure and iterates in that order, destructively.
 *
 * @package Ds
 *
 * @template TValue
 * @implements Sequence<TValue>
 */
final class Stack implements Collection, \ArrayAccess
{
    use Traits\GenericCollection;

    /**
     * @var Vector internal vector to store values of the stack.
     *
     * @psalm-var Vector<TValue>
     */
    private $vector;

    /**
     * Creates an instance using the values of an array or Traversable object.
     *
     * @param iterable<mixed> $values
     *
     * @psalm-param iterable<TValue> $values
     */
    public function __construct(iterable $values = [])
    {
        $this->vector = new Vector($values);
    }

    /**
     * Clear all elements in the Stack
     */
    public function clear()
    {
        $this->vector->clear();
    }

    /**
     * @inheritdoc
     */
    public function copy(): self
    {
        return new self($this->vector);
    }

    /**
     * Returns the number of elements in the Stack
     */
    public function count(): int
    {
        return count($this->vector);
    }

    /**
     * Ensures that enough memory is allocated for a specified capacity. This
     * potentially reduces the number of reallocations as the size increases.
     *
     * @param int $capacity The number of values for which capacity should be
     *                      allocated. Capacity will stay the same if this value
     *                      is less than or equal to the current capacity.
     */
    public function allocate(int $capacity)
    {
        $this->vector->allocate($capacity);
    }

    /**
     * Returns the current capacity of the stack.
     */
    public function capacity(): int
    {
        return $this->vector->capacity();
    }

    /**
     * Returns the value at the top of the stack without removing it.
     *
     * @return mixed
     *
     * @throws \UnderflowException if the stack is empty.
     *
     * @psalm-return TValue
     */
    public function peek()
    {
        return $this->vector->last();
    }

    /**
     * Returns and removes the value at the top of the stack.
     *
     * @return mixed
     *
     * @throws \UnderflowException if the stack is empty.
     *
     * @psalm-return TValue
     */
    public function pop()
    {
        return $this->vector->pop();
    }

    /**
     * Pushes zero or more values onto the top of the stack.
     *
     * @param mixed ...$values
     *
     * @psalm-param TValue ...$values
     */
    public function push(...$values)
    {
        $this->vector->push(...$values);
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return array_reverse($this->vector->toArray());
    }

    /**
     *
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        while ( ! $this->isEmpty()) {
            yield $this->pop();
        }
    }

    /**
     * @inheritdoc
     *
     * @throws OutOfBoundsException
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            $this->push($value);
        } else {
            throw new Error();
        }
    }

    /**
     * @inheritdoc
     *
     * @throws Error
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        throw new Error();
    }

    /**
     * @inheritdoc
     *
     * @throws Error
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        throw new Error();
    }

    /**
     * @inheritdoc
     *
     * @throws Error
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        throw new Error();
    }

    /**
     * Ensures that the internal vector will be cloned too.
     */
    public function __clone()
    {
        $this->vector = clone $this->vector;
    }
}
