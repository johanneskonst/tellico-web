<?php

namespace Tellico\Web;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Exception as E;
use Tellico\Template;
use Tellico\Data\File as Datafile;

class Overview {

    public function __construct (
        private ContainerInterface $container,
        private Template $template
    ) {}

    public function __invoke (ResponseInterface $response): ResponseInterface {
        // your code to access items in the container... $this->container->get('');
        $files = glob($_ENV['DATA_DIR'] . '/*.tc');
        $datafiles = [];
        foreach ($files as $file) {
            $datafiles[] = new Datafile($file);
        }
        $this->template->setDatafiles($datafiles);
        $response->getBody()->write($this->template->getOverview());
        return $response;
    }
}

