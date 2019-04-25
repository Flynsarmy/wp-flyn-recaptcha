<?php
/**
* @package Flynsarmy Recaptcha
* @version 1.0.0
*
* Plugin Name: Flynsarmy Recaptcha
* Description: Adds recaptcha support to your site
* Author: Flynsarmy
* Version: 0.1
* Author URI: http://www.flynsarmy.com
*/

class FlynRC
{
    private static $_instance;
    protected $options = [];

    public static function instance()
    {
        if ( !self::$_instance )
            self::$_instance = new self;

        return self::$_instance;
    }

    /**
     * @param array $overrides
     * @return array
     */
    public function get_options(array $overrides = [])
    {
        if ( empty($this->options) )
        {
            $default_options = apply_filters('flynrc_get_options', [
                'site_key' => defined('FLYNRC_SITE_KEY') ? FLYNRC_SITE_KEY : '',
                'secret_key' => defined('FLYNRC_SECRET_KEY') ? FLYNRC_SECRET_KEY : '',
                'action' => 'default',
            ]);

            $this->options = array_merge($default_options, $overrides);
        }

        return $this->options;
    }

    /**
     * @param array $options
     * @return \ReCaptcha\ReCaptcha
     */
    public function get_recaptcha(array $options = [])
    {
        require_once __DIR__.'/vendor/autoload.php';

        $options = $this->get_options($options);

        return new \ReCaptcha\ReCaptcha($options['secret_key']);
    }

    /**
     * Calls the reCAPTCHA siteverify API to verify whether the user passes
     * CAPTCHA test.
     *
     * @param string $response The value of 'g-recaptcha-response' in the submitted form.
     * @param string $action v3 API action name
     * @param string $remoteIp The end user's IP address.
     * @return \ReCaptcha\Response Response from the service.
     */
    public function verify($response, $action, $remoteIp = null)
    {
        return $this->get_recaptcha()
            ->setExpectedAction($action)
            ->verify($response, $remoteIp);
    }

    /**
     * Renders a ReCaptcha captcha on the page.
     * Uses $themedir/plugins/flynsarmy-recaptcha/frontend.php if available
     * @param array $overrides
     */
    public function render(array $overrides = [])
    {
        $located = locate_template('plugins/flyn-recaptcha/frontend.php');
        $options = $this->get_options($overrides);
        $recaptcha = $this->get_recaptcha($options);

        if ( $located )
            require $located;
        else
            require __DIR__.'/views/frontend.php';
    }
}