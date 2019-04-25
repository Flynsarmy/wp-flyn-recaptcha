<?php $id = uniqid('g-recaptcha-3-'); ?>
<script src="https://www.google.com/recaptcha/api.js?render=<?= $options['site_key'] ?>"></script>
<input type="hidden" name="g-recaptcha-3-action" value="<?= esc_attr($options['action']) ?>" />
<input id="<?= $id ?>" type="hidden" name="g-recaptcha-3" />
<script>
    grecaptcha.ready(function() {
        grecaptcha.execute("<?= esc_attr($options['site_key']) ?>", {action: "<?= esc_attr($options['action']) ?>"}).then(function(token) {
            var input = document.getElementById("<?= $id ?>");
            if ( input ) input.value = token;
        });
    });
</script>