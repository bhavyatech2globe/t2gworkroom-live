<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    #dynamicFilterContainer .filter-row {
        display: inline-block;
        margin: 10px;
    }

    #dynamicFilterContainer label {
        display: inline-block;
        margin-right: 10px;
        /* Adjust as needed */
    }

    #dynamicFilterContainer input {
        display: inline-flex;
        width: 150px;
        /* Adjust as needed */
    }
</style>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="_filters _hidden_inputs">
                <?php
            foreach ($statuses as $status) {
                echo form_hidden('credit_notes_status_' . $status['id'], isset($status['filter_default'])
                  && $status['filter_default'] ? 'true' : '');
            }
           foreach ($years as $year) {
               echo form_hidden('year_' . $year['year'], $year['year']);
           }
        ?>
            </div>
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                    <div class="_buttons">
                        <?php if (has_permission('credit_notes', '', 'create')) { ?>
                        <a href="<?php echo admin_url('credit_notes/credit_note'); ?>"
                            class="btn btn-primary pull-left display-block">
                            <i class="fa-regular fa-plus tw-mr-1"></i>
                            <?php echo _l('new_credit_note'); ?>
                        </a>
                        <?php } ?>
                        <div class="display-block text-right">
                            <div class="btn-group pull-right mleft4 btn-with-tooltip-group _filter_data"
                                data-toggle="tooltip" data-title="<?php echo _l('filter_by'); ?>">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-filter" aria-hidden="true"></i>
                                </button>
                                <ul class="dropdown-menu width300">
                                    <li>
                                        <a href="#" data-cview="all"
                                            onclick="dt_custom_view('','.table-credit-notes',''); return false;">
                                            <?php echo _l('credit_notes_list_all'); ?>
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <?php foreach ($statuses as $status) { ?>
                                    <li class="<?php if (isset($status['filter_default']) && $status['filter_default']) {
            echo 'active';
        } ?>">
                                        <a href="#" data-cview="credit_notes_status_<?php echo $status['id']; ?>"
                                            onclick="dt_custom_view('credit_notes_status_<?php echo $status['id']; ?>','.table-credit-notes','credit_notes_status_<?php echo $status['id']; ?>'); return false;">
                                            <?php echo format_credit_note_status($status['id'], true); ?>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <div class="clearfix"></div>
                                    <?php
                  if (count($years) > 0) { ?>
                                    <li class="divider"></li>
                                    <?php foreach ($years as $year) { ?>
                                    <li class="active">
                                        <a href="#" data-cview="year_<?php echo $year['year']; ?>"
                                            onclick="dt_custom_view(<?php echo $year['year']; ?>,'.table-credit-notes','year_<?php echo $year['year']; ?>'); return false;"><?php echo $year['year']; ?>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <?php } ?>
                                </ul>
                            </div>
                            <a href="#" class="btn btn-default btn-with-tooltip toggle-small-view hidden-xs"
                                onclick="toggle_small_view('.table-credit-notes','#credit_note'); return false;"
                                data-toggle="tooltip" title="<?php echo _l('invoices_toggle_table_tooltip'); ?>"><i
                                    class="fa fa-angle-double-left"></i></a>
                                    <div class="btn-group pull-right mleft4 btn-with-tooltip-group _filter_data">

                        <button id="toggleFilter" class="btn btn-success ">Filter</button>
                    </div>
                        </div>
                    </div>
                </div>
            <div id="dynamicFilterContainer">
                <!-- The dynamic filter UI will be inserted here -->
            </div>
                <div class="row">
                    <div class="col-md-12" id="small-table">
                        <div class="panel_s">
                            <div class="panel-body panel-table-full">
                                <!-- if credit not id found in url -->
                                <?php echo form_hidden('credit_note_id', $credit_note_id); ?>
                                <?php $this->load->view('admin/credit_notes/table_html'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7 small-table-right-col">
                        <div id="credit_note" class="hide">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/includes/modals/sales_attach_file'); ?>
<script>
var hidden_columns = [4, 5, 6, 7];
</script>
<?php init_tail(); ?>
<script>
$(function() {
    var Credit_Notes_ServerParams = {};
    $.each($('._hidden_inputs._filters input'), function() {
        Credit_Notes_ServerParams[$(this).attr('name')] = '[name="' + $(this).attr('name') + '"]';
    });
    initDataTable('.table-credit-notes', admin_url + 'credit_notes/table', ['undefined'], ['undefined'],
        Credit_Notes_ServerParams, [
            [1, 'desc'],
            [0, 'desc']
        ]);
    init_credit_note();
    customFilterSearch();
});


    function hideShowFilter() {
        $('#dynamicFilterContainer').hide(); // Hide filter by default

        $('#toggleFilter').on('click', function() {
            $('#dynamicFilterContainer').toggle();
        });

    }


    function customFilterSearch() {
        hideShowFilter();

        var projectTable = $('#DataTables_Table_0').DataTable();
        // Generate search inputs for each column
        var filters = []; // Store filter values
        function applyFilters() {
            projectTable.columns().every(function(index) {
                if (typeof filters[index] !== 'undefined') {
                    this.search(filters[index]);
                } else {
                    this.search('');
                }
            });

            projectTable.draw(); // Redraw the table
        }

        function isDateColumn(title) {
            const dateKeywords = ['date', 'deadline']; // Add more keywords as needed
            title = title.toLowerCase();

            return dateKeywords.some(keyword => title.includes(keyword));
        }

        projectTable.columns().every(function(index) {
            var column = this;
            var title = $(column.header()).text();
            var input;
            if (title == '#' || title == ' - ') {
                return false;
            }

            if (isDateColumn(title)) {
                input = $('<div class="filter-row"><label for="filter_' + index + '">' + title + ': </label><input type="date" class="form-control" placeholder="' + title + '"></div>')
                    .appendTo($('#dynamicFilterContainer'));
            } else {
                input = $('<div class="filter-row"><label for="filter_' + index + '">' + title + ': </label><input type="text" class="form-control" placeholder="' + title + '"></div>')
                    .appendTo($('#dynamicFilterContainer'));
            }


            input.find('input').on('keyup change', function() {
                filters[index] = $(this).val(); // Store the filter value
                applyFilters(); // Apply filters and redraw the table

            });
        });
        // $('#applyFiltersBtn').on('click', function() {
        //     applyFilters(); // Apply filters and redraw the table
        // });

    }
    
</script>
</body>

</html>