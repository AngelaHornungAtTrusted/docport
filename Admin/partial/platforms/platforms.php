<?php

?>
<h3>Platforms</h3>
<div class="row">
    <form id="platformForm" class="form">
        <label for="platformTitle">Platform Title</label>
        <input type="text" id="ptitle" name="ptitle">
        <input type="checkbox" id="pactive" name="pactive">
        <label for="platformStatus">Platform Status</label>
        <a class="button button-secondary submit" id="platformButton">Create Platform</a>
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
        <tbody id="platformTable">

        </tbody>
    </table>
</div>
