<?php

$urls = array();
for ($i=0;$i<10000;$i++)
{
    $urls[] = $i;
}
$count = count($urls);
$total = ceil($count/2000);
$tmp = array();
for ($i = 0;$i < $total;$i++)
{
    for ($a=0;$a<2000;$a++)
    {
        $tmp[] = array_shift($urls);
    }
    echo count($tmp).'<br>';
    unset($tmp);
}