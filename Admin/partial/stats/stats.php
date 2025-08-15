<?php

?>
<h3>Statistics Panel</h3>
<div class="container">
    <div class="row align-items-start">
        <div class="col">
            <h4>Downloads Per Day</h4>
            <div class="row">
                <canvas id="day-graph"></canvas>
            </div>
            <div class="row" style="margin-left: 15px; margin-top: 15px;">
                <label for="documentSelect" hidden>Filter Document</label>
                <select id="documentSelect">
                    <option selected value="0">Filter By Document</option>
                </select>
                <div class="container" style="margin-top: 15px;">
                    <div class="row">
                        <div class="col">
                            <label for="startDate">Start Date</label>
                            <input id="startDate" type="date">
                        </div>
                        <div class="col">
                            <label for="endDate">End Date</label>
                            <input id="endDate" type="date">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <h4>Top Downloads</h4>
            <div class="row">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Document Name</th>
                            <th>Download Quantity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="downloadsTable">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>