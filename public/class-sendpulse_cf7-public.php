<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @see       https://namli.ru
 * @since      0.1.0
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @author     Aleksei Andrushchenko <aleksey.andrushchenko@gmail.com>
 */
class Sendpulse_cf7_Public
{
    /**
     * The ID of this plugin.
     *
     * @since    0.1.0
     *
     * @var string the ID of this plugin
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    0.1.0
     *
     * @var string the current version of this plugin
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    0.1.0
     *
     * @param string $plugin_name the name of the plugin
     * @param string $version     the version of this plugin
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
}
