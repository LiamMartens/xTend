# HTML Handler
Want to avoid `echo '<a href=""></a>` statements? You can use xTend's HTML handler to build your DOM elements.

## Getting started
To get started you must first create a document using the HTML handler class. The document acts as a container for multiple elements which you can write out all at once.
```
namespace Application;
use Application\Core\HTMLHandler;

$document = HTMLHandler::create();
```

*Notice this returns an instance of `HTMLDocument` which inherits `HTMLElement`. Both classes can be found in `Objects/HTMLHandler.php`*

## Creating elements
When you have your document you can start creating elements, to do so you have to use the `create()` method of the `HTMLElement` class. The method accepts 1 or 2 parameters. The first one is the name of the element and the second one is an optional array of attributes.
```
$element = $document->create('li', [
    'class' => 'list__item'
]);
$link = $element->create('a', [
    'href' => 'https://google.com'
]);
```
As you can see, you can easily create child elements using the `create()` function as well.

## Adding text
Besides creating HTML elements you can also add text to an element by using the `text()` method. The function accepts a maximum of 2 parameters. The first one is the content and the second one is a boolean defining whether to put the text before the child elements or after (by default it is before).
```
$link = $element->create('a', [
    'href' => 'https://google.com'
])->text('Visit google');
```

## Writing out your HTML
To write out your html you can use the `write()` method. If you execute this function without passing any parameters it will just return the HTML as string but you can also pass `true` as argument if you want to `echo` the content as well.

*Notice if you pass `true` to the `create()` method of your document, the `write()` will also add your standard html structure*

