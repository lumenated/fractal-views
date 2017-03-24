<?php

namespace Test\Lumenated\FractalViews;

use League\Fractal\Manager;
use League\Fractal\Pagination\Cursor;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use PHPUnit\Framework\TestCase;
use Lumenated\FractalViews\View;

class ViewTest extends TestCase
{
    /** @var  Manager */
    private $manager;

    /** @var  View */
    private $view;

    public function setUp()
    {
        parent::setUp();
        $this->manager = new Manager();
        $this->view = new ExampleView($this->manager);
    }

    public function testRenderOne()
    {
        $resource = new Example(1);
        $item = new Item($resource, new ExampleTransformer());

        $this->assertEquals($this->manager->createData($item)->toArray(), $this->view->renderOne($resource));
    }

    /** @dataProvider renderManyProvider */
    public function testRenderMany($resources)
    {
        $collection = new Collection($resources, new ExampleTransformer());

        $this->assertEquals($this->manager->createData($collection)->toArray(), $this->view->renderMany($resources));
    }

    /** @dataProvider renderManyProvider */
    public function testRenderManyWithCursor($resources)
    {
        $cursor = new Cursor(0, 1, 2, 3);
        $collection = new Collection($resources, new ExampleTransformer());
        $collection->setCursor($cursor);

        $this->assertEquals($this->manager->createData($collection)->toArray(), $this->view->renderMany($resources, $cursor));
    }

    public function renderManyProvider()
    {
        return [
            [array_map(function ($id) {
                return new Example($id);
            }, range(0, 10))]
        ];
    }
}

class Example
{
    private $id;

    /**
     * Example constructor.
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}

class ExampleTransformer extends TransformerAbstract
{
    public function transform(Example $example)
    {
        return ['id' => $example->getId()];
    }
}

class ExampleView extends View
{
    protected $transformerClass = ExampleTransformer::class;
}
