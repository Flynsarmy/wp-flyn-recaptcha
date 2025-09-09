# Flynsarmy Recaptcha for WordPress

This plugin adds extremely lightweight recaptcha support for WordPress.


## Installation

* `git clone` to */wp-content/plugins/flyn-recaptcha*
* `composer install`
* Activate in your WordPress plugins list
* Either add `FLYNRC_SITE_KEY` and `FLYNRC_SECRET_KEY` definitions to your *wp-config.php*, or alternatively add the following in your themes *functions.php*:
    ```javascript
    // Override default FlynRecaptcha options
    add_filter('flynrc_get_options', function(array $options) {
        $options['site_key'] = 'your_public_key';
        $options['secret_key'] = 'your_private_key';

        return $options;
    });
    ```


## Usage

* Initiate the captcha with a given unique ID `$captcha = new FlynRC('my-id');`
* An optional second argument can be passed to `FlynRC` for a recaptcha v3 action. Default is `default`.
* Add it to your form with `$captcha->render();`
* Recaptcha v3 needs to be executed on form submit. That can be done with `FlynRC` with the following:
    ```javascript
    flyn_recaptcha( "<?= esc_attr($captcha->id) ?>", "<?= esc_attr($captcha->action) ?>" ).then(
        function (token, id, action) {
            // your form submit logic here
        }
    )
    ```

Note: Nothing special needs to be done with the token, id or action passed by the `flyn_recaptcha` promise. Those values are saved to your form automatically. They're just passed on for convenience.

## Sample

```javascript
<?php
    $captcha = new FlynRC('my-form');
?>
<script type="text/javascript">// <![CDATA[
    jQuery(document).ready(function($) {
        $( '#myForm' ).submit( function ( e ) {
            e.preventDefault();

            var form = $( this );

            form.find( ':submit' ).attr( 'disabled', 'disabled' );

            flyn_recaptcha( form.data( 'frc_id' ), form.data( 'frc_action' ) ).then(
			    function () {
                    $.ajax( {
                        url: form.attr( 'action' ),
                        type: form.attr( 'method' ),
                        data: form.serialize(),
                        dataType: 'json',
                        complete: function () {
                            form.find( ':submit' ).removeAttr( 'disabled' );
                        },
                        success: function ( response ) {
                            alert(response.message);
                        },
                    } );
                }
            );
        } );
    });
// ]]></script>
<form class="form-horizontal" id="myForm" action="/submit" method="post" data-frc_id="<?= esc_attr($captcha->id) ?>" data-frc_action="<?= esc_attr($captcha->action) ?>">
    <div class="form-group">
        <label class="col-sm-3 control-label" for="inputName">Name</label>
        <div class="col-sm-6"><input class="form-control input-sm" id="inputName" type="text" name="name" /></div>
    </div>

    <div class="form-group">
        <?php
            // Add recaptcha support using Flynsarmy ReCaptcha plugin
            if ( class_exists('FlynRC') ) $captcha->render();
        ?>

        <div class="col-sm-offset-3 col-sm-6"><input class="btn btn-default" type="submit" value="Submit" /></div>
    </div>
</form>
```

## Customisation

You can change the markup on recaptcha fields by copying */plugins/flyn-recaptcha/views/frontend.php* to *$themedir$/plugins/flyn-recaptcha/frontend.php* and modifying accordingly.