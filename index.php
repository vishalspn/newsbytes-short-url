<?php
require_once './ShortUrl.php';
$path = $_SERVER['REQUEST_URI'];
$obj = new ShortUrl();
switch ($path) {
    case '/newsbytes-short-url/api/createlink':
        $res = $obj->createShortUrl($_POST);
        header('Content-Type: text/json; charset=utf-8');
        echo json_encode($res);
        die;
    default:
        $res = $obj->redirect($path);
        if (!empty($res)) {
            header('Content-Type: text/html; charset=utf-8');
            $reshtml = <<<EOD
        <html>
            <body>
                <p>Redirecting to $res</P>
            </body>
            <script>
                window.location.href = '$res';
            </script>
        </html>

EOD;
            echo $reshtml;
        }

}
