<?php
/**
* @package Flynsarmy Recaptcha
* @version 2.0.0
*
* Plugin Name: Flynsarmy Recaptcha
* Description: Adds recaptcha support to your site
* Author: Flynsarmy
* Version: 0.1
* Author URI: http://www.flynsarmy.com
*/

class FlynRC
{
    public $id = '';
    protected $options = [];

    /**
     * FlynRC constructor.
     * @param $id
     * @param array $options
     */
    public function __construct($id, array $options = [])
    {
        $this->id = 'g-recaptcha-3-' . $id;
        $this->options = $this->get_options($options);
    }

    /**
     * @param array $overrides
     * @return array
     */
    public function get_options(array $overrides = [])
    {
        if ( empty($options) )
        {
            $default_options = apply_filters('flynrc_get_options', [
                'site_key' => defined('FLYNRC_SITE_KEY') ? FLYNRC_SITE_KEY : '',
                'secret_key' => defined('FLYNRC_SECRET_KEY') ? FLYNRC_SECRET_KEY : '',
                'action' => 'default',
            ]);

            $options = array_merge($default_options, $overrides);
        }

        return $options;
    }

    /**
     * @param array $options
     * @return \ReCaptcha\ReCaptcha
     */
    public function get_recaptcha()
    {
        require_once __DIR__.'/vendor/autoload.php';

        return new \ReCaptcha\ReCaptcha($this->options['secret_key']);
    }

    public function reload()
    {
        require __DIR__.'/views/script.js.php';
    }

    /**
     * Calls the reCAPTCHA siteverify API to verify whether the user passes
     * CAPTCHA test.
     *
     * @param array $post     POST data submitted by the form
     * @param null $remoteIp  The end user's IP address.
     * @return \ReCaptcha\Response Response from the service.
     */
    public function verify(array $post, $remoteIp = null)
    {
        return $this->get_recaptcha()
            ->setExpectedAction(@$post['g-recaptcha-3-action'])
            ->verify(@$post['g-recaptcha-3'], $remoteIp);
    }

    /**
     * Renders a ReCaptcha captcha on the page.
     * Uses $themedir/plugins/flynsarmy-recaptcha/frontend.php if available
     * @param array $overrides
     */
    public function render(array $overrides = [])
    {
        $located = locate_template('plugins/flyn-recaptcha/frontend.php');
        $recaptcha = $this->get_recaptcha();

        if ( $located )
            require $located;
        else
            require __DIR__.'/views/frontend.php';
    }
}