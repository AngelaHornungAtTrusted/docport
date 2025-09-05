<?php

?>
<h3>Documents</h3>
<style>
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f1f1f1;
        min-width: 120px;
        overflow-y: auto;
        max-height: 100px;
        border: 1px solid #ddd;
        z-index: 1;
    }
    .dropdown-content label {
        display: block;
        padding: 5px 10px;
        cursor: pointer;
    }
    .dropdown-content label:hover {
        background-color: #ddd;
    }
    .show {
        display: block;
    }
    .selected-items {
        width:100px;
        margin-top: 5px;
        padding: 5px;
        border: 1px solid #ccc;
    }
    .selected-items div {
        margin-bottom: 5px;
    }
</style>
<div class="row">
    <div class="col-md-3">
        <button class="" id="documentUpload">Upload Files</button>
    </div>
    <div class="col-md-9">
        <p style="margin-top: 5px;">On this page you can manage your portfolio documents and what campaigns/categories they're associated with.
            The buttons under action can either delete the document or give the document a custom thumbnail.</p>
    </div>
</div>
<div class="row" style="margin-top: 15px;">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Title</th>
                <th scope="col">Campaigns</th>
                <th scope="col">Categories</th>
                <th scope="col">Platforms</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody id="documentTable">

        </tbody>
    </table>
</div>
