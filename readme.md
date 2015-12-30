# Stencil #

Stencil is an interface plugin that enables the use of templating engines to be used for themeing.

To use Stencil in your theme you just need two things:

## Implementation ##

1. An implementation plugin, which provides an engine
1. A few lines of code in your theme to activate Stencil and let it know what you need

I have created some implementations to test the concept. All these implementations have their own repository and are functional.

All available implementations are (in alphabetical order):
* [Dwoo 2](https://github.com/moorscode/stencil-dwoo2)
* [Mustache](https://github.com/moorscode/stencil-mustache)
* [Savant 3](https://github.com/moorscode/stencil-savant)
* [Smarty 2.x](https://github.com/moorscode/stencil-smarty2)
* [Smarty 3.x](https://github.com/moorscode/stencil-smarty3)
* [Twig](https://github.com/moorscode/stencil-twig)

I personally prefer the use of Smarty 3.x, but you can use whatever makes you smile the most.

## Theme ##

Now all you need is to have your theme let Stencil know what implementation is required.

Because of the structure that template engines use I have create a couple of sample themes that can be used as bootstrap for creating new themes.

The basic breakdown is as follows:

* index.php - This file handles all template engine requests, nothing more, nothing less.
* functions.php - Register the implementation needed, optionally adjust settings
* views/ - This is the default folder to place your individual views in
* controllers/ - This is where you put your snippets of code to provide specific template variable generation/collection
* assets/ - This is the root for your css, js and images folders, though not required it keeps the theme folder neat and organised

You can see a basic implementation of this in the sample themes I've made to test the implementations.

* [Dwoo 2](https://github.com/moorscode/stencil-sample-theme-dwoo2)
* [Mustache](https://github.com/moorscode/stencil-sample-theme-mustache)
* [Savant 3](https://github.com/moorscode/stencil-sample-theme-savant)
* [Smarty 3.x](https://github.com/moorscode/stencil-sample-theme-smarty)
* [Twig](https://github.com/moorscode/stencil-sample-theme-twig)

(You might notice Smarty 2.x missing, this is because I highly recommend the use of Smarty 3.x. Though if you really insist, the only thing you need to adjust in the theme is replace "Smarty 3.x" to "Smarty 2.x" in the functions.php file)