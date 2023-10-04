<?php

namespace Tellico\Web;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


class Attachment {
    private $container;

    public function __construct (ContainerInterface $container) {
        $this->container = $container;
    }

    public function __invoke (ResponseInterface $response, string $database, string $entry, string $attachment): ResponseInterface {
        // your code to access items in the container... $this->container->get('');
        $response->getBody()->write("Attachment $attachment for entry $entry from $database.tc");
        return $response;
    }
}

