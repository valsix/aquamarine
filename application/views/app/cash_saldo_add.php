<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("Bank");
$bank = new Bank(); 
$bank->selectByParamsMonitoring(array(),-1,-1,'',"ORDER BY NAMA ASC");
$aColumns = array("CASH_SALDO_DETAIL_ID","CASH_SALDO_ID","URAIAN","BANK_ID","CURENCY","AMOUNT","AMOUNT_IDR","AMOUNT_USD");

while ($bank->nextRow()) {
 array_push($aColumns, $bank->getField('NAMA'));
}
  array_push($aColumns, 'AKSI');

$this->load->model("CastSaldo");
$cast_saldo = new CastSaldo();

$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $cast_saldo->selectByParamsMonitoring(array("A.CAST_SALDO_ID" => $reqId));
    $cast_saldo->firstRow();
    $reqId = $cast_saldo->getField("CAST_SALDO_ID");
    $reqTanggal = $cast_saldo->getField("TANGGAL");

}
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />


<script type="text/javascript" language="javascript" class="init">
    var oTable;
    var reqIds;
   

    $(document).ready(function() {
        reqIds = $("#reqId").val();
        if (reqIds == '') {
            reqIds = '-0';
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
            "bSort": false,
            "bProcessing": true,
            "bServerSide": false,
            "bScrollInfinite": true,
            "sAjaxSource": "web/cash_saldo_detail_json/json?reqId="+ reqIds,
            "fnServerParams": function(aoData) {
                // aoData.push( { "name": "input1", "value": $("#input1").val() } );
                // aoData.push( { "name": "input2", "value": $("#input2").val() } );
                // aoData.push( { "name": "input3", "value": $("#input3").val() } );
            },
            columnDefs: [{
                className: 'never',
                targets: [0,1,3,4,5,6,7]
            }],
            "bStateSave": true,
            "fnStateSave": function(oSettings, oData) {
                localStorage.setItem('DataTables_' + window.location.pathname, JSON.stringify(oData));
            },
            "fnStateLoad": function(oSettings) {
                var data = localStorage.getItem('DataTables_' + window.location.pathname);
                return JSON.parse(data);
            },
            "sPaginationType": "full_numbers",
             "footerCallback": function(row, data, start, end, display) {
                 var api = this.api(),
                    data;
                var intVal = function(i) {
                    // console.log(i);
                    return typeof i === 'string' ?
                    i.replaceAll('.', '') * 1 :
                    typeof i === 'number' ?
                    i : 0;
                };

                var colomn = [4,5];
                 var total_usd=0;
                 var total_idr=0;
             
                  total_usd = api
                    .column(7)
                    .data()
                    .reduce(function(a, b) {

                          
                          return parseFloat(a = a || 0) + parseFloat(b = b || 0);
                           
                           
                    }, 0);
                    total_idr = api
                    .column(6)
                    .data()
                    .reduce(function(a, b) {

                          
                           return parseFloat(a = a || 0) + parseFloat(b = b || 0);
                           
                           
                    }, 0);

       
                
               
                // console.log(total_idr);

                 $("#totalIDR").val(total_idr.toFixed(2));
                   // $("#totalUSD").val(FormatCurrencyBaru(total_usd.toFixed(2)));
                 
                $("#totalIDR").val(format1(total_idr,'Rp '));
                 $("#totalUSD").val(format1(total_usd, '$ '));
                 

             }

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

        $('#btnEdit').on('click', function() {
            if (anSelectedData == "")
                return false;
            // document.location.href = "app/index/cash_report_add?reqId=" + anSelectedId;
            // alert(anSelectedData);

            $("#reqCashReportDetilId").val(elements[0]);
            $('#reqDetailTanggal').datebox('setValue', elements[1]);
            $("#reqKeterangan").val(elements[2]);
            $("#reqPelunasan").val(elements[3]);
            $("#reqNoRek").val(elements[4]);
            $("#reqDebet").val(elements[5]);
            $("#reqKredit").val(elements[6]);
            // $("#reqSaldo").val(elements[7]);
            // console.log(elements[7]);

            $('#btnProses').show();
            $('#btnProses').html('Update');

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
<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/cash_saldo"> Saldo Cash</a> &rsaquo; Form Saldo Cash
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>
          <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right:  10px" onclick="cetakPdf()"><i class="fa fa-file-pdf-o "> </i><span> Pdf</span> </a>
    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Data
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>
                        </h3>
                        <br>
                    </div>

                    <div class="form-group">
                        <label for="reqTanggal" class="control-label col-md-2">Tanggal</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input id="reqTanggal" class="easyui-datebox textbox form-control" name="reqTanggal" value="<?= $reqTanggal ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Cash Report Detail</h3>
                    </div>
                    <br>
                    <div class="form-group">
                       
                        <label for="reqNoRek" class="control-label col-md-2">Bank </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="hidden" id="reqCashSaldoDetailId" name="reqCashSaldoDetailId">
                                     <input class="easyui-combobox form-control " style="width:100%" id="reqBankId" name="reqBankId" data-options="width:'372',editable:true, valueField:'id',textField:'text',url:'combo_json/ComboBank'" value="" />
                                </div>
                            </div>
                        </div>
                        <label for="reqDeskripsi" class="control-label col-md-2">Currency </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                      <input class="easyui-combobox form-control " style="width:100%" id="reqCurency" name="reqCurency" data-options="width:'372',editable:true, valueField:'id',textField:'text',url:'combo_json/comboValueDollar2'" value="" />
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="form-group">
                        <label for="reqDeskripsi" class="control-label col-md-2">Desciption </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea type="text" id="reqUraian" rows="3" cols="3" class="form-control" name="reqUraian" value="" style=" width:100%"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <label for="reqDeskripsi" class="control-label col-md-2">Amount </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqAmount" class="easyui-validatebox form-control" name="reqAmount" value="" style=" width:100%" onchange="numberWithCommas('reqAmount')" onkeyup="numberWithCommas('reqAmount')" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                       
                        
                       

                        <div style="text-align:center;padding:5px">
                            <input type="hidden" name="reqId" id="reqId" value="<?= $reqId ?>" />

                            <a href="javascript:void(0)" class="btn btn-warning" onclick="reseti()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
                            <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Submit</a>
                            <!-- <a href="javascript:void(0)" class="btn btn-primary" onclick="delete_datas(<?= $reqChild ?>)"><i class="fa fa-fw fa-trash"></i> Delete</a> -->

                        </div>

                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="form-group ">
                                <div class="col-md-12">
                                    <table id="example" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <?php
                                                for ($i = 1; $i < count($aColumns); $i++) {
                                                ?>
                                                    <th><?= str_replace('_', ' ', $aColumns[$i])  ?></th>
                                                <?php

                                                };
                                                ?>
                                            </tr>
                                        </thead>
                                    </table>

                                </div>
                            </div>
                        </div>

                    </div>

                </form>
                    <div class="page-header">
                <h3><i class="fa fa-file-text fa-lg"></i> Summary</h3>
            </div>
            <br>
            <input type="hidden" id="RP_USD" value="">
            <input type="hidden" id="RP" value="">

            <table style="width: 100%" style="font-weight: bold;">
                <td style="width: 60%"> Grand Total </td>
                <td style="width: 20%"><b> IDR <b> <input style="width: 60%" type="text" id="totalIDR" class="form-control" disabled readonly /> </td>

                <td style="width: 20%"><b> USD<b> <input style="width: 60%" type="text" id="totalUSD" class="form-control" disabled readonly /></td>


            </table>

            <br>
            </div>


        </div>

    </div>

    <script>
        function submitForm() {
            $('#ff').form('submit', {
                url: 'web/cash_saldo_detail_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    //alert(data);
                    var datas = data.split('-');
                    if (datas[1] != '') {
                        $('#reqId').val(datas[1]);
                        reqIds = datas[1];
                        show_toast('info', 'Information', 'Header success added'+datas[1]);
                        $.messager.alertLink('Info', datas[0], 'info', "app/index/cash_saldo_add/?reqId="+datas[1]);
                    } else {
                        oTable.api().ajax.reload(null,false);
                        show_toast('info', 'Information', datas[0]);
                    }
                   reseti();
                }

            });
        }

        // function clearForm() {
        //     $('#ff').form('clear');
        //     $('#reqTanggal').datebox('setValue', '<?= $reqTanggal ?>');  
        //     $('#reqId').val('<?= $reqId ?>');  

        // }
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#btnProses').hide();
         
        });
    </script>

    <script type="text/javascript">
        function deleting(id) {
            var elements = oTable.fnGetData(id);
            var kata = '<b>Detail </b><br>' + elements[2] ;
            $.get("web/cash_saldo_detail_json/delete?reqId=" + elements[0], function(data) {
                oTable.api().ajax.reload(null,false);

               
                show_toast('warning', 'Success delete row', kata);


            });
        }

        function editing(id) {
            var elements = oTable.fnGetData(id);
            $("#reqCashSaldoDetailId").val(elements[0]);
            $('#reqUraian').val(elements[2]);
            $("#reqBankId").combobox('setValue', elements[3]);
            $("#reqCurency").combobox('setValue', elements[4]);
            $("#reqAmount").val(FormatCurrencyBaru(elements[5]));
            

        }

        function reseti() {
            oTable.api().ajax.reload(null,false);
             $("#reqCashSaldoDetailId").val('');
            $('#reqUraian').val('');
            $("#reqBankId").combobox('setValue', '');
            $("#reqCurency").combobox('setValue', '');
            $("#reqAmount").val('');
           
        }

        function cetakPdf(){
            openAdd("app/loadUrl/report/cash_saldo_pdf?reqId=<?=$reqId?>");
        }
    </script>
<script type="text/javascript">
    function format1(n, currency) {
  return currency + n.toFixed(2).replace(/./g, function(c, i, a) {
    return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "." + c : c;
  });
}
</script>
   
</div>
</div>