<?php

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @see       https://namli.ru
 * @since      0.1.0
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      0.1.0
 *
 * @author     Aleksei Andrushchenko <aleksey.andrushchenko@gmail.com>
 */
class Sendpulse_cf7_i18n
{
    /**
     * Load the plugin text domain for translation.
     *
     * @since    0.1.0
     */
    public function load_plugin_textdomain()
    {
        load_plugin_textdomain(
            'sendpulse_cf7',
            false,
            dirname(dirname(plugin_basename(__FILE__))).'/languages/'
        );
    }
}
