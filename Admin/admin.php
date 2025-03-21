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
            <input type="number" class="hidden" id="dp-post-type" name="dp-post-type" value="0"></input>
            <button type="submit" id="dp-cat-submit">Submit</button>
        </form>
        <div style="height: 200px; overflow:auto; border: 1px; border-color: black; margin-top: 15px;">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Category</th>
                    <th scope="col">Active</th>
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
        <form id="dp-doc-form" action="http://localhost/devplugin/wp-content/plugins/Docport/Admin/document.php" method="post" enctype="multipart/form-data">
            <label class="hidden" for="dp-doc-upload">Document Upload</label>
            <input type="file" name="dp-doc-upload" id="dp-doc-upload">
            <button type="submit" id="dp-doc-submit">Submit</button>
        </form>
        <div style="height: 200px; overflow:auto; border: 1px; border-color: black; margin-top: 15px;">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Document</th>
                    <th scope="col">Category</th>
                    <th scope="col">Active</th>
                </tr>
                </thead>
			    <?php //todo get rid of hard coded address ?>
                <tbody id="dp-doc-table" data-loader="http://localhost/devplugin/wp-content/plugins/Docport/Admin/document.php"></tbody>
            </table>
        </div>
    </div>
</div>