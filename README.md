Guzzle Bundle [![Build Status](https://travis-ci.org/gionnivaleriana/GuzzleBundle.svg?branch=master)](https://travis-ci.org/gionnivaleriana/GuzzleBundle)
=============

A [Guzzle 5](http://docs.guzzlephp.org/en/latest/) Bundle for Symfony 2

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require Kopjra/GuzzleBundle "~1"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding the following line in the `app/AppKernel.php`
file of your project:

``` php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Kopjra\GuzzleBundle\KopjraGuzzleBundle(),
        );

        // ...
    }

    // ...
}
```


Usage
-----

By default this bundle doesn't need to be configured, but you can configure it following the [Configuration Reference](https://github.com/gionnivaleriana/GuzzleBundle/tree/master/Resources/doc/configuration-reference.rst).

To use it just invoke the `kpj_guzzle` service:

``` php
// src/Acme/DemoBundle/Controller/DemoController.php

class DemoController extends Controller
{
    /**
     * ...
     */
    public function indexAction()
    {
        $guzzleClient = $this->get('kpj_guzzle');

        // ...
    }
}

```

It will be already configured with subscribers and client options.

Readings
--------

 * [Configuration Reference](https://github.com/gionnivaleriana/GuzzleBundle/tree/master/Resources/doc/configuration-reference.rst)


License
-------

This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE
