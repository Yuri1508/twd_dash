<?php
    require 'class/api.class.php';


    //*
    $api = new twd();

    $Dstart = $_POST['Dstart'];

    $Dend = $_POST['Dend'];

    $data = $api->getData($Dstart,$Dend);

    echo $data;
?>