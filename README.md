# Flynsarmy Recaptcha for WordPress

This plugin adds extremely lightweight recaptcha support for WordPress.


## Installation

* `git clone` to */wp-content/plugins/flyn-recaptcha*
* `composer install`
* Activate in your WordPress plugins list
* Either add `FLYNRC_SITE_KEY` and `FLYNRC_SECRET_KEY` definitions to your *wp-config.php*, or alternatively add the following in your themes *functions.php*:
    ```
    // Override default FlynRecaptcha options
    add_filter('flynrc_get_options', function(array $options) {
        $options['site_key'] = 'your_public_key';
        $options['secret_key'] = 'your_private_key';
        $options['action'] = 'your_site_action';
    
        return $options;
    });
    ```


## Usage

* Initiate the captcha with a given unique ID `$captcha = new FlynRC('my-id');`
* Add it to your form with `$captcha->render();`
* Recaptcha makes an AJAX request to google servers, so it doesn't load instantly with your page. If you submit your form too quickly you'll get an 'Invalid captcha' error. You can fix this by disabling your forms submit button on page load then adding the following callback:
    ```
    jQuery('#<?= $captcha->id ?>').on('FlynRC.reloaded', function(event) {
        jQuery(event.target).closest('FORM').find(':submit').removeAttr('disabled').val('Submit');
    });
    ``` 
* After each form submit, refresh the captcha (this will output javascript): `<?php $captcha->reload() ?>`
* Where you want to verify the response code, add 
    ```
    $captcha_error = '';
    if ( class_exists('FlynRC') )
    {
        try
        {
            $response = (new FlynRC('my-id'))
                ->verify($_POST, $_SERVER['REMOTE_ADDR']);
    
            if ( !$response->isSuccess() )
                $captcha_error = "Invalid captcha response. Did you complete the captcha correctly?";
        } catch (Exception $e) {
            $captcha_error = "Captcha error: " . $e->getMessage();
        }
    }
    ```
    
## Sample

```
<?php
    $captcha = new FlynRC('my-form');
?>
<script type="text/javascript">// <![CDATA[
    jQuery('#<?= $captcha->id ?>').on('FlynRC.reloaded', function(event) {
        jQuery(event.target).closest('FORM').find(':submit').removeAttr('disabled').val('Submit');
    });

    jQuery(document).ready(function($) {
        $('#myForm').ajaxForm({
            dataType: 'json',
            beforeSubmit: function(arr, $form, options) {
                $form.find(':submit').attr('disabled', 'disabled').val('Submitting...');
            },
            success: function(response, statusText, xhr, $form) {
                alert(response.message);
            },
            error: function(jxXHR, status, error, $form) {
                alert('An unknown error occurred: ' + error);
            },
            complete: function(jxXHR, textStatus) {
                <?php $captcha->reload() ?>
            }
        });
    });
// ]]></script>
<form class="form-horizontal" id="myForm" action="/submit" method="post">
    <div class="form-group">
        <label class="col-sm-3 control-label" for="inputName">Name</label>
        <div class="col-sm-6"><input class="form-control input-sm" id="inputName" type="text" name="name" /></div>
    </div>

    <div class="form-group">
        <?php
            // Add recaptcha support using Flynsarmy ReCaptcha plugin
            if ( class_exists('FlynRC') ) $captcha->render();
        ?>
    
        <div class="col-sm-offset-3 col-sm-6"><input class="btn btn-default" type="submit" value="Submit" disabled /></div>
    </div>
</form>
```
    
## Customisation

You can change the markup on recaptcha fields by copying */plugins/flyn-recaptcha/views/frontend.php* to *$themedir$/plugins/flyn-recaptcha/frontend.php* and modifying accordingly.