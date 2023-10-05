<?php

namespace Tellico;

use Psr\Container\ContainerInterface;
use Exception as E;
use Tellico\Data\File as Datafile;
use Tellico\Data\Entry;

class Template {
    private $container;
    /** @var Datafile[] $datafiles */
    private array $datafiles; // list of datafiles
    private ?Datafile $datafile; // selected datafile
    private ?Entry $entry; // selected entry;

    public function __construct (ContainerInterface $container) {
        $this->container = $container;
        $this->datafiles = [];
        $this->datafile = null;
        $this->entry = null;
        // your code to access items in the container... $this->container->get('');
    }

    public function setDatafiles (array $datafiles) {
        $this->datafiles = $datafiles;
    }

    public function setDatafile (Datafile $datafile) {
        $this->datafile = $datafile;
    }

    public function setEntry (Entry $entry) {
        $this->entry = $entry;
    }

    public function getDetails () {
        ob_start();
        include $_ENV['TEMPLATE_DIR'] . '/partial/header.php';
        include $_ENV['TEMPLATE_DIR'] . '/details.php';
        include $_ENV['TEMPLATE_DIR'] . '/partial/footer.php';
        return ob_get_clean();
    }

    public function getContents () {
        ob_start();
        include $_ENV['TEMPLATE_DIR'] . '/partial/header.php';
        include $_ENV['TEMPLATE_DIR'] . '/contents.php';
        include $_ENV['TEMPLATE_DIR'] . '/partial/footer.php';
        return ob_get_clean();
    }

    public function getOverview () {
        ob_start();
        include $_ENV['TEMPLATE_DIR'] . '/partial/header.php';
        include $_ENV['TEMPLATE_DIR'] . '/overview.php';
        include $_ENV['TEMPLATE_DIR'] . '/partial/footer.php';
        return ob_get_clean();
    }

}