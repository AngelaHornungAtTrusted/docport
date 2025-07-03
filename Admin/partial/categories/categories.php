<?php

?>
<h3>Categories</h3>
<div class="row">
    <form id="categoryForm" class="form">
        <label for="categoryTitle">Category Title</label>
        <input type="text" id="ctitle" name="title">
        <input type="checkbox" id="cactive" name="active">
        <label for="categoryStatus">Category Status</label>
        <a class="button button-secondary submit" id="categoryButton">Create Category</a>
    </form>
</div>
<div class="row" style="margin-top: 15px;">
    <table class="striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody id="categoryTable">

        </tbody>
    </table>
</div>
