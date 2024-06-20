<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class commercial_support_json extends CI_Controller
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

    function has_input_box($id,$id_tree){
      $this->load->model('Offer');
      $offer = new Offer();
      $statement = "";
      if (!empty($id))
      {
        $statement = " AND A.OFFER_ID =".$id;  
        $offer->selectByParamsMonitoring(array(),-1,-1,$statement);
        $offer->firstRow();
        $reqStandBy = $offer->getField("STAND_BY_RATE");
        $reqSoDays = $offer->getField("SO_DAYS");
        $reqLumpsumDays = $offer->getField("LUMPSUM_DAYS");
      }
      $text = '';
      if($id_tree==9){
        $text  ='<input type="text" name="reqStandBy" id="reqStandBy" value="'.$reqStandBy.'">';
      }  
      if($id_tree==6){
        $text  ='<input type="text" name="reqSoDays" id="reqSoDays" value="'.$reqSoDays.'">';
      }   
      if($id_tree==10){
        $text  ='<input type="text" name="reqLumpsumDays" id="reqLumpsumDays" value="'.$reqLumpsumDays.'">';
      }  
      return $text;

    }

    function treeGrid(){
        // $arrData = array();

        $this->load->model("CommercialSupport");
        
        $techical_scope = new CommercialSupport();
        $id= $this->input->post("id");
        $reqParam = $this->input->get('reqParam');
        if(!empty($reqParam)){
          $this->load->model("Offer");
          $offer = new Offer();
          $offer->selectByParamsMonitoring(array('A.OFFER_ID'=>$reqParam));
          $offer->firstRow();
          $reqTechicalScope = $offer->getField("COMMERCIAL_SUPPORT");
          $reqTechicalScope =json_decode($reqTechicalScope ,true);
        } else {
          $reqTechicalScope = array(
            1 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
            2 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
            3 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
            4 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
            5 => array("INC" => "Include", "ENC" => false, "REMARK" => ""),
            6 => array("INC" => "Include", "ENC" => false, "REMARK" => ""),
            7 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
            8 => array("INC" => "Include", "ENC" => false, "REMARK" => ""),
            9 => array("INC" => false, "ENC" => "Exclude", "REMARK" => "")
          );
        }


        $i=0;
        if($id=='0'){

            $techical_scope->selectByParamsMonitoring(array('A.PARENT_ID'=>0),-1,-1);
            // echo $techical_scope->query;exit;
            while($techical_scope->nextRow())
            {
                $remark = $reqTechicalScope[$techical_scope->getField('ID')]['REMARK'];
                $inc_check = $reqTechicalScope[$techical_scope->getField('ID')]['INC'];
                $enc_check = $reqTechicalScope[$techical_scope->getField('ID')]['ENC'];
                if(!empty($inc_check)){$inc_check='checked';}
                if(!empty($enc_check)){$enc_check='checked';}

                $arrData[$i]['id']=$techical_scope->getField('ID');
                $arrData[$i]['ID']=$techical_scope->getField('ID');
                // $arrData[$i]['NAMA']=$techical_scope->getField('NAMA').'  '.$this->has_input_box($reqParam,$techical_scope->getField('ID'));
                $checkname = $arrData[$i]['NAMA']=$techical_scope->getField('NAMA');
                // echo  $check;exit;
                $replace = str_replace('{}', $this->has_input_box($reqParam,$techical_scope->getField('ID')), $checkname);
                $arrData[$i]['NAMA'] = $replace;
                 $arrData[$i]['INC']='<input type="checkbox" '.$inc_check.' name="reqCommercialSupportInc'.$techical_scope->getField("ID").'" value="Include">';
                  $arrData[$i]['ENC']='<input type="checkbox" '.$enc_check.' name="reqCommercialSupportEnc'.$techical_scope->getField("ID").'" value="Exclude">';
                  $arrData[$i]['REMARK']='<input type="text" name="reqCommercialSupportRemark[]" value="'.$remark.'"><input type="hidden" name="reqCommercialSupportId[]" value="'.$techical_scope->getField('ID').'">';  
                $arrData[$i]['state'] =$this->tree_child_json($techical_scope->getField("ID"),$statement) ? 'closed' : 'open';
                $i++;

            } 
            // exit;  
         
            $result["rows"] = $arrData; 
        }else{

             $techical_scope = new CommercialSupport();
             $techical_scope->selectByParamsMonitoring(array('A.PARENT_ID'=>$id), -1, -1);

              while($techical_scope->nextRow())
            {
                $remark = $reqTechicalScope[$techical_scope->getField('ID')]['REMARK'];
                $inc_check = $reqTechicalScope[$techical_scope->getField('ID')]['INC'];
                $enc_check = $reqTechicalScope[$techical_scope->getField('ID')]['ENC'];
                if(!empty($inc_check)){$inc_check='checked';}
                if(!empty($enc_check)){$enc_check='checked';}

                $checkname = $techical_scope->getField('NAMA');
                $replace = str_replace('{}', $this->has_input_box($reqParam,$techical_scope->getField('ID')), $checkname);

                $result[$i]['id']=$techical_scope->getField('ID');
                $result[$i]['ID']=$techical_scope->getField('ID');
                $result[$i]['NAMA']=$replace;
                $result[$i]['INC']='<input type="checkbox" '.$inc_check.' name="reqCommercialSupportInc'.$techical_scope->getField("ID").'" value="Include">';
                $result[$i]['ENC']='<input type="checkbox" '.$enc_check.' name="reqCommercialSupportEnc'.$techical_scope->getField("ID").'" value="Exclude">';
                $result[$i]['REMARK']='<input type="text" name="reqCommercialSupportRemark[]" value="'.$remark.'"><input type="hidden" name="reqCommercialSupportId[]" value="'.$techical_scope->getField('ID').'">';  
                $result[$i]['state'] =$this->tree_child_json($techical_scope->getField("ID"),$statement) ? 'closed' : 'open'
                ;
                $i++;
            }
        }
        echo json_encode($result);  

    }

    function tree_child_json($id){
        $this->load->model("CommercialSupport");
        $techical_scope = new CommercialSupport();
        $techical_scope->selectByParamsMonitoring(array('A.PARENT_ID'=>$id), -1, -1, $statement);
         // echo $techical_scope->query;exit;

        $techical_scope->firstRow();
        $tempId= $techical_scope->getField("ID");
        if($tempId == "")
            return false;
        else
            return true;
    
    }


}
