<?php

function readableEntry($value) {
    if ($value == '0') {
        echo "No";
    } else {
        echo "Yes";
    }
}

function stripDate($date) {
    $date = explode(' ', $date);
    echo $date[0];
}

function formatDate($getDate) {
    $getDate = explode(' ', $getDate);
    $date = str_replace('-', '/', $getDate[0]);
    $time = DateTime::createFromFormat('H:i:s', $getDate[1]);
    echo "$date" . " at " . $time->format('g:ia');
}

function formatPhone($phone_number) {
    echo "(".substr($phone_number, 0, 3).") ".substr($phone_number, 3, 3)."-".substr($phone_number,6);
}

function colourLabel($status) {
    switch ($status) {
        case '1':
            echo "label-default";
            break;
        case '2':
            echo "label-info";
            break;
        case '3':
            echo "label-primary";
            break;
        case '4':
            echo "label-success";
            break;
        case '5':
            echo "label-warning";
            break;
        case '6':
            echo "label-danger";
            break;
        case '7':
            echo "label-warning";
            break;
    }
}