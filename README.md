# Flynsarmy Recaptcha for WordPress

This plugin adds extremely lightweight recaptcha support for WordPress.


## Installation

* `git clone` to */wp-content/plugins/flyn-recaptcha*
* `composer install`
* Activate in your WordPress plugins list


## Usage

* Where you want your captcha to display, add `<?php FlynRC::instance()->render(); ?>`
* Where you want to very the response code, add `$response = FlynRC::instance()->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);`
