<h1 class="ctgen-plugin-header">
    <img src="<?php /* phpcs:disable PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage */ echo esc_attr( CTGEN_LOGO ) ?>" alt="Custom Thumbnail Generator" />
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

<div id="ctgen-action-container">
    <div id="ctgen-form-div">
        <fieldset class="ctgen-form-container">
            <h3>Add New Thumbnail Size</h3>
            <hr />
            <form id="ctgen-form" method="post">
                <table class="ctgen-form-table">
                    <tr>
                        <th>Width&nbsp;</th>
                        <td>
                            <input type="number" min="25" name="ctgen-width" id="ctgen-width" required /> px
                        </td>
                    </tr>
                    <tr>
                        <th>Height&nbsp;</th>
                        <td>
                            <input type="number" min="25" name="ctgen-height" id="ctgen-height" required /> px
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <input type="checkbox" name="ctgen-crop" id="ctgen-crop" value="1" />
                            &nbsp;<label for="ctgen-crop">Allow crop</label>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <input type="submit" value="Add Thumbnail Size" class="button button-primary" id="ctgen-addsise" name="ctgen-addsize" />
                        </td>
                    </tr>
                </table>

                <div id="ctgen-form-response"></div>
            </form>
        </fieldset>
        
        <div id="ctgen-generator-ui">
            <hr />
            <p>Hit the button below to (re)generate all thumbnails.</p>
            <button id="ctgen-regenerate" class="button button-secondary">
                <i class="fa fa-recycle" aria-hidden="true"></i>&nbsp;Regenerate Thumbnails
            </button>
            <p>For a specific thumbnail size, hit the corresponding green button ( <i class="fa fa-recycle ctgen-regen-thumbs inline" aria-hidden="true"></i> ) in the table.&nbsp;<i class="fa fa-hand-o-right ctgen-arrow-pointer" aria-hidden="true"></i></p>
            <div id="ctgen-progressbar">
                <div id="ctgen-progress"></div>
                <div id="ctgen-percentage-increment"></div>
            </div>
            <div id="ctgen-generator-status"></div>
        </div>
    </div>

    <?php require_once 'ctgen-custom-thumb-list.html.php' ?>
</div>

