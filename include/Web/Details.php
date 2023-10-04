<?php

namespace Tellico\Web;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Exception as E;


class Details {
    private $container;

    public function __construct (ContainerInterface $container) {
        $this->container = $container;
    }

    public function __invoke (ResponseInterface $response, string $database, string $entry): ResponseInterface {
        // your code to access items in the container... $this->container->get('');
        $file = realpath($_ENV['DATA_DIR'] . '/' . $database . '.tc');
        if (false === $file) throw new E('invalid tellico file `' . $database . '`');
        $file = new \Tellico\Data\File($file);
        $database = $file->getName();
        $tx = $file->loadDefinition();

        foreach ($tx->collection->entry as $e) {
            if ($e->id == $entry) {
                $entry = $e;
                break;
            }
        }
        if (is_numeric($entry)) throw new E('invalid entry ' . $entry);

        $title = (string) $entry->title;
        $body = $response->getBody();
        $body->write("<h1>Details of $title</h1><ul>");
        $afb = null;
        foreach ($entry->children() as $k => $v) {
            if ($k == 'afbeelding' && $v) $afb = $v;
            $body->write(sprintf('<li>%s: %s</li>', $k, $v));
        }
        $body->write('</ul>');
        if ($afb) {
            $w = $h = null;
            foreach ($tx->collection->images->image as $im) {
                if ($im['id'] == $afb) {
                    $w = (int) $im['width'];
                    $h = (int) $im['height'];
                }
            }
            $wh = null;
            if ($w && $h) {
                while ($w > 1000) {
                    $w /= 10;
                    $h /= 10;
                }
                $wh = 'width="'.floor($w).'" height="'.floor($h).'" ';
            }
            $body->write(sprintf('<img src="%s/%s/%s/%s" %s/>', $_ENV['BASE_PATH'], $file->getName('.tc'), $entry->id, $afb, $wh));
        }
        return $response;
    }
}

