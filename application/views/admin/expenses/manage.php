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
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                    <div class="_buttons">
                        <?php if (has_permission('expenses', '', 'create')) { ?>
                            <a href="<?php echo admin_url('expenses/expense'); ?>" class="btn btn-primary">
                                <i class="fa-regular fa-plus tw-mr-1"></i>
                                <?php echo _l('new_expense'); ?>
                            </a>
                            <div class="btn-group pull-right mleft4 btn-with-tooltip-group _filter_data">

                                <button id="toggleFilter" class="btn btn-success ">Filter</button>
                            </div>
                            <a href="<?php echo admin_url('expenses/import'); ?>" class="btn btn-primary mleft5">
                                <i class="fa-solid fa-upload tw-mr-1"></i>
                                <?php echo _l('import_expenses'); ?>
                            </a>
                        <?php } ?>
                        <?php $this->load->view('admin/expenses/filter_by_template'); ?>
                        
                        <a href="#" onclick="slideToggle('#stats-top'); return false;" class="pull-right btn btn-default mleft5 btn-with-tooltip" data-toggle="tooltip" title="<?php echo _l('view_stats_tooltip'); ?>"><i class="fa fa-bar-chart"></i></a>
                        <a href="#" class="btn btn-default pull-right btn-with-tooltip toggle-small-view hidden-xs" onclick="toggle_small_view('.table-expenses','#expense'); return false;" data-toggle="tooltip" title="<?php echo _l('invoices_toggle_table_tooltip'); ?>"><i class="fa fa-angle-double-left"></i></a>
                        <div id="stats-top" class="hide">
                            <hr />
                            <div id="expenses_total"></div>
                        </div>
                    </div>
                    <div id="dynamicFilterContainer">
                        <!-- The dynamic filter UI will be inserted here -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" id="small-table">
                        <div class="panel_s">
                            <div class="panel-body">
                                <div class="clearfix"></div>
                                <!-- if expenseid found in url -->
                                <?php echo form_hidden('expenseid', $expenseid); ?>
                                <div class="panel-table-full">
                                    <?php $this->load->view('admin/expenses/table_html', ['withBulkActions' => true]); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7 small-table-right-col">
                        <div id="expense" class="hide">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="expense_convert_helper_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('additional_action_required'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="radio radio-primary">
                    <input type="radio" checked id="expense_convert_invoice_type_1" value="save_as_draft_false" name="expense_convert_invoice_type">
                    <label for="expense_convert_invoice_type_1"><?php echo _l('convert'); ?></label>
                </div>
                <div class="radio radio-primary">
                    <input type="radio" id="expense_convert_invoice_type_2" value="save_as_draft_true" name="expense_convert_invoice_type">
                    <label for="expense_convert_invoice_type_2"><?php echo _l('convert_and_save_as_draft'); ?></label>
                </div>
                <div id="inc_field_wrapper">
                    <hr />
                    <p><?php echo _l('expense_include_additional_data_on_convert'); ?></p>
                    <p><b><?php echo _l('expense_add_edit_description'); ?> +</b></p>
                    <div class="checkbox checkbox-primary inc_note">
                        <input type="checkbox" id="inc_note">
                        <label for="inc_note"><?php echo _l('expense'); ?>
                            <?php echo _l('expense_add_edit_note'); ?></label>
                    </div>
                    <div class="checkbox checkbox-primary inc_name">
                        <input type="checkbox" id="inc_name">
                        <label for="inc_name"><?php echo _l('expense'); ?> <?php echo _l('expense_name'); ?></label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="expense_confirm_convert"><?php echo _l('confirm'); ?></button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script>
    var hidden_columns = [4, 5, 6, 7, 8, 9];
</script>
<?php init_tail(); ?>
<script>
    Dropzone.autoDiscover = false;
    $(function() {
        // Expenses additional server params
        var Expenses_ServerParams = {};

        $.each($('._hidden_inputs._filters input'), function() {
            Expenses_ServerParams[$(this).attr('name')] = '[name="' + $(this).attr('name') + '"]';
        });

        initDataTable('.table-expenses', admin_url + 'expenses/table', [0], [0], Expenses_ServerParams,
                <?php echo hooks()->apply_filters('expenses_table_default_order', json_encode([6, 'desc'])); ?>)
            .column(1).visible(false, false).columns.adjust();

        init_expense();

        $('#expense_convert_helper_modal').on('show.bs.modal', function() {
            var emptyNote = $('#tab_expense').attr('data-empty-note');
            var emptyName = $('#tab_expense').attr('data-empty-name');
            if (emptyNote == '1' && emptyName == '1') {
                $('#inc_field_wrapper').addClass('hide');
            } else {
                $('#inc_field_wrapper').removeClass('hide');
                emptyNote === '1' && $('.inc_note').addClass('hide') || $('.inc_note').removeClass('hide')
                emptyName === '1' && $('.inc_name').addClass('hide') || $('.inc_name').removeClass('hide')
            }
        });

        $('body').on('click', '#expense_confirm_convert', function() {
            var parameters = new Array();
            if ($('input[name="expense_convert_invoice_type"]:checked').val() == 'save_as_draft_true') {
                parameters['save_as_draft'] = 'true';
            }
            parameters['include_name'] = $('#inc_name').prop('checked');
            parameters['include_note'] = $('#inc_note').prop('checked');
            window.location.href = buildUrl(admin_url + 'expenses/convert_to_invoice/' + $('body').find(
                '.expense_convert_btn').attr('data-id'), parameters);
        });

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