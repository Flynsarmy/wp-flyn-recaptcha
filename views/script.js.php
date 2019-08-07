grecaptcha.ready(function() {
    grecaptcha.execute("<?= esc_attr($this->options['site_key']) ?>", {action: "<?= esc_attr($this->options['action']) ?>"}).then(function(token) {
        var input = document.getElementById("<?= esc_attr($this->id) ?>");
        if ( input ) input.value = token;

        if ( jQuery ) jQuery(input).trigger('FlynRC.reloaded')
    });
});