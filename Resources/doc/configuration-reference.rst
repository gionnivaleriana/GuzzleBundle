Bundle Configuration Reference
==============================

.. code-block:: yaml

    # app/config/config.yml
    kpj_guzzle:
        # Guzzle 5 client configuration (http://docs.guzzlephp.org/en/latest/clients.html)
        client:               []
        subscribers:

            # Defaults:
            cache:               false
            log:                 false
            oauth1:              false
            retry:               false
            server_cache:        false
        twig:
            enabled:              false


Client-side Cache Subscriber
----------------------------

Reference: https://github.com/guzzle/cache-subscriber

.. code-block:: yaml

    kpj_guzzle:
        subscribers:
            cache: true


Server-side Cache Subscriber
----------------------------

Reference: https://github.com/EmanueleMinotto/guzzle-cache-subscriber

.. code-block:: yaml

    kpj_guzzle:
        subscribers:
            server_cache: true

**Attention**: both server-side and client-side caching systems, if almost one of them is enabled, need a service called ``kpj_guzzle.subscribers.cache.storage`` that's an implementation of the ``Doctrine\Common\Cache\Cache`` interface.


Log Subscriber
--------------

Reference: https://github.com/guzzle/log-subscriber

.. code-block:: yaml

    kpj_guzzle:
        subscribers:
            log: true


OAuth 1 Subscriber
------------------

Reference: https://github.com/guzzle/oauth-subscriber

.. code-block:: yaml

    kpj_guzzle:
        subscribers:
            oauth1:
                request_method: header
                signature_method: HMAC-SHA1
                callback: ~
                consumer_key: anonymous
                consumer_secret: anonymous
                realm: ~
                token: ~
                token_secret: ~
                verifier: ~
                version: 1.0


Retry Subscriber
----------------

Reference: https://github.com/guzzle/retry-subscriber

.. code-block:: yaml

    kpj_guzzle:
        subscribers:
            retry:
                delay: 1000
                filter:
                    class: GuzzleHttp\Subscriber\Retry\RetrySubscriber
                    method: createStatusFilter
                max: 5


Guzzle 4/5 Client
-----------------

This is the main Guzzle client configuration, read more about it here: http://docs.guzzlephp.org/en/latest/clients.html#creating-a-client


Twig Extension
--------------

The extension ``Kopjra\GuzzleBundle\Twig\GuzzleExtension`` will be added to included extensions, this adds:

 * a global ``guzzle`` variable, that's the main Guzzle client used by the bundle
 * a ``guzzle`` function that extracts from the remote URL (passed as unique parameter) the content JSON
 * a ``guzzle`` guzzle that extracts from the remote URL (passed as unique parameter) the content JSON
 * a ``visitable`` test that match URLs

.. code-block:: yaml

    kpj_guzzle:
        twig: true
