<?php

namespace Tellico\Web;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Exception as E;

class Overview {
    private $container;

    public function __construct (ContainerInterface $container) {
        $this->container = $container;
    }

    public function __invoke (ResponseInterface $response): ResponseInterface {
        // your code to access items in the container... $this->container->get('');
        $files = glob($_ENV['DATA_DIR'] . '/*.tc');
        $body = $response->getBody();
        $body->write("<h1>Overview</h1><ul>");
        foreach ($files as $file) {
            $file = new \Tellico\Data\File($file);
            $body->write(sprintf('<li><a href="%s/%s">%s</a> <i>(%s %s)</i></li>', $_ENV['BASE_PATH'], $file->getName('.tc'), $file->getName(), $file->getFormattedSize(), $file->getFormattedModified()));
        }
        $body->write('</ul>');
        return $response;
    }
}

