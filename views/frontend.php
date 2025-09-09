<script src="https://www.google.com/recaptcha/api.js?render=<?= $this->options['site_key'] ?>"></script>
<input type="hidden" name="g-recaptcha-3-action" id="<?= esc_attr($this->id) ?>-action" value="<?= esc_attr($this->action) ?>" />
<input type="hidden" name="g-recaptcha-3" id="<?= esc_attr($this->id) ?>" />
<script>
    /**
     * Executes the reCAPTCHA v3 challenge and returns a Promise that resolves with the token.
     * This refactors the original callback-based function to a more modern Promise-based approach.
     *
     * @returns {Promise<string>} A Promise that resolves with the reCAPTCHA token, id and action of this instance.
     */
    function flyn_recaptcha(id, action) {
        // Return a new Promise to handle the asynchronous operation.
        return new Promise((resolve, reject) => {
            // Ensure the reCAPTCHA library is ready before executing.
            grecaptcha.ready(function() {
                try {
                    // Execute the reCAPTCHA challenge. This returns a Promise.
                    grecaptcha.execute("<?= esc_attr($this->get_options()['site_key']) ?>", {action: action})
                        .then(function(token) {
                            jQuery('#' + id).val(token);
                            jQuery('#' + id + '-action').val(action);
                        })
                        .then(function(token) {
                            resolve(token, id, action);
                        })
                        .catch(function(error) {
                            jQuery('#' + id + '-action').val(action);
                            reject(error, id, action);
                        });
                } catch (e) {
                    // Catch any synchronous errors that might occur.
                    reject(e);
                }
            });
        });
    }
</script>
