<?php

namespace Tellico\Data;

use Exception as E;

class Image {

    // <image height="150" id="577...jpeg" width="170" format="jpeg"/>
    public function __construct(
        private string $id,
        private int $width,
        private int $height,
        private string $format
    ) {}

    public function getId () {
        return $this->id;
    }

    public function getWidth () {
        return $this->width;
    }

    public function getHeight () {
        return $this->height;
    }

    // !! https://codebrowser.dev/qt5/qtbase/src/gui/image/qimagewriter.cpp.html#835
    public function getFormat () {
        return $this->format;
    }

    public function getData (File $file) {
        throw new E('not implemented');
    }

}
