<?php

loadcache('plugin');
if (is_array($_G['cache']['plugin']['dc_pay']))
{
    return true;
} else {
    return false;
}