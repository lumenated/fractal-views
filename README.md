# Fractal views
A view abstraction for Fractal making it more simple to integrate inside a framework.

![fractal views](https://cloud.githubusercontent.com/assets/4613944/24282298/f421469c-105e-11e7-9c8d-404ec7d05ad2.png)
[![Build Status](https://travis-ci.org/lumenated/fractal-views.svg?branch=master)](https://travis-ci.org/lumenated/fractal-views)
[![Coverage Status](https://coveralls.io/repos/github/lumenated/fractal-views/badge.svg?branch=master)](https://coveralls.io/github/lumenated/fractal-views?branch=master)
[![Packagist](https://img.shields.io/packagist/v/lumenated/fractal-views.svg)]()
[![Packagist](https://img.shields.io/packagist/dt/lumenated/fractal-views.svg)]()

# Installation
```sh
composer require lumenated/fractal-views
```

# Configuration
In order to use Fractal views for a resource we need to implement a transformer to map the resource to a serializable array and a view to present our resource to the consumer.
A View extends the `Lumenated\FractalViews\Views` class which exposes two methods:
- renderOne
  which renders a single resource to an array
- renderMany
  which renders multiple objects to an array with pagination support

```php
class BookView extends Lumenated\FractalViews\View 
{
  // The fractal transformer that has to be used for this view
  protected $transformerClass = BookTransformer::class;
}
```

Next up we need to implement the `BookTransformer`. Below is an example from the [fractal documentation](http://fractal.thephpleague.com/transformers/):

```php
<?php
namespace Acme\Transformer;

use Acme\Model\Book;
use League\Fractal;

class BookTransformer extends Fractal\TransformerAbstract
{
	public function transform(Book $book)
	{
	    return [
	        'id'      => (int) $book->id,
	        'title'   => $book->title,
	        'year'    => (int) $book->yr,
            'links'   => [
                [
                    'rel' => 'self',
                    'uri' => '/books/'.$book->id,
                ]
            ],
	    ];
	}
}
```

# Usage
After configuring our view they can be used inside our project.
Below is an example how to use them in a [Lumen](https://lumen.laravel.com/) controller:

```php
namespace \App\Http\Controllers;

class BookController extends Controller 
{
  private $view;
  
  public function __construct(BookView $view) 
  {
    $this->view = $view;
    
  }
  
  public function get($id) 
  {
    $book = Book::findOrFail($id);
    
    return response()->json($this->view->render($book));
  }
  
  public function getAll() 
  {
    $books = Book::all();
    
    return response()->json($this->view->render($books));
  }
}
```

# Running tests
after installing the dependencies with:
```sh
composer install
```

Execute the following command to execute the tests:
```sh
vendor/bin/phpunit
```
