<?php

namespace SortedLinkedList;

use SortedLinkedList\Enum\Sort;

class LinkedList
{
    /**
     * @var Node|null
     */
    private ?Node $firstNode = null;

    /**
     * @var string|null
     */
    private ?string $dataType = null;

    /**
     * @param Sort $sort
     */
    public function __construct(private readonly Sort $sort = Sort::ASC){}

    /**
     * @param Sort $sort
     * @return self
     */
    public static function new(Sort $sort = Sort::ASC): self
    {
        return new self($sort);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $array = [];
        $current = $this->firstNode;
        while ($current !== null) {
            $array[] = $current->getValue();
            $current = $current->getNext();
        }

        return $array;
    }

    /**
     * @param array $values
     * @return LinkedList
     * @throws \Exception
     */
    public function fromArray(array $values): LinkedList
    {
        $linkedList = self::new($this->sort);
        foreach ($values as $value) {
            $linkedList->add($value);
        }

        return $linkedList;
    }

    /**
     * @param string|int $val
     * @return $this
     * @throws \Exception
     */
    public function add(string|int $val): static
    {
        // Check if the value type matches the list's type
        $type = gettype($val);
        if ($this->dataType === null) {
            $this->dataType = $type;
        } else if ($this->dataType !== $type) {
            throw new \Exception("Invalid datatype");
        }

        $newNode = new Node($val);

        // Empty list case
        if ($this->firstNode === null) {
            $this->firstNode = $newNode;
            return $this;
        }

        // Insert at beginning if needed
        if ($this->shouldInsertBefore($val, $this->firstNode->getValue())) {
            $newNode->setNext($this->firstNode);
            $this->firstNode = $newNode;
            return $this;
        }

        // Find insertion point
        $current = $this->firstNode;
        while ($current->getNext() !== null &&
            !$this->shouldInsertBefore($val, $current->getNext()->getValue())) {
            $current = $current->getNext();
        }

        // Insert after current node
        $newNode->setNext($current->getNext());
        $current->setNext($newNode);

        return $this;
    }

    /**
     * @param string|int $val
     * @return $this
     * @throws \Exception
     */
    public function remove(string|int $val): static
    {
        // Check if the value type matches the list's type
        $type = gettype($val);
        if ($this->dataType !== null && $this->dataType !== $type) {
            throw new \Exception("Invalid datatype");
        }

        // Handle empty list
        if ($this->firstNode === null) {
            return $this;
        }

        // Handle first node removal
        if ($this->firstNode->getValue() === $val) {
            $this->firstNode = $this->firstNode->getNext();
            return $this;
        }

        // Search for node to remove
        $current = $this->firstNode;
        while ($current->getNext() !== null && $current->getNext()->getValue() !== $val) {
            $current = $current->getNext();
        }

        // If we found the value
        if ($current->getNext() !== null) {
            $current->setNext($current->getNext()->getNext());
        }

        return $this;
    }

    /**
     * @param string|int $newVal
     * @param string|int $existingVal
     * @return bool
     */
    private function shouldInsertBefore(string|int $newVal, string|int $existingVal): bool
    {
        if ($this->dataType === 'string') {
            $comparison = strcasecmp($newVal, $existingVal);
            return $this->sort === Sort::ASC ? $comparison < 0 : $comparison > 0;
        }

        return $this->sort === Sort::ASC ? $newVal < $existingVal : $newVal > $existingVal;
    }
}