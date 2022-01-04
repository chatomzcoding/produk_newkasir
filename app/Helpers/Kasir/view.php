<?php

if (! function_exists('showstatus')) {
    function showstatus($status)
    {
       switch ($status) {
           case 'selesai':
               $html = "<span class='badge badge-success'>SELESAI</span>";
               break;
           case 'proses':
               $html = "<span class='badge badge-warning'>PROSES</span>";
               break;
           case 'retur':
               $html = "<span class='badge badge-dark'>RETUR</span>";
               break;
           
           default:
               $html = NULL;
               break;
       }
       return $html;
    }
}