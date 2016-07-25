#HTML Handler
Don't want ugly `echo '<a href=""></a>'` statements anymore? Use xTend's HTML handler
to build your DOM elements!

###Getting the HTML handler
To get your current HTML handler, all your app's `getHTMLHandler()` method. With the handler you can start creating your HTML.

###Creating a document
To start making your HTML you first need a document. You can use `createDocument()` for this. This will return an `HTMLDocument` object which allows you to start creating your html elements. You can also pass in a variable to specify whether xTend should consider it a full HTML document. This means you won't have to add the html element to your document.

###Creating an HTML element
Use the `createElement()` method on your document (or any other previously created HTML element if you need child elements) to create a new element. The function accepts 2 parameters being the name of the DOM element and an array with attributes.

####Example
```
    $p = $document->createElement('p', [
        'class' => 'a-class'
    ]);
    $p->createElement('span');
```
This will result in following DOM structure
```
    <p>
        <span></span>
    </p>
```

###Adding text to an HTML element
You can add text to an element by calling `addText()` on an HTML element.
*Notice text always comes before child elements*

####Example
```
    $p = $document->createElement('p', [
        'class' => 'a-class'
    ]);
    $p->addText('Hi ');
    $p->createElement('span')->addText('user');
```
This will result in following DOM structure
```
    <p>
        Hi
        <span>user</span>
    </p>
```

###Adding an HTML element
Whenever you already have 2 HTML elements and want one of them to be a child of the other you can use the `addElement()` method. Just pass in the HTML element you want to add as a child to the function and you're good to go.
*Notice it's advised to just use createElement on the right HTML instead*

###Writing out your HTML
You can call the `write()` method on any HTML element to write out the html. It is however advised to just call it on your original document to write out the whole thing. By default the `write()` method will solely return a string but if you want to `echo` your html out as well you can pass `true` as a parameter.
