<?php

namespace Tellico\Data;

use Exception as E;
use SimpleXMLElement;
use ZipArchive;

class File {
    private string $filename;
    private ZipArchive $za;
    private SimpleXMLElement $tx;
    private int $sz;
    private int $mt;

    public function __construct(string $filename) {
        $this->filename = $filename;
        // <mime-type type="application/x-tellico">
        // <sub-class-of type="application/zip"/>
        // <comment>Tellico Data File</comment>
        // <glob pattern="*.tc"/>
        // <glob pattern="*.bc"/>
        if (!is_readable($this->filename) || !is_file($this->filename)) throw new E('not a readable file');
        if ('application/zip' !== mime_content_type($this->filename)) throw new E('not a tellico file');
        $this->za = new ZipArchive;
        $this->za->open($this->filename, ZipArchive::RDONLY);
        $t = $this->za->statName('tellico.xml');
        if (false !== $t && is_array($t) && isset($t['size'])) {
            $this->sz = $t['size'];
            $this->mt = $t['mtime'];
        }
        else {
            $this->za->close();
            throw new E('not a real tellico file');
        }
    }

    public function getName($suffix = '') {
        return basename($this->filename, $suffix);
    }

    public function getSize() {
        return $this->sz;
    }

    public function getFormattedSize() {
        $a = ['b', 'Kb', 'Mb', 'Gb', 'Tb'];
        $i = 0;
        $s = $a[$i];
        $b = $this->getSize();
        while ($b > 1024 && $i < 5) {
            $b = round($b / 1024, 2);
            $s = $a[++$i];
        }
        return $b.$s;
    }

    public function getModified() {
        return $this->mt;
    }

    public function getFormattedModified() {
        return date(DATE_RFC7231, $this->getModified());
    }

    public function loadDefinition() {
        $this->tx = simplexml_load_string($this->za->getFromName('tellico.xml'));
        return $this->tx;
    }

    public function __destruct() {
        if ($this->za instanceof ZipArchive) $this->za->close();
    }

}