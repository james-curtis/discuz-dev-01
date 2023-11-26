<?php
$urls = array();
for ($i=0;$i<10000;$i++)
{
    $urls[] = $i;
}
$count = count($urls);
$temp_urls = $urls;
unset($urls);
$ret_ = 0;
$total = ceil($count/2000);
for ($i = 0;$i < $total;$i++)
{
    $offset = $i*2000;
    $offset_ = $offset+1000;
    $urls = array_slice($temp_urls,$offset,$offset_);
    $a = $urls;
    echo  $i."\t\t";
    echo '|begin:'.array_shift($a)."\t\t";
    echo '|end:'.$a[count($a)-1]."\t\t";
    echo '|offset:'.$offset.'->'.$offset_."\t\t";
    echo '|get out:'.($offset_-$offset)."\t\t";
    echo '|total:'.count($urls)."\t\t";
    echo "\n";
    $urls = array();
}
echo count(array_slice($temp_urls,3000,5000));