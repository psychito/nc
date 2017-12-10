<?php
/**
 * Created by PhpStorm.
 * User: zhuhua
 * Date: 11/08/15
 * Time: 9:45 AM
 */

?>

<!-- references -->
<div class="vl-desc-item">
    <div class="row">
        <div class="col-md-2">
            <span><strong>References</strong></span>
        </div>
        <div class="col-md-10">
            <!-- url -->
            <div class="row" data-bind="foreach: $data.url">
                <div class="col-md-2"><strong>URL</strong></div>
                <div class="col-md-10"><a data-bind="filterurl: $data"></a></div>
            </div>

            <!-- CVE -->
            <div class="row" data-bind="foreach:$data.cve">
                <div class="col-md-2"><strong>CVE</strong></div>
                <div class="col-md-10"><a data-bind="filterCVE: $data"></a></div>
            </div>

            <!-- osvdb -->
            <div class="row" data-bind="foreach:$data.osvdb">
                <div class="col-md-2"><strong>OSVDB</strong></div>
                <div class="col-md-10"><a data-bind="filterosvdb: $data"></a></div>
            </div>

            <!-- exploitdb -->
            <div class="row" data-bind="foreach:$data.exploitdb">
                <div class="col-md-2"><strong>EXPLOITDB</strong></div>
                <div class="col-md-10"><a data-bind="filterexploitdb: $data"></a></div>
            </div>

            <!-- metasploit -->
            <div class="row" data-bind="foreach:$data.metasploit">
                <div class="col-md-2"><strong>METASPLOIT</strong></div>
                <div class="col-md-10"><a data-bind="filtermetasploit: $data"></a></div>
            </div>

            <!-- secunia -->
            <div class="row" data-bind="foreach:$data.secunia">
                <div class="col-md-2"><strong>SECUNIA</strong></div>
                <div class="col-md-10"><a data-bind="filtersecunia: $data"></a></div>
            </div>
        </div>
    </div>
</div>
