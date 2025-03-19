<?php

?>
<h2>Docport Administration</h2>
<div class="row">
    <div class="col-md-6">
        <h5>Document Category Management</h5>
	    <?php //todo get rid of hard coded address, did when tired, dynamic call tries to include mamp in the address (composer autoloader?) ?>
        <form id="dp-cat-form" action="http://localhost/devplugin/wp-content/plugins/Docport/Admin/category.php" method="post">
            <label class="hidden" for="dp-cat-name">Category Name</label>
            <input type="text" id="dp-cat-name" name="dp-cat-name">
            <button type="submit" id="dp-cat-submit">Submit</button>
        </form>
        <div style="height: 100px; overflow:auto">
            <table>
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <?php //todo get rid of hard coded address ?>
                <tbody id="dp-cat-table" data-loader="http://localhost/devplugin/wp-content/plugins/Docport/Admin/category.php"></tbody>
            </table>
        </div>
    </div>
    <div class="col-md-6">
        <h5>Document Upload Management</h5>
	    <?php //todo get rid of hard coded address, did when tired, dynamic call tries to include mamp in the address (composer auto loader?) ?>
        <form id="dp-doc-form" action="http://localhost/devplugin/wp-content/plugins/Docport/Admin/document.php">
            <label class="hidden" for="dp-doc-upload">Document Upload</label>
            <input type="file" name="dp-doc-upload" id="dp-doc-upload">
        </form>
    </div>
</div>