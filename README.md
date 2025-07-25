# Sorted Linked List

A PHP implementation of a self-sorting linked list that maintains elements in either ascending or descending order. Supports both integer and string values.

## Features

- Automatically maintains sorted order (ascending or descending)
- Supports both integer and string values (cannot mix types)
- Fluent interface for method chaining
- Type safety enforcement
- Case-sensitive string sorting

## Installation

Install via Composer:

`composer require jedlynch/sorted-linked-list`


## Usage

### Creating a New List
```<?php 

use SortedLinkedList\LinkedList; 
use SortedLinkedList\Enum\Sort;

// Create an ascending sorted list (default)
$list = LinkedList::new();

// Or create a descending sorted list
$list = LinkedList::new(Sort::DESC)
```

### Adding Elements
```
<?php 

// Add individual elements 
$list->add(3)->add(1)->add(4);

// Create from array 
$list = LinkedList::new()->fromArray([3, 1, 4]);

// Result: [1, 3, 4] for ascending order 
// Result: [4, 3, 1] for descending order
```

### Removing Elements
```
<?php

// Removes the first occurrence of 3
$list->remove(3); 
```

### Converting to Array

```
<?php 

$array = $list->toArray();
```

### Working with Strings
```
<?php 

$list = LinkedList::new(Sort::ASC);list->add("banana")
    ->add("apple")
    ->add("cherry");

// Result: ["apple", "banana", "cherry"]
```

## Type Safety

The list enforces type safety - you cannot mix strings and integers in the same list:
```
<?php 

$list = LinkedList::new();
$list->add(1); 
$list->add("string"); 

// Throws Exception
```

## Examples

```
<?php 

// Creating a descending number list 
$numbers = LinkedList::new(Sort::DESC);numbers->fromArray([1, 5, 3, 2, 4]);

// Outputs: 5, 4, 3, 2, 1
echo implode(', ', $numbers->toArray()); 

// Creating an ascending string list 
$words = LinkedList::new(Sort::ASC)
    ->add("zebra") 
    ->add("apple")
    ->add("banana"); 

// Outputs: apple, banana, zebra
echo implode(', ', $words->toArray()); 
```

## Requirements

- PHP 8.1 or higher
- Composer


## License

MIT License
