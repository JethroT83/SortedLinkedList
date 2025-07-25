<?php

namespace SortedLinkedList;

class Node
{
    /**
     * @var Node|null
     */
    private ?Node $next = null;

    /**
     * @param string|int $value
     */
    public function __construct(private readonly string|int $value){}

    /**
     * @return int|string
     */
    public function getValue(): int|string
    {
        return $this->value;
    }

    /**
     * @return Node|null
     */
    public function getNext(): ?Node
    {
        return $this->next;
    }

    /**
     * @param Node|null $next
     * @return void
     */
    public function setNext(?Node $next): void
    {
        $this->next = $next;
    }

    /**
     * @return bool
     */
    public function isLast(): bool
    {
        return null === $this->next;
    }
}