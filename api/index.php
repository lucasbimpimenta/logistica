<?php
    $request_method = $_SERVER["REQUEST_METHOD"];

    echo "request_method: " . $request_method . "</br>";

    echo '<pre>';
    print_r($_GET);
    print_r($_POST);
    echo '</pre>';
?>