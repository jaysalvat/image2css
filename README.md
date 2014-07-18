# Pointless Image To CSS Converter

You suck at CSS but want to impress your friends anyway?
This PHP class converts an image to thousands of CSS lines of box-shadow properties. It's totally pointless... but life is pointless...

## Demo:

You can see a demonstration on [CodePen.io](http://codepen.io/jaysalvat/pen/HaqBf).

http://codepen.io/jaysalvat/pen/HaqBf

## Example:

    $img = new \Image2Css\Converter("monalisa.jpg");
    $img->setWidth(200);
    $img->setPixelSize(4);
    $img->setBlur(4);

    echo $img->computeStyle();

## Note:

GD extension required.

http://www.php.net/manual/en/book.image.php
    