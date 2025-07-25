<?php

namespace Tests\Feature;

use SortedLinkedList\LinkedList;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SortedLinkedList\Enum\Sort;
use Exception;

class SortedListTest extends TestCase
{
    /**
     * @param $input
     * @param $control
     * @return void
     */
    #[DataProvider('dataSetAdd')]
    public function test_it_can_nodes($input, $control)
    {
        if ($control === Exception::class) {
            $this->expectException(Exception::class);
        }

        $list = LinkedList::new($input['sort']);

        foreach ($input['set'] as $value) {
            $list->add($value);
        }

        $this->assertSame($control, $list->toArray());
    }

    #[DataProvider('dataSetRemove')]
    public function test_it_can_remove_nodes($input, $control)
    {
        if ($control === Exception::class) {
            $this->expectException(Exception::class);
        }

        $list = LinkedList::new($input['sort'])
            ->fromArray($input['set']);

        foreach ($input['remove'] as $value) {
            $list->remove($value);
        }

        $this->assertSame($control, $list->toArray());
    }

    /**
     * @return array[]
     */
    public static function dataSetAdd(): array
    {
        return [
            'ascending integers simple' => [
                [
                    "set"   => [1, 4, 3],
                    "sort"  => Sort::ASC,
                ],
                [1, 3, 4]
            ],
            'descending strings simple' => [
                [
                    "set"   => ['cat', 'zebra', 'ball'],
                    "sort"  => Sort::DESC,
                ],
                ['zebra', 'cat', 'ball']
            ],
            'mixed types throws exception' => [
                [
                    "set"   => ['cat', 1, 4],
                    "sort"  => Sort::DESC,
                ],
                Exception::class
            ],
            'ascending with duplicates' => [
                [
                    "set"   => [3, 3, 1, 3, 2],
                    "sort"  => Sort::ASC,
                ],
                [1, 2, 3, 3, 3]
            ],
            'descending integers with negative' => [
                [
                    "set"   => [1, -3, 5, 0, -1],
                    "sort"  => Sort::DESC,
                ],
                [5, 1, 0, -1, -3]
            ],
            'ascending single element' => [
                [
                    "set"   => [42],
                    "sort"  => Sort::ASC,
                ],
                [42]
            ],
            'empty set' => [
                [
                    "set"   => [],
                    "sort"  => Sort::ASC,
                ],
                []
            ],
            'ascending strings with numbers' => [
                [
                    "set"   => ['a1', 'a10', 'a2', 'a20'],
                    "sort"  => Sort::ASC,
                ],
                ['a1', 'a10', 'a2', 'a20']
            ],
            'descending with special characters' => [
                [
                    "set"   => ['z@z', 'a#a', 'b&b'],
                    "sort"  => Sort::DESC,
                ],
                ['z@z', 'b&b', 'a#a']
            ],
            'ascending with identical values' => [
                [
                    "set"   => [5, 5, 5, 5],
                    "sort"  => Sort::ASC,
                ],
                [5, 5, 5, 5]
            ],
            'descending large numbers' => [
                [
                    "set"   => [999999, 100000, 500000],
                    "sort"  => Sort::DESC,
                ],
                [999999, 500000, 100000]
            ],
            'ascending case sensitive strings' => [
                [
                    "set"   => ['Apple', 'apple', 'Zebra', 'zebra'],
                    "sort"  => Sort::ASC,
                ],
                ['Apple','apple','Zebra','zebra']
            ],
            'type mismatch mid-sequence' => [
                [
                    "set"   => [1, 2, "oops", 4],
                    "sort"  => Sort::ASC,
                ],
                Exception::class
            ],
            'ascending with zero' => [
                [
                    "set"   => [5, 0, 3, -1],
                    "sort"  => Sort::ASC,
                ],
                [-1, 0, 3, 5]
            ]

        ];
    }

    /**
     * @return array[]
     */
    public static function dataSetRemove(): array
    {
        return [
            'remove first element' => [
                [
                    "set"    => [1, 4, 3],
                    'remove' => [1],
                    "sort"   => Sort::ASC,
                ],
                [3, 4]
            ],
            'remove multiple elements' => [
                [
                    "set"    => ['cat', 'zebra', 'ball'],
                    'remove' => ['zebra', 'ball'],
                    "sort"   => Sort::DESC,
                ],
                ['cat']
            ],
            'remove from empty list' => [
                [
                    "set"    => [],
                    'remove' => [1],
                    "sort"   => Sort::ASC,
                ],
                []
            ],
            'remove non-existent element' => [
                [
                    "set"    => [1, 2, 3],
                    'remove' => [4],
                    "sort"   => Sort::ASC,
                ],
                [1, 2, 3]
            ],
            'remove last element' => [
                [
                    "set"    => [1, 2, 3],
                    'remove' => [3],
                    "sort"   => Sort::ASC,
                ],
                [1, 2]
            ],
            'remove middle element' => [
                [
                    "set"    => [1, 2, 3],
                    'remove' => [2],
                    "sort"   => Sort::ASC,
                ],
                [1, 3]
            ],
            'remove all elements' => [
                [
                    "set"    => [1, 2, 3],
                    'remove' => [1, 2, 3],
                    "sort"   => Sort::ASC,
                ],
                []
            ],
            'type mismatch removal' => [
                [
                    "set"    => [1, 2, 3],
                    'remove' => ["string"],
                    "sort"   => Sort::ASC,
                ],
                Exception::class
            ],
            'remove from single element list' => [
                [
                    "set"    => [1],
                    'remove' => [1],
                    "sort"   => Sort::ASC,
                ],
                []
            ],
            'remove with duplicates in list' => [
                [
                    "set"    => [1, 2, 2, 3, 2],
                    'remove' => [2],
                    "sort"   => Sort::ASC,
                ],
                [1, 2, 2, 3]
            ],
            'descending order remove' => [
                [
                    "set"    => [5, 4, 3, 2, 1],
                    'remove' => [3],
                    "sort"   => Sort::DESC,
                ],
                [5, 4, 2, 1]
            ],
            'remove with special characters' => [
                [
                    "set"    => ['a@b', 'b#c', 'c$d'],
                    'remove' => ['b#c'],
                    "sort"   => Sort::ASC,
                ],
                ['a@b', 'c$d']
            ],
            'remove multiple consecutive elements' => [
                [
                    "set"    => [1, 2, 3, 4, 5],
                    'remove' => [2, 3, 4],
                    "sort"   => Sort::ASC,
                ],
                [1, 5]
            ],
            'attempt remove with empty removal list' => [
                [
                    "set"    => [1, 2, 3],
                    'remove' => [],
                    "sort"   => Sort::ASC,
                ],
                [1, 2, 3]
            ],
            'remove negative numbers' => [
                [
                    "set"    => [-3, -2, -1, 0, 1],
                    'remove' => [-2, 0],
                    "sort"   => Sort::ASC,
                ],
                [-3, -1, 1]
            ]

        ];
    }
}