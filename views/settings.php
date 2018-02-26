<label>
    <input <?php echo implode(' ', $atts); ?> <?php checked('value="1"', $atts['value']);?> />
    <?php esc_html_e($description) ?>
</label>
