<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class tree_json extends CI_Controller
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

    function has_input_box($id,$id_tree,$reqRevId){
      $this->load->model('Offer');
      $offer = new Offer();
      $statement = "";
      if (!empty($id) && empty($reqRevId))
      {
        $statement = " AND A.OFFER_ID =".$id;  
          $offer->selectByParamsMonitoring(array(),-1,-1,$statement);
      }else if(!empty($reqRevId)){
         $offer->selectByParamsRevisi(array("OFFER_ID" => $id, "OFFER_REVISI_ID" => $reqRevId));
      }
      
    
      $offer->firstRow();
      $reqClassAddend = $offer->getField("CLASS_ADDEND");
      $reqClassAddend2 = $offer->getField("CLASS_ADDEND2");
      $text = '';
      if($id_tree==21){
        $text  ='<input type="text" name="reqClassAddend" id="reqClassAddend" value="'.$reqClassAddend.'">';
      } 
      if($id_tree==22){
        $text  ='<input type="text" name="reqClassAddend2" id="reqClassAddend2" value="'.$reqClassAddend2.'">';
      }  
      return $text;

    }

    function treeGrid(){
        // $arrData = array();

        $this->load->model("TechicalScope");
        $reqParam = $this->input->get('reqParam');
        $reqRevId = $this->input->get('reqRevId');
        $techical_scope = new TechicalScope();
        $id= $this->input->post("id");
        if(!empty($reqParam) && empty( $reqRevId )){
          $this->load->model("Offer");
          $offer = new Offer();
          $offer->selectByParamsMonitoring(array('A.OFFER_ID'=>$reqParam));
          $offer->firstRow();
          $reqTechicalScope = $offer->getField("TECHICAL_SCOPE");

          $reqTechicalScope =json_decode($reqTechicalScope ,true);
        }else if(!empty($reqRevId)){
         $this->load->model("Offer");
         $offer = new Offer();
         $offer->selectByParamsRevisi(array("OFFER_ID" => $reqParam, "OFFER_REVISI_ID" => $reqRevId));
         $offer->firstRow();
         $reqTechicalScope = $offer->getField("TECHICAL_SCOPE");
          $reqTechicalScope =json_decode($reqTechicalScope ,true);
        }


         else {
          $reqTechicalScope = array(
            2 => array("INC" => "Include", "ENC" => false, "REMARK" => "Propeller And Stern Tube Only "),
            3 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
            4 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
            5 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
            6 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
            7 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
            8 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
            9 => array("INC" => "Include", "ENC" => false, "REMARK" => ""),
            10 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
            20 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
           
            21 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
             22 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
            25 => array("INC" => "Include", "ENC" => false, "REMARK" => ""),
            29 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
            30 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
            31 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
          );
        }

        // print_r(json_encode($reqTechicalScope));exit;

        $i=0;

        // echo 'Arik';exit;
        if($id=='0'){

            $techical_scope->selectByParamsMonitoring(array('A.PARENT_ID'=>0),-1,-1,'',' ORDER BY A.URUTAN ASC');
          // $techical_scope->selectByParamsMonitoring(array('A.PARENT_ID'=>0),-1,-1);
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
                $arrData[$i]['NAMA']=$techical_scope->getField('NAMA').'  '.$this->has_input_box($reqParam,$techical_scope->getField('ID'),$reqRevId);


                 $arrData[$i]['INC']='<input type="checkbox" '.$inc_check.' name="reqTechicalScopeInc'.$techical_scope->getField('ID').'" value="Include">';
                  $arrData[$i]['ENC']='<input type="checkbox" '.$enc_check.' name="reqTechicalScopeEnc'.$techical_scope->getField('ID').'" value="Exclude">';
                   $arrData[$i]['REMARK']='<input type="text" name="reqTechicalScopeRemark[]" value="'.$remark.'"><input type="hidden" name="reqTechicalScopeId[]" value="'.$techical_scope->getField('ID').'">';   
                $arrData[$i]['state'] =$this->tree_child_json($techical_scope->getField("ID"),$statement) ? 'closed' : 'open';
                $i++;

            }   
         
            $result["rows"] = $arrData; 
        }else{

             $techical_scope = new TechicalScope();
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
              $result[$i]['NAMA']=$techical_scope->getField('NAMA').'  '.$this->has_input_box($reqParam,$techical_scope->getField('ID'));
              $result[$i]['INC']='<input type="checkbox" '.$inc_check.' name="reqTechicalScopeInc'.$techical_scope->getField('ID').'" value="Include">';
              $result[$i]['ENC']='<input type="checkbox" '.$enc_check.' name="reqTechicalScopeEnc'.$techical_scope->getField('ID').'" value="Exclude">';
              $result[$i]['REMARK']='<input type="text" name="reqTechicalScopeRemark[]" value="'.$remark.'"><input type="hidden" name="reqTechicalScopeId[]" value="'.$techical_scope->getField('ID').'">'; 
              $result[$i]['state'] =$this->tree_child_json($techical_scope->getField("ID"),$statement) ? 'closed' : 'open'
              ;
                    $i++;
            }
        }
        echo json_encode($result);  

    }

    function tree_child_json($id){
        $this->load->model("TechicalScope");
        $techical_scope = new TechicalScope();
        $techical_scope->selectByParamsMonitoring(array('A.PARENT_ID'=>$id), -1, -1, $statement);

        $techical_scope->firstRow();
        $tempId= $techical_scope->getField("ID");
        if($tempId == "")
            return false;
        else
            return true;
    
    }

    function hak_akses()
    {
      $this->load->model("Users_management");
      $usersManagement = new Users_management();
      $reqId = $this->input->get("reqId");
      if(empty($reqId))
        $reqId = 0;

      $usersManagement->selectByParams(array("USERID" => $reqId));
      $usersManagement->firstRow();

      $arrMenu = array(
         array(
          "ID" => 7,
          "MENU" => "MENUWAREHOUSE", 
          "NAME" => "Warehouse"
        ),
        array(
          "ID" => 1,
          "MENU" => "MENUMARKETING", 
          "NAME" => "Marketing"
        ),
        array(
          "ID" => 2,
          "MENU" => "MENUFINANCE", 
          "NAME" => "Finance",
          "children" => array(
             array(
              "ID" => 22,
              "MENU" => "MENUINVPROJECT", 
              "NAME" => "Invoice Project"
             )
          )
        ),
        array(
          "ID" => 3,
          "MENU" => "MENUPRODUCTION", 
          "NAME" => "Production",
          "children" => array(
            array(
              "ID" => 31,
              "MENU" => "MENUEPL", 
              "NAME" => "Equipment Project List"
            ),
            array(
              "ID" => 32,
              "MENU" => "MENUUWILD", 
              "NAME" => "Form Underwater/UWILD"
            ),
            array(
              "ID" => 33,
              "MENU" => "MENUWP", 
              "NAME" => "Working Procedures"
            ),
            array(
              "ID" => 34,
              "MENU" => "MENUPL", 
              "NAME" => "Personal List"
            ),
            array(
              "ID" => 35,
              "MENU" => "MENUEL", 
              "NAME" => "Equipment List"
            ),
            array(
              "ID" => 36,
              "MENU" => "MENUPMS", 
              "NAME" => "PMS"
            ),
            array(
              "ID" => 37,
              "MENU" => "MENURS", 
              "NAME" => "Report Survey"
            ),
            array(
              "ID" => 38,
              "MENU" => "MENUSTD", 
              "NAME" => "Standarisasi"
            ),
            array(
              "ID" => 39,
              "MENU" => "MENUSTEN", 
              "NAME" => "Tender"
            ),
            array(
              "ID" => 40,
              "MENU" => "MENUSWD", 
              "NAME" => "Weekly Meeting"
            )
          )
        ),
        array(
          "ID" => 4,
          "MENU" => "MENUDOCUMENT", 
          "NAME" => "Document"
        ),
        array(
          "ID" => 5,
          "MENU" => "MENUSEARCH", 
          "NAME" => "Research & Develop"
        ),
        array(
          "ID" => 6,
          "MENU" => "MENUOTHERS", 
          "NAME" => "Other"
        )
      );

      for ($i=0; $i < count($arrMenu); $i++) { 
        $check = $usersManagement->getField($arrMenu[$i]["MENU"]) == "1" ? "checked" : "";
        $arrMenu[$i]["CHECK"] = '<input type="checkbox" '.$check.' id="'.$arrMenu[$i]["MENU"].'" name="'.$arrMenu[$i]["MENU"].'">';
        for ($j=0; $j < count($arrMenu[$i]["children"]); $j++) { 
          $check = $usersManagement->getField($arrMenu[$i]["children"][$j]["MENU"]) == "1" ? "checked" : "";
          $arrMenu[$i]["children"][$j]["CHECK"] = '<input type="checkbox" '.$check.' id="'.$arrMenu[$i]["children"][$j]["MENU"].'" name="'.$arrMenu[$i]["children"][$j]["MENU"].'">';
        }
      }

      echo json_encode($arrMenu);

    }


}
