=== Stencil ===
Contributors: jipmoors
Donate link: https://goo.gl/qDlCPC
Tags: stencil, template, themeing, smarty, dwoo, twig, interface, developer, artist, front-end
Requires at least: 3.0
Tested up to: 4.4
Stable tag: 1.0
License: GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Stencil is an interface plugin that enables the use of templating engines to be used for themeing.

== Description ==
Stencil is a plugin that enables the use of templating engines in your theme.
You need to install and activate a Stencil Implementation that implements the templating engine that you need.

Multiple Implementations have been created by us and are available in the plugin directory or at https://github.com/moorscode/stencil/

If you need help to get started on your theme (or just need a clean slate with an optimal structure)
feel free to using one of our example themes that we have created for you!

Check them out at https://github.com/moorscode/stencil/

== Installation ==
1. Upload the folder `stencil` to the `/wp-content/plugins/` directory or use the WordPress build in plugin-installer
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Install the Stencil Implementation of the engine that you want to use
1. Activate the addon you have installed
1. Start building the theme from scratch or using one of our example themes

Stencil requires your theme to have a specific basic structure.
There are few requirements and a couple of starter themes to get you familiar with the
structure. Find in depth documentation at http://www.jipmoors.nl/stencil/getting-started/

The most important thing to know is that all logic is routed through `index.php`.
The minimal contents of your `index.php` looks like this:

    <?php

    if ( function_exists( 'get_stencil' ) {
	    get_stencil()->run();
    }

    ?>

You need to let Stencil know what engine you are requiring:

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


    themes/{your_theme_name}/assets/     img/js/css
    themes/{your_theme_name}/views/      template files
    themes/{your_theme_name}/controllers/

Paths that should be excluded from version control but should exist and be writable:

    uploads/{your_theme_name}/cache/      template cache gets written here
    uploads/{your_theme_name}/compiled/   compiled templates get written here

= Using custom template files =

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


= Using the engine in your code =

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

= Recording data into a variable =

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

= Template Handler =

The core Stencil class uses Flow to determine what page and view need to be used.
A handler is the part of Stencil that only handles the engine of an implementation.

This can be useful when you are writing an AJAX function that needs to return a snippet of HTML.
You can request a new handler and provide the view that the handler needs to fetch or display.

    <?php

    $handler = get_stencil_handler();
    $handler->set( 'variable', 'value' );
    $handler->display( 'snippets/some_snippet' );

    ?>

= Human Made - WordPress Objects =

Support for the Human Made WordPress Objects has been implemented fully.
If the classes are loaded they will be used to set the variables 'post', 'taxonomy' and 'user'.

*note: this is an optional feature*

= Stencil Hooks =

Listed below are all available hooks

*   **stencil.initialize**
    Called when: the engine is loaded and an addon has registered itself
    Parameters: $stencil
    Use case: register globally needed variables

*	**stencil.pre_display**
	**stencil.pre_fetch**
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
    **stencil.404**
    **stencil.search**
    **stencil.front_page**
    **stencil.home**
    **stencil.archive_{post_type}**
    **stencil.single**
    **stencil.{post_type}**
    **stencil.ID**
    **stencil.{custom_template.php}**
    **stencil.{custom_template}**
    **stencil.attachment**
    **stencil.attachment_{ID}**
    **stencil.comments_popup**
    **stencil.tax**
    **stencil.tag**
    **stencil.date**
    **stencil.category**
    **stencil.category_{name}**
    **stencil.author**
    **stencil.author_{ID}**
    **stencil.archive**

= Stencil Filters =

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

*   **stencil:page-{page}**
    **stencil:page**
    Called when: The page has been determined but the view has not been abstracted
    Use case: Override the page to force loading another view

*   **stencil:views**
    Called when: The view hierarchy has been build for a page
    Use case: Override what views will be searched for to display the request

*   **stencil:view-{view}**
    **stencil:view**
    Called when: The view has been determined based upon the page and available view files
    Use case: Override the view that will be loaded

== Changelog ==
v1.0 Initial version