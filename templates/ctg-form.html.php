<!-- Form the add nre thumbnail size -->
<h1>Create Custom Thumbnail Sizes</h1>
<hr />
<p style="width: 72%;">
    <strong>Custom Thumbnail Generator</strong> ensures that all custom thumbnail sizes 
    defined through the plugin are registered and seamlessly integrated 
    into the environment as long as the plugin remains active. 
    They remain fully accessible and functional throughout the application, even when 
    switching themes, providing a consistent image handling.
</p>

<fieldset class="ctg-form-container">
    <legend>
        <h3>Add New Thumbnail Size</h3>
    </legend>
    <form id="ctg-form" method="post">
        <table class="ctg-form-table">
            <tr>
                <th>Width</th>
                <td>
                    <input type="number" name="ctg-width" id="ctg-width" required /> px
                </td>
            </tr>
            <tr>
                <th>Height</th>
                <td>
                    <input type="number" name="ctg-height" id="ctg-height" required /> px
                </td>
            </tr>
            <tr>
                <th>
                    <label for="ctg-crop">Allow crop</label>
                </th>
                <td>
                    <input type="checkbox" name="ctg-crop" id="ctg-crop" value="1" />
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>
                    <input type="submit" value="Add New Thumbnail Size" class="button button-primary" id="ctg-addsise" name="ctg-addsize" />
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <?php echo wp_nonce_field( 'ctg_form_action', 'ctg_form_nonce' ); ?>
                </td>
            </tr>
        </table>

        <div id="ctg-form-response"><!-- AJAX response appears here --></div>
    </form>
</fieldset>