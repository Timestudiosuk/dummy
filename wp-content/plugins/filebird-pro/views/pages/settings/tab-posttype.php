<h2><?php esc_html_e( 'Post Type', 'filebird' ); ?></h2>
<table class="form-table">
    <tbody>
        <th scope="row">
            <label for=""><?php esc_html_e( 'Which Post Types do you want to use with FileBird ?', 'filebird' ); ?></label>
        </th>
        <td>
            <fieldset>
            <?php foreach ( $post_types as $type ) : ?>
                <label for="<?php echo esc_attr( "enabled_post_type_$type" ); ?>">
                    <input 
                        type="checkbox" 
                        id="<?php echo esc_attr( "enabled_post_type_$type" ); ?>" 
                        name="enabled_post_types[]" 
                        value="<?php echo esc_attr( $type ); ?>" 
                        <?php checked( in_array( $type, $enabled_posttypes ), 1 ); ?> 
                    />
                    <?php echo esc_html( ucfirst( $type ) ); ?>
                </label>
                <br />
            <?php endforeach; ?>
            </fieldset>
        </td>
    </tbody>
</table>
<button class="button button-primary fbv-save-posttype-settings" type="button"><?php esc_html_e( 'Save Changes' ); ?></button>