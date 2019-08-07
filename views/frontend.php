<script src="https://www.google.com/recaptcha/api.js?render=<?= $this->options['site_key'] ?>"></script>
<input type="hidden" name="g-recaptcha-3-action" value="<?= esc_attr($this->options['action']) ?>" />
<input id="<?= esc_attr($this->id) ?>" type="hidden" name="g-recaptcha-3" />
<script>
    <?= $this->reload(); ?>
</script>