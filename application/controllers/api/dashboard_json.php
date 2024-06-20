<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class dashboard_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
    }
 
    // show data entitas
    function index_get() {
        $aColumns = array("JAM_MASUK", "JAM_PULANG", "JAM_MASUK_SEHARUSNYA", "JAM_PULANG_SEHARUSNYA", "TERLAMBAT_JAM", "TERLAMBAT_MENIT", "PULANG_CEPAT_JAM", "PULANG_CEPAT_MENIT", "ATASAN", "JABATAN");
        
        $reqToken = $this->input->get('reqToken');
        
        $this->load->model('UserLoginMobile');
        
        //CEK PEGAWAI ID DARI TOKEN
        $user_login_mobile = new UserLoginMobile();
        $reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        
        if($reqPegawaiId <> "0")
        {
            $this->load->model('Pegawai');

            $pegawai = new Pegawai();
            $pegawai->selectByParams(array('A.PEGAWAI_ID' => $reqPegawaiId));
            $pegawai->firstRow();
            $reqCabangId = $pegawai->getField("CABANG_ID");
            $reqJabatan = $pegawai->getField("JABATAN");
            $reqNama = $pegawai->getField("NAMA");

            $pegawai = $pegawai->getCountByParamsPegawaiAtasan(array('PEGAWAI_ID_ATASAN' => $reqPegawaiId));
            if($status_atasan == 0)
                $status_atasan = "0";
            else
                $status_atasan = "1";

            $row['PEGAWAI_ID'] = $reqPegawaiId;
            $row['NAMA'] = $reqNama;
            $row['JABATAN'] = $reqJabatan;
            $row['FOTO'] = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRWUBN41TsV3JOjt7GwMHBHA5GF5w3N753-n05kf4lodByyVNdv';

            $result[] = $row;
            $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => count($aColumns), 'atasan' => $status_atasan, 'result' => $result));
        }
        else
            $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakahir', 'code' => 502));
    }
    
    /*
    function index_get() {
        // $this->response->format = "xml";
        $aColumns = array("PEGAWAI_ID", "NAMA");
        $aColumnsAlias = array("PEGAWAI_ID", "NAMA");

        $reqPegawaiId = $this->input->get('reqPegawaiId');
        $this->load->model('Pegawai');

        $entitas = new Pegawai();
        $result = array();

        if ($reqPegawaiId == '') {
            $entitas->selectByParams(array(),-1,-1,'','');
        } else {
             $entitas->selectByParams(array('pegawai_id'=>$reqPegawaiId),-1,-1,'','');
        }

       //*while($entitas->nextRow()){
            //$result[] = ['entitas_id'=>$entitas->getField('ENTITAS_ID'),'nama'=>$entitas->getField('NAMA'),'keterangan'=>$entitas->getField('KETERANGAN')];
        //}

        while($entitas->nextRow())
            {
                $row = array();
                for ( $i=0 ; $i<count($aColumns) ; $i++ )
                {
                    $row[trim($aColumns[$i])] = $entitas->getField(trim($aColumns[$i]));
                }
                
                $result[] = $row;
        
            }

        $this->response($result, 200);
    }
    */
    // insert new data to entitas
    function index_post() {
        /*$query = "select max(entitas_id) from entitas";
        $entitas_id = $this->db->query($query);

        $data = array(
                    'entitas_id' => $entitas_id->result()[0]->max+1,
                    'keterangan'           => $this->post('keterangan'),
                    'nama'          => $this->post('nama'));
        
        $insert = $this->db->insert('entitas', $data);*/
/*
        $this->load->model('Entitas');
        $this->load->model('EntitasDetail');
        
        $entitas = new Entitas();
        
        // $this->response($this->post('detail'), 200);exit();
                
        $reqKode= $this->post("reqKode");
        $reqNama= $this->post("reqNama");
        $reqKeterangan= $this->post("reqKeterangan");
        $reqAlamat= $this->post("reqAlamat");
        $reqLokasi= $this->post("reqLokasi");
        $reqTelepon= $this->post("reqTelepon");
        $reqFaximile= $this->post("reqFaximile");
        $reqEmail= $this->post("reqEmail");
        $reqStatus_entitas= $this->post("reqStatus_entitas");

    
        $entitas->setField("KODE", $reqKode);
        $entitas->setField("NAMA", $reqNama);
        $entitas->setField("KETERANGAN", $reqKeterangan);
        $entitas->setField("ALAMAT", $reqAlamat);
        $entitas->setField("LOKASI", $reqLokasi);
        $entitas->setField("TELEPON", $reqTelepon);
        $entitas->setField("FAXIMILE", $reqFaximile);
        $entitas->setField("EMAIL", $reqEmail);
        $entitas->setField("STATUS_ENTITAS", $reqStatus_entitas);
       
        $temp =[];
        if ($entitas->insert()) {
            foreach ($this->post('detail') as $key => $value) {
               $entitas_detail = new EntitasDetail();

               $entitas_detail->setField('ENTITAS_ID',$entitas->id);
               $entitas_detail->setField('NAMA',$value['reqNama']);
               $entitas_detail->setField('ALAMAT',$value['reqAlamat']);

               $entitas_detail->insert();
               unset($entitas_detail);
            }
            // $this->response($temp);
            $this->response(array('status' => 'success','id' => $entitas->id, 200));
        } else {
            $this->response(array('status' => 'fail', 502));
        }
        */
    }
 
    // update data entitas
    function index_put() {
        /*
        $entitas_id = $this->put('entitas_id');
        $data = array(
                    'entitas_id'       => $this->put('entitas_id'),
                    'nama'      => $this->put('nama'),
                    'id_jurusan'=> $this->put('id_jurusan'),
                    'alamat'    => $this->put('alamat'));
        $this->db->where('entitas_id', $entitas_id);
        $update = $this->db->update('entitas', $data);
        if ($update) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
        */
    }
 
    // delete entitas
    function index_delete() {
        /*
        $entitas_id = $this->delete('entitas_id');
        $this->db->where('entitas_id', $entitas_id);
        $delete = $this->db->delete('entitas');
        if ($delete) {
            $this->response(array('status' => 'success'), 201);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
        */
    }
 
}