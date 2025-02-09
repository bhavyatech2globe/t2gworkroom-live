var addMoreCandidateInputKey;
var addMoreInterviewsInputKey;
var round_number = 1;
(function ($) {
  "use strict";
  initDataTable('.table-table_interview', admin_url + 'recruitment/table_interview');
  appValidateForm($('#interview_schedule-form'), {
    rec_campaign: 'required', is_name: 'required', 'interview_day[0]': 'required', 'from_time[0]': 'required', 'to_time[0]': 'required', campaign: 'required', position: 'required', 'interviewer[0]': 'required'
  });

  init_recruitment_interview_schedules();

  $('#from_time').datetimepicker({
    datepicker: false,
    format: 'H:i'
  });
  $('#to_time').datetimepicker({
    datepicker: false,
    format: 'H:i'
  });

  addMoreCandidateInputKey = $('.list_candidates input[name*="email"]').length;
  $("body").on('click', '.new_candidates', function () {
    if ($(this).hasClass('disabled')) { return false; }

    var newattachment = $('.list_candidates').find('#candidates-item').eq(0).clone().appendTo('.list_candidates');
    newattachment.find('button[data-toggle="dropdown"]').remove();
    newattachment.find('select').selectpicker('refresh');

    newattachment.find('select[name="candidate[0]"]').attr('name', 'candidate[' + addMoreCandidateInputKey + ']').val('');
    newattachment.find('select[id="candidate[0]"]').attr('id', 'candidate[' + addMoreCandidateInputKey + ']').selectpicker('refresh');

    newattachment.find('input[name="email[0]"]').attr('name', 'email[' + addMoreCandidateInputKey + ']').val('');
    newattachment.find('input[id="email[0]"]').attr('id', 'email[' + addMoreCandidateInputKey + ']').val('');

    newattachment.find('input[name="phonenumber[0]"]').attr('name', 'phonenumber[' + addMoreCandidateInputKey + ']').val('');
    newattachment.find('input[id="phonenumber[0]"]').attr('id', 'phonenumber[' + addMoreCandidateInputKey + ']').val('');

    newattachment.find('button[name="add"] i').removeClass('fa-plus').addClass('fa-minus');
    newattachment.find('button[name="add"]').removeClass('new_candidates').addClass('remove_candidates').removeClass('btn-success').addClass('btn-danger');


    addMoreCandidateInputKey++;

  });

  $("body").on('click', '.remove_candidates', function () {
    $(this).parents('#candidates-item').remove();
  });


  addMoreInterviewsInputKey = $('.list_interviews input[name*="interview_day"]').length;
  // let addMoreInterviewsInputKey = parseInt($('#interview_heading').text().match(/\d+/)[0]);
  // console.log(headingText); // Output: "Round 2"
  // var round_number = parseInt($('.list_interviews').find('#interview_heading').text().match(/\d+/)[0]);

  // Extract the number from the text
  // let roundNumber = headingText.match(/\d+/)[0];


  $("body").on('click', '.new_interviews', function () {
    if ($(this).hasClass('disabled')) { return false; }


    // let addMoreInterviewsInputKey = parseInt($('#interview_heading').text().match(/\d+/)[0]);

    var newattachment = $('.list_interviews').find('#interviews-item').eq(0).clone().appendTo('.list_interviews');
    // var newattachment = $('.list_interviews').find('#interviews-item').last().clone().appendTo('.list_interviews');

    console.log($('.list_interviews').find('#interviews-item'));


    newattachment.find('button[data-toggle="dropdown"]').remove();
    newattachment.find('select').selectpicker('refresh');

    newattachment.find('input[name="interview_day[0]"]').attr('name', 'interview_day[' + addMoreInterviewsInputKey + ']').val('');
    newattachment.find('div[app-field-wrapper="interview_day[0]"]').attr('app-field-wrapper', 'interview_day[' + addMoreInterviewsInputKey + ']');

    var newInput = newattachment.find('input[id="interview_day[0]"]').attr('id', 'interview_day[' + addMoreInterviewsInputKey + ']').val('');

    // Remove existing datepicker instance
    newInput.removeClass('hasDatepicker').datepicker('destroy');
    // Initialize the datepicker on the new input element
    newInput.datepicker();


    newattachment.find('input[name="from_time[0]"]').attr('name', 'from_time[' + addMoreInterviewsInputKey + ']').val('');
    newattachment.find('input[id="from_time[0]"]').attr('id', 'from_time[' + addMoreInterviewsInputKey + ']').val('');

    newattachment.find('input[name="to_time[0]"]').attr('name', 'to_time[' + addMoreInterviewsInputKey + ']').val('');
    newattachment.find('input[id="to_time[0]"]').attr('id', 'to_time[' + addMoreInterviewsInputKey + ']').val('');


    newattachment.find('textarea[name="interview_link[0]"]').attr('name', 'interview_link[' + addMoreInterviewsInputKey + ']').val('');
    newattachment.find('textarea[id="interview_link[0]"]').attr('id', 'interview_link[' + addMoreInterviewsInputKey + ']').val('');


    newattachment.find('select[name="interviewer[0][]"]').attr('name', 'interviewer[' + addMoreInterviewsInputKey + '][]').val('');
    newattachment.find('select[id="interviewer[0]"]').attr('id', 'interviewer[' + addMoreInterviewsInputKey + ']').selectpicker('refresh');


    newattachment.find('button[name="add"] i').removeClass('fa-plus').addClass('fa-minus');
    newattachment.find('button[name="add"]').removeClass('new_interviews').addClass('remove_interviews').removeClass('btn-success').addClass('btn-danger');


    newattachment.find('input[id="round[0]"]').attr('id', 'round[' + addMoreInterviewsInputKey + ']').val(round_number + 1);
    newattachment.find('input[name="round[0]"]').attr('name', 'round[' + addMoreInterviewsInputKey + ']').val(round_number + 1);

    newattachment.find('#interview_heading').html('Round ' + (round_number + 1));

    addMoreInterviewsInputKey++;
    round_number++;

  });

  $("body").on('click', '.remove_interviews', function () {
    $(this).parents('#interviews-item').remove();
    addMoreInterviewsInputKey--;
    round_number--;
  });



})(jQuery);
var job_position;

function new_interview_schedule() {
  "use strict";
  round_number = 1;
  $('.list_interviews').children(':not(:first)').remove();
  $('#interview_schedules_modal').modal('show');
  $('.add-title').removeClass('hide');
  $('.edit-title').addClass('hide');
  $('#additional_interview').html('');

  $('select[id="candidate"]').val('').change();
  $('select[id="interviewer"]').val('').change();
  $('input[id="is_name"]').val('').change();
  $('input[id="from_time"]').val('');
  $('input[id="to_time"]').val('');
  $('select[id="campaign"]').val('').change();
  job_position = '';
  $('input[id="email"]').val('');
  $('input[id="phonenumber"]').val('');

  // $('input[name="edit"]').val('');

  $('input[name="id"]').val('');

  requestGetJSON('recruitment/get_candidate_sample').done(function (response) {
    addMoreCandidateInputKey = response.total_candidate;

    $('.list_candidates').html('');
    $('.list_candidates').append(response.html);
    $('.selectpicker').selectpicker('refresh');
  });

}

// custom edit interview

function edit_interview(round, id) {
  "use strict";

  $('.list_interviews').children(':not(:first)').remove();

  $('#interview_schedules_modal').modal('show');
  $('.add-title').removeClass('hide');
  $('.edit-title').addClass('hide');
  $('#additional_interview').html('');

  $('.list_interviews').find('#interview_heading').html('Round ' + parseInt(round));

  // $('input[name="edit"]').val('1');
  // $('#interview_heading').html('Round ' + round);

  // $('select[id="candidate"]').val('').change();
  // $('select[id="interviewer"]').val('').change();
  // $('input[id="is_name"]').val('').change();
  // $('input[id="from_time"]').val('');
  // $('input[id="to_time"]').val('');
  // $('select[id="campaign"]').val('').change();
  // job_position = '';
  // $('input[id="email"]').val('');
  // $('input[id="phonenumber"]').val('');

  $.post(admin_url + 'recruitment/get_candidate_infor_change_edit/' + id + '/' + round).done(function (response) {
    response = JSON.parse(response);


    $('select[name="candidate"]').val(id).change();
    $('select[name="campaign"]').val(response.rec_campaign).change();

    $('input[name="email"]').val(response.email);
    $('input[name="phonenumber"]').val(response.phonenumber);
    $('#position').val(response.job_position).change();
    $('input[name="position"]').val(response.job_position);

    $('input[name="interview_day[0]"]').val(response.interview_date);
    $('input[name="from_time[0]"]').val(response.from);
    $('input[name="to_time[0]"]').val(response.to);
    $('textarea[name="interview_link[0]"]').val(response.interview_link);

    $('input[name="round[0]"]').val(response.round);

    $('input[name="id"]').val(response.id);

    round_number = parseInt(response.round);

    let interviewer_array = response.interviewers.split(',');

    $('select[name="interviewer[0][]"]').val(interviewer_array).change();



  });


  // requestGetJSON('recruitment/get_candidate_sample').done(function (response) {
  //   addMoreCandidateInputKey = response.total_candidate;

  //   $('.list_candidates').html('');
  //   $('.list_candidates').append(response.html);
  //   $('.selectpicker').selectpicker('refresh');
  // });

}



function edit_interview_schedule(invoker, id) {
  "use strict";
  $('#interview_schedules_modal').modal('show');
  $('.add-title').addClass('hide');
  $('.edit-title').removeClass('hide');
  $('#additional_interview').html('');
  $('#additional_interview').append(hidden_input('id', id));
  $('#interview_schedules_modal input[name="is_name"]').val($(invoker).data('is_name'));

  if ($(invoker).data('position') != 0 && $(invoker).data('position') != '') {
    job_position = $(invoker).data('position');

  } else {
    job_position = '';

  }
  if ($(invoker).data('campaign') != 0) {

    $('#interview_schedules_modal select[name="campaign"]').val($(invoker).data('campaign')).change();
  } else {
    $('#interview_schedules_modal select[name="campaign"]').val('').change();

  }


  $('#interview_schedules_modal input[name="interview_day"]').val($(invoker).data('interview_day'));
  $('#interview_schedules_modal input[name="from_time"]').val($(invoker).data('from_time'));
  $('#interview_schedules_modal input[name="to_time"]').val($(invoker).data('to_time'));

  var interviewer = $(invoker).data('interviewer');
  if (typeof (interviewer) == "string") {
    $('#interview_schedules_modal select[name="interviewer[]"]').val(($(invoker).data('interviewer')).split(',')).change();
  } else {
    $('#interview_schedules_modal select[name="interviewer[]"]').val($(invoker).data('interviewer')).change();

  }
  $('.selectpicker').selectpicker('refresh');


  $.post(admin_url + 'recruitment/get_candidate_edit_interview/' + id).done(function (response) {
    response = JSON.parse(response);
    addMoreCandidateInputKey = response.total_candidate;

    $('.list_candidates').html('');
    $('.list_candidates').append(response.html);
    $('.selectpicker').selectpicker('refresh');
  });
}
function init_recruitment_interview_schedules(id) {
  load_small_table_item_interview_schedules(id, '#interview_sm_view', 'interview_id', 'recruitment/get_interview_data_ajax', '.interview_sm');
}
function load_small_table_item_interview_schedules(pr_id, selector, input_name, url, table) {
  "use strict";
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
  if (typeof (pr_id) == 'undefined' || pr_id === '') { return; }
  if (!$("body").hasClass('small-table')) { toggle_small_view_interview_schedules(table, selector); }
  $('input[name="' + input_name + '"]').val(pr_id);
  do_hash_helper(pr_id);
  $(selector).load(admin_url + url + '/' + pr_id);
  if (is_mobile()) {
    $('html, body').animate({
      scrollTop: $(selector).offset().top + 150
    }, 600);
  }
}
function toggle_small_view_interview_schedules(table, main_data) {
  "use strict";
  var hidden_columns = [4, 6, 7];
  $("body").toggleClass('small-table');
  var tablewrap = $('#small-table');
  if (tablewrap.length === 0) { return; }
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
function candidate_infor_change(invoker) {
  "use strict";
  // var result = invoker.name.match(/\d/g);
  var data = {};
  // data.interview_day = $('input[name="interview_day"]').val();
  // data.from_time = $('input[name="from_time"]').val();
  // data.to_time = $('input[name="to_time"]').val();
  data.candidate = invoker.value;
  data.id = $('input[name="id"]').val();

  if (invoker.value == '') {
    $('input[name="email"]').val('');
    $('input[name="phonenumber"]').val('');
    $('input[name="position"]').val('');

  } else {
    $.post(admin_url + 'recruitment/get_candidate_infor_change/' + invoker.value).done(function (response) {
      response = JSON.parse(response);


      $('input[name="email').val(response.email);
      $('input[name="phonenumber"]').val(response.phonenumber);
      $('#position').val(response.job_position).change();
      $('input[name="position"]').val(response.job_position);


    });
    // $.post(admin_url + 'recruitment/check_time_interview', data).done(function (response) {
    //   response = JSON.parse(response);
    //   if (response.return == true) {
    //     alert_float('warning', response.rs, 6000);
    //     $('select[name="candidate[' + result + ']"]').val('').change();

    //   }
    // });
  }
}


function campaign_change() {

  var data_select = {};
  data_select.campaign = $('select[name="campaign"]').val();

  $.post(admin_url + 'recruitment/get_position_fill_data', data_select).done(function (response) {
    response = JSON.parse(response);
    $("select[name='position']").html('');

    $("select[name='position']").append(response.position);
    $("select[name='position']").selectpicker('refresh');

    if (job_position != 0 || job_position != '') {

      $('#interview_schedules_modal select[name="position"]').val(job_position).change();

    }


  });

};