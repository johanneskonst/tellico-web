<?php

namespace Tellico\Data;

class Field {

    const TYPE_UNDEF = 0;
    const TYPE_LINE = 1;
    const TYPE_PARA = 2;
    const TYPE_CHOICE = 3;
    const TYPE_BOOL = 4;
    const TYPE_READONLY = 5; // deprecated in favor of FieldFlags::NoEdit
    const TYPE_NUMBER = 6;
    const TYPE_URL = 7;
    const TYPE_TABLE = 8;
    const TYPE_TABLE2 = 9; // deprecated in favor of property("columns")
    const TYPE_IMAGE = 10;
    const TYPE_DEPENDENT = 11; // deprecated in favor of FieldFlags::Derived
    const TYPE_DATE = 12;
    const TYPE_KEYWORD = 13; // Michael Zimmermann used 13 for his Keyword field, so go ahead and skip it
    const TYPE_RATING = 14; // similar to a Choice field, but allowed values are numbers only
    // if you add your own field type, best to start at a high number say 100, to ensure uniqueness

    const FORMAT_PLAIN = 0;
    const FORMAT_TITLE = 1;
    const FORMAT_NAME = 2;
    const FORMAT_DATE = 3;
    const FORMAT_NONE = 4;

    // enum FormatFlag {
    //     FormatPlain     = 0,   // format plain, allows capitalization
    //     FormatTitle     = 1,   // format as a title, i.e. shift articles to end
    //     FormatName      = 2,   // format as a personal full name
    //     FormatDate      = 3,   // format as a date
    //     FormatNone      = 4    // no format, i.e. no capitalization allowed
    //   };

    const FLAG_MULTIPLE = 1;
    const FLAG_GROUPED = 2;
    const FLAG_AUTOCOMPLETE = 4;
    const FLAG_NODELETE = 8;
    const FLAG_NOEDIT = 16;
    const FLAG_DERIVED = 32;

    // enum FieldFlag {
    //     AllowMultiple   = 1 << 0,   // allow multiple values, separated by a semi-colon
    //     AllowGrouped    = 1 << 1,   // this field can be used to group entries
    //     AllowCompletion = 1 << 2,   // allow auto-completion
    //     NoDelete        = 1 << 3,   // don't allow user to delete this field
    //     NoEdit          = 1 << 4,   // don't allow user to edit this field
    //     Derived         = 1 << 5    // dependent value
    //   };

    public function __construct (
        private string $name,
        private string $title,
        private string $description,
        private int $type,
        private int $format,
        private string $category,
        private int $flags,
        private array $properties,
    ) {}

// <field
//     name="id"
//     title="ID"
//     description=""
//     type="6"
//     format="4"
//     category="Personal"
//     flags="32"
// >
//     <prop name="template">%{@id}</prop>
// \</field>

    public function getName () {
        return $this->name;
    }

    public function getTitle () {
        return $this->title;
    }

    public function getDescription () {
        return $this->description;
    }

    public function getType () {
        return $this->type;
    }

    public function getFormat () {
        return $this->format;
    }

    public function getCategory () {
        return $this->category;
    }

    public function getFlags () {
        return $this->flags;
    }

    public function getProperties () {
        return $this->properties;
    }

    public function hasFlag ($flag) {
        return ($flag === ($this->flags & $flag));
    }

}
