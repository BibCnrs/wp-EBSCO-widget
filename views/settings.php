<label>
    <input <?php echo implode(' ', $atts); ?> />
    <?php if ( array_key_exists('description', $options )) : ?>
    <?php esc_html_e( $options['description'] ); ?>
    <?php endif; ?>
</label>
