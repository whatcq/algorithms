<?php

function p($ditu)
{
    foreach ($ditu as $j => $row) {
        echo implode(' ', $row), "\n";
    }
    echo "\n";
}