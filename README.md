# Wordpress API Client.

This is a guzzle client written to interact with the [Wordpres API](https://codex.wordpress.org/WordPress.org_API)

# Example

    $client = \Wordpress\Api\WordpressClient::factory();

    // Get new plugins.

    $plugins = $client->getIterator('GetNewPlugins');

    foreach ($plugins as $plugin)
    {
        var_dump($plugin);
    }

    // Get a plugin

    $plugin = $client->getCommand(
        'GetPlugin',
        array(
            'request' => array(
                'slug' => 'akismet',
            )
        )
    )->execute();

    var_dump($plugin);

_See src/Wordpress/Api/services.json for all available operations_
