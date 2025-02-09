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
                <div class="_buttons">
                    <?php if (has_permission('subscriptions', '', 'create')) { ?>
                        <a href="<?php echo admin_url('subscriptions/create'); ?>" class="btn btn-primary pull-left display-block">
                            <i class="fa-regular fa-plus tw-mr-1"></i>
                            <?php echo _l('new_subscription'); ?>
                        </a>
                    <?php } ?>
                    <div class="btn-group pull-right mleft4 btn-with-tooltip-group _filter_data">

                        <button id="toggleFilter" class="btn btn-success ">Filter</button>
                    </div>
                    <div class="btn-group pull-right mleft4 btn-with-tooltip-group _filter_data" data-toggle="tooltip" data-title="<?php echo _l('filter_by'); ?>">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-filter" aria-hidden="true"></i>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-right width300">
                            <li>
                                <a href="#" data-cview="all" onclick="dt_custom_view('','.table-subscriptions',''); return false;">
                                    <?php echo _l('all'); ?>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li class="<?php if (!$this->input->get('status') || $this->input->get('status') && $this->input->get('status') == 'not_subscribed') {
                                            echo 'active';
                                        } ?>">
                                <a href="#" data-cview="not_subscribed" onclick="dt_custom_view('not_subscribed','.table-subscriptions','not_subscribed'); return false;">
                                    <?php echo _l('subscription_not_subscribed'); ?>
                                </a>
                            </li>
                            <?php foreach (get_subscriptions_statuses() as $status) { ?>
                                <li class="<?php if ($status['filter_default'] == true && !$this->input->get('status') || $this->input->get('status') == $status['id']) {
                                                echo 'active';
                                            } ?>">
                                    <a href="#" data-cview="<?php echo 'subscription_status_' . $status['id']; ?>" onclick="dt_custom_view('subscription_status_<?php echo $status['id']; ?>','.table-subscriptions','subscription_status_<?php echo $status['id']; ?>'); return false;">
                                        <?php echo _l('subscription_' . $status['id']); ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>

                <div class="clearfix"></div>
                <div id="dynamicFilterContainer">
                    <!-- The dynamic filter UI will be inserted here -->
                </div>

                <div class="panel_s tw-mt-2 sm:tw-mt-4">
                    <div class="_filters _hidden_inputs">
                        <?php
                        foreach (get_subscriptions_statuses() as $status) {
                            $val = '';
                            if (!$this->input->get('status') || $this->input->get('status') && $this->input->get('status') == $status['id']) {
                                $val = $status['id'];
                            }
                            if (!$this->input->get('status') && $status['id'] == 'canceled') {
                                $val = '';
                            }
                            echo form_hidden('subscription_status_' . $status['id'], $val);
                        }
                        echo form_hidden('not_subscribed', !$this->input->get('status') || $this->input->get('status') && $this->input->get('status') == 'not_subscribed' ? 'not_subscribed' : '');
                        ?>
                    </div>
                    <div class="panel-body">

                        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg">
                            <i class="fa-brands fa-stripe" aria-hidden="true"></i>
                            <?php echo _l('subscriptions_summary'); ?>
                        </h4>

                        <div class="tw-grid tw-grid-cols-2 md:tw-grid-cols-3 lg:tw-grid-cols-8 tw-gap-2 tw-mt-2 sm:tw-mt-4">
                            <?php foreach (subscriptions_summary() as $summary) { ?>
                                <div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center lg:last:tw-border-r-0">
                                    <span class="tw-font-semibold tw-mr-3 tw-text-lg">
                                        <?php echo $summary['total']; ?>
                                    </span>
                                    <span style="color:<?php echo $summary['color']; ?>">
                                        <?php echo _l('subscription_' . $summary['id']); ?>
                                    </span>
                                </div>
                            <?php } ?>
                        </div>
                        <hr class="hr-panel-separator" />
                        <div class="panel-table-full">
                            <?php hooks()->do_action('before_subscriptions_table'); ?>
                            <?php $this->load->view('admin/subscriptions/table_html', ['url' => admin_url('subscriptions/table')]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>

<script>

    $(function() {

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

        var projectTable = $('#table-subscriptions').DataTable();
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