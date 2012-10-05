<!doctype html>
<html>
<head>
<style>
#monalisa {
<?php
    include 'PointlessImageToCssConverter.php';

    $img = new PointlessImageToCssConverter("monalisa.jpg");
    $img->setWidth(200);
    $img->setPixelSize(4);
    $img->setColorType(PointlessImageToCssConverter::HEXA);
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