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
    public string $id = '';
    public string $action = 'default';

    /**
     * @var array{
     *  site_key: string,
     *  secret_key: string
     * }
     */
    protected array $options = [
        'site_key' => '',
        'secret_key' => '',
    ];

    /**
     * FlynRC constructor.
     * @param string $id
     * @param string $action
     */
    public function __construct(string $id, string $action = 'default')
    {
        $this->id = 'g-recaptcha-3-' . $id;
        $this->action = $action;

        $this->options = [
            'site_key' => defined('FLYNRC_SITE_KEY') ? FLYNRC_SITE_KEY : '',
            'secret_key' => defined('FLYNRC_SECRET_KEY') ? FLYNRC_SECRET_KEY : '',
        ];
    }

    /**
     * @return array{
     *  site_key: string,
     *  secret_key: string
     * }
     */
    public function get_options(): array
    {
        $options = apply_filters('flynrc_get_options', $this->options);

        return [
            'site_key' => $options['site_key'] ?? '',
            'secret_key' => $options['secret_key'] ?? '',
        ];
    }

    public function get_recaptcha(): \ReCaptcha\ReCaptcha
    {
        if (!class_exists('ReCaptcha\\ReCaptcha')) {
            require_once __DIR__ . '/vendor/autoload.php';
        }

        return new \ReCaptcha\ReCaptcha($this->options['secret_key']);
    }

    /**
     * Calls the reCAPTCHA siteverify API to verify whether the user passes
     * CAPTCHA test.
     *
     * @param array<mixed> $post     POST data submitted by the form
     * @param null|string $remoteIp  The end user's IP address.
     * @return \ReCaptcha\Response Response from the service.
     */
    public function verify(array $post, null|string $remoteIp = null): \ReCaptcha\Response
    {
        return $this->get_recaptcha()
            ->setExpectedAction(@$post['g-recaptcha-3-action'])
            ->verify(@$post['g-recaptcha-3'], $remoteIp);
    }

    /**
     * Renders a ReCaptcha captcha on the page.
     * Uses $themedir/plugins/flynsarmy-recaptcha/frontend.php if available
     */
    public function render(): void
    {
        $located = locate_template('plugins/flyn-recaptcha/frontend.php');
        $recaptcha = $this->get_recaptcha();

        if ($located) {
            require $located;
        } else {
            require __DIR__ . '/views/frontend.php';
        }
    }
}
