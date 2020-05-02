<?php

use Sendpulse\RestApi\ApiClient;
use Sendpulse\RestApi\Storage\FileStorage;

/**
 * The admin-specific functionality of the plugin.
 *
 * @see       https://namli.ru
 * @since      0.1.0
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @author     Aleksei Andrushchenko <aleksey.andrushchenko@gmail.com>
 */
class Sendpulse_cf7_Admin
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
     * @param string $plugin_name the name of this plugin
     * @param string $version     the version of this plugin
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Check if contact form 7 is active.
     *
     * @return [type] [description]
     */
    public function verify_dependencies()
    {
        if (!is_plugin_active('contact-form-7/wp-contact-form-7.php')) {
            $notice = [
                'id' => 'cf7-not-active',
                'type' => 'warning',
                'notice' => __('Contact form 7 api integrations requires CONTACT FORM 7 Plugin to be installed and active', $this->textdomain),
                'dismissable_forever' => false,
            ];

            $this->admin_notices->wp_add_notice($notice);
        }
    }

    /**
     * Sets the form additional properties.
     *
     * @param [type] $properties   [description]
     * @param [type] $contact_form [description]
     */
    public function add_sf_properties($properties, $contact_form)
    {
        $properties['spcf7_data'] = isset($properties['spcf7_data']) ? $properties['spcf7_data'] : [];

        return $properties;
    }

    /**
     * Adds a new tab on conract form 7 screen.
     *
     * @param [type] $panels [description]
     */
    public function add_integrations_tab($panels)
    {
        $integration_panel = [
            'title' => __('SendPulse integration', $this->textdomain),
            'callback' => [$this, 'wpcf7_integrations'],
        ];

        $panels['qs-cf7-api-integration'] = $integration_panel;

        return $panels;
    }

    /**
     * The admin tab display, settings and instructions to the admin user.
     *
     * @param [type] $post [description]
     *
     * @return [type] [description]
     */
    public function wpcf7_integrations($post)
    {
        $spcf7_data = $post->prop('spcf7_data');

        $spcf7_data['spcf7_send_to_api'] = isset($spcf7_data['spcf7_send_to_api']) ? $spcf7_data['spcf7_send_to_api'] : '';
        $spcf7_data['spcf7_disable_send'] = isset($spcf7_data['spcf7_disable_send']) ? $spcf7_data['spcf7_disable_send'] : '';
        $spcf7_data['spcf7_userid'] = isset($spcf7_data['spcf7_userid']) ? $spcf7_data['spcf7_userid'] : '';
        $spcf7_data['spcf7_usersec'] = isset($spcf7_data['spcf7_usersec']) ? $spcf7_data['spcf7_usersec'] : '';
        $spcf7_data['spcf7_bookid'] = isset($spcf7_data['spcf7_bookid']) ? $spcf7_data['spcf7_bookid'] : ''; ?>
				<h2><?php echo esc_html(__('API Integration', $this->textdomain)); ?></h2>

				<?php do_action('before_base_fields', $post); ?>

          <div class="cf7_row">
                  <input type="checkbox" id="spcf7-send-to-api" name="spcf7[spcf7_send_to_api]" <?php checked($spcf7_data['spcf7_send_to_api'], 'on'); ?>/>
                  <label for="spcf7-send-to-api"><?php _e('Send to api ?', $this->textdomain); ?></label>
					</div>
					<div class="cf7_row">
                  <input type="checkbox" id="spcf7-disable-send" name="spcf7[spcf7_disable_send]" <?php checked($spcf7_data['spcf7_disable_send'], 'on'); ?>/>
                  <label for="spcf7-disable-send"><?php _e('Disable send mail ?', $this->textdomain);
        echo $this->textdomain; ?></label>
          </div>

          <div class="cf7_row">
						<label for="spcf7-userid"><?php _e('User ID', $this->textdomain); ?></label>
						<input type="text" id="spcf7-userid" name="spcf7[spcf7_userid]" class="large-text" value="<?php echo $spcf7_data['spcf7_userid']; ?>" />
					</div>
					<div class="cf7_row">
						<label for="spcf7-usersec"><?php _e('User SEC', $this->textdomain); ?></label>
						<input type="text" id="spcf7-usersec" name="spcf7[spcf7_usersec]" class="large-text" value="<?php echo $spcf7_data['spcf7_usersec']; ?>" />
					</div>
					<div class="cf7_row">
						<label for="spcf7-bookid"><?php _e('Book ID', $this->textdomain); ?></label>
						<input type="text" id="spcf7-bookid" name="spcf7[spcf7_bookid]" class="large-text" value="<?php echo $spcf7_data['spcf7_bookid']; ?>" />
          </div>

	<?php
    }

    /**
     * Saves the API settings.
     *
     * @param [type] $contact_form [description]
     *
     * @return [type] [description]
     */
    public function spcf7_save_contact_form_details($contact_form)
    {
        $properties = $contact_form->get_properties();

        $properties['spcf7_data'] = isset($_POST['spcf7']) ? $_POST['spcf7'] : '';

        $contact_form->set_properties($properties);
    }

    /**
     * The handler that will send the data to the api.
     *
     * @param [type] $WPCF7_ContactForm [description]
     *
     * @return [type] [description]
     */
    public function spcf7_send_data_to_api($WPCF7_ContactForm)
    {
        $submission = WPCF7_Submission::get_instance();

        $url = $submission->get_meta('url');
        $this->post = $WPCF7_ContactForm;
        $spcf7_data = $WPCF7_ContactForm->prop('spcf7_data');

        // Send to SendPulse
        if (isset($spcf7_data['spcf7_send_to_api']) && 'on' == $spcf7_data['spcf7_send_to_api']) {
            $bookID = $spcf7_data['spcf7_bookid'];
            $userid = $spcf7_data['spcf7_userid'];
            $userSec = $spcf7_data['spcf7_usersec'];
            $emails = [
                [
                    'email' => $submission->get_posted_data('your-email'),
                    'variables' => [
                        'Name' => $submission->get_posted_data('your-name'),
                    ],
                ],
            ];
            $this->SPApiClient = new ApiClient($userid, $userSec, new FileStorage());
            $this->SPApiClient->addEmails($bookID, $emails);
            if (isset($spcf7_data['spcf7_disable_send']) && 'on' == $spcf7_data['spcf7_disable_send']) {
                add_filter('wpcf7_skip_mail', '__return_true');
            }
        }
    }
}
