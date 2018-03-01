<?php

function GetMapDiff($arrayOldData, $arrayNewData)
{
    $differ = new Diff\Differ\MapDiffer();
    $result = [];
    $diffs = $differ->doDiff($arrayOldData, $arrayNewData);
    foreach ($diffs as $key => $diff) {
        if ($diff->getType() == 'change') {
            $newValue = $diff->getNewValue();
            $oldValue = $diff->getOldValue();
            if (is_array($newValue) && is_array($oldValue)) {
                $result[$key] = GetDiff($oldValue, $newValue);
            } else {
                $result[$key] = $diff;
            }
        }
        $result[$key] = $diff;
    }
}