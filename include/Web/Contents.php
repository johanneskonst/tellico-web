<?php

namespace Tellico\Web;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Exception as E;

class Contents {
    private $container;

    public function __construct (ContainerInterface $container) {
        $this->container = $container;
    }

    public function __invoke (ResponseInterface $response, string $database): ResponseInterface {
        // your code to access items in the container... $this->container->get('');
        $file = realpath($_ENV['DATA_DIR'] . '/' . $database . '.tc');
        if (false === $file) throw new E('invalid tellico file `' . $database . '`');
        $file = new \Tellico\Data\File($file);
        $database = $file->getName();
        $tx = $file->loadDefinition();

        $body = $response->getBody();
        $body->write("<h1>Contents of $database</h1><ul>");
        foreach ($tx->collection->entry as $entry) {
            $body->write(sprintf('<li><a href="%s/%s/%s">%s</a></li>', $_ENV['BASE_PATH'], $file->getName('.tc'), $entry->id, $entry->title));
        }
        $body->write('</ul>');
        return $response;
    }
}

