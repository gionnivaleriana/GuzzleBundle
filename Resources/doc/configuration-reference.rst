Default Bundle Configuration
============================

.. code-block:: yaml

    # app/config/config.yml
    kopjra_guzzle:
        client: # Guzzle 5 client configuration (http://docs.guzzlephp.org/en/latest/clients.html)
        subscribers:
          cache:
            enabled: true
            # Must implement the Doctrine\Common\Cache\Cache interface
            # Default provider: Doctrine\Common\Cache\ArrayCache
            provider: '%kopjra_guzzle.subscribers.cache.provider%'
          log: # ...