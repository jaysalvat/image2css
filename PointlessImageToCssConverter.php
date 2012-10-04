<?php
/*
    Pointless Image to CSS box-shadow Converter
    Copyright (c) 2012, Jay Salvat
    http://jaysalvat.com

    DoWhateverYouWantWithIt-ButDontAbuse License.

    THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" 
    AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE 
    IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE 
    ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE 
    LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL 
    DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR 
    SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER 
    CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, 
    OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE 
    OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/
Class PointlessImageToCssConverter {    
    private $path;
    private $type;
    private $width;
    private $blur;
    private $color_type;
    private $pixel_size;
    private $image;

    CONST HEXA = 0;
    CONST RGBA = 2;

    public function __construct($filename) {
        $this->setPath($filename);
        $this->setColorType(PointlessImageToCssConverter::HEXA);
        $this->setWidth(100);
        $this->setPixelSize(8);
        $this->setBlur(0);
    }

    public function setColorType($type) {
        if ($type !== PointlessImageToCssConverter::HEXA 
         && $type !== PointlessImageToCssConverter::RGBA) {
            throw new Exception('Color type not allowed.');
        }
        $this->color_type = $type;
    }

    public function getColorType() {
        return $this->color_type;
    }

    public function setWidth($width) {
        $this->width = $width;
    }

    public function getWidth() {
        return $this->width;
    }

    public function setPath($path) {
        $this->path = $path;
    }

    public function getPath() {
        return $this->path;
    }

    public function setBlur($blur) {
        $this->blur = $blur;
    }

    public function getBlur() {
        return $this->blur;
    }

    public function setPixelSize($size) {
        $this->pixel_size = $size;
    }

    public function getPixelSize() {
        return $this->pixel_size;
    }

    public function load($filename) {
        $image_info = @getimagesize($filename);

        if ($image_info) {
            $this->type = $image_info[2];

            switch ($this->type) {
                case IMAGETYPE_JPEG:
                    $this->image = imagecreatefromjpeg($filename);
                break;
                case IMAGETYPE_PNG:
                    $this->image = imagecreatefrompng($filename);
                break;
                default:
                    throw new Exception('Image Type not allowed.');
            }
        } else {
            throw new Exception('The file must be a valid image.');
        }
    }

    public function getImageWidth() {
        return imagesx($this->image);
    }

    public function getImageHeight() {
        return imagesy($this->image);
    }

    public function resize($width) {
        $ratio  = $width / $this->getImageWidth();
        $height = $this->getImageHeight() * $ratio;
       
        $new_image = imagecreatetruecolor($width, $height);
        imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getImageWidth(), $this->getImageHeight());
        $this->image = $new_image;
    }

    public function getColorMap() {
        $colors = array();
        $iw = $this->getImageWidth();
        $ih = $this->getImageHeight();
        for($h = 0; $h < $ih; $h++) {
            $colors[$h] = array();
            for($w = 0; $w < $iw; $w++) {
                $a = imagecolorat($this->image, $w, $h);
                $b = imagecolorsforindex($this->image, $a);
                $colors[$h][$w] = $b;
            }
        }
        return $colors;
    }

    public function computeStyle() {
        $this->load($this->getPath());
        $this->resize($this->getWidth());

        $nb = $this->getImageWidth() * $this->getImageHeight();
        $step = $this->getPixelSize();
        $pixels = $this->getColorMap();

        $style  = "    width:0;\n";
        $style .= "    height:0;\n";
        $style .= "    box-shadow:\n";
        foreach($pixels as $row => $cols) {
            $style .= "\n";
            foreach($cols as $col => $colors) {
                $style .= '   ';
                $style .= sprintf("%4s", $col * $step)."px ";
                $style .= sprintf("%2s", $row * $step)."px ";
                $style .= $this->getBlur() ? $this->getBlur()."px " : "0 ";
                $style .= $step."px ";

                if ($this->color_type === PointlessImageToCssConverter::RGBA) {
                    $style .= "rgba(".$colors["red"].",".$colors["green"].",".$colors["blue"].",1)";
                } else {
                    $style .= strtoupper(
                        '#'.
                        $this->rgb2hexa($colors['red']).
                        $this->rgb2hexa($colors['green']).
                        $this->rgb2hexa($colors['blue'])
                    );
                }
                $style .=",\n";
            }
        }
        return preg_replace('/,$/', ';', $style);
    }

    public function __toString() {
        return $this->computeStyle();
    }

    private function rgb2hexa($value) {
        return str_pad(dechex($value), 2, '0', STR_PAD_LEFT);
    }
}