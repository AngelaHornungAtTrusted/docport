<?php

?>
<h3>Campaigns</h3>
<div class="row">
    <form id="campaignForm" class="form">
        <label for="campaignTitle">Campaign Title</label>
        <input type="text" id="title" name="title">
        <input type="checkbox" id="active" name="active">
        <label for="campaignStatus">Campaign Status</label>
        <a class="button button-secondary submit" id="campaignButton">Create Campaign</a>
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
        <tbody id="campaignTable">

        </tbody>
    </table>
</div>
