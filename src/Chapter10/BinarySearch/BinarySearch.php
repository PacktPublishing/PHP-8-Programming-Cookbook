<?php

namespace Cookbook\Chapter10\BinarySearch;

class BinarySearch
{
    public function __call($name, int|array $arguments)
    {
        // Manual method overloading:
        if ($name === 'search') {
            if (is_int($arguments[1])) {
                echo "\tRunning basic search: \n";
                return $this->searchSingleDimension($arguments[0], $arguments[1]);
            } else {
                if (is_array($arguments[1])) {
                    echo "\tRunning associative array criteria search: \n";
                    return $this->executeAllAssociativeSearchMethods($arguments[0], $arguments[1]);
                } else {
                    throw new \Exception("Invalid search method");
                }
            }
        }
    }

    private function executeAllAssociativeSearchMethods(array $arg1, array $arg2): array
    {
        $startTime1 = microtime(true);
        $result1 = $this->searchAssociative($arg1, $arg2);
        $endTime1 = microtime(true);
        $executionTime1 = number_format($endTime1 - $startTime1, 6);

        $startTime2 = microtime(true);
        $result2 = $this->searchAssociativeHeap($arg1, $arg2);
        $endTime2 = microtime(true);
        $executionTime2 = number_format($endTime2 - $startTime2, 6);

        return [
            "binary_search" => [
                "result" => $result1,
                "execution_time" => $executionTime1,
            ],
            "heap_search" => [
                "result" => $result2,
                "execution_time" => $executionTime2,
            ],
            "execution_times" => [
                "binary_search" => $executionTime1,
                "heap_search" => $executionTime2,
                "winner" => (float)$executionTime1 < (float)$executionTime2 ? $executionTime1 : $executionTime2
            ]
        ];
    }

    private function searchSingleDimension(array $arr, int $target): ?int
    {
        $left = 0;
        $right = count($arr) - 1;

        while ($left <= $right) {
            $mid = (int)floor(($left + $right) / 2);

            if ($arr[$mid] === $target) {
                return $mid;
            }

            if ($arr[$mid] < $target) {
                $left = $mid + 1;
            } else {
                $right = $mid - 1;
            }
        }

        return null;
    }

    function searchAssociative(array $dataArray, array $searchTerms): ?array
    {
        $sortedKeys = array_keys($dataArray);
        sort($sortedKeys, SORT_NATURAL);

        $startIndex = 0;
        $endIndex = count($sortedKeys) - 1;

        while ($startIndex <= $endIndex) {
            $middleIndex = (int)floor(($startIndex + $endIndex) / 2);
            $middleKey = $sortedKeys[$middleIndex];
            $middleItem = $dataArray[$middleKey];

            $criteriaMatched = true;
            foreach ($searchTerms as $searchTermKey => $searchTermValue) {
                if (is_array($searchTermValue)) {
                    foreach ($searchTermValue as $subSearchTermKey => $subSearchTermValue) {
                        if (!isset($middleItem[$searchTermKey][$subSearchTermKey]) || $middleItem[$searchTermKey][$subSearchTermKey] !== $subSearchTermValue) {
                            $criteriaMatched = false;
                            break 2;
                        }
                    }
                } else {
                    if (!isset($middleItem[$searchTermKey]) || $middleItem[$searchTermKey] !== $searchTermValue) {
                        $criteriaMatched = false;
                        break;
                    }
                }
            }

            if ($criteriaMatched) {
                return ['id' => $middleKey] + $middleItem;
            }

            $firstSearchTermKey = array_key_first($searchTerms);
            if (isset($middleItem[$firstSearchTermKey]) && isset($searchTerms[$firstSearchTermKey])) {
                if ($middleItem[$firstSearchTermKey] < $searchTerms[$firstSearchTermKey]) {
                    $startIndex = $middleIndex + 1;
                } else {
                    $endIndex = $middleIndex - 1;
                }
            } else {
                return null;
            }
        }

        return null;
    }

    private function searchAssociativeHeap(array $dataArray, array $searchTerms): ?array
    {
        $heap = new HeapSearch($searchTerms);

        foreach ($dataArray as $key => $item) {
            $heap->insert(['key' => $key, 'item' => $item]);
        }

        $heap->top();

        while ($heap->valid()) {
            $current = $heap->current();
            $item = $current['item'];

            if ($heap->match($item)) {
                return ['id' => $current['key']] + $item;
            }

            $heap->next();
        }

        return null;
    }
}