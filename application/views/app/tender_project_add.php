<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("TenderTypeUpload");
$this->load->model("Tender");
$tender = new Tender();

$reqId = $this->input->get("reqId");

if($reqId == ""){
    $reqMode = "insert";
}else{

    $tender_type_upload = new TenderTypeUpload();
    $jumlah = $tender_type_upload->getCountByParamsMonitoring(array("TENDER_ID" => $reqId, "TYPE" => "DocTender"));

    if($jumlah == 0){
        $query = "
            INSERT INTO TENDER_TYPE_UPLOAD (TENDER_TYPE_UPLOAD_ID, TYPE, NAME, DESCRIPTION, TENDER_ID) 
            SELECT (SELECT MAX(TENDER_TYPE_UPLOAD_ID) FROM TENDER_TYPE_UPLOAD)+ROW_NUMBER() OVER(), 
                TYPE, NAME, DESCRIPTION, '".$reqId."' TENDER_ID
            FROM TENDER_TYPE_UPLOAD WHERE TYPE = 'DocTender' AND TENDER_ID = 0
        ";
        $this->db->query($query);
    }

    $jumlah = $tender_type_upload->getCountByParamsMonitoring(array("TENDER_ID" => $reqId, "TYPE" => "Persiapan"));

    if($jumlah == 0){
        $query = "
            INSERT INTO TENDER_TYPE_UPLOAD (TENDER_TYPE_UPLOAD_ID, TYPE, NAME, DESCRIPTION, TENDER_ID) 
            SELECT (SELECT MAX(TENDER_TYPE_UPLOAD_ID) FROM TENDER_TYPE_UPLOAD)+ROW_NUMBER() OVER(), 
                TYPE, NAME, DESCRIPTION, '".$reqId."' TENDER_ID
            FROM TENDER_TYPE_UPLOAD WHERE TYPE = 'Persiapan' AND TENDER_ID = 0
        ";
        $this->db->query($query);
    }

    $jumlah = $tender_type_upload->getCountByParamsMonitoring(array("TENDER_ID" => $reqId, "TYPE" => "Pelaksanaan"));

    if($jumlah == 0){
        $query = "
            INSERT INTO TENDER_TYPE_UPLOAD (TENDER_TYPE_UPLOAD_ID, TYPE, NAME, DESCRIPTION, TENDER_ID) 
            SELECT (SELECT MAX(TENDER_TYPE_UPLOAD_ID) FROM TENDER_TYPE_UPLOAD)+ROW_NUMBER() OVER(), 
                TYPE, NAME, DESCRIPTION, '".$reqId."' TENDER_ID
            FROM TENDER_TYPE_UPLOAD WHERE TYPE = 'Pelaksanaan' AND TENDER_ID = 0
        ";
        $this->db->query($query);
    }

    $jumlah = $tender_type_upload->getCountByParamsMonitoring(array("TENDER_ID" => $reqId, "TYPE" => "Penyelesaian"));

    if($jumlah == 0){
        $query = "
            INSERT INTO TENDER_TYPE_UPLOAD (TENDER_TYPE_UPLOAD_ID, TYPE, NAME, DESCRIPTION, TENDER_ID) 
            SELECT (SELECT MAX(TENDER_TYPE_UPLOAD_ID) FROM TENDER_TYPE_UPLOAD)+ROW_NUMBER() OVER(), 
                TYPE, NAME, DESCRIPTION, '".$reqId."' TENDER_ID
            FROM TENDER_TYPE_UPLOAD WHERE TYPE = 'Penyelesaian' AND TENDER_ID = 0
        ";
        $this->db->query($query);
    }


    $reqMode = "ubah";
    $statement= " AND A.TENDER_ID = ".$reqId;
    $tender->selectByParams(array(), -1,-1, $statement);
    $tender->firstRow();

    $reqCompanyId= $tender->getField("COMPANY_ID");
    $reqCompanyName= $tender->getField("COMPANY_NAME");
    $reqProjectName= $tender->getField("PROJECT_NAME");
    $reqProjectNo= $tender->getField("PROJECT_NO");
    $reqIssuedDate= $tender->getField("ISSUED_DATE");
    $reqRegisterDate= $tender->getField("REGISTER_DATE");
    $reqPQDate= $tender->getField("PQ_DATE");
    $reqPrebidDate= $tender->getField("PREBID_DATE");
    $reqSubmissionDate= $tender->getField("SUBMISSION_DATE");
    $reqOpening1Date= $tender->getField("OPENING1_DATE");
    $reqOpening2Date= $tender->getField("OPENING2_DATE");
    $reqAnnouncement= $tender->getField("ANNOUNCEMENT");
    $reqLoa= $tender->getField("LOA");
    $reqRemark= $tender->getField("REMARK");
    $reqDocTenderPathJson= $tender->getField("DOC_TENDER_PATH");
    $reqPersiapanPathJson= $tender->getField("PERSIAPAN_PATH");
    $reqPelaksanaanPathJson= $tender->getField("PELAKSANAAN_PATH");
    $reqPenyelesaianPathJson= $tender->getField("BA_PENY_PATH");
    // print_r($reqPersiapanPath); exit();
}

?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />

<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/tender_project"> File Project</a> &rsaquo; Form File Project
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>
        <?php
        if($reqId != "")
        {
        ?>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="master_type_tender('Penyelesaian', '<?=$reqId?>')"><i class="fa fa-fw fa-gavel fa lg"> </i><span>  Master Type Penyelesaian</span> </a>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="master_type_tender('Pelaksanaan', '<?=$reqId?>')"><i class="fa fa-fw fa-gavel fa lg"> </i><span>  Master Type Pelaksanaan</span> </a>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="master_type_tender('Persiapan', '<?=$reqId?>')"><i class="fa fa-fw fa-gavel fa lg"> </i><span>  Master Type Persiapan</span> </a>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="master_type_tender('DocTender', '<?=$reqId?>')"><i class="fa fa-fw fa-gavel fa lg"> </i><span>  Master Type Doc Tender</span> </a>
        <?php
        }
        ?>

    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Entry File Project
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>

                        </h3>
                        <br>
                    </div>
                     <div class="form-group">
                        <label for="reqCompanyName" class="control-label col-md-2">Project No</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                  <!--   <input type="text" id="reqProjectNo" class="easyui-validatebox textbox form-control"  name="reqProjectNo" value="<?= $reqProjectNo ?>" style=" width:100%"  /> -->

                                   <input class="easyui-combobox form-control" style="width:100%" name="reqProjectNo" id="reqProjectNo" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'web/master_tender_json/combo_master',
                       onSelect: function(param){
              rubah_keterangan(param.id);
        }," value="<?= $reqProjectNo ?>"  />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqCompanyName" class="control-label col-md-2">Project Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea cols="3" rows="2" id="reqProjectName" name="reqProjectName" class="form-control"><?=$reqProjectName?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Company Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <div class="input-group">
                                        <span class="input-group-addon" onclick="openCompany()"><i class="fa fa-address-book-o fa-lg"></i> </span>
                                        <span class="input-group-addon" onclick="clearCompany()" ><i class="fa fa-times fa-lg"></i> </span>
                                        <input type="text" class="form-control"  id="reqCompanyName" name="reqCompanyName" value="<?= $reqCompanyName ?>" 
                                        style=" width:150%"
                                        >

                                    </div>

                                  
                                    <input type="hidden" class="easyui-validatebox textbox form-control" name="reqCompanyId" id="reqCompanyId" value="<?= $reqCompanyId ?>" style=" width:100%" />

                                </div>
                            </div>
                        </div>
                    </div>

                   
                    <div class="form-group">
                        <label for="reqPhone" class="control-label col-md-2">Status</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqAnnouncement"  id="reqAnnouncement" data-options="width:'150',editable:false, valueField:'id',textField:'text',url:'combo_json/comboAnnouncementProject'" value="<?= $reqAnnouncement ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                if($reqId != "")
                {
                ?>   
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Entry File Doc Tender</h3>
                    </div>

                    <div class="form-group" style="padding: 10px">
                        <?
                        $tender_type_upload = new TenderTypeUpload();
                        $tender_type_upload->selectByParamsMonitoring(array("TYPE" => "DocTender", "TENDER_ID" => $reqId));

                        while ($tender_type_upload->nextRow()) {

                            $reqTypeId = $tender_type_upload->getField("TENDER_TYPE_UPLOAD_ID");
                            $reqJenis           = $tender_type_upload->getField("NAME");
                            $reqDescription     = $tender_type_upload->getField("DESCRIPTION");
                        ?>
                        <div class="form-group">
                            <label for="reqCompanyName" class="control-label col-md-2"><?=$reqJenis?></label>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <div class="col-md-12">

                                        <table style="width: 100%" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th width="90%"> File Name <a onclick="tambahPenyebab('', 'DocTender', '<?=$reqTypeId?>', '<?=$reqJenis?>')" id="btnPenyebab" class="btn btn-info"><i class="fa fa-plus-square"></i></a></th>
                                                    <th width="10%"> Action </th>
                                                </tr>
                                            </thead>
                                            <tbody id="tambahAttacmentDocTender<?=$reqTypeId?>">
                                                <?
                                                $reqDocTenderPath = json_decode($reqDocTenderPathJson, true);
                                                for ($i = 0; $i < count($reqDocTenderPath); $i++) 
                                                {
                                                    if($reqDocTenderPath[$i]["type"] == $reqJenis && $reqDocTenderPath[$i]["file"] != "")
                                                    {
                                                        $reqTypeDocTender = $reqDocTenderPath[$i]["type"];
                                                        $reqFileDocTender = $reqDocTenderPath[$i]["file"];
                                                        $ext = substr($reqFileDocTender, -3);
                                                ?>
                                                        <tr>
                                                            <input type="hidden" style="width:100%" name="reqTypeDocTender[]" value="<?= $reqTypeDocTender ?>" />
                                                            <td>
                                                                <input type="file" onchange="getFileName(this, '<?=($i+1)?>', 'DocTender', '<?=$reqTypeId?>', '<?=$reqJenis?>')" name="reqLinkFileDocTender[]" multiple class="form-control" style="width: 90%">
                                                                <input type="hidden" name="reqLinkFileDocTenderTemp[]" value="<?= $reqFileDocTender ?>">
                                                                <?if ($ext !=='pdf')
                                                                {
                                                                ?>
                                                                  <a href="uploads/tender_doc_tender/<?= $reqId ?>/<?= $reqFileDocTender ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFileDocTender<?=$reqTypeId?><?=($i+1)?>"> <?= $reqFileDocTender ?> </span>
                                                                <?
                                                                }
                                                                else
                                                                {
                                                                ?>
                                                                  <a onclick="openAdd(`uploads/tender_doc_tender/<?= $reqId ?>/<?= $reqFileDocTender ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFileDocTender<?=$reqTypeId?><?=($i+1)?>"> <?= $reqFileDocTender ?> </span>
                                                                <?
                                                                }
                                                                ?>
                                                            </td>
                                                            <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
                                                        </tr>
                                                <?
                                                    }
                                                }
                                                ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?
                        }
                        ?>
                    </div>
                    
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Entry File Persiapan</h3>
                    </div>

                    <div class="form-group" style="padding: 10px">
                        <?
                        $tender_type_upload = new TenderTypeUpload();
                        $tender_type_upload->selectByParamsMonitoring(array("TYPE" => "Persiapan", "TENDER_ID" => $reqId));

                        while ($tender_type_upload->nextRow()) {

                            $reqTypeId = $tender_type_upload->getField("TENDER_TYPE_UPLOAD_ID");
                            $reqJenis           = $tender_type_upload->getField("NAME");
                            $reqDescription     = $tender_type_upload->getField("DESCRIPTION");
                        ?>
                        <div class="form-group">
                            <label for="reqCompanyName" class="control-label col-md-2"><?=$reqJenis?></label>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <div class="col-md-12">

                                        <table style="width: 100%" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th width="90%"> File Name <a onclick="tambahPenyebab('', 'Persiapan', '<?=$reqTypeId?>', '<?=$reqJenis?>')" id="btnPenyebab" class="btn btn-info"><i class="fa fa-plus-square"></i></a></th>
                                                    <th width="10%"> Action </th>
                                                </tr>
                                            </thead>
                                            <tbody id="tambahAttacmentPersiapan<?=$reqTypeId?>">
                                                <?
                                                $reqPersiapanPath = json_decode($reqPersiapanPathJson, true);
                                                for ($i = 0; $i < count($reqPersiapanPath); $i++) 
                                                {
                                                    if($reqPersiapanPath[$i]["type"] == $reqJenis && $reqPersiapanPath[$i]["file"] != "")
                                                    {
                                                        $reqTypePersiapan = $reqPersiapanPath[$i]["type"];
                                                        $reqFilePersiapan = $reqPersiapanPath[$i]["file"];
                                                        $ext = substr($reqFilePersiapan, -3);
                                                ?>
                                                        <tr>
                                                            <input type="hidden" style="width:100%" name="reqTypePersiapan[]" value="<?= $reqTypePersiapan ?>" />
                                                            <td>
                                                                <input type="file" onchange="getFileName(this, '<?=($i+1)?>', 'Persiapan', '<?=$reqTypeId?>', '<?=$reqJenis?>')" name="reqLinkFilePersiapan[]" multiple class="form-control" style="width: 90%">
                                                                <input type="hidden" name="reqLinkFilePersiapanTemp[]" value="<?= $reqFilePersiapan ?>">
                                                                <?if ($ext !=='pdf')
                                                                {
                                                                ?>
                                                                  <a href="uploads/tender_persiapan/<?= $reqId ?>/<?= $reqFilePersiapan ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFilePersiapan<?=$reqTypeId?><?=($i+1)?>"> <?= $reqFilePersiapan ?> </span>
                                                                <?
                                                                }
                                                                else
                                                                {
                                                                ?>
                                                                  <a onclick="openAdd(`uploads/tender_persiapan/<?= $reqId ?>/<?= $reqFilePersiapan ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFilePersiapan<?=$reqTypeId?><?=($i+1)?>"> <?= $reqFilePersiapan ?> </span>
                                                                <?
                                                                }
                                                                ?>
                                                            </td>
                                                            <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
                                                        </tr>
                                                <?
                                                    }
                                                }
                                                ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?
                        }
                        ?>
                    </div>
                    
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Entry File Pelaksanaan</h3>
                    </div>

                    <div class="form-group" style="padding: 10px">
                        <?
                        $tender_type_upload = new TenderTypeUpload();
                        $tender_type_upload->selectByParamsMonitoring(array("TYPE" => "Pelaksanaan", "TENDER_ID" => $reqId));
                        while ($tender_type_upload->nextRow()) {

                            $reqTypeId = $tender_type_upload->getField("TENDER_TYPE_UPLOAD_ID");
                            $reqJenis           = $tender_type_upload->getField("NAME");
                            $reqDescription     = $tender_type_upload->getField("DESCRIPTION");
                        ?>
                        <div class="form-group">
                            <label for="reqCompanyName" class="control-label col-md-2"><?=$reqJenis?></label>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <div class="col-md-12">

                                        <table style="width: 100%" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th width="90%"> File Name  <a onclick="tambahPenyebab('', 'Pelaksanaan', '<?=$reqTypeId?>', '<?=$reqJenis?>')" id="btnPenyebab" class="btn btn-info"><i class="fa fa-plus-square"></i></a></th>
                                                    <th width="10%"> Action </th>
                                                </tr>
                                            </thead>
                                            <tbody id="tambahAttacmentPelaksanaan<?=$reqTypeId?>">
                                                <?
                                                $reqPelaksanaanPath = json_decode($reqPelaksanaanPathJson, true);

                                               // echo  count($reqPelaksanaanPath);
                                                for ($i = 0; $i < count($reqPelaksanaanPath); $i++) 
                                                {
                                                    // echo $reqPelaksanaanPath[$i]["type"].'<br>';
                                                    if($reqPelaksanaanPath[$i]["type"] == $reqJenis && $reqPelaksanaanPath[$i]["file"] != "")
                                                    {
                                                        $reqTypePelaksanaan = $reqPelaksanaanPath[$i]["type"];
                                                        $reqFilePelaksanaan = $reqPelaksanaanPath[$i]["file"];
                                                        $ext = substr($reqFilePelaksanaan, -3);
                                                ?>
                                                        <tr>
                                                            <input type="hidden" style="width:100%" name="reqTypePelaksanaan[]"  value="<?= $reqTypePelaksanaan ?>" />
                                                            <td>
                                                                <input type="file" onchange="getFileName(this, '<?=($i+1)?>', 'Pelaksanaan', '<?=$reqTypeId?>', '<?=$reqJenis?>')" name="reqLinkFilePelaksanaan[]" multiple class="form-control" style="width: 90%">
                                                                <input type="hidden" name="reqLinkFilePelaksanaanTemp[]" value="<?= $reqFilePelaksanaan ?>">
                                                                <?if ($ext !=='pdf')
                                                                {
                                                                ?>
                                                                  <a href="uploads/tender_pelaksanaan/<?= $reqId ?>/<?= $reqFilePelaksanaan ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFilePelaksanaan<?=$reqTypeId?><?=($i+1)?>"> <?= $reqFilePelaksanaan ?> </span>
                                                                <?
                                                                }
                                                                else
                                                                {
                                                                ?>
                                                                  <a onclick="openAdd(`uploads/tender_pelaksanaan/<?= $reqId ?>/<?= $reqFilePelaksanaan ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFilePelaksanaan<?=$reqTypeId?><?=($i+1)?>"> <?= $reqFilePelaksanaan ?> </span>
                                                                <?
                                                                }
                                                                ?>
                                                            </td>
                                                            <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
                                                        </tr>
                                                <?
                                                    }
                                                }
                                                ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?
                        }
                        ?>
                    </div>
                    
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Entry File Penyelesaian</h3>
                    </div>

                    <div class="form-group" style="padding: 10px">
                        <?
                        $tender_type_upload = new TenderTypeUpload();
                        $tender_type_upload->selectByParamsMonitoring(array("TYPE" => "Penyelesaian", "TENDER_ID" => $reqId));
                        while ($tender_type_upload->nextRow()) {

                            $reqTypeId = $tender_type_upload->getField("TENDER_TYPE_UPLOAD_ID");
                            $reqJenis           = $tender_type_upload->getField("NAME");
                            $reqDescription     = $tender_type_upload->getField("DESCRIPTION");
                        ?>
                        <div class="form-group">
                            <label for="reqCompanyName" class="control-label col-md-2"><?=$reqJenis?></label>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <div class="col-md-12">

                                        <table style="width: 100%" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th width="90%"> File Name <a onclick="tambahPenyebab('', 'Penyelesaian', '<?=$reqTypeId?>', '<?=$reqJenis?>')" id="btnPenyebab" class="btn btn-info"><i class="fa fa-plus-square"></i></a></th>
                                                    <th width="10%"> Action </th>
                                                </tr>
                                            </thead>
                                            <tbody id="tambahAttacmentPenyelesaian<?=$reqTypeId?>">
                                                <?
                                                $reqPenyelesaianPath = json_decode($reqPenyelesaianPathJson, true);
                                                for ($i = 0; $i < count($reqPenyelesaianPath); $i++) 
                                                {
                                                    if($reqPenyelesaianPath[$i]["type"] == $reqJenis && $reqPenyelesaianPath[$i]["file"] != "")
                                                    {
                                                        $reqTypePenyelesaian = $reqPenyelesaianPath[$i]["type"];
                                                        $reqFilePenyelesaian = $reqPenyelesaianPath[$i]["file"];
                                                        $ext = substr($reqFilePenyelesaian, -3);
                                                ?>
                                                        <tr>
                                                            <input type="hidden" style="width:100%" name="reqTypePenyelesaian[]" value="<?= $reqTypePenyelesaian ?>" />
                                                            <td>
                                                                <input type="file" onchange="getFileName(this, '<?=($i+1)?>', 'Penyelesaian', '<?=$reqTypeId?>', '<?=$reqJenis?>')" name="reqLinkFilePenyelesaian[]" multiple class="form-control" style="width: 90%">
                                                                <input type="hidden" name="reqLinkFilePenyelesaianTemp[]" value="<?= $reqFilePenyelesaian ?>">
                                                                <?if ($ext !=='pdf')
                                                                {
                                                                ?>
                                                                  <a href="uploads/tender_penyelesaian/<?= $reqId ?>/<?= $reqFilePenyelesaian ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFilePenyelesaian<?=$reqTypeId?><?=($i+1)?>"> <?= $reqFilePenyelesaian ?> </span>
                                                                <?
                                                                }
                                                                else
                                                                {
                                                                ?>
                                                                  <a onclick="openAdd(`uploads/tender_penyelesaian/<?= $reqId ?>/<?= $reqFilePenyelesaian ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFilePenyelesaian<?=$reqTypeId?><?=($i+1)?>"> <?= $reqFilePenyelesaian ?> </span>
                                                                <?
                                                                }
                                                                ?>
                                                            </td>
                                                            <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
                                                        </tr>
                                                <?
                                                    }
                                                }
                                                ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?
                        }
                        ?>
                    </div>

                <?php        
                } 
                ?>
                    <?php
                    /*
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Entry File Pelaksanaan</h3>
                    </div>

                    <div class="form-group" style="padding: 10px">
                        <table style="width: 100%" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="40%"> Type <a onclick="tambahPenyebab('', 'Pelaksanaan')" id="btnPenyebab" class="btn btn-info"><i class="fa fa-plus-square"></i></a></th>
                                    <th width="50%"> File Name </th>
                                    <th width="10%"> Action </th>
                                </tr>
                            </thead>
                            <tbody id="tambahAttacmentPelaksanaan">
                                <?
                                $reqPelaksanaanPath = json_decode($reqPelaksanaanPath, true);
                                for ($i = 0; $i < count($reqPelaksanaanPath); $i++) {
                                    $reqTypePelaksanaan = $reqPelaksanaanPath[$i]["type"];
                                    $reqFilePelaksanaan = $reqPelaksanaanPath[$i]["file"];
                                    $ext = substr($reqFilePelaksanaan, -3);
                                ?>
                                        <tr>
                                            <td>
                                                <input class="easyui-combobox form-control" style="width:100%" name="reqTypePelaksanaan[]"  id="reqTypePelaksanaan<?=$i?>" data-options="width:'400',editable:false, valueField:'id',textField:'text',url:'combo_json/comboTypePelaksanaan'" value="<?= $reqTypePelaksanaan ?>" />
                                            </td>
                                            <td>
                                                <input type="file" onchange="getFileName(this, '<?=($i+1)?>', 'Pelaksanaan')" name="reqLinkFilePelaksanaan[]" multiple class="form-control" style="width: 90%">
                                                <input type="hidden" name="reqLinkFilePelaksanaanTemp[]" value="<?= $reqFilePelaksanaan ?>">
                                                <?if ($ext !=='pdf')
                                                {
                                                ?>
                                                  <a href="uploads/tender_pelaksanaan/<?= $reqId ?>/<?= $reqFilePelaksanaan ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFilePelaksanaan<?=($i+1)?>"> <?= $reqFilePelaksanaan ?> </span>
                                                <?
                                                }
                                                else
                                                {
                                                ?>
                                                  <a onclick="openAdd(`uploads/tender_pelaksanaan/<?= $reqId ?>/<?= $reqFilePelaksanaan ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFilePelaksanaan<?=($i+1)?>"> <?= $reqFilePelaksanaan ?> </span>
                                                <?
                                                }
                                                ?>
                                            </td>
                                            <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
                                        </tr>
                                <?
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>


                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Entry File BA Penyelesaian</h3>
                    </div>

                    <div class="form-group" style="padding: 10px">
                        <table style="width: 100%" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="40%"> Type <a onclick="tambahPenyebab('', 'Penyelesaian')" id="btnPenyebab" class="btn btn-info"><i class="fa fa-plus-square"></i></a></th>
                                    <th width="50%"> File Name </th>
                                    <th width="10%"> Action </th>
                                </tr>
                            </thead>
                            <tbody id="tambahAttacmentPenyelesaian">
                                <?
                                $reqPenyelesaianPath = json_decode($reqBaPenyPath, true);
                                for ($i = 0; $i < count($reqPenyelesaianPath); $i++) {
                                    $reqTypePenyelesaian = $reqPenyelesaianPath[$i]["type"];
                                    $reqFilePenyelesaian = $reqPenyelesaianPath[$i]["file"];
                                    $ext = substr($reqFilePenyelesaian, -3);
                                ?>
                                        <tr>
                                            <td>
                                                <input class="easyui-combobox form-control" style="width:100%" name="reqTypePenyelesaian[]"  id="reqTypePenyelesaian<?=$i?>" data-options="width:'400',editable:false, valueField:'id',textField:'text',url:'combo_json/comboTypePenyelesaian'" value="<?= $reqTypePenyelesaian ?>" />
                                            </td>
                                            <td>
                                                <input type="file" onchange="getFileName(this, '<?=($i+1)?>', 'Penyelesaian')" name="reqLinkFilePenyelesaian[]" multiple class="form-control" style="width: 90%">
                                                <input type="hidden" name="reqLinkFilePenyelesaianTemp[]" value="<?= $reqFilePenyelesaian ?>">
                                                <?if ($ext !=='pdf')
                                                {
                                                ?>
                                                  <a href="uploads/tender_penyelesaian/<?= $reqId ?>/<?= $reqFilePenyelesaian ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFilePenyelesaian<?=($i+1)?>"> <?= $reqFilePenyelesaian ?> </span>
                                                <?
                                                }
                                                else
                                                {
                                                ?>
                                                  <a onclick="openAdd(`uploads/tender_penyelesaian/<?= $reqId ?>/<?= $reqFilePenyelesaian ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFilePenyelesaian<?=($i+1)?>"> <?= $reqFilePenyelesaian ?> </span>
                                                <?
                                                }
                                                ?>
                                            </td>
                                            <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
                                        </tr>
                                <?
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>

                    */
                    ?>

                 
                    <input type="hidden" name="reqId" value="<?= $reqId ?>" />
                    <input type="hidden" name="reqMode" value="<?= $reqMode ?>" />

                </form>
            </div>
            <div style="text-align:center;padding:5px">
                <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
                <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Submit</a>
            </div>

        </div>

    </div>

    <script>
        function submitForm() {
            $('#ff').form('submit', {
                url: 'web/tender_json/add_project',
                onSubmit: function(param) {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    //alert(data);
                    var datas = data.split('-');
                      show_toast('info', 'Information', 'Header success added <br>' + datas[1]);
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/tender_project_add?reqId=" + datas[0]);
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }
    </script>

    <script type="text/javascript" src="libraries/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript">
        async function tambahPenyebab(filename='',type='',typeId='',jenis='') {
            return new Promise(function(resolve, reject) {
                var id = $('#tambahAttacment'+type+typeId+' tr').length+1;
                $.get("app/loadUrl/app/tempalate_row_attacment_tender?id="+id+"&filename="+filename+"&type="+type+"&typeId="+typeId+"&jenis="+jenis, function(data) {
                    $("#tambahAttacment"+type+typeId).append(data);
                    resolve(true);
                });
            });
        }

        async function getFileName(input,  id,type, typeId='', jenis='') {
            for (var i = 0; i < input.files.length; i++) {
                if(i == 0){
                    $("#namaFile"+type+typeId+id).html(input.files[0].name);
                    if(input.files.length > 1){
                        for (var j = 0; j < input.files.length - 1; j++) {
                            // $("#namaFile"+type+typeId+id).append(`<input type="text" style="width:100%" name="reqType${type}[]"  value="${typeId} " />`);
                        }
                    }
                }
                else{
                    await tambahPenyebab(encodeURIComponent(input.files[i].name), type, typeId,jenis)
                }
            }
            
        }

        function rubah_keterangan(id){
                      $.get("web/tender_json/pilih_detail?reqId=" + id, function(data) {
                            var obj = JSON.parse(data);
                            $("#reqProjectName").val(obj.KETERANGAN);
                            $("#reqCompanyId").val(obj.COMPANY_ID);
                            $("#reqCompanyName").val(obj.COMPANY_NAME);
                    });             
                           
        }

        function openCompany() {
            openAdd('app/loadUrl/app/template_load_company_id');
        }
        function clearCompany(){

            $('#reqCompanyName').val('');
            $('#reqCompanyId').val('');
            $('#reqDocumentPerson').val('');
            $('#reqAddress').val('');
            $('#reqEmail').val('');
            $('#reqTelephone').val('');
            $('#reqFaximile').val('');
            $('#reqHp').val('');
        }
        // $('#reqFromDate').datebox('setValue', '6/1/2012');
        $('#reqFromDate').datebox({
            onSelect: function(date){
                // alert(date.getFullYear()+":"+(date.getMonth()+1)+":"+date.getDate());
                ambil_interval();
            }
        });
          $('#reqToDate').datebox({
            onSelect: function(date){
                 ambil_interval();
                // alert(date.getFullYear()+":"+(date.getMonth()+1)+":"+date.getDate());
            }
        });

        function ambil_interval(){
         var tgl1 =   $('#reqFromDate').datebox('getValue');
         var tgl2 =   $('#reqToDate').datebox('getValue');

         var selisih =hitungSelisihHari(tgl1,tgl2);
         // console.log(selisih+" Day ");
         $("#reqDuration").val(selisih);

        }
         function company_pilihans(id, name, contact, reqAddress, reqEmail, reqTelephone, reqFaximile, reqHp) {
            $("#reqCompanyName").val(name);
            $("#reqCompanyId").val(id);
            $("#reqDocumentPerson").val(contact);
            $("#reqAddress").val(reqAddress);
            $("#reqEmail").val(reqEmail);
            $("#reqTelephone").val(reqTelephone);
            $("#reqFaximile").val(reqFaximile);
            $("#reqHp").val(reqHp);

        }
        

    </script>
</div>
</div>