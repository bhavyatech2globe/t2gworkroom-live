<?php


defined('BASEPATH') or exit('No direct script access allowed');
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once 'google-client/vendor/autoload.php';

class Test extends AdminController
{

    private $client;
    private $service;
    public function __construct()
    {

        parent::__construct();
        
        $this->client = new Google_Client();
        $this->client->setAuthConfig('google-client/hr-recruit-cv-429106-d2dfaffb44de.json');
        $this->client->addScope(Google_Service_Drive::DRIVE);
        $this->service = new Google_Service_Drive($this->client);

    }
    public function index()
    {
        $working_hours_summary = $this->working_hours_summary();
        $less_than_9_hrs_data = $this->less_than_9_hrs_monthly_summary();
        // $consecutive_three_days = $this->consecutive_three_days();
        $this->daily_report_email($working_hours_summary, $less_than_9_hrs_data);

        // $this->asset_image_script();

        // $this->check_compliances();
        // $staffs = $this->staff_list_compliances_and_documents();

        // $this->hr_docs_biweekly_report($staffs);

    }

    public function is_staff_present($staffid)
    {
        // getting previous date for testing purposes
        $date = date('Y-m-d', strtotime("-1 days"));

        $query = "SELECT * FROM `tblcheck_in_out` WHERE DATE(date) = '$date' and staff_id = $staffid";

        // echo $query;

        $today_present_data = $this->db->query($query)->result_array();

        // print_r($today_present_data); die;

        // return  $today_present_data;

        if (!empty($today_present_data)) {

            return 1 ;

            // if ($today_present_data[0]['type'] == 'AB') {
            //     return 0;
            // } else {
            //     return 1;
            // }
        } 

        return 0;

    }
    public function get_staff_working_hrs($staffid)
    {
        $date = date('Y-m-d', strtotime("-1 days"));

        $query = "SELECT * FROM tbltimesheets_timesheet WHERE staff_id = $staffid AND date_work = '$date'";

        $today_present_data = $this->db->query($query)->result_array();

        if ($today_present_data) {
            // return $today_present_data[0]['value'] ?? 0;
            if ($today_present_data[0]['value']) {
                return $today_present_data[0]['value'];
            } else {
                return null;
            }
        } else {
            return 0;
        }
    }

    public function check_working_hrs($hrs, &$nine_plus, &$five_to_nine, &$zero_to_five)
    {

        switch (true) {
            case $hrs <= 5 && $hrs > 0:
                $zero_to_five++;
                break;
            case $hrs > 5 && $hrs < 9:
                $five_to_nine++;
                break;
            case $hrs >= 9:
                $nine_plus++;
                break;

        }
    }

    public function managers_list()
    {
        $query = "SELECT team_manage from tblstaff";
        $data = $this->db->query($query)->result_array();
        $manager_ids = array_filter(array_unique(array_column($data, 'team_manage')));

        $managers = [];

        foreach ($manager_ids as $manager_id) {

            $managers[$manager_id] = get_staff_full_name($manager_id);

        }

        return $managers;


    }

    public function working_hours_summary()
    {

        // $query = "SELECT * FROM tblstaff LEFT JOIN tblstaff_info ON tblstaff.staffid = tblstaff_info.staffid where manageLeave = 1";

        // $data = $this->db->query($query)->result_array();

        $data = $this->managers_list();

        echo '<pre>';

        $working_hours_summary = array();

        foreach ($data as $manager_id => $manager_name) {


            $query = "SELECT staffid FROM tblstaff WHERE team_manage = $manager_id AND active = 1";


            $team_staffids = array_column($this->db->query($query)->result_array(), 'staffid');
            $staff_data = [];
            $nine_plus = 0;
            $five_to_nine = 0;
            $zero_to_five = 0;
            foreach ($team_staffids as $team_staffid) {
                $staff_data[$team_staffid]['attendance'] = $this->is_staff_present($team_staffid);
                $working_hrs = $this->get_staff_working_hrs($team_staffid);
                $staff_data[$team_staffid]['working_hrs'] = $working_hrs;
                $this->check_working_hrs($working_hrs, $nine_plus, $five_to_nine, $zero_to_five);

            }
            $working_hours_summary[$manager_id]['team_data'] = $staff_data;

            $working_hours_summary[$manager_id]['date'] = date('Y-m-d', strtotime("-1 days"));
            $working_hours_summary[$manager_id]['team_count'] = count($staff_data);
            $present = array_sum(array_column($staff_data, 'attendance'));
            $working_hours_summary[$manager_id]['present'] = $present;
            $working_hours_summary[$manager_id]['absent'] = $working_hours_summary[$manager_id]['team_count'] - $present;
            $occupancy = $working_hours_summary[$manager_id]['team_count'] ? ($working_hours_summary[$manager_id]['present'] / $working_hours_summary[$manager_id]['team_count']) * 100 : 0;
            $working_hours_summary[$manager_id]['occupancy'] = round($occupancy, 2);
            $working_hours_summary[$manager_id]['nine_plus'] = $nine_plus;
            $working_hours_summary[$manager_id]['five_to_nine'] = $five_to_nine;
            $working_hours_summary[$manager_id]['zero_to_five'] = $zero_to_five;

        }



        // print_r($working_hours_summary); die;
        return $working_hours_summary;

    }
    public function daily_report_email($working_hrs_summary, $less_than_nine_summary)
    {

        $date = date('m/d/Y', strtotime("-1 days"));

        try {

            $this->email->set_mailtype("html");
            $this->email->from('no-reply@t2gworkroom.com', 'Tech2globe');
            $this->email->to('bhavyakhanna.tech2globe@gmail.com');
            // $this->email->to('sarabjeet@tech2globe.net');
            $this->email->cc(array('ishan.negi@tech2globe.in'));
            // $this->email->cc(array('bhavya.khanna@tech2globe.in','naved.ahamad@tech2globe.in','bhavyakhanna.tech2globe@gmail.com'));

            // $this->email->cc(array('ishan.negi@tech2globe.in','sarabjeet@tech2globe.net','sarabjeet@tech2globe.com'));
            $subject = "Daily Report - T2GWorkroom - $date ";

            $message = '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Performance review</title>
                <style>
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    th, td {
                        border: 1px solid black;
                        padding: 8px;
                        text-align: left;
                    }
                    th {
                        background-color: #2600bd;
                        color : white;
                    }

                    td {
                        color : black;
                    }
                </style>
            </head>
            <body>
            ';


            // $count = 1;
            // foreach ($working_hrs_summary as $manager_id => $manager_data) {
            //     $message .= "<tr>
            //     <td>" . $count++ . "</td>
            //     <td>" . get_staff_full_name($manager_id) . "</td>
            //     <td>" . $manager_data['team_count'] . "</td>
            //     <td>" . $manager_data['occupancy'] . "%</td>
            //     </tr>";
            // }



            $message .= '
                

            <h2>Today\'s Attendance</h2>
            <table>
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Cluster</th>
                        <th>Team Count</th>
                        <th>Present</th>
                        <th>Absent</th>
                        <th>Occupancy</th>

                    </tr>
                </thead>
            <tbody>
            ';

            $count = 1;
            foreach ($working_hrs_summary as $manager_id => $manager_data) {
                $message .= "<tr>
                <td>" . $count++ . "</td>
                <td>" . get_staff_full_name($manager_id) . "</td>
                <td>" . $manager_data['team_count'] . "</td>
                <td>" . $manager_data['present'] . "</td>
                <td>" . $manager_data['absent'] . "</td>
                <td>" . $manager_data['occupancy'] . "%</td>

                </tr>";
            }


            // $message .= ' </tbody> </table>
            // <h2>Working Hours Summary</h2>
            // <table>
            //         <thead>
            //             <tr>
            //                 <th>S.No.</th>
            //                 <th>Cluster</th>
            //                 <th>Team Count</th>
            //                 <th>9+ hours</th>
            //                 <th>5 to 9 hours</th> 
            //                 <th>0 to 5 hours</th> 
            //             </tr>
            //         </thead>
            //     <tbody>';

            // $count = 1;
            // foreach ($working_hrs_summary as $manager_id => $manager_data) {
            //     $message .= "<tr>
            //     <td>" . $count++ . "</td>
            //     <td>" . get_staff_full_name($manager_id) . "</td>
            //     <td>" . $manager_data['team_count'] . "</td>
            //     <td>" . $manager_data['nine_plus'] . "</td>
            //     <td>" . $manager_data['five_to_nine'] . "</td>
            //     <td>" . $manager_data['zero_to_five'] . "</td>
            //     </tr>";
            // }

            $message .= "</tbody></table>
            <h2>Absent Employees Summary</h2>
            <table>
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Emp ID</th>
                            <th>Emp Name</th>
                            <th>Reporting Person</th>
                            <th>Department</th> 
                             
                        </tr>
                    </thead>
                <tbody>";


            foreach ($less_than_nine_summary as $key => $less_than_nine_data) {
                $sno = $key + 1;
                $message .= "<tr>
                <td>" . $sno . "</td>
                <td>" . $less_than_nine_data['emp_id'] . "</td>
                <td>" . $less_than_nine_data['employee'] . "</td>
                <td>" . $less_than_nine_data['manager_name'] . "</td>
                <td>" . $less_than_nine_data['department_name'] . "</td>
               
                </tr>";
            }



            $message .= "</tbody></table></body></html>";

            $this->email->subject($subject);
            $this->email->message($message);
            if ($this->email->send()) {
                echo 'mail sent';
            } else {
                echo 'mail not sent';
            }

            log_message('error', 'Workroom report email sent!');

        } catch (phpmailerException $e) {
            echo $e->errorMessage(); //Pretty error messages from PHPMailer
        } catch (Exception $e) {
            echo $e->getMessage(); //Boring error messages from anything else!
        }

    }
    public function less_than_9_hrs_monthly_summary()
    {


        // $less_than_9_hrs_monthly_summary = array();

        $date = date('Y-m-d', strtotime("-1 days"));


        $month = date('m', strtotime($date));

        // $query = "SELECT staff_id,COUNT(staff_id) AS monthly_count ,departmentid,team_manage,staff_identifi  FROM tbltimesheets_timesheet LEFT JOIN tblstaff_departments ON tbltimesheets_timesheet.staff_id = tblstaff_departments.staffid LEFT JOIN tblstaff ON tbltimesheets_timesheet.staff_id = tblstaff.staffid WHERE month(date_work) = $month AND value < 9 AND value > 0 GROUP BY staff_id, departmentid, team_manage, staff_identifi; ;";

        $query = "SELECT
            staffid,
            firstname,
            lastname,
            staff_identifi,
            team_manage,
            (
            SELECT
                GROUP_CONCAT(
                    tblstaff_departments.departmentid
                )
            FROM
                tblstaff_departments
            WHERE
                staffid = tblstaff.staffid
        ) AS departmentids
        FROM
            `tblstaff`
        LEFT JOIN tblcheck_in_out ON tblstaff.staffid = tblcheck_in_out.staff_id AND DATE(DATE) = '$date' AND type_check = 1
        WHERE
            tblcheck_in_out.staff_id IS NULL AND active = 1 AND(
            SELECT
                COUNT(sd.departmentid)
            FROM
                tblstaff_departments sd
            WHERE
                sd.staffid = tblstaff.staffid
        ) > 0 AND tblstaff.staffid != 1
        ";

        $less_than_9_hrs_data = $this->db->query($query)->result_array();



        foreach ($less_than_9_hrs_data as &$data) {
            $data['employee'] = get_staff_full_name($data['staffid']);

            $data['department_name'] = $this->db->query("select GROUP_CONCAT(tbldepartments.name SEPARATOR ', ') AS department_names from tbldepartments where departmentid IN (" . $data['departmentids'] . ')')->row()->department_names;



            $data['manager_name'] = get_staff_full_name($data['team_manage']);
            $data['emp_id'] = $data['staff_identifi'];
            // $data['manager_name'] = 
            // print_r(array_column($arr,'staffid')); 

        }


        return $less_than_9_hrs_data;
    }


    function uploadImageToDrive($filePath, $service, $folderId) {
        $fileMetadata = new Google_Service_Drive_DriveFile([
            'name' => basename($filePath),
            'parents' => [$folderId]
        ]);
        $content = file_get_contents($filePath);
        $file = $this->service->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => mime_content_type($filePath),
            'uploadType' => 'multipart',
            'fields' => 'id'
        ]);
        return $file->id;
    }

    function updateFileIdInDatabase($fileId, $imageName) {

        $this->db->where('asset_image', $imageName);
        $this->db->update('tblassets', ['file_id' => $fileId]);
   
    }



    function asset_image_script(){

            // Folder ID where you want to upload images
        $folderId = '1VUrGQn8e9kBpoMpYmcLTUwztfAGLwnjo';

        // Path to the directory containing images
        $imagesDirectory = 'modules/assets/uploads';

        // Fetch image names from the database
        $query = "SELECT asset_image FROM tblassets WHERE file_id IS NULL AND asset_image IS NOT NULL"; // Adjust the query as needed
        $result = $this->db->query($query);
        
        if ($result->num_rows() > 0) {
            foreach ($result->result() as $row) {
                $imageName = $row->asset_image;
                $filePath = $imagesDirectory . '/' . $imageName;

                if (file_exists($filePath)) {
                    $fileId = $this->uploadImageToDrive($filePath, $this->service, $folderId);
                    $this->updateFileIdInDatabase($fileId, $imageName);
                    echo "Uploaded and updated file ID for: " . $imageName . "<br>";
                } else {
                    echo "File not found: " . $filePath . "<br>";
                }
            }
        } else {
            echo "No images to upload.<br>";
        }

        $this->db->close();
    }



    // function check_compliances($staffs){
        
  
    //     $data = [];
    //     foreach ($staffs as $staff){
    //         if(!$staff['appointment_letter']||$staff['appointment_letter']=='no' || !$staff['coi_letter']||$staff['coi_letter']=='no' || !$staff['nda']||$staff['nda']=='no'|| !$staff['policy_document']||$staff['policy_document']=='no'|| !$staff['appointment_letter']||$staff['appointment_letter']=='no'|| !$staff['bio_enroll']||$staff['bio_enroll']=='no'|| !$staff['join_kit']||$staff['join_kit']=='no'|| !$staff['id_card']||$staff['id_card']=='no'|| !$staff['bgv']||$staff['bgv']=='no'){
    //             $data[] = $staff;
    //         }
    //     }

    //     return $data;

    // }

    function check_document_uploaded($staffs){
        

        $data = [];

        foreach($staffs as $staff){
            if(!$staff['identification']||!$staff['_10_marksheet']||!$staff['_12_marksheet']||!$staff['declaration_signature']||!$staff['appointment_letter']||$staff['appointment_letter']=='no' || !$staff['coi_letter']||$staff['coi_letter']=='no' || !$staff['nda']||$staff['nda']=='no'|| !$staff['policy_document']||$staff['policy_document']=='no'|| !$staff['appointment_letter']||$staff['appointment_letter']=='no'|| !$staff['bio_enroll']||$staff['bio_enroll']=='no'|| !$staff['join_kit']||$staff['join_kit']=='no'|| !$staff['id_card']||$staff['id_card']=='no'|| !$staff['bgv']||$staff['bgv']=='no'){
                $data[] = $staff;
            }
        }

        return $data;
    }


    function staff_list_compliances_and_documents(){
        $staffs = $this->db->query('SELECT
            *,
            (
            SELECT
                GROUP_CONCAT(
                    tblstaff_departments.departmentid
                )
            FROM
                tblstaff_departments
            WHERE
                staffid = tblstaff.staffid
        ) AS departmentids
        FROM
            `tblstaff`
        
        WHERE active = 1 AND staffid != 1')->result_array();

        foreach ($staffs as &$data) {

            if($data['departmentids']){

                $data['department_name'] = $this->db->query("select GROUP_CONCAT(tbldepartments.name SEPARATOR ', ') AS department_names from tbldepartments where departmentid IN (" . $data['departmentids'] . ')')->row()->department_names;
            } else{
                $data['department_name'] = '';
            }




        }

         $data = $this->check_document_uploaded($staffs);

         return $data;

    }

    public function hr_docs_biweekly_report($staffs)
    {

        $date = date('m/d/Y');


        try {

            $this->email->set_mailtype("html");
            $this->email->from('no-reply@t2gworkroom.com', 'Tech2globe');
            $this->email->to('bhavyakhanna.tech2globe@gmail.com');
            // $this->email->to('sarabjeet@tech2globe.net');
            $this->email->cc(array('ishan.negi@tech2globe.in'));
            // $this->email->cc(array('bhavya.khanna@tech2globe.in','naved.ahamad@tech2globe.in','bhavyakhanna.tech2globe@gmail.com'));

            // $this->email->cc(array('ishan.negi@tech2globe.in','sarabjeet@tech2globe.net','sarabjeet@tech2globe.com'));
            $subject = "Staff list for documents not uploaded- T2GWorkroom - $date ";

            $message = '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Performance review</title>
                <style>
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    th, td {
                        border: 1px solid black;
                        padding: 8px;
                        text-align: left;
                    }
                    th {
                        background-color: #2600bd;
                        color : white;
                    }

                    td {
                        color : black;
                    }
                </style>
            </head>
            <body>
            
            ';




    


            $message .= "
            <h2>Documents Not Uploaded</h2>
            <table>
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Emp ID</th>
                            <th>Emp Name</th>
                            <th>Reporting Person</th>
                            <th>Department</th> 
                             
                        </tr>
                    </thead>
                <tbody>";


            foreach ($staffs as $key => $staff) {
                $sno = $key + 1;
                $message .= "<tr>
                <td>" . $sno . "</td>
                <td>" . $staff['staff_identifi'] . "</td>
                <td>" . $staff['firstname'] . ' ' . $staff['lastname'] . "</td>
                <td>" . get_staff_full_name($staff['team_manage']) . "</td>
                <td>" . $staff['department_name'] . "</td>
               
                </tr>";
            }



            $message .= "</tbody></table></body></html>";

            $this->email->subject($subject);
            $this->email->message($message);
            if ($this->email->send()) {
                echo 'mail sent';
            } else {
                echo 'mail not sent';
            }

            log_message('error', 'Workroom report email sent!');

        } catch (phpmailerException $e) {
            echo $e->errorMessage(); //Pretty error messages from PHPMailer
        } catch (Exception $e) {
            echo $e->getMessage(); //Boring error messages from anything else!
        }

    }

}
