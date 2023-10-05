<?php

namespace Tellico\Data;

use OutOfBoundsException;

class Collection {

    const TYPE_BASE = 1;
    const TYPE_BOOK = 2;
    const TYPE_VIDEO = 3;
    const TYPE_ALBUM = 4;
    const TYPE_BIBTEX = 5;
    const TYPE_COMICBOOK = 6;
    const TYPE_WINE = 7;
    const TYPE_COIN = 8;
    const TYPE_STAMP = 9;
    const TYPE_CARD = 10;
    const TYPE_GAME = 11;
    const TYPE_FILE = 12;
    const TYPE_BOARDGAME = 13;

    public function __construct(
        private string $title = '',
        private int $type = Collection::TYPE_BASE,
        private array $fields = [],
        private array $entries = [],
        private array $images = [],
        private array $borrowers = []
    ) {
    }

    public function getTitle () {
        return $this->title;
    }

    public function getType () {
        return $this->type;
    }

    public function getField ($name) {
        if (isset($this->fields[$name])) {
            return $this->fields[$name];
        }
        throw new OutOfBoundsException('unknown field name');
    }

    public function getFieldCategories () {
        $catList = [];
        foreach ($this->fields as $field) {
            $cat = $field->getCategory();
            $catList[$cat] = $cat;
        }
        return array_keys($catList);
    }

    public function getFieldCount () {
        return count($this->fields);
    }

    public function getFields () {
        return $this->fields;
    }

    public function getGroupFields () {
        $fieldList = [];
        foreach ($this->fields as $field) {
            if ($field->hasFlag(Field::FLAG_GROUPED)) {
                $fieldList[] = $field;
            }
        }
        return $fieldList;
    }

    public function getDefaultGroupField () {
        return $this->getGroupFields()[0];
    }

    public function getEntry ($id) {
        if (isset($this->entries[$id])) {
            return $this->entries[$id];
        }
        throw new OutOfBoundsException('unknown entry id');
    }

    public function getEntryCount () {
        return count($this->entries);
    }

    public function getEntries () {
        return $this->entries;
    }

    public function getImage ($id) {
        if (isset($this->images[$id])) {
            return $this->images[$id];
        }
        throw new OutOfBoundsException('unknown image id');
    }

    public function getImageCount () {
        return count($this->fields);
    }

    public function getImages () {
        return $this->images;
    }

    public function getBorrowers () {
        return $this->borrowers;
    }

}
