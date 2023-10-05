<?php

namespace Tellico\Web;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Exception as E;
use Tellico\Template;
use Tellico\Data\File as Datafile;

class Contents {

    public function __construct (
        private ContainerInterface $container,
        private Template $template
    ) {}

    public function __invoke (ResponseInterface $response, string $database): ResponseInterface {
        // your code to access items in the container... $this->container->get('');
        $files = glob($_ENV['DATA_DIR'] . '/*.tc');
        $datafiles = [];
        foreach ($files as $file) {
            $datafiles[] = new Datafile($file);
        }
        $this->template->setDatafiles($datafiles);

        $file = realpath($_ENV['DATA_DIR'] . '/' . $database . '.tc');
        if (false === $file) throw new E('invalid tellico datafile `' . $database . '`');
        $datafile = new Datafile($file);
        $this->template->setDatafile($datafile);

        // $database = $file->getName();
        // $tx = $file->loadDefinition();

        // $body = $response->getBody();
        // $body->write("<h1>Contents of $database</h1><ul>");
        // foreach ($tx->collection->entry as $entry) {
        //     $body->write(sprintf('<li><a href="%s/%s/%s">%s</a></li>', $_ENV['BASE_PATH'], $file->getName('.tc'), $entry->id, $entry->title));
        // }
        // $body->write('</ul>');

        $response->getBody()->write($this->template->getContents());
        return $response;
    }
}

