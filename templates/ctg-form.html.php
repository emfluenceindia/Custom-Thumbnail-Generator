<h1 class="ctg-plugin-header">
    <img src="<?php /* phpcs:disable PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage */ echo esc_attr( CTG_LOGO ) ?>" alt="Custom Thumbnail Generator" />
    <span>Custom Thumbnail Generator</span>
</h1>
<hr />
<p>
    <strong>Custom Thumbnail Generator</strong> ensures that all custom thumbnail sizes 
    defined through the plugin are registered and seamlessly integrated 
    into the environment as long as the plugin remains active. 
    They remain fully accessible and functional throughout the application, even when 
    switching themes, providing a consistent image handling.
</p>

<div id="ctg-action-container">
    <div id="ctg-form-div">
        <fieldset class="ctg-form-container">
            <h3>Add New Thumbnail Size</h3>
            <hr />
            <form id="ctg-form" method="post">
                <table class="ctg-form-table">
                    <tr>
                        <th>Width&nbsp;</th>
                        <td>
                            <input type="number" min="25" name="ctg-width" id="ctg-width" required /> px
                        </td>
                    </tr>
                    <tr>
                        <th>Height&nbsp;</th>
                        <td>
                            <input type="number" min="25" name="ctg-height" id="ctg-height" required /> px
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <input type="checkbox" name="ctg-crop" id="ctg-crop" value="1" />
                            &nbsp;<label for="ctg-crop">Allow crop</label>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <input type="submit" value="Add Thumbnail Size" class="button button-primary" id="ctg-addsise" name="ctg-addsize" />
                        </td>
                    </tr>
                </table>

                <div id="ctg-form-response"></div>
            </form>
        </fieldset>
        
        <div id="ctg-generator-ui">
            <hr />
            <p>Some of your previous attachments may be missing Custom Thumbnail Generator thumbnails.</p>
            <button id="ctg-regenerate" class="button button-secondary">
                <i class="fa fa-recycle" aria-hidden="true"></i>&nbsp;Regenerate Thumbnails
            </button>
            <div id="ctg-progressbar">
                <div id="ctg-progress"></div>
                <div id="ctg-percentage-increment"></div>
            </div>
            <div id="ctg-generator-status"></div>
        </div>
    </div>

    <?php require_once 'ctg-custom-thumb-list.html.php' ?>
</div>

