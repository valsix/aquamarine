<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class project_cost_detil_json extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->kauth->getInstance()->hasIdentity()) {
            redirect('login');
        }

        $this->db->query("SET DATESTYLE TO PostgreSQL,European;");
        $this->aduanId = $this->kauth->getInstance()->getIdentity()->Dokumen_id;
        $this->Nip = $this->kauth->getInstance()->getIdentity()->Nip;
        $this->nama = $this->kauth->getInstance()->getIdentity()->nama;
        $this->Aduan = $this->kauth->getInstance()->getIdentity()->Aduan;
        $this->linkFile = $this->kauth->getInstance()->getIdentity()->link_file;
        $this->createdBy = $this->kauth->getInstance()->getIdentity()->created_by;
        $this->createdDate = $this->kauth->getInstance()->getIdentity()->created_date;
        $this->updateBy = $this->kauth->getInstance()->getIdentity()->update_by;
        $this->updateDate = $this->kauth->getInstance()->getIdentity()->update_date;
    }

    function add()
    {
        $this->load->model("Project_cost_detil");
        $projectCostDetil = new Project_cost_detil();


        $reqId = $this->input->post("reqId");
        $reqCostDate = $this->input->post("reqCostDate");
        $reqCost = $this->input->post("reqCost");
        $reqDescription = $this->input->post("reqDescription");
        $reqStatus = $this->input->post("reqStatus");


        $projectCostDetil->setField("COST_PROJECT_DETIL_ID", $reqId);
        $projectCostDetil->setField("COST_DATE", dateToDBCheck($reqCostDate));
        $projectCostDetil->setField("COST", dateToDBCheck($reqCost));
        $projectCostDetil->setField("DESCRIPTION", $reqDescription);
        $projectCostDetil->setField("STATUS", $reqStatus);

        if (empty($reqId)) {
            $projectCostDetil->insert();
        } else {
            $projectCostDetil->update();
        }

        echo 'Data Berhasil di simpan';
    }

    function delete_detail()
    {
        $this->load->model("Project_cost_detil");
        $projectCostDetil = new Project_cost_detil();

        $reqId = $this->input->get('reqId');
        $projectCostDetil->setField("COST_PROJECT_DETIL_ID", $reqId);
        if ($projectCostDetil->delete())
            $arrJson["PESAN"] = "Data berhasil dihapus.";
        else
            $arrJson["PESAN"] = "Data gagal dihapus.";

        echo json_encode($arrJson);
    }
}
