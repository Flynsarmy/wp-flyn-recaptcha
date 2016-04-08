# Flynsarmy Recaptcha for WordPress

This plugin adds extremely lightweight recaptcha support for WordPress.


## Installation

* `git clone` to */wp-content/plugins/flyn-recaptcha*
* `composer install`
* Activate in your WordPress plugins list
* Either add `FLYNRC_PUBLIC_KEY` and `FLYNRC_PUBLIC_KEY` definitions to your *wp-config.php*, or alternatively add the following in your themes *functions.php*:
    ```
    // Override default FlynRecaptcha options
    add_filter('flynrc_get_options', function(array $options) {
        $options['public_key'] = 'your_public_key';
        $options['private_key'] = 'your_private_key';
    
        return $options;
    });
    ```


## Usage

* Where you want your captcha to display, add `<?php FlynRC::instance()->render(); ?>`
* Where you want to very the response code, add 
    ```
    $captcha_error = '';
    if ( class_exists('FlynRC') )
    {
        try
        {
            $response = FlynRC::instance()
                ->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
    
            if ( !$response->isSuccess() )
                $captcha_error = "Invalid captcha response. Did you complete the captcha correctly?";
        } catch (Exception $e) {
            $captcha_error = "Captcha error: " . $e->getMessage();
        }
    }
    ```
    
## Customisation

You can change the markup on recaptcha fields by copying */plugins/flyn-recaptcha/views/frontend.php* to *$themedir$/plugins/flyn-recaptcha/frontend.php* and modifying accordingly.