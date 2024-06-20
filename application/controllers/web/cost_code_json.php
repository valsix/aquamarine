<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class cost_code_json extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        if (!$this->kauth->getInstance()->hasIdentity()) {
            redirect('login');
        }

        $this->db->query("SET DATESTYLE TO PostgreSQL,European;");
        $this->USERID = $this->kauth->getInstance()->getIdentity()->USERID;
        $this->USERNAME = $this->kauth->getInstance()->getIdentity()->USERNAME;
        $this->FULLNAME = $this->kauth->getInstance()->getIdentity()->FULLNAME;
        $this->USERPASS = $this->kauth->getInstance()->getIdentity()->USERPASS;
        $this->LEVEL = $this->kauth->getInstance()->getIdentity()->LEVEL;
        $this->MENUMARKETING = $this->kauth->getInstance()->getIdentity()->MENUMARKETING;
        $this->MENUFINANCE = $this->kauth->getInstance()->getIdentity()->MENUFINANCE;
        $this->MENUPRODUCTION = $this->kauth->getInstance()->getIdentity()->MENUPRODUCTION;
        $this->MENUDOCUMENT = $this->kauth->getInstance()->getIdentity()->MENUDOCUMENT;
        $this->MENUSEARCH = $this->kauth->getInstance()->getIdentity()->MENUSEARCH;
        $this->MENUOTHERS = $this->kauth->getInstance()->getIdentity()->MENUOTHERS;
    }

    function tree_json(){
        $this->load->model("CostCode");
        
        $group_by = ' ORDER BY A.KODE ASC';
        $id = $this->input->post("id");
        $i = 0;
        if ($id == '0') {
            $cost_code = new CostCode();
            $statement = " AND A.PARENT_ID ='0'";
            $cost_code->selectByParamsMonitoring(array(),-1,-1,$statement);
            while ($cost_code->nextRow()) {
                $arrData[$i]['id'] =$cost_code->getField('COST_CODE_ID');
                $arrData[$i]['ID'] =$cost_code->getField('COST_CODE_ID');
                $arrData[$i]['CODE'] =$cost_code->getField('KODE');
                $arrData[$i]['NAMA'] = $cost_code->getField('NAMA');
                $arrData[$i]['AKSI'] = $this->cek_link($cost_code->getField('COST_CODE_ID'));
                $arrData[$i]['state'] = $this->tree_child_json($cost_code->getField('COST_CODE_ID')) ? 'closed' : 'open';
               $i++;
            }
            $result["rows"] = $arrData;
        }else{
            $cost_code = new CostCode();
            $statement = " AND A.PARENT_ID ='".$id."'";

            $cost_code->selectByParamsMonitoring(array(),-1,-1,$statement);
            // echo $cost_code->query;exit;
            while ($cost_code->nextRow()) {
                $result[$i]['id'] = $cost_code->getField('COST_CODE_ID');
                $result[$i]['ID'] = $cost_code->getField('COST_CODE_ID');
                $result[$i]['CODE'] = $cost_code->getField('KODE');
                $result[$i]['NAMA'] = $cost_code->getField('NAMA');
                $result[$i]['AKSI'] = $this->cek_link($cost_code->getField('COST_CODE_ID'));
                $result[$i]['state'] = $this->tree_child_json($cost_code->getField('COST_CODE_ID')) ? 'closed' : 'open';
                $i++;
            }

        }
            echo json_encode($result);
    }
    function tree_child_json($id='', $statement='')
    {
        $this->load->model("CostCode");
        $cost_code = new CostCode();
        $cost_code->selectByParamsMonitoring(array('A.PARENT_ID' => $id), -1, -1);
        // ECHO  $cost_code->query;
        $cost_code->firstRow();
        $tempId = $cost_code->getField("COST_CODE_ID");
        if ($tempId == "")
            return false;
        else
            return true;
    }

    function cek_link($id=''){
        $this->load->model("CostCode");
        $cost_code = new CostCode();
        $cost_code->selectByParamsMonitoring(array("A.COST_CODE_ID"=>$id));   
        $cost_code->firstRow(); 

        $cost_code_total = new CostCode();
        $total  = $cost_code_total->getCountByParamsMonitoring(array("A.PARENT_ID"=>$id));

        $DEL = "Delete('".$id."')";
        $link_delete = '<a title="Delete ' . $cost_code->getField('NAMA') . '" onclick="'.$DEL.'"><img src="images/icon-hapus.png" heigth="15px" width="15px" style="margin-right:5px"></a>';
        $POP = "popTambah('".$id."')";
        $link_tambah = '<a title="Tambah  ' . $cost_code->getField('NAMA') . '" onclick="'.$POP.'"><img src="images/icon-tambah.png" heigth="15px" width="15px" style="margin-right:5px"></a>';

        $ED = "popEdit('".$id."')";
        $link   .= '<a title="Edit ' . $cost_code->getField('NAMA') . '" onclick="'.$ED.'"><img src="images/icon-edit.png" heigth="15px" width="15px" style="margin-right:5px"></a>';

        $link_html = '';
        if($total==0){
            $link_html .= $link_tambah.$link.$link_delete;
        }else{
            $link_html .= $link_tambah.$link;
        }
        
        return  $link_html;


    }
    
    function add(){
        $this->load->model("CostCode");
       
        $reqParentId = $this->input->post("reqParentId");
        $reqCode     = $this->input->post("reqCode");
        $reqNama     = $this->input->post("reqNama");
        $reqId      = $this->input->post("reqId");
        $reqMode      = $this->input->post("reqMode");

          $cost_codes = new CostCode();
          $cost_codes->selectByParamsMonitoring(array("A.KODE"=>$reqParentId));
          $cost_codes->firstRow();
          $reqPar =  $cost_codes->getField('COST_CODE_ID');
          if(empty($reqPar)){
            $reqPar=0;
          }

         $cost_code = new CostCode();
         $cost_code->setField("KODE",$reqCode);
         $cost_code->setField("NAMA",$reqNama );
         $cost_code->setField("PARENT_ID",$reqPar);
         $cost_code->setField("COST_CODE_ID",$reqId );

         if($reqMode =='insert'){
            $cost_code->insert();
            $reqId = $cost_code->id;
         }
        if($reqMode =='edit'){
            $cost_code->update();
         }
         if($reqMode =='baru'){
            $cost_code->setField("PARENT_ID",0);
            $cost_code->insert();
             $reqId = $cost_code->id;
        }
         

        echo $reqId.'-Data berhasil di simpan -';
    }

    function delete(){
          $reqId      = $this->input->get("reqId");
        $this->load->model("CostCode");
        $cost_code = new CostCode();
         $cost_code->setField("COST_CODE_ID",$reqId );
          $cost_code->delete();
          echo 'Data berhasil di hapus';

    }

}
