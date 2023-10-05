<?php

namespace Tellico\Data;

use Exception as E;
use SimpleXMLElement;
use ZipArchive;

class File {
    private string $filename;
    private ZipArchive $za;
    private SimpleXMLElement $tx;
    private Collection $collection;
    private array $fields = [];
    private array $entries = [];
    private array $images = [];
    private array $borrowers = [];

    public function __construct(string $filename) {
        $this->filename = $filename;
        // <mime-type type="application/x-tellico">
        // <sub-class-of type="application/zip"/>
        // <comment>Tellico Data File</comment>
        // <glob pattern="*.tc"/>
        // <glob pattern="*.bc"/>
        if (!is_readable($this->filename) || !is_file($this->filename)) throw new E('not a readable file');
        if ('application/zip' !== mime_content_type($this->filename) || !$this->openFile()) throw new E('not a valid tellico file');
        $this->loadDefinition();
    }

    private function openFile () {
        $this->za = new ZipArchive;
        if (true !== $this->za->open($this->filename, ZipArchive::RDONLY)) return false;
        if (false === $this->za->statName('tellico.xml')) {
            $this->za->close();
            return false;
        }
        return true;
    }

    private function loadDefinition() {
        $this->tx = simplexml_load_string($this->za->getFromName('tellico.xml'));
        foreach ($this->tx->collection->fields->field as $xf) {
            $properties = [];
            foreach ($xf->prop as $p) {
                $properties[(string) $p['name']] = (string) $p;
            }
            $f = new Field(
                (string) $xf['name'],
                (string) $xf['title'],
                (string) $xf['description'],
                (int) $xf['type'],
                (int) $xf['format'],
                (string) $xf['category'],
                (int) $xf['flags'],
                $properties
            );
            $this->fields[$f->getName()] = $f;
        }
        foreach ($this->tx->collection->entry as $xe) {
            $fields = $values = [];
            foreach ($this->fields as $fieldName => $field) {
                if (Field::FORMAT_DATE == $field->getFormat()) {
                    $fieldValue = (int) $xe->$fieldName->year . '-'
                                . (int) $xe->$fieldName->month . '-'
                                . (int) $xe->$fieldName->day;
                }
                else {
                    $fieldValue = (string) $xe->$fieldName;
                }
                $fields[] = $fieldName;
                $values[$fieldName] = $fieldValue;
            }
            $e = new Entry(
                (int) $xe['id'],
                (string) $xe->title,
                $fields,
                $values
            );
            $this->entries[$e->getId()] = $e;
        }
        foreach ($this->tx->collection->images->image as $xi) {
            $i = new Image(
                (string) $xi['id'],
                (int) $xi['width'],
                (int) $xi['height'],
                (string) $xi['format']
            );
            $this->images[$i->getId()] = $i;
        }
        $this->collection = new Collection(
            (string) $this->tx->collection['title'],
            (int) $this->tx->collection['type'],
            $this->fields,
            $this->entries,
            $this->images,
            $this->borrowers
        );
    }

    public function isContained() {
        return (!is_dir(dirname($this->filename) . '/' . basename($this->filename, '.tc') . '_files'));
    }

    public function getCollection() {
        return $this->collection;
    }

    public function getFile($filename) {
        if ($this->za->statName($filename) !== false) {
            return $this->za->getFromName($filename);
        }
        return false;
    }

    public function getName($suffix = '') {
        return basename($this->filename, $suffix);
    }

    public function getSize() {
        return filesize($this->filename);
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
        return filemtime($this->filename);
    }

    public function getFormattedModified() {
        return date(DATE_RFC7231, $this->getModified());
    }

    public function __destruct() {
        if ($this->za instanceof ZipArchive) $this->za->close();
    }

}