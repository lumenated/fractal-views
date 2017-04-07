<?php

namespace Lumenated\FractalViews;


use League\Fractal\Manager;
use League\Fractal\Pagination\Cursor;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Traversable;

/**
 * Class View
 * @package Lumenated\FractalViews
 */
abstract class View
{
    /**
     * @var
     */
    protected $transformerClass;

    /**
     * @var Manager
     */
    private $manager;

    /**
     * View constructor.
     * @param Manager $manager
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Renders a single resource or collection into a serializable array
     * @param $resource
     * @param null $cursor
     * @description render a collection or single item to an array using a transformer
     * @return array
     */
    public function render($resource, $cursor = null)
    {
        if (is_array($resource) || $resource instanceof Traversable) {
            return $this->renderMany($resource, $cursor);
        }

        return $this->renderOne($resource);
    }

    /**
     * Renders a single resource to a serializable array
     * @param $resource
     * @return array
     */
    public function renderOne($resource)
    {
        $transformer = new $this->transformerClass;
        $item = new Item($resource, $transformer);

        return $this->manager->createData($item)->toArray();
    }

    /**
     * Renders a multiple resources to a serializable array with optional pagination support
     * @param $resources
     * @param Cursor|null $cursor
     * @return array
     */
    public function renderMany($resources, Cursor $cursor = null)
    {
        $transformer = new $this->transformerClass;
        $collection = new Collection($resources, $transformer);
        if ($cursor) {
            $collection->setCursor($cursor);
        }

        return $this->manager->createData($collection)->toArray();
    }
}