# Models
In MVC the Models are used to handle most of your data retrieval. xTend has simple Model support for you to use.

## Creating a Model
To create a model you can either choose to create a file manually or use the `workbench`. If you use the workbench you'll just have to call the `new:model` command and it will create it for you with some default model code. If not you'll have create the file and add the code yourself.

The default `Model` class allows for database interaction using xTend's xORM, read more about xORM in another section, but you don't have to extend it for your application to work.
