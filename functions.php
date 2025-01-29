<?php
function formatDate($dateString) {
    $date = new DateTime($dateString);
    return [
        'posted_on' => $date->format('Y-m-d'),
        'sent_on' => $date->format('F j, Y')
    ];
}