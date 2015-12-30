# Stencil

Stencil is an interface plugin that enables the use of templating engines to be used for theming.

To use Stencil in your theme you just need two things:

## Implementation

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

### Stencil Hooks

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

### Stencil Filters

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