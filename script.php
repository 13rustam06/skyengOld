<?php

function sum($num1, $num2)
{
    $result = "";
    $flag = 0;
    $num1Count = strlen($num1);
    $num2Count = strlen($num2);
    $maxNumCount = max($num1Count, $num2Count);

    for ($i = $maxNumCount - 1; $i >= 0; $i--)
    {
        $diff1 = $maxNumCount - $num1Count;
        $diff2 = $maxNumCount - $num2Count;
        $num1t = ($diff1 <= $i) ? substr($num1, $i - $diff1, 1) : 0;
        $num2t = ($diff2 <= $i) ? substr($num2, $i - $diff2, 1) : 0;
        $num = $num1t + $num2t + $flag;
        if ($num >= 10)
        {
            $num -= 10;
            $flag = 1;
        }
        else
        {
            $flag = 0;
        }
        $result = $num.$result;
    }
    if ($flag == 1)
    {
        $result = $flag;
    }
    return $result;
}

// example
echo sum("100080000000000000000089000", "60000000000780000007000");

