<?php
    require_once(__DIR__ . '/category.php');
?>
<h2>Docport Administration</h2>
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/additional-methods.min.js"></script>
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.min.js"></script>
<div class="row">
    <h3>Document Category Management</h3>
    <form id="dp-cat-form" action="category.php" method="post">
        <label for="dp-cat-name">Category Name</label>
        <input id="dp-cat-name" type="text">
        <input type="submit" value="Category Submit" name="dp-cat-submit" id="dp-cat-submit">
    </form>
</div>
<div class="row">
    <h3>Document Upload Management</h3>
    <form id="dp-doc-form" action="document.php">
        <label for="dp-doc-upload">Document Upload</label>
        <input type="file" name="dp-doc-upload" id="dp-doc-upload">
        <input type="submit" value="Document Submit" name="dp-doc-submit" id="dp-doc-submit">
    </form>
</div>