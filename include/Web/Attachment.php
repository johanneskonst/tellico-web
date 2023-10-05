<?php

namespace Tellico\Web;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Exception as E;
use Tellico\Data\File as Datafile;

class Attachment {

    public function __construct (
        private ContainerInterface $container
    ) {}

    public function __invoke (ResponseInterface $response, string $database, string $entry, string $attachment): ResponseInterface {
        // your code to access items in the container... $this->container->get('');
        $file = realpath($_ENV['DATA_DIR'] . '/' . $database . '.tc');
        if (false === $file) throw new E('invalid tellico file `' . $database . '`');
        $file = new Datafile($file);
        //$database = $file->getName();
        $tx = $file->loadDefinition();
        foreach ($tx->collection->images->image as $im) {
            // <image format="jpeg" id="a8b60d485c9167487bdf780757af8987.jpeg" width="5850" height="8000"/>
            if ($im['id'] == $attachment) {
                if (($att = $file->getFile('images/' . $im['id'])) !== false) {
                    $response->getBody()->write($att);
                    return $response->withHeader('Content-Type', 'image/' . $im['format']);
                }
                if (($file = realpath($_ENV['DATA_DIR'] . '/' . $database . '_files/' . $im['id'])) !== false) {
                    $response->getBody()->write(file_get_contents($file));
                    return $response->withHeader('Content-Type', 'image/' . $im['format']); 
                }
            }
        }
        $response->getBody()->write(file_get_contents(APP_ROOT . '/assets/icons/nocover_book.png'));
        return $response->withHeader('Content-Type', 'image/png');
    }
}
