<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Cron extends App_Controller
{
    public function index($key = '')
    {
        update_option('cron_has_run_from_cli', 1);

        if (defined('APP_CRON_KEY') && (APP_CRON_KEY != $key)) {
            header('HTTP/1.0 401 Unauthorized');
            die('Passed cron job key is not correct. The cron job key should be the same like the one defined in APP_CRON_KEY constant.');
        }

        $last_cron_run = get_option('last_cron_run');
        $seconds = hooks()->apply_filters('cron_functions_execute_seconds', 300);

        if ($last_cron_run == '' || (time() > ($last_cron_run + $seconds))) {
            $this->load->model('cron_model');
            $this->cron_model->run();
        }
    }


    public function workroom_report($key = '')
    {

        $this->load->model('cron_model');
        $this->cron_model->workroom_report_run();
    }

    public function it_asset_report($key = '')
    {
        $this->load->model('cron_model');
        $this->cron_model->it_asset_report_run();
    }

    public function hr_recruit_report_run($key = '')
    {

        $this->load->model('cron_model');
        $this->cron_model->hr_recruit_report_run();

    }

    public function update_leave_balance($key = '')
    {

        $this->load->model('cron_model');
        $this->cron_model->update_leave_balance();

    }
    public function biweekly_document_report($key = '')
    {

        $this->load->model('cron_model');
        $this->cron_model->biweekly_document_report();

    }
}
