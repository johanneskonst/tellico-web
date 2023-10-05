<?php

namespace Tellico\Data;

use Exception as E;

class Entry {

    public function __construct (
        private int $id,
        private string $title,
        private array $fields,
        private array $values
    ) {}

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

}
