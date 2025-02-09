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
        <div class="panel_s">
          <div class="panel-body">
            <div class="row">
              <div class="col-md-4 border-right">
                <h4 class="no-margin font-bold"><i class="fa fa-bank" aria-hidden="true"></i> <?php echo _l($title); ?></h4>
                <hr />
              </div>
            </div>
            <div class="row">
              <div class="_buttons col-md-3" style="display: flex;">
                <?php if (has_permission('assets', '', 'create') || is_admin()) { ?>
                  <a href="#" onclick="new_asset(); return false;" class="btn btn-info " style="margin-right: 4px;">
                    <?php echo _l('new_asset') . ' hardware'; ?>
                  </a>
                  <a href="#" onclick="new_asset2(); return false;" class="btn btn-info ">
                    <?php echo _l('new_asset') . ' software'; ?>
                  </a>
                <?php } ?>

                <div class="btn-group pull-right mleft4 btn-with-tooltip-group _filter_data">

                  <button id="toggleFilter" class="btn btn-success ">Filter</button>
                </div>
              </div>
            </div>
            <br><br>
            <div class="horizontal-scrollable-tabs preview-tabs-top">
              <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
              <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
              <div class="horizontal-tabs">
                <ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
                  <li role="presentation" class="active">
                    <a href="#all_asset" aria-controls="all_asset" role="tab" data-toggle="tab" aria-controls="all_asset">
                      <span class="glyphicon glyphicon-align-justify"></span>&nbsp;<?php echo _l('all_asset'); ?>
                    </a>
                  </li>
                  <li role="presentation">
                    <a href="#not_pending_yet" aria-controls="not_pending_yet" role="tab" data-toggle="tab" aria-controls="not_pending_yet">
                      <span class="glyphicon glyphicon-briefcase"></span>&nbsp;<?php echo htmlspecialchars(_l('not_pending_yet')); ?>
                    </a>
                  </li>

                  <li role="presentation">
                    <a href="#using" aria-controls="using" role="tab" data-toggle="tab" aria-controls="using">
                      <span class="glyphicon glyphicon-expand"></span>&nbsp;<?php echo htmlspecialchars(_l('using')); ?>
                    </a>
                  </li>
                  <li role="presentation">
                    <a href="#liquidation" aria-controls="liquidation" role="tab" data-toggle="tab" aria-controls="liquidation">
                      <i class="glyphicon glyphicon-unchecked"></i>&nbsp;<?php echo htmlspecialchars(_l('liquidation')); ?>
                    </a>
                  </li>
                  <li role="presentation">
                    <a href="#warranty_repair" aria-controls="warranty_repair" role="tab" data-toggle="tab" aria-controls="warranty_repair">
                      <i class="glyphicon glyphicon-cog"></i>&nbsp;<?php echo htmlspecialchars(_l('warranty_repair')); ?>
                    </a>
                  </li>
                  <li role="presentation">
                    <a href="#lost" aria-controls="lost" role="tab" data-toggle="tab" aria-controls="lost">
                      <span class="glyphicon glyphicon-new-window"></span>&nbsp;<?php echo htmlspecialchars(_l('lost')); ?>
                    </a>
                  </li>
                  <li role="presentation">
                    <a href="#broken" aria-controls="broken" role="tab" data-toggle="tab" aria-controls="broken">
                      <span class="glyphicon glyphicon-remove"></span>&nbsp;<?php echo htmlspecialchars(_l('broken')); ?>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
            <a href="#" class="btn btn-default pull-right btn-with-tooltip toggle-small-view hidden-xs" onclick="toggle_small_view_asset('.asset_sm','#asset_sm_view'); return false;" data-toggle="tooltip" title="<?php echo htmlspecialchars(_l('invoices_toggle_table_tooltip')); ?>"><i class="fa fa-angle-double-left"></i></a>
          </div>
        </div>
      </div>
      <div id="dynamicFilterContainer" style="display: inline;">
        <!-- The dynamic filter UI will be inserted here -->
        <!-- <button id="applyFiltersBtn">Apply Filters</button> -->
      </div>


      <div class="col-md-12" id="small-table">
        <div class="panel_s">
          <div class="panel-body">
            <?php echo form_hidden('asset_id', $asset_id); ?>
            <div class="tab-content">
              <div role="tabpanel" class="tab-pane active" id="all_asset">
                <?php
                $table_data = [];
                array_push($table_data, [
                  'name'    => _l('asset_image'),
                  'th_attrs' => ['id' => 'th-consent', 'class' => 'not-export'],
                ]);
                $table_data = array_merge($table_data, [
                  _l('asset_image'),
                  _l('asset_code'),
                  _l('asset_name'),
                  _l('asset_group'),
                  _l('date_buy'),
                  _l('amount_allocate'),
                  _l('amount_rest'),
                  _l('original_price'),
                  _l('unit'),
                  _l('department'),
                  _l('assigned_to_customer'),
                ]);
                render_datatable($table_data, 'table_assets1', ['asset_sm' => 'asset_sm']);
                ?>
              </div>
              <div role="tabpanel" class="tab-pane" id="not_pending_yet">
                <?php
                $table_data = [];
                array_push($table_data, [
                  'name'    => _l('asset_image'),
                  'th_attrs' => ['id' => 'th-consent', 'class' => 'not-export'],
                ]);
                $table_data = array_merge($table_data, [
                  _l('asset_image'),
                  _l('asset_code'),
                  _l('asset_name'),
                  _l('asset_group'),
                  _l('date_buy'),
                  _l('amount_allocate'),
                  _l('amount_rest'),
                  _l('original_price'),
                  _l('unit'),
                  _l('department'),
                  _l('assigned_to_customer'),
                ]);
                render_datatable($table_data, 'table_assets2', ['asset_sm' => 'asset_sm']);
                ?>
              </div>
              <div role="tabpanel" class="tab-pane" id="using">
                <?php
                $table_data = [];
                array_push($table_data, [
                  'name'    => _l('asset_image'),
                  'th_attrs' => ['id' => 'th-consent', 'class' => 'not-export'],
                ]);
                $table_data = array_merge($table_data, [
                  _l('asset_image'),
                  _l('asset_code'),
                  _l('asset_name'),
                  _l('asset_group'),
                  _l('date_buy'),
                  _l('amount_allocate'),
                  _l('amount_rest'),
                  _l('original_price'),
                  _l('unit'),
                  _l('department'),
                  _l('assigned_to_customer'),
                ]);
                render_datatable($table_data, 'table_assets3', ['asset_sm' => 'asset_sm']);
                ?>
              </div>
              <div role="tabpanel" class="tab-pane" id="liquidation">
                <?php
                $table_data = [];
                array_push($table_data, [
                  'name'    => _l('asset_image'),
                  'th_attrs' => ['id' => 'th-consent', 'class' => 'not-export'],
                ]);
                $table_data = array_merge($table_data, [
                  _l('asset_image'),
                  _l('asset_code'),
                  _l('asset_name'),
                  _l('asset_group'),
                  _l('date_buy'),
                  _l('amount_allocate'),
                  _l('amount_rest'),
                  _l('original_price'),
                  _l('unit'),
                  _l('department'),
                  _l('assigned_to_customer'),
                ]);
                render_datatable($table_data, 'table_assets4', ['asset_sm' => 'asset_sm']);
                ?>
              </div>
              <div role="tabpanel" class="tab-pane" id="warranty_repair">
                <?php
                $table_data = [];
                array_push($table_data, [
                  'name'    => _l('asset_image'),
                  'th_attrs' => ['id' => 'th-consent', 'class' => 'not-export'],
                ]);
                $table_data = array_merge($table_data, [
                  _l('asset_image'),
                  _l('asset_code'),
                  _l('asset_name'),
                  _l('asset_group'),
                  _l('date_buy'),
                  _l('amount_allocate'),
                  _l('amount_rest'),
                  _l('original_price'),
                  _l('unit'),
                  _l('department'),
                  _l('assigned_to_customer'),
                ]);
                render_datatable($table_data, 'table_assets5', ['asset_sm' => 'asset_sm']);
                ?>
              </div>
              <div role="tabpanel" class="tab-pane" id="lost">
                <?php
                $table_data = [];
                array_push($table_data, [
                  'name'    => _l('asset_image'),
                  'th_attrs' => ['id' => 'th-consent', 'class' => 'not-export'],
                ]);
                $table_data = array_merge($table_data, [
                  _l('asset_image'),
                  _l('asset_code'),
                  _l('asset_name'),
                  _l('asset_group'),
                  _l('date_buy'),
                  _l('amount_allocate'),
                  _l('amount_rest'),
                  _l('original_price'),
                  _l('unit'),
                  _l('department'),
                  _l('assigned_to_customer'),
                ]);
                render_datatable($table_data, 'table_assets6', ['asset_sm' => 'asset_sm']);
                ?>
              </div>
              <div role="tabpanel" class="tab-pane" id="broken">
                <?php
                $table_data = [];
                array_push($table_data, [
                  'name'    => _l('asset_image'),
                  'th_attrs' => ['id' => 'th-consent', 'class' => 'not-export'],
                ]);
                $table_data = array_merge($table_data, [
                  _l('asset_image'),
                  _l('asset_code'),
                  _l('asset_name'),
                  _l('asset_group'),
                  _l('date_buy'),
                  _l('amount_allocate'),
                  _l('amount_rest'),
                  _l('original_price'),
                  _l('unit'),
                  _l('department'),
                  _l('assigned_to_customer'),
                ]);
                render_datatable($table_data, 'table_assets7', ['asset_sm' => 'asset_sm']);
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-7 small-table-right-col">
        <div id="asset_sm_view" class="hide">
        </div>
      </div>

    </div>
  </div>
</div>
<div class="modal fade" id="assets" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open_multipart(admin_url('assets/asset'), ['id' => 'assets-form']); ?>
    <div class="modal-content modalwidth">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
          <span class="edit-title"><?php echo htmlspecialchars(_l('edit_asset')); ?></span>
          <span class="add-title"><?php echo htmlspecialchars(_l('new_asset')); ?></span>
        </h4>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active">
            <a href="#asset_information" aria-controls="tab_staff_profile" role="tab" data-toggle="tab">
              <?php echo 'Asset information'; ?>
            </a>
          </li>

        </ul>
        <div class="tab-content">

          <div role="tabpanel" class="tab-pane active" id="asset_information">

            <div class="row">
              <div class="col-md-12">
                <div id="additional"></div>
                <div class="panel panel-info">
                  <div class="panel-heading"><?php echo htmlspecialchars(_l('asset_information')) . " : for Laptop / Desktop / Mouse / Keyboard / Monitor / Accessories / Other Accessories"; ?></div>
                  <div class="panel-body">
                    <div class="row">
                      <div class="col-md-6">
                        <?php echo render_input('assets_code', 'asset_code', ''); ?>
                      </div>
                      <div class="col-md-6">
                        <?php echo render_input('assets_name', 'asset_name', ''); ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <?php $arrAtt        = [];
                        $arrAtt['data-type'] = 'currency';
                        echo render_input('amount', 'amounts', '', 'number'); ?>
                      </div>
                      <div class="col-md-6">
                        <label for="unit"><?php echo htmlspecialchars(_l('unit')); ?></label>
                        <select name="unit" id="unit" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo htmlspecialchars(_l('ticket_settings_none_assigned')); ?>">
                          <option value=""></option>
                          <?php foreach ($unit as $s) { ?>
                            <option value="<?php echo htmlspecialchars($s['unit_id']); ?>"><?php echo htmlspecialchars($s['unit_name']); ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <?php echo render_input('series', 'series', ''); ?>
                      </div>
                      <div class="col-md-6">
                        <label for="asset_group"><?php echo htmlspecialchars(_l('asset_group')); ?></label>
                        <select name="asset_group" id="asset_group" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo htmlspecialchars(_l('ticket_settings_none_assigned')); ?>">
                          <option value=""></option>
                          <?php foreach ($group as $s) { ?>
                            <option value="<?php echo htmlspecialchars($s['group_id']); ?>"><?php echo htmlspecialchars($s['group_name']); ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <label for="department"><?php echo htmlspecialchars(_l('room_management')); ?></label>
                        <select name="department" id="department" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo htmlspecialchars(_l('ticket_settings_none_assigned')); ?>">
                          <option value=""></option>
                          <?php foreach ($departments as $s) { ?>
                            <option value="<?php echo htmlspecialchars($s['departmentid']); ?>"><?php echo htmlspecialchars($s['name']); ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <label for="asset_location"><?php echo htmlspecialchars(_l('asset_location')); ?></label>
                        <select name="asset_location" id="asset_location" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo htmlspecialchars(_l('ticket_settings_none_assigned')); ?>">
                          <option value=""></option>
                          <?php foreach ($location as $s) { ?>
                            <option value="<?php echo htmlspecialchars($s['location_id']); ?>"><?php echo htmlspecialchars($s['location']); ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-6">
                        <?php echo render_date_input('date_buy', 'date_buy', ''); ?>
                      </div>
                      <div class="col-md-6">
                        <?php echo render_input('warranty_period', 'warranty_period', '', 'number'); ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <?php echo render_input('unit_price', 'unit_price', '', 'text', $arrAtt); ?>
                      </div>
                      <div class="col-md-6">
                        <?php echo render_input('depreciation', 'depreciation_month', '', 'number'); ?>
                        <p id="depreciation-error" class="text-danger"></p>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group select-placeholder">
                          <label for="clientid" class="control-label"><?php echo _l('client_belongs_to'); ?></label>
                          <select id="clientid" name="clientid[]" data-live-search="true" data-width="100%" class="ajax-search" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" multiple></select>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <label for="visible_to_client"><?php echo _l('visible_to_client'); ?></label>
                        <div class="checkbox checkbox-danger">
                          <input type="checkbox" name="visible_to_client" id="visible_to_client" value="<?php echo isset($product) ? $product->visible_to_client : ''; ?>" <?php echo isset($product) ? ('1' == $product->visible_to_client) ? 'checked' : '' : ''; ?>>
                          <label></label>
                        </div>
                      </div>
                    </div>
                    <!-- adding custom fields -->
                    <div class="row">
                      <div class="col-md-6">
                        <?php echo render_input('make', 'Make', ''); ?>
                      </div>
                      <div class="col-md-6">
                        <?php echo render_input('serial_no', 'Serial No.', ''); ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <?php echo render_input('screen_size', 'Screen Size', ''); ?>
                      </div>
                      <div class="col-md-6">
                        <label for="processor"><?php echo 'Processor'; ?></label>
                        <select name="processor" id="processor" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo htmlspecialchars(_l('ticket_settings_none_assigned')); ?>">
                          <option value=""></option>
                          <option value="i3">i3</option>
                          <option value="i5">i5</option>
                          <option value="i7">i7</option>
                          <option value="i9">i9</option>
                          <option value="AMD">AMD</option>
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <?php echo render_input('processor_gen', 'Processor Gen.', ''); ?>
                      </div>
                      <div class="col-md-6">
                        <label for="ram_gen"><?php echo 'Ram Gen'; ?></label>
                        <select name="ram_gen" id="ram_gen" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo htmlspecialchars(_l('ticket_settings_none_assigned')); ?>">
                          <option value=""></option>
                          <option value="DDR3">DDR3</option>
                          <option value="DDR4">DDR4</option>
                          <option value="DDR5">DDR5</option>
                          <option value="Onboard">Onboard</option>
                        </select>
                      </div>

                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <?php echo render_input('ram_slot_no', 'RAM Slot no.', ''); ?>
                      </div>
                      <div class="col-md-6">
                        <?php echo render_input('ram', 'RAM', ''); ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <label for="storage_type"><?php echo 'Storage Type'; ?></label>
                        <select name="storage_type" id="storage_type" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo htmlspecialchars(_l('ticket_settings_none_assigned')); ?>">
                          <option value=""></option>
                          <option value="HDD">HDD</option>
                          <option value="SSD">SSD</option>
                          <option value="NVME">NVME</option>
                          <option value="PEN Drive">PEN Drive</option>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <?php echo render_input('storage_1', 'Storage 1', ''); ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <?php echo render_input('storage_2', 'Storage 2', ''); ?>
                      </div>
                      <div class="col-md-6">
                        <?php echo render_input('graphic_card', 'Graphic card', ''); ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <?php echo render_input('mac_address', 'MAC Address', ''); ?>
                      </div>
                      <div class="col-md-6">
                        <?php echo render_input('mac_address2', 'MAC Address 2', ''); ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <?php echo render_input('imei_number', 'IMEI Number', ''); ?>
                      </div>
                      <div class="col-md-6">
                        <?php echo render_input('imei_number2', 'IMEI Number 2', ''); ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <?php echo render_input('operating_system', 'Operating System', ''); ?>
                      </div>
                      <div class="col-md-6">
                        <?php echo render_input('office', 'Office', ''); ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <?php echo render_input('adopter', 'Adopter', ''); ?>
                      </div>
                      <div class="col-md-6">
                        <?php echo render_input('vendor_name', 'Vendor Name', ''); ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <?php echo render_input('bill_no', 'Bill Number', ''); ?>
                      </div>

                    </div>
                    <!-- custom fields end -->
                    <div class="row">
                      <div class="col-md-12">
                        <div class="attachment">
                          <div class="form-group">
                            <!-- <label for="attachment" class="control-label"><small class="req text-danger">* </small><?php echo _l('asset_image'); ?></label> -->
                            <label for="attachment" class="control-label"><?php echo _l('asset_image'); ?></label>

                            <input type="file" extension="png,jpg,jpeg,gif" filesize="<?php echo file_upload_max_size(); ?>" class="form-control" name="asset_image" id="asset_image">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div id="asset_existing_image"></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="panel panel-info">
                  <div class="panel-heading"><?php echo htmlspecialchars(_l('supplier_information')); ?></div>
                  <div class="panel-body">
                    <div class="row">
                      <div class="col-md-6">
                        <?php echo render_input('supplier_name', 'supplier_name', ''); ?>
                      </div>
                      <div class="col-md-6">
                        <?php echo render_input('supplier_phone', 'supplier_phone', ''); ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <?php echo render_input('supplier_address', 'supplier_address', ''); ?>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <?php echo render_textarea('description', 'description', ''); ?>
                  </div>
                </div>
              </div>
            </div>
          </div>


        </div>
      </div>
      <div class="modal-footer">
        <button type="
                    " class="btn btn-default" data-dismiss="modal"><?php echo htmlspecialchars(_l('close')); ?></button>
        <button id="sm_btn" type="submit" class="btn btn-info"><?php echo htmlspecialchars(_l('submit')); ?></button>
      </div>
    </div><!-- /.modal-content -->
    <?php echo form_close(); ?>
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- custom modal created for softwate -->
<div class="modal fade" id="assets2" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open_multipart(admin_url('assets/asset'), ['id' => 'assets-form-software']); ?>
    <div class="modal-content modalwidth">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
          <span class="edit-title"><?php echo htmlspecialchars(_l('edit_asset')); ?></span>
          <span class="add-title"><?php echo htmlspecialchars(_l('new_asset')); ?></span>
        </h4>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active">
            <a href="#asset_information" aria-controls="tab_staff_profile" role="tab" data-toggle="tab">
              <?php echo 'Asset information'; ?>
            </a>
          </li>

        </ul>
        <div class="tab-content">

          <div role="tabpanel" class="tab-pane active" id="asset_information">

            <div class="row">
              <div class="col-md-12">
                <div id="additional"></div>
                <div class="panel panel-info">
                  <div class="panel-heading"><?php echo htmlspecialchars(_l('asset_information')) . " : Licences"; ?></div>
                  <div class="panel-body">
                    <div class="row">
                      <div class="col-md-6">
                        <?php echo render_input('assets_code', 'asset_code', ''); ?>
                      </div>
                      <div class="col-md-6">
                        <?php echo render_input('assets_name', 'asset_name', ''); ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <?php $arrAtt        = [];
                        $arrAtt['data-type'] = 'currency';
                        echo render_input('amount', 'amounts', '', 'number'); ?>
                      </div>
                      <div class="col-md-6">
                        <label for="unit"><?php echo htmlspecialchars(_l('unit')); ?></label>
                        <select name="unit" id="unit" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo htmlspecialchars(_l('ticket_settings_none_assigned')); ?>">
                          <option value=""></option>
                          <?php foreach ($unit as $s) { ?>
                            <option value="<?php echo htmlspecialchars($s['unit_id']); ?>"><?php echo htmlspecialchars($s['unit_name']); ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="row">

                      <div class="col-md-6">
                        <label for="asset_group"><?php echo htmlspecialchars(_l('asset_group')); ?></label>
                        <select name="asset_group" id="asset_group" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo htmlspecialchars(_l('ticket_settings_none_assigned')); ?>">
                          <option value=""></option>
                          <?php foreach ($group as $s) { ?>
                            <option value="<?php echo htmlspecialchars($s['group_id']); ?>"><?php echo htmlspecialchars($s['group_name']); ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <?php echo render_input('serial_no', 'Serial No.', ''); ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <label for="department"><?php echo htmlspecialchars(_l('room_management')); ?></label>
                        <select name="department" id="department" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo htmlspecialchars(_l('ticket_settings_none_assigned')); ?>">
                          <option value=""></option>
                          <?php foreach ($departments as $s) { ?>
                            <option value="<?php echo htmlspecialchars($s['departmentid']); ?>"><?php echo htmlspecialchars($s['name']); ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <label for="asset_location"><?php echo htmlspecialchars(_l('asset_location')); ?></label>
                        <select name="asset_location" id="asset_location" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo htmlspecialchars(_l('ticket_settings_none_assigned')); ?>">
                          <option value=""></option>
                          <?php foreach ($location as $s) { ?>
                            <option value="<?php echo htmlspecialchars($s['location_id']); ?>"><?php echo htmlspecialchars($s['location']); ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-6">
                        <?php echo render_date_input('date_buy', 'date_buy', ''); ?>
                      </div>
                      <div class="col-md-6">
                        <?php echo render_input('warranty_period', 'warranty_period', '', 'number'); ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <?php echo render_input('unit_price', 'unit_price', '', 'text', $arrAtt); ?>
                      </div>
                      <div class="col-md-6">
                        <?php echo render_input('depreciation', 'depreciation_month', '', 'number'); ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group select-placeholder">
                          <label for="clientid" class="control-label"><?php echo _l('client_belongs_to'); ?></label>
                          <select id="clientid" name="clientid[]" data-live-search="true" data-width="100%" class="ajax-search" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" multiple></select>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <label for="visible_to_client"><?php echo _l('visible_to_client'); ?></label>
                        <div class="checkbox checkbox-danger">
                          <input type="checkbox" name="visible_to_client" id="visible_to_client" value="<?php echo isset($product) ? $product->visible_to_client : ''; ?>" <?php echo isset($product) ? ('1' == $product->visible_to_client) ? 'checked' : '' : ''; ?>>
                          <label></label>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <?php echo render_input('make', 'Make', ''); ?>
                      </div>
                      <div class="col-md-6">
                        <?php echo render_input('no_of_users', 'For no. of users', ''); ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <?php echo render_input('vendor_name', 'Vendor Name', ''); ?>
                      </div>
                      <div class="col-md-6">
                        <?php echo render_input('bill_no', 'Bill no.', ''); ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <?php echo render_date_input('subscription_expiry', 'Subscription Expiry', ''); ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="attachment">
                          <div class="form-group">
                            <!-- <label for="attachment" class="control-label"><small class="req text-danger">* </small><?php echo 'Upload Bill Copy'; ?></label> -->
                            <label for="attachment" class="control-label"><?php echo 'Upload Bill Copy'; ?></label>

                            <input type="file" extension="png,jpg,jpeg,gif" filesize="<?php echo file_upload_max_size(); ?>" class="form-control" name="asset_image" id="asset_image">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div id="asset_existing_image"></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="panel panel-info">
                  <div class="panel-heading"><?php echo htmlspecialchars(_l('supplier_information')); ?></div>
                  <div class="panel-body">
                    <div class="row">
                      <div class="col-md-6">
                        <?php echo render_input('supplier_name', 'supplier_name', ''); ?>
                      </div>
                      <div class="col-md-6">
                        <?php echo render_input('supplier_phone', 'supplier_phone', ''); ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <?php echo render_input('supplier_address', 'supplier_address', ''); ?>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <?php echo render_textarea('description', 'description', ''); ?>
                  </div>
                </div>
              </div>
            </div>
          </div>


        </div>
      </div>
      <div class="modal-footer">
        <button type="
                    " class="btn btn-default" data-dismiss="modal"><?php echo htmlspecialchars(_l('close')); ?></button>
        <button id="sm_btn" type="submit" class="btn btn-info"><?php echo htmlspecialchars(_l('submit')); ?></button>
      </div>
    </div><!-- /.modal-content -->
    <?php echo form_close(); ?>
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php init_tail(); ?>
</body>

</html>
<script>
  var hidden_columns = [2, 3, 6, 7, 8];
</script>
<script>
  appValidateForm($('#assets-form'), {
    assets_name: 'required',
    amount: 'required',
    unit: 'required',
    date_buy: 'required',
    warranty_period: 'required',
    unit_price: 'required',
    assets_code: {
      required: true,
      remote: {
        url: site_url + "admin/assets/assets_code_exists",
        type: 'post',
        data: {
          assets_code: function() {
            return $('input[name="assets_code"]').val();
          },
          id: function() {
            return $('input[name="id"]').val();
          }
        }
      }
    }
  });
  var table = initDataTable('.table-table_assets1', admin_url + 'assets/table_assets/' + 'all_asset');
  table.column(1).visible(false);
  var table = initDataTable('.table-table_assets2', admin_url + 'assets/table_assets/' + 'not_pending_yet');
  table.column(1).visible(false);
  var table = initDataTable('.table-table_assets3', admin_url + 'assets/table_assets/' + 'using');
  table.column(1).visible(false);
  var table = initDataTable('.table-table_assets4', admin_url + 'assets/table_assets/' + 'liquidation');
  table.column(1).visible(false);
  var table = initDataTable('.table-table_assets5', admin_url + 'assets/table_assets/' + 'warranty_repair');
  table.column(1).visible(false);
  var table = initDataTable('.table-table_assets6', admin_url + 'assets/table_assets/' + 'lost');
  table.column(1).visible(false);
  var table = initDataTable('.table-table_assets7', admin_url + 'assets/table_assets/' + 'broken');
  table.column(1).visible(false);

  function new_asset() {
    $('#assets').modal('show');
    $('.edit-title').addClass('hide');
    $('.add-title').removeClass('hide');
    $('#additional').html('');

    $('#assets #asset_existing_image').html('');
    $('#assets select#clientid').html('').change();
    // $("#assets #asset_image").prop('required', 'required');
    $("#assets .attachment .req").show();
    $('#assets input#visible_to_client').prop('checked', false);


  }

  // custom created funtion for new software asset
  function new_asset2() {
    $('#assets2').modal('show');
    $('.edit-title').addClass('hide');
    $('.add-title').removeClass('hide');
    $('#additional2').html('');

    $('#assets2 #asset_existing_image2').html('');
    $('#assets2 select#clientid2').html('').change();
    // $("#assets2 #asset_image2").prop('required', 'required');
    $("#assets2 .attachment .req").show();
    $('#assets2 input#visible_to_client2').prop('checked', false);


  }


  // validation for software asset modal

  appValidateForm($('#assets-form-software'), {
    assets_name: 'required',
    amount: 'required',
    unit: 'required',
    date_buy: 'required',
    warranty_period: 'required',
    unit_price: 'required',
    assets_code: {
      required: true,
      remote: {
        url: site_url + "admin/assets/assets_code_exists",
        type: 'post',
        data: {
          assets_code: function() {
            return $('input[name="assets_code"]').val();
          },
          id: function() {
            return $('input[name="id"]').val();
          }
        }
      }
    }
  });

  function edit_asset(invoker, id, fileid) {

    $('#additional').html('');
    $('#additional').append(hidden_input('id', id));
    $('#assets input[name="assets_code"]').val($(invoker).data('assets_code'));
    $('#assets input[name="assets_name"]').val($(invoker).data('assets_name'));
    $('#assets input[name="date_buy"]').val($(invoker).data('date_buy'));
    $('#assets input[name="amount"]').val($(invoker).data('amount'));
    $('#assets input[name="unit_price"]').val($(invoker).data('unit_price'));
    $('#assets input[name="supplier_phone"]').val($(invoker).data('supplier_phone'));
    $('#assets input[name="supplier_name"]').val($(invoker).data('supplier_name'));
    $('#assets input[name="supplier_address"]').val($(invoker).data('supplier_address'));
    $('#assets input[name="series"]').val($(invoker).data('series'));
    $('#assets input[name="warranty_period"]').val($(invoker).data('warranty_period'));
    $('#assets input[name="depreciation"]').val($(invoker).data('depreciation'));
    $('#assets select[name="unit"]').val($(invoker).data('unit'));
    $('#assets select[name="unit"]').change();
    $('#assets select[name="asset_group"]').val($(invoker).data('asset_group'));
    $('#assets select[name="asset_group"]').change();
    $('#assets select[name="department"]').val($(invoker).data('department'));
    $('#assets select[name="department"]').change();
    $('#assets select[name="asset_location"]').val($(invoker).data('asset_location'));
    $('#assets select[name="asset_location"]').change();
    $('#assets textarea[name="description"]').val($(invoker).data('description'));

    console.log($(invoker).data('file_id'));
    console.log($(invoker).data());

    $('#assets #asset_existing_image').html(`<img src="https://drive.google.com/thumbnail?id=${fileid}" class='img-thumbnail img-responsive' style="width: 150px; height: 150px;" >`);

    $('#assets select#clientid').html($(invoker).data('belongs_to_option'));
    $('#assets select#clientid').change();

    $('#assets input#visible_to_client').prop('checked', false);
    if ($(invoker).data('visible_to_client') == "1") {
      $('#assets input#visible_to_client').prop('checked', true);
    }

    $("#assets #asset_image").removeAttr('required');
    $("#assets .attachment .req").hide();

    $('#assets').modal('show');
    $('.edit-title').removeClass('hide');
    $('.add-title').addClass('hide');
  }
  init_asset();

  function init_asset(id) {
    load_small_table_item_asset(id, '#asset_sm_view', 'asset_id', 'assets/get_asset_data_ajax', '.asset_sm');
  }

  function load_small_table_item_asset(pr_id, selector, input_name, url, table) {
    var _tmpID = $('input[name="' + input_name + '"]').val();
    // Check if id passed from url, hash is prioritized becuase is last
    if (_tmpID !== '' && !window.location.hash) {
      pr_id = _tmpID;
      // Clear the current id value in case user click on the left sidebar credit_note_ids
      $('input[name="' + input_name + '"]').val('');
    } else {
      // check first if hash exists and not id is passed, becuase id is prioritized
      if (window.location.hash && !pr_id) {
        pr_id = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
      }
    }
    if (typeof(pr_id) == 'undefined' || pr_id === '') {
      return;
    }
    if (!$("body").hasClass('small-table')) {
      toggle_small_view_asset(table, selector);
    }
    $('input[name="' + input_name + '"]').val(pr_id);
    do_hash_helper(pr_id);
    $(selector).load(admin_url + url + '/' + pr_id);
    if (is_mobile()) {
      $('html, body').animate({
        scrollTop: $(selector).offset().top + 150
      }, 600);
    }
  }

  function toggle_small_view_asset(table, main_data) {

    $("body").toggleClass('small-table');
    var tablewrap = $('#small-table');
    if (tablewrap.length === 0) {
      return;
    }
    var _visible = false;
    if (tablewrap.hasClass('col-md-5')) {
      tablewrap.removeClass('col-md-5').addClass('col-md-12');
      _visible = true;
      $('.toggle-small-view').find('i').removeClass('fa fa-angle-double-right').addClass('fa fa-angle-double-left');
    } else {
      tablewrap.addClass('col-md-5').removeClass('col-md-12');
      $('.toggle-small-view').find('i').removeClass('fa fa-angle-double-left').addClass('fa fa-angle-double-right');
    }
    var _table = $(table).DataTable();
    // Show hide hidden columns
    _table.columns(hidden_columns).visible(_visible, false);
    _table.columns.adjust();
    $(main_data).toggleClass('hide');
    $(window).trigger('resize');
  }

  function preview_asset_btn(invoker) {
    var id = $(invoker).attr('id');
    var rel_id = $(invoker).attr('rel_id');
    view_asset_file(id, rel_id);
  }

  function view_asset_file(id, rel_id) {
    $('#asset_file_data').empty();
    $("#asset_file_data").load(admin_url + 'assets/file/' + id + '/' + rel_id, function(response, status, xhr) {
      if (status == "error") {
        alert_float('danger', xhr.statusText);
      }
    });
  }

  function close_modal_preview() {
    $('._project_file').modal('hide');
  }



  // custom filter code added

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




  function generateFilter() {
    // Generate dynamic filter form fields
    hideShowFilter();
    var filterHTML = '<form id="dynamicFilterForm">';
    $('#DataTables_Table_0 thead tr th').each(function(index) {
      var heading = $(this).text();
      var fieldType = inferColumnType(index, heading); // Call a function to infer column type
      filterHTML += '<div class="filter-input">';
      filterHTML += '<label for="filter_' + index + '">' + heading + ': </label>';

      filterHTML += fieldType;

      filterHTML += '</div>';
    });
    filterHTML += '<button type="submit" class = "btn btn-primary">Apply Filter</button></form>';

    // Insert the filter form into the dynamic filter container
    // $('#dynamicFilterContainer').html(filterHTML);
  }

  $(function() {
    customFilterSearch();

  })
</script>

<script>
  $(document).ready(function() {
    $('#assets-form').on('submit', function() {
      $('#depreciation-error').empty();
      var value = $('#depreciation').val();
      if (value.trim() === '0') {
        $('#depreciation-error').show();
        $('#depreciation-error').append('Value cannot be zero');
        event.preventDefault(); // Prevent form submission
        $('#depreciation').val('').focus();
      }
    });
  });
</script>