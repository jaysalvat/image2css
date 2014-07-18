<?php ini_set('display_errors', true); ?>
<!doctype html>
<html>
<head>
<style>
#monalisa {
<?php
    include '../src/Image2Css/Converter.php';

    $img = new \Image2Css\Converter("monalisa.jpg");
    $img->setWidth(200);
    $img->setPixelSize(4);
    $img->setColorType(\Image2Css\Converter::HEXA);
    $img->setBlur(4);

    echo $img->computeStyle();
?>
}
</style>
</head>
<body>
    <div id="monalisa" />
</body>
</html>