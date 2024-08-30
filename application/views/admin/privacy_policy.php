<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    body,
    html {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0
    }

    .row-container {
        display: flex;
        width: 100%;
        height: 100%;
        flex-direction: column;
        background-color: blue;
        overflow: hidden;
    }

    .first-row {
        background-color: lime;
    }

    .second-row {
        flex-grow: 1;
        border: none;
        margin: 0;
        padding: 0;
        height: 500px;
    }
</style>
<div id="wrapper">
    <div class="content">
        <!-- <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4>Privacy Policy</h4>
                        <hr class="hr-panel-heading">

                        <?php echo " <iframe class = 'second-row' src='/uploads/privacy_policy_upload/" . $pdf_file[0]['uploaded_file_name'] . "#toolbar=0". "'></iframe>"; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
            </div>
        </div> -->

        <div class="row-container">
            
            <?php echo " <iframe class = 'second-row' src='/uploads/privacy_policy_upload/" . $pdf_file[0]['uploaded_file_name'] . "#toolbar=0" . "'></iframe>"; ?>
        </div>
    </div>
</div>
<?php init_tail(); ?>