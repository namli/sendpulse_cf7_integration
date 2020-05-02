<?php

/**
 * The file that defines the core plugin class.
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @see       https://namli.ru
 * @since      1.0.0
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 *
 * @author     Aleksei Andrushchenko <aleksey.andrushchenko@gmail.com>
 */
class Sendpulse_cf7
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     *
     * @var Sendpulse_cf7_Loader maintains and registers all hooks for the plugin
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     *
     * @var string the string used to uniquely identify this plugin
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     *
     * @var string the current version of the plugin
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('SENDPULSE_CF7_VERSION')) {
            $this->version = SENDPULSE_CF7_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'sendpulse_cf7';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     *
     * @return string the name of the plugin
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     *
     * @return Sendpulse_cf7_Loader orchestrates the hooks of the plugin
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     *
     * @return string the version number of the plugin
     */
    public function get_version()
    {
        return $this->version;
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Sendpulse_cf7_Loader. Orchestrates the hooks of the plugin.
     * - Sendpulse_cf7_i18n. Defines internationalization functionality.
     * - Sendpulse_cf7_Admin. Defines all hooks for the admin area.
     * - Sendpulse_cf7_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     */
    private function load_dependencies()
    {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)).'includes/class-sendpulse_cf7-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)).'includes/class-sendpulse_cf7-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)).'admin/class-sendpulse_cf7-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)).'public/class-sendpulse_cf7-public.php';

        /**
         * Add SendPulse API.
         */
        require_once plugin_dir_path(dirname(__FILE__)).'includes/sendpulse/rest-api/src/ApiInterface.php';
        require_once plugin_dir_path(dirname(__FILE__)).'includes/sendpulse/rest-api/src/ApiClient.php';
        require_once plugin_dir_path(dirname(__FILE__)).'includes/sendpulse/rest-api/src/Storage/TokenStorageInterface.php';
        require_once plugin_dir_path(dirname(__FILE__)).'includes/sendpulse/rest-api/src/Storage/FileStorage.php';
        require_once plugin_dir_path(dirname(__FILE__)).'includes/sendpulse/rest-api/src/Storage/SessionStorage.php';
        require_once plugin_dir_path(dirname(__FILE__)).'includes/sendpulse/rest-api/src/Storage/MemcachedStorage.php';
        require_once plugin_dir_path(dirname(__FILE__)).'includes/sendpulse/rest-api/src/Storage/MemcacheStorage.php';

        $this->loader = new Sendpulse_cf7_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Sendpulse_cf7_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    0.1.0
     */
    private function set_locale()
    {
        $plugin_i18n = new Sendpulse_cf7_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new Sendpulse_cf7_Admin($this->get_plugin_name(), $this->get_version());
        /*
         * Check if required plugins are active
         * @var [type]
         */
        $this->loader->add_action('admin_init', $plugin_admin, 'verify_dependencies');

        // before sending email to user actions
        $this->loader->add_action('wpcf7_before_send_mail', $plugin_admin, 'spcf7_send_data_to_api');

        // adds another tab to contact form 7 screen
        $this->loader->add_filter('wpcf7_editor_panels', $plugin_admin, 'add_integrations_tab', 1, 1);

        // actions to handle while saving the form
        $this->loader->add_action('wpcf7_save_contact_form', $plugin_admin, 'spcf7_save_contact_form_details', 10, 1);

        $this->loader->add_filter('wpcf7_contact_form_properties', $plugin_admin, 'add_sf_properties', 10, 2);
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     */
    private function define_public_hooks()
    {
        $plugin_public = new Sendpulse_cf7_Public($this->get_plugin_name(), $this->get_version());
    }
}
