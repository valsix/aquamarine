<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class techical_support_json extends CI_Controller
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

    function has_input_box($id,$id_tree,$nama){
      $this->load->model('Offer');
      $offer = new Offer();
      $statement = "";
      if (!empty($id))
      {
        $statement = " AND A.OFFER_ID =".$id;  
        $offer->selectByParamsMonitoring(array(),-1,-1,$statement);
        $offer->firstRow();
        $reqLandTransport = $offer->getField("LAND_TRANSPORT");
      }
      
      
      $text = '';
      if($id_tree==4){
        $text  ='<input type="text" name="reqLandTransport" id="reqLandTransport" value="'.$reqLandTransport.'">';
        $nama = str_replace('{}', $text, $nama);
      }  
      return $nama;

    }

    function treeGrid(){
        // $arrData = array();

        $this->load->model("TechicalSupport");
        
        $techical_scope = new TechicalSupport();
        $id= $this->input->post("id");
        $reqParam = $this->input->get('reqParam');
          $this->load->model("Offer");
        if(!empty($reqParam)){
            $offer = new Offer();
            $offer->selectByParamsMonitoring(array('A.OFFER_ID'=>$reqParam));
            $offer->firstRow();
            $reqTechicalScope = $offer->getField("TECHICAL_SUPPORT");
            $reqTechicalScope =json_decode($reqTechicalScope ,true);
        } else {
            $reqTechicalScope = array(
                2 => array("INC" => false, "ENC" => "Exclude", "REMARK" => "Provided by Client"),
                3 => array("INC" => false, "ENC" => "Exclude", "REMARK" => "Provided by Client"),
                4 => array("INC" => "Include", "ENC" => false, "REMARK" => ""),
                5 => array("INC" => "Include", "ENC" => false, "REMARK" => ""),
                6 => array("INC" => "Include", "ENC" => false, "REMARK" => ""),
                7 => array("INC" => "Include", "ENC" => false, "REMARK" => ""),
                8 => array("INC" => "Include", "ENC" => false, "REMARK" => "Jamsostek"),
                9 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
                10 => array("INC" => "Include", "ENC" => false, "REMARK" => "At Cost by Client (Rapid Test Only)"),
                11 => array("INC" => false, "ENC" => "Exclude", "REMARK" => "At Cost by Client(If Needed)"),
                14 => array("INC" => "Include", "ENC" => false, "REMARK" => ""),
                15 => array("INC" => "Include", "ENC" => false, "REMARK" => ""),
                16 => array("INC" => "Include", "ENC" => false, "REMARK" => ""),
                17 => array("INC" => "Include", "ENC" => false, "REMARK" => ""),
                18 => array("INC" => "Include", "ENC" => false, "REMARK" => ""),
                19 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
                20 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
                21 => array("INC" => "Include", "ENC" => false, "REMARK" => "")
            );
        }
        $i=0;
        if($id=='0'){

            $techical_scope->selectByParamsMonitoring(array('A.PARENT_ID'=>0),-1,-1);
            while($techical_scope->nextRow())
            {
              $remark = $reqTechicalScope[$techical_scope->getField('ID')]['REMARK'];
              $inc_check = $reqTechicalScope[$techical_scope->getField('ID')]['INC'];
              $enc_check = $reqTechicalScope[$techical_scope->getField('ID')]['ENC'];
              if(!empty($inc_check)){$inc_check='checked';}
              if(!empty($enc_check)){$enc_check='checked';}

                $arrData[$i]['id']=$techical_scope->getField('ID');
                $arrData[$i]['ID']=$techical_scope->getField('ID');
                $arrData[$i]['NAMA']=$this->has_input_box($reqParam,$techical_scope->getField('ID'),$techical_scope->getField('NAMA'));
                $arrData[$i]['INC']='<input type="checkbox" '.$inc_check.' name="reqTechicalSupportInc'.$techical_scope->getField("ID").'" value="Include">';
                $arrData[$i]['ENC']='<input type="checkbox" '.$enc_check.' name="reqTechicalSupportEnc'.$techical_scope->getField("ID").'" value="Exclude">';
                $arrData[$i]['REMARK']='<input type="text" name="reqTechicalSupportRemark[]" value="'.$remark.'"><input type="hidden" name="reqTechicalSupportId[]" value="'.$techical_scope->getField('ID').'">'; 
                $arrData[$i]['state'] =$this->tree_child_json($techical_scope->getField("ID"),$statement) ? 'closed' : 'open';
                $i++;

            }   
         
            $result["rows"] = $arrData; 
        }else{

             $techical_scope = new TechicalSupport();
             $techical_scope->selectByParamsMonitoring(array('A.PARENT_ID'=>$id), -1, -1);
              while($techical_scope->nextRow())
            {

              $remark = $reqTechicalScope[$techical_scope->getField('ID')]['REMARK'];
              $inc_check = $reqTechicalScope[$techical_scope->getField('ID')]['INC'];
              $enc_check = $reqTechicalScope[$techical_scope->getField('ID')]['ENC'];
              if(!empty($inc_check)){$inc_check='checked';}
              if(!empty($enc_check)){$enc_check='checked';}

                     $result[$i]['id']=$techical_scope->getField('ID');
                    $result[$i]['ID']=$techical_scope->getField('ID');
                    $result[$i]['NAMA']=$this->has_input_box($reqParam,$techical_scope->getField('ID'),$techical_scope->getField('NAMA'));
                    $result[$i]['INC']='<input type="checkbox" '.$inc_check.' name="reqTechicalSupportInc'.$techical_scope->getField("ID").'" value="Include">';
                    $result[$i]['ENC']='<input type="checkbox" '.$enc_check.' name="reqTechicalSupportEnc'.$techical_scope->getField("ID").'" value="Exclude">';
                    $result[$i]['REMARK']='<input type="text" name="reqTechicalSupportRemark[]" value="'.$remark.'"><input type="hidden" name="reqTechicalSupportId[]" value="'.$techical_scope->getField('ID').'">'; 
                     $result[$i]['state'] =$this->tree_child_json($techical_scope->getField("ID"),$statement) ? 'closed' : 'open'
                    ;
                    $i++;
            }
        }
        echo json_encode($result);  

    }

    function tree_child_json($id){
        $this->load->model("TechicalSupport");
        $techical_scope = new TechicalSupport();
        $techical_scope->selectByParamsMonitoring(array('A.PARENT_ID'=>$id), -1, -1, $statement);

        $techical_scope->firstRow();
        $tempId= $techical_scope->getField("ID");
        if($tempId == "")
            return false;
        else
            return true;
    
    }


}
