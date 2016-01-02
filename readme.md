# Stencil

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/moorscode/stencil/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/moorscode/stencil/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/moorscode/stencil/badges/build.png?b=master)](https://scrutinizer-ci.com/g/moorscode/stencil/build-status/master)

Stencil gives you the power to use a proper template engine in your WordPress theme.

Because of the rules for WordPress plugins, Stencil is going to be setup as an "in-theme" framework.
This repository will exist and be used for updating the plugin and providing usage documentation.

To use Stencil you need two things:

1. An implementation plugin, which provides an engine
1. A few lines of code in your theme to activate Stencil and let it know what you need

Fortunately Stencil provides a settings page where you can easily install an Implementation and sample theme.

Stencil requires your theme to have a specific basic structure.
There are few requirements and a couple of starter themes to get you familiar with the
structure.

The most important thing to know is that all logic is routed through `index.php`.
The minimal contents of your `index.php` looks like this:

    <?php

    if ( function_exists( 'get_stencil' ) {
	    get_stencil()->run();
    }

    ?>

You need to let Stencil know what engine you are requiring. The example shows the request for the 'Stencil 3.x' implementation:

    <?php

    // Hook into the require filter to tell Stencil what Implementation we need.
    add_filter( 'stencil:require', 'stencil_theme_implementation' );
    function stencil_theme_implementation() {
    	return 'Smarty 3.x';
    }

    ?>

If you don't have any files that require the regular WordPress file handling, you can make sure that only the index.php file is used by supplying the following filter:

    <?php

    // enable redirecting everthing to 'index.php'
    add_filter( 'stencil:template_index_only', '__return_true' );

    ?>


To be able to display a page, all you need to do is create an `index` file in your `views` folder. This file needs
to have the extention that is default for your templating engine i.e. `.tpl` for Smarty, `.html` for Twig and so on.

The filenames you are used working with in WordPress are also working in the `views` folder. For example, if you want
to create an author page you can use the `author.tpl` template file.

Archive-type views can be placed in the `archive` folder inside your `views` and `singular`-type views can be placed
inside the `single` folder.


    themes/{your_theme_name}/assets/     img/js/css folders
    themes/{your_theme_name}/views/      template files
    themes/{your_theme_name}/controllers/

Paths that should be excluded from version control but should exist and be writable:

    uploads/{your_theme_name}/cache/      template cache gets written here
    uploads/{your_theme_name}/compiled/   compiled templates get written here

### Using custom template files

Template files can be created as you are used to do in normal WordPress themes.

**template-custom-1.php**

    <?php
    /**
     **Template Name: {your template name}
     */

Inside your custom template you can call up the controller and add your custom required
data to the view. This will keep all the logic in one place.

Alternatively you could also use the 'stencil:template-custom-1' filter to hook your data
in the view.

The view that will be loaded will be searched for in the views/custom folder first.


    themes/{your_theme_name}/views/custom/template-custom-1.tpl


### Setting variables to the engine

When you hook into one of the engine hooks the engine will be passed as variable to the callback.

    <?php

    add_action( 'stencil.front_page', function( $controller ) {
        $controller->set( 'my_variable', 'my_value' );
    } );

If you want to load a separate file to handle the logic you have to fetch the controller via the
like this:

    <?php

    $stencil = get_stencil();

    ?>

#### Recording data into a variable

Because you cannot always retrieve the code you want to pass to your template via get functions Stencil
has a recording capability.

To capture a piece of output you can simply use the following code:

    <?php

    $stencil = get_stencil();
    $stencil->start_recording( 'my_variable' );

    echo 'This will be captured';
    the_title();

    $stencil->stop_recording();

The variable 'my_variable' will be available containing all output that has been generated between the two
functions.

### Template Handler

The core Stencil class uses Flow to determine what page and view need to be used.
A handler is the part of Stencil that only handles the engine of an implementation.

This can be useful when you are writing an AJAX function that needs to return a snippet of HTML.
You can request a new handler and provide the view that the handler needs to fetch or display.

    <?php

    $handler = get_stencil_handler();
    $handler->set( 'variable', 'value' );
    $handler->display( 'snippets/some_snippet' );

    ?>

# Implementation

To use Stencil you need an active implementation and a theme that requires that implementation.

We have created some implementations to test the concept. All these implementations have their own repository and are functional.

All available implementations are (in alphabetical order):
* [Dwoo 2](https://github.com/moorscode/stencil-dwoo2)
* [Mustache](https://github.com/moorscode/stencil-mustache)
* [Savant 3](https://github.com/moorscode/stencil-savant)
* [Smarty 2.x](https://github.com/moorscode/stencil-smarty2)
* [Smarty 3.x](https://github.com/moorscode/stencil-smarty3)
* [Twig](https://github.com/moorscode/stencil-twig)

I personally prefer the use of Smarty 3.x, but you can use whatever makes you smile the most.

## Theme

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

## Stencil Hooks

Listed below are all available hooks

*   **stencil.initialize**

    Called when: the engine is loaded and an addon has registered itself
    Parameters: $stencil
    Use case: register globally needed variables

*	**stencil.pre_display** + **stencil.pre_fetch**

	Called when: executing fetch or display of a specific template
	Default: setting of wp_head and wp_footer variables
	Use case: Adding advertisments when all the other information is available

*   **stencil.singular**

    Called when: a page is loaded that is either {single, post, attachement}

*   **stencil.paged**

    Called when: the current page has pagination

*   **stencil.{page}**

    Called when: WordPress has determined we are loading a certain page

    Pages:
    * **stencil.404**
    * **stencil.search**
    * **stencil.front_page**
    * **stencil.home**
    * **stencil.archive_{post_type}**
    * **stencil.single**
    * **stencil.{post_type}**
    * **stencil.ID**
    * **stencil.{custom_template.php}**
    * **stencil.{custom_template}**
    * **stencil.attachment**
    * **stencil.attachment_{ID}**
    * **stencil.comments_popup**
    * **stencil.tax**
    * **stencil.tag**
    * **stencil.date**
    * **stencil.category**
    * **stencil.category_{name}**
    * **stencil.author**
    * **stencil.author_{ID}**
    * **stencil.archive**

## Stencil Filters

Listed below are all implemented filters

*   **stencil:require**

    Called when: Applying the assets path and redirecting of all WordPress template executions to `index.php`
    Use case: Make sure this returns anything but `false` to enable optimal use of Stencil

* 	**stencil:set**

	Called when: Setting the value of a variable
	Use case: Allows for global filtering of template data; append, prepend, wrap or otherwise

*   **stencil:assets_path**

    Called when: Applying the assets path
    Use case: By default `assets` like images, css and javascript are placed in the `assets` folder inside the theme
    directory. `get_template_directory_uri()` will return the `assets` folder instead of the theme folder for ease
    of use.

*   **stencil:implementation**

    Called when: Stencil is ready for an addon to register themself
    Use case: Register your addon at this point and figure out if another addon has registered itself already

*   **stencil:post_as_object**

    Called when: On a singular page
    Use case: Determines if the 'post' variable will be an array or object

*   **stencil:force_permalink**

    When the page is requested in a different way then permalink, redirect to the permalink or not

*   **stencil:page-{page}** + **stencil:page**

    Called when: The page has been determined but the view has not been abstracted
    Use case: Override the page to force loading another view

*   **stencil:views**

    Called when: The view hierarchy has been build for a page
    Use case: Override what views will be searched for to display the request

*   **stencil:view-{view}** + **stencil:view**

    Called when: The view has been determined based upon the page and available view files
    Use case: Override the view that will be loaded

## Human Made - WordPress Objects

Support for the Human Made WordPress Objects has been implemented fully.
If the classes are loaded they will be used to set the variables 'post', 'taxonomy' and 'user'.

*note: this is an optional feature*