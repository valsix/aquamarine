<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$aColumns = array(
    "VESSEL_ID", "COMPANY_ID", "NAME", "LENGTH", "BREATH", "DEPTH",
    "TYPE_VESSEL", "CLASS_VESSEL", "TYPE_SURVEY", "LOCATION_SURVEY", "CONTACT_PERSON",
    "TELEPONE", "tanggal survey", "next survey", "NET Tonnage", "Reason", "AKSI"
);
$this->load->model('VendorCode');
$this->load->model("Company");
$this->load->model("CostumerSupport");
$company = new Company();
$costumer_support = new CostumerSupport();
$reqId = $this->input->get("reqId");
// $reqId =3513;
if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $company->selectByParamsMonitoring(array("COMPANY_ID" => $reqId));
    $company->firstRow();

    $reqCompanyId          = $company->getField("COMPANY_ID");
    $reqName               = $company->getField("NAME");
    $reqAddress            = $company->getField("ADDRESS");
    $reqPhone              = $company->getField("PHONE");
    $reqFax                = $company->getField("FAX");
    $reqEmail              = $company->getField("EMAIL");
    $reqCp1Name            = $company->getField("CP1_NAME");
    $reqCp1Telp            = $company->getField("CP1_TELP");
    $reqCp2Name            = $company->getField("CP2_NAME");
    $reqCp2Telp            = $company->getField("CP2_TELP");
    $reqLa1Name            = $company->getField("LA1_NAME");
    $reqLa1Address         = $company->getField("LA1_ADDRESS");
    $reqLa1Phone           = $company->getField("LA1_PHONE");
    $reqLa1Fax             = $company->getField("LA1_FAX");
    $reqLa1Email           = $company->getField("LA1_EMAIL");
    $reqLa1Cp1             = $company->getField("LA1_CP1");
    $reqLa1Cp2             = $company->getField("LA1_CP2");
    $reqLa2Name            = $company->getField("LA2_NAME");
    $reqLa2Address         = $company->getField("LA2_ADDRESS");
    $reqLa2Telp            = $company->getField("LA2_TELP");
    $reqLa2Fax             = $company->getField("LA2_FAX");
    $reqLa2Email           = $company->getField("LA2_EMAIL");
    $reqLa2Cp1             = $company->getField("LA2_CP1");
    $reqLa2Cp2             = $company->getField("LA2_CP2");
    $reqLa1Cp1Phone        = $company->getField("LA1_CP1_PHONE");
    $reqLa1Cp2Phone        = $company->getField("LA1_CP2_PHONE");
    $reqLa2Cp1Phone        = $company->getField("LA2_CP1_PHONE");
    $reqLa2Cp2Phone        = $company->getField("LA2_CP2_PHONE");
    $reqTipe               = $company->getField("TIPE");
    $reqProvinsi               = $company->getField("PROPINSI_ID");
    $combo_kabupaten               = $company->getField("KABUPATEN_ID");

    $vendorcode = new VendorCode();
    $vendorcode->selectByParamsMonitoring(array('A.SUPPLIER_ID'=>$reqId,'A.STATUS_AKTIF'=>'1'));
    $arrDataVendor = $vendorcode->rowResult;
    $arrDataVendor =   $arrDataVendor[0];
    $reqKodeVendor = $arrDataVendor['kode'];
    $reqType = $arrDataVendor['type'];
    $reqLoc = $arrDataVendor['area'];
}


$total_support = $costumer_support->getCountByParamsMonitoring(array("CAST(A.COMPANY_ID AS VARCHAR)"=>$reqId));
$costumer_support->selectByParamsMonitoring(array("CAST(A.COMPANY_ID AS VARCHAR)"=>$reqId));
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />

<script type="text/javascript" language="javascript" class="init">
    var oTable;
    var total;
    var reqIds;
    $(document).ready(function() {
        reqIds = $("#reqId").val();
        if (reqIds == '') {
            reqIds = -1;
        }
        oTable = $('#example').dataTable({
            bJQueryUI: true,
            "iDisplayLength": 10,
            /* UNTUK MENGHIDE KOLOM ID */
            "aoColumns": [{
                    bVisible: false
                },
                <?
                for ($i = 1; $i < count($aColumns) - 1; $i++) {
                ?>
                    null,
                <?
                }
                ?>
                null
            ],
            "bSort": true,
            "bProcessing": true,
            "bServerSide": false,
            "bScrollInfinite": true,
        "sAjaxSource": "web/vessel_detail_json/json?reqId=" + reqIds,
            "fnServerParams": function(aoData) {
                // aoData.push( { "name": "input1", "value": $("#input1").val() } );
                // aoData.push( { "name": "input2", "value": $("#input2").val() } );
                // aoData.push( { "name": "input3", "value": $("#input3").val() } );
            },
            columnDefs: [{
                className: 'never',
                targets: [0, 1, 6, 7, 8, 9,14]
            }],
            "bStateSave": true,
            "fnStateSave": function(oSettings, oData) {
                localStorage.setItem('DataTables_' + window.location.pathname, JSON.stringify(oData));
            },
            "fnStateLoad": function(oSettings) {
                var data = localStorage.getItem('DataTables_' + window.location.pathname);
                return JSON.parse(data);
            },
            "sPaginationType": "full_numbers"

        });
        /* Click event handler */

        /* RIGHT CLICK EVENT */
        var anSelectedData = '';
        var anSelectedId = '';
        var elements = '';
        var anSelectedDownload = '';
        var anSelectedPosition = '';

        function fnGetSelected(oTableLocal) {
            var aReturn = new Array();
            var aTrs = oTableLocal.fnGetNodes();
            for (var i = 0; i < aTrs.length; i++) {
                if ($(aTrs[i]).hasClass('row_selected')) {
                    aReturn.push(aTrs[i]);
                    anSelectedPosition = i;
                }
            }
            return aReturn;
        }

        $("#example tbody").click(function(event) {
            $(oTable.fnSettings().aoData).each(function() {
                $(this.nTr).removeClass('row_selected');
            });
            $(event.target.parentNode).addClass('row_selected');

            var anSelected = fnGetSelected(oTable);

            anSelectedData = String(oTable.fnGetData(anSelected[0]));
            // console.log(oTable.fnGetData(anSelected[0]));
            var element = anSelectedData.split(',');
            elements = oTable.fnGetData(anSelected[0]);
            anSelectedId = element[0];
        });

        $('#btnAdd').on('click', function() {
            // document.location.href = "app/index/cash_report_add";

            $('#btnProses').show();
            $('#btnProses').html('Add');
            clearForm();

        });

        $('.editing').on('click', function() {
            if (anSelectedData == "")
                return false;
            // document.location.href = "app/index/cash_report_add?reqId=" + anSelectedId;
            // alert(anSelectedData);





        });

        $('#btnDelete').on('click', function() {
            if (anSelectedData == "")
                return false;
            // deleteData("web/cash_report_json/delete", anSelectedId);
            del(anSelectedId);
        });

        $('#btnRefresh').on('click', function() {
            Refresh();
        });

        $('#btnProses').on('click', function() {
            submitForm();
        });

    });
</script>
<style type="text/css">
    #tabel-vessel tr th {
        color: white;
        text-transform: uppercase;
        font-weight: bold;

    }
</style>
<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/customer_list">Customer</a> &rsaquo; Form Customer List
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>

         <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="classes()"><i class="fa fa-fw fa-gavel fa lg"> </i><span>  Master Class of Vessel</span> </a>

          <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="types()"><i class="fa fa-fw fa-gavel fa lg"> </i><span> Master Type of Vessel</span> </a>

    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Company
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>

                            <button type="button" id="" class="btn btn-default pull-right " style="margin-right: 10px" onclick="btn_next()"><i id="opens" class="fa fa-arrow-right fa-lg"></i><b id="htmlopen">Next</b></button>

                            <button type="button" id="" class="btn btn-default pull-right " style="margin-right: 10px" onclick="btn_prev()"><i id="opens" class="fa fa-arrow-left fa-lg"></i><b id="htmlopen">Prev</b></button>

                        </h3>
                        <br>
                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Company Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" name="reqName" id="reqName" value="<?= $reqName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqPhone" class="control-label col-md-2">Telp</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqPhone" class="easyui-validatebox textbox form-control" name="reqPhone" value="<?= $reqPhone ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqAddress" class="control-label col-md-2">Address</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea name="reqAddress" id="reqAddress" class="form-control tinyMCES" style="width:100%;"><?= $reqAddress ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                      <div class="form-group">
                    <label for="reqFax" class="control-label col-md-2">Fax</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqFax" class="easyui-validatebox textbox form-control" name="reqFax" value="<?= $reqFax ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>

                        <label for="reqEmail" class="control-label col-md-2">Email</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqEmail" class="easyui-validatebox textbox form-control" name="reqEmail" value="<?= $reqEmail ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqCp1Name" class="control-label col-md-2">Contact Person I</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqCp1Name" class="easyui-validatebox textbox form-control" name="reqCp1Name" value="<?= $reqCp1Name ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqCp1Telp" class="control-label col-md-2">Mobile Phone 1</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqCp1Telp" class="easyui-validatebox textbox form-control" name="reqCp1Telp" value="<?= $reqCp1Telp ?>" onkeypress='validate(event)' style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqCp2Name" class="control-label col-md-2">Contact Person II</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqCp2Name" class="easyui-validatebox textbox form-control" name="reqCp2Name" value="<?= $reqCp2Name ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqCp2Telp" class="control-label col-md-2">Mobile Phone 2</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" onkeypress='validate(event)' id="reqCp2Telp" class="easyui-validatebox textbox form-control" name="reqCp2Telp" value="<?= $reqCp2Telp ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                      <div class="form-group">
                        <label for="reqCp2Name" class="control-label col-md-2">Provinsi</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqProvinsi" id="reqProvinsi" data-options="width:'350',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_provinsi',onSelect: function(rec){

                                                  gantiKota(rec.id);
                                           }" value="<?= $reqProvinsi ?>" />
                                </div>
                            </div>
                        </div>
                        <label for="reqCp2Telp" class="control-label col-md-2">Kota</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                     <input class="easyui-combobox form-control" style="width:100%" name="combo_kabupaten" id="combo_kabupaten" data-options="width:'350',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_kabupaten?reqId=<?= $reqProvinsi ?>'" value="<?= $combo_kabupaten ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        
                        <label for="reqCp2Telp" class="control-label col-md-2">Type</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:40%"   name="reqType" data-options="width:'190',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_type_sub'" value="<?=$reqType?>" />
                                </div>
                            </div>
                        </div>
                        <label for="reqCp2Telp" class="control-label col-md-2">Location Code</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                        <input class="easyui-combobox form-control" style="width:40%"   name="reqLoc" data-options="width:'290',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_type_location'" value="<?=$reqLoc?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqCp2Telp" class="control-label col-md-2">Vendor Code</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                 <input type="text"  disabled readonly class="easyui-validatebox textbox form-control" value="<?=$reqKodeVendor?>"  style=" width:60%" />
                                 <br><input type="checkbox" name ='reqCek' value="1" /> <em style="color: red">( ! ) Click Check  untuk melakukan revisi </em>
                             </div>
                         </div>
                     </div>
                 </div>
                    <?
                    if(!empty($reqId)){
                    ?>
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Support</h3>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                            <th style="width: 40%">NAMA <a onclick="tambahPenyebabSupport()"  class="btn btn-info"><i class="fa fa-fw fa-plus-square"></i></a></th>
                            <th style="width: 20%">TELP / HP </th>
                            <th style="width: 30%">Email </th>
                            <th style="width: 10%">Aksi </th>
                            </tr>
                        </thead>
                        <tbody id="bodySupport">
                            <?
                           
                            while ($costumer_support->nextRow()) {
                                $no = $costumer_support->getField("COSTUMER_SUPPORT_ID");
                            ?>
                            <tr>
                                <td>
                                    <input type="hidden" class="form-control" value="<?=$costumer_support->getField("COSTUMER_SUPPORT_ID")?>" id="reqSupportId<?=$no?>"> 

                                    <input class="form-control" value="<?=$costumer_support->getField("NAMA")?>" id="reqSupportName<?=$no?>">  </td>
                                <td><input class="form-control" onkeypress='validate(event)' value="<?=$costumer_support->getField("TELP")?>" id="reqSupportTelp<?=$no?>"> </td>
                                <td><input class="form-control" value="<?=$costumer_support->getField("EMAIL")?>" id="reqSupportEmail<?=$no?>"</td>
                                <td>

                                    <button type="button" class="btn btn-info " onclick="editing_support(<?=$costumer_support->getField("COSTUMER_SUPPORT_ID")?>)"><i class="fa fa-pencil-square-o fa-lg"> </i> </button>

                                    <button type="button" class="btn btn-danger hapusi" onclick="delete_support(<?=$costumer_support->getField("COSTUMER_SUPPORT_ID")?>)"><i class="fa fa-trash-o fa-lg"> </i> </button> </td>
                            </tr>
                            <?
                           
                            }
                            ?>
                        </tbody>
                    </table>

                    <?
                        }   
                    ?>
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Local Agent</h3>
                    </div>

                    <div class="form-group">
                        <label for="reqLa1Name" class="control-label col-md-2">Company Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqLa1Name" class="easyui-validatebox textbox form-control" name="reqLa1Name" value="<?= $reqLa1Name ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqLa1Phone" class="control-label col-md-2">Telp</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" onkeypress='validate(event)' id="reqLa1Phone" class="easyui-validatebox textbox form-control" name="reqLa1Phone" value="<?= $reqLa1Phone ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqLa1Address" class="control-label col-md-2">Address</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea name="reqLa1Address" id="reqLa1Address" class="form-control tinyMCES" style="width:100%;"><?= $reqLa1Address ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="form-group">
                    <label for="reqLa1Fax" class="control-label col-md-2">Fax</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqLa1Fax" onkeypress='validate(event)' class="easyui-validatebox textbox form-control" name="reqLa1Fax" value="<?= $reqLa1Fax ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>

                        <label for="reqLa1Email" class="control-label col-md-2">Email</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqLa1Email" class="easyui-validatebox textbox form-control" name="reqLa1Email" value="<?= $reqLa1Email ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqLa1Cp1" class="control-label col-md-2">Contact Person I</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqLa1Cp1" class="easyui-validatebox textbox form-control" name="reqLa1Cp1" value="<?= $reqLa1Cp1 ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqLa1Cp1Phone" class="control-label col-md-2">Mobile Phone 1</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" onkeypress='validate(event)' id="reqLa1Cp1Phone" class="easyui-validatebox textbox form-control" name="reqLa1Cp1Phone" value="<?= $reqLa1Cp1Phone ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqLa1Cp2" class="control-label col-md-2">Contact Person II</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqLa1Cp2" class="easyui-validatebox textbox form-control" name="reqLa1Cp2" value="<?= $reqLa1Cp2 ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqLa1Cp2Phone" class="control-label col-md-2">Mobile Phone 2</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqLa1Cp2Phone" onkeypress='validate(event)' class="easyui-validatebox textbox form-control" name="reqLa1Cp2Phone" value="<?= $reqLa1Cp2Phone ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Local Agent II</h3>
                    </div>

                    <div class="form-group">
                        <label for="reqLa2Name" class="control-label col-md-2">Company Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqLa2Name" class="easyui-validatebox textbox form-control" name="reqLa2Name" value="<?= $reqLa2Name ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqLa2Telp" class="control-label col-md-2">Telp</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqLa2Telp" onkeypress='validate(event)' class="easyui-validatebox textbox form-control" name="reqLa2Telp" value="<?= $reqLa2Telp ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqLa2Address" class="control-label col-md-2">Address</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea name="reqLa2Address" id="reqLa2Address" class="form-control tinyMCES" style="width:100%;"><?= $reqLa2Address ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="form-group">
                    <label for="reqLa2Fax" class="control-label col-md-2">Fax</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" onkeypress='validate(event)' id="reqLa2Fax" class="easyui-validatebox textbox form-control" name="reqLa2Fax" value="<?= $reqLa2Fax ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>

                        <label for="reqLa2Email" class="control-label col-md-2">Email</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqLa2Email" class="easyui-validatebox textbox form-control" name="reqLa2Email" value="<?= $reqLa2Email ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqLa2Cp1" class="control-label col-md-2">Contact Person I</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqLa2Cp1" class="easyui-validatebox textbox form-control" name="reqLa2Cp1" value="<?= $reqLa2Cp1 ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqLa2Cp1Phone" class="control-label col-md-2">Mobile Phone 1</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" onkeypress='validate(event)' id="reqLa2Cp1Phone" class="easyui-validatebox textbox form-control" name="reqLa2Cp1Phone" value="<?= $reqLa2Cp1Phone ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqLa2Cp2" class="control-label col-md-2">Contact Person II</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqLa2Cp2" class="easyui-validatebox textbox form-control" name="reqLa2Cp2" value="<?= $reqLa2Cp2 ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqLa2Cp2Phone" class="control-label col-md-2">Mobile Phone 2</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqLa2Cp2Phone" onkeypress='validate(event)' class="easyui-validatebox textbox form-control" name="reqLa2Cp2Phone" value="<?= $reqLa2Cp2Phone ?>" style=" width:100%" />


                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="page-header">

                        <h3 style="text-transform: none;"><i class="fa fa-file-text fa-lg"></i> <b>Vessel</b>

                            <a onclick="tambahPenyebab2()" id="btnPenyebab" class="btn btn-info"><i class="fa fa-fw fa-plus-square"></i></a>
                            <a onclick="reload_table()" class="btn btn-info"><i class="fa fa-fw fa-refresh"></i></a>

                        </h3>
                    </div>
                    <div class="form-group">

                        <div>
                            <table id="example" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <?php
                                        for ($i = 1; $i < count($aColumns); $i++) {
                                            if ($i == 2) {
                                        ?>
                                                <th><?= ucfirst( strtolower(str_replace('_', ' ', $aColumns[$i])))  ?>

                                                </th>
                                            <?
                                            } else {
                                            ?>
                                                <th><?= ucfirst( strtolower(str_replace('_', ' ', $aColumns[$i])))  ?></th>
                                        <?php
                                            }
                                        };
                                        ?>
                                    </tr>
                                </thead>
                            </table>

                        </div>


                    </div>




                    <input type="hidden" name="reqId" id="reqId" value="<?= $reqId ?>" />
                    <input type="hidden" name="reqMode" value="<?= $reqMode ?>" />

                </form>
            </div>
            <div style="text-align:center;padding:5px">
                <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
                <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Submit</a>
            </div>

        </div>

    </div>

    <script type="text/javascript">
        var halam = [];
        $(document).ready(function() {
            // setTimeout(function() {
            halam = [];
            halaman_ready();

            //     }, 1000);
        });
    </script>
    <script type="text/javascript">
        function halaman_ready() {

            <?
            $company = new Company();
            $company->selectByParamsMonitoring(array());
            while ($company->nextRow()) {

            ?>


                halam.push("<?= $company->getField("COMPANY_ID") ?>");
            <?
            }
            ?>

        }

        function check_halaman() {
            var reqId = "<?= $reqId ?>";
            var index = 0;
            for (var i = 0; i < halam.length; i++) {
                if (halam[i] == reqId) {
                    index = i;
                }
            }
            return index;
        }

        function btn_next() {

            var index = check_halaman();
            var halaman = parseInt(index) + 1;
            var hal = halam[halaman];
            // console.log(hal);
            if (typeof hal === "undefined") {
                $.messager.alert('Info', "Halaman not Founds", 'info');

            } else {
                window.location.href = "app/index/customer_list_add?reqId=" + hal;
            }

        }

        function btn_prev() {

            var index = check_halaman();
            var halaman = parseInt(index) - 1;
            var hal = halam[halaman];
            // console.log(hal);
            if (typeof hal === "undefined") {

                $.messager.alert('Info', "Halaman not Founds", 'info');
            } else {
                window.location.href = "app/index/customer_list_add?reqId=" + hal;
            }

        }
    </script>
    <script>
        function submitForm() {

            $('#ff').form('submit', {
                url: 'web/customer_json/add_new',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    // alert(data);
                    var datas = data.split('-');
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/customer_list_add?reqId=" + datas[0]);
                }
            });
        }

        function reload_table() {
            oTable.api().ajax.reload(null,false);
        }

        function clearForm() {
            $('#ff').form('clear');
        }

        function tambahPenyebab() {


            $.get("app/loadUrl/app/tempalate_vessel?", function(data) {
                $("#tambahVassel").append(data);
            });
        }

        function editing(id) {

            var elements = oTable.fnGetData(id);
            // console.log(elements[0]);

            openAdd('app/loadUrl/app/template_load_vessel?reqCompanyId=<?= $reqId ?>&reqId=' + elements[0]);

        }

        function deleting(id) {
            var elements = oTable.fnGetData(id);
            // var kata =  '<b>Detail </b><br>'+elements[2]+'<br> At'+elements[3];

            deleteData_for_table('web/vessel_detail_json/delete', elements[0], id, 2);


        }

        function delete_support(id){
            deleteData('web/customer_json/delete_costumer_support',id);
        }

        function tambahPenyebab2() {
            <?
            if (empty($reqId)) {
            ?>
                validate_next_proses();
            <?
            } else {
            ?>
                openAdd('app/loadUrl/app/template_load_vessel?reqCompanyId=<?= $reqId ?>&reqId=0');
            <?
            }
            ?>
        }
        function tambahPenyebabSupport() {
          $.get("app/loadUrl/app/tempalate_add_support?", function(data) {
            $("#bodySupport").append(data);
        });
        }

        function editing_support(id){
            var reqSupportId = $("#reqSupportId"+id).val();
            var reqSupportName = $("#reqSupportName"+id).val();
            var reqSupportTelp = $("#reqSupportTelp"+id).val();
            var reqSupportEmail = $("#reqSupportEmail"+id).val();
              var reqCompanyId = '<?=$reqId?>';
            var url = "web/customer_json/add_support";
            $.post(url,{reqSupportId:reqSupportId,reqSupportName:reqSupportName,reqSupportTelp:reqSupportTelp,reqSupportEmail:reqSupportEmail,reqCompanyId:reqCompanyId}, function(data) {
                var datas = data.split('-');
                var tambahan_form ='<button type="button" class="btn btn-info " onclick="editing_support('+datas[0]+')"><i class="fa fa-pencil-square-o fa-lg"> </i> </button><button type="button" class="btn btn-danger hapusi" onclick="delete_support('+datas[0]+')"><i class="fa fa-trash-o fa-lg"> </i> </button>';
             
                if(datas[1]=='tambah'){
                    $('#tdparent'+id).empty();
                    $('#tdparent'+id).append(tambahan_form);
                    $("#reqSupportId"+id).val(datas[0]);

                }

            });
        }
    </script>
    <script type="text/javascript">
        function gantiKota(id){
             var url = 'web/combo_baru_json/combo_kabupaten?reqId='+id;
            $('#combo_kabupaten').combobox('reload', url);
        }

    </script>
</div>
</div>