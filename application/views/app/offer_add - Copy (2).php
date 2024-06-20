<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("Offer");
$this->load->model("Company");
$this->load->model("Vessel");

$offer = new Offer();

$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $offer->selectByParamsMonitoring(array("OFFER_ID" => $reqId));
    // echo  $offer->query;exit;
    $offer->firstRow();
    $reqOfferId          = $offer->getField("OFFER_ID");
    $reqDocumentId       = $offer->getField("DOCUMENT_ID");
    $reqDocumentPerson   = $offer->getField("DOCUMENT_PERSON");
    $reqDestination      = $offer->getField("DESTINATION");
    $reqDateOfService    = $offer->getField("DATE_OF_SERVICE");
    $reqTypeOfService    = $offer->getField("TYPE_OF_SERVICE");
    $reqScopeOfWork      = $offer->getField("SCOPE_OF_WORK");
    $reqTermAndCondition = $offer->getField("TERM_AND_CONDITION");
    $reqPaymentMethod    = $offer->getField("PAYMENT_METHOD");
    $reqTotalPrice       = $offer->getField("TOTAL_PRICE");
    $reqTotalPriceWord   = $offer->getField("TOTAL_PRICE_WORD");
    $reqStatus           = $offer->getField("STATUS");
    $reqReason           = $offer->getField("REASON");
    $reqNoOrder          = $offer->getField("NO_ORDER");
    $reqDateOfOrder      = $offer->getField("DATE_OF_ORDER");
    $reqCompanyName      = $offer->getField("COMPANY_NAME");
    $reqAddress          = $offer->getField("ADDRESS");
    $reqFaximile         = $offer->getField("FAXIMILE");
    $reqEmail            = $offer->getField("EMAIL");
    $reqTelephone        = $offer->getField("TELEPHONE");
    $reqHp               = $offer->getField("HP");
    $reqVesselName       = $offer->getField("VESSEL_NAME");
    $reqTypeOfVessel     = $offer->getField("TYPE_OF_VESSEL");
    $reqClassOfVessel    = $offer->getField("CLASS_OF_VESSEL");
    $reqMaker            = $offer->getField("MAKER");
}

$company = new Company();

$statement   =  " AND UPPER(A.NAME) LIKE '%" . strtoupper($reqCompanyName) . "%' ";
$company->selectByParamsMonitoring(array(), -1, -1, $statement);
$company->firstRow();

$companyIds = $company->getField('COMPANY_ID');
// echo $companyIds;
// exit;

if (!empty($companyIds)) {
    $vessel  = new Vessel();
    $statement   =  " AND UPPER(A.NAME) LIKE '%" . strtoupper($reqVesselName) . "%' ";
    $vessel->selectByParamsMonitoring(array("A.COMPANY_ID" => $companyIds), -1, -1, $statement);
    // echo $vessel->query;
    $vessel->firstRow();
    $vesselIds = $vessel->getField('VESSEL_ID');
}

?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />

<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/offer">Offer</a> &rsaquo; Form Offer
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold; margin-left: 10px;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-left: 10px" onclick="cetakPdf()"><i class="fa fa-file-pdf-o "> </i><span> Pdf</span> </a>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-left: 10px" onclick="send_email()"><i class="fa fa-envelope"> </i><span> Send As Email</span> </a>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="loadAttacment()"><i class="fa fa-paperclip"> </i><span> Attachment</span> </a>
    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Client Name
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>
                            <a href="javascript:void(0)" class="pull-right" style="margin-right: 20px;color: white">

                                <input type="radio" id="eng" name="bahasa" value="eng">
                                <label for="male">Eng</label>
                                <input type="radio" id="idr" name="bahasa" checked value="ind">
                                <label for="male">Ind</label><br>
                            </a>
                        </h3>
                        <br>
                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Company Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" onclick="openCompany()" name="reqCompanyName" id="reqCompanyName" value="<?= $reqCompanyName ?>" style=" width:100%" />
                                    <input type="hidden" class="easyui-validatebox textbox form-control" name="reqCompanyId" id="reqCompanyId" value="<?= $companyIds ?>" style=" width:100%" />

                                </div>
                            </div>
                        </div>
                        <label for="reqPhone" class="control-label col-md-2">Contact</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" id="reqDocumentPerson" name="reqDocumentPerson" value="<?= $reqDocumentPerson ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="page-header">

                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Adress</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea class="form-control" name="reqAddress" id="reqAddress" style="width:100%;"><?= $reqAddress; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <label for="reqPhone" class="control-label col-md-2">Email</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqEmail" class="easyui-validatebox textbox form-control" name="reqEmail" value="<?= $reqEmail ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqPhone" class="control-label col-md-2">Telephone</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" onkeypress='validate(event)' id="reqTelephone" class="easyui-validatebox textbox form-control" name="reqTelephone" value="<?= $reqTelephone ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Faximile</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" name="reqFaximile" id="reqFaximile" value="<?= $reqFaximile ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqPhone" class="control-label col-md-2">Handphone</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqHp" onkeypress='validate(event)' class="easyui-validatebox textbox form-control" name="reqHp" value="<?= $reqHp ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i>Vassel Name</h3>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Vessel Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="hidden" id="reqVesselId" name="reqVesselId" value="<?= $vesselIds ?>">
                                    <input type="text" onclick="openVessel()" class="easyui-validatebox textbox form-control" name="reqVesselName" id="reqVesselName" value="<?= $reqVesselName ?>" style=" width:100%" />

                                </div>
                            </div>
                        </div>
                        <label for="reqClassOfVessel" class="control-label col-md-2">Class of Vessel</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" id="reqClassOfVessel" name="reqClassOfVessel" data-options="width:'200',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueClassOfVessel'" value="<?= $reqClassOfVessel ?>" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Type Of Vessel</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" id="reqTypeOfVessel" name="reqTypeOfVessel" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueTypeOfVessel'" value="<?= $reqTypeOfVessel ?>" />
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i>Service Information</h3>
                    </div>
                    <div class="form-group">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="reqName" class="control-label col-md-3"> No Order</label>
                                <div class="col-md-8">
                                    <input type="text" class="easyui-validatebox textbox form-control" name="reqNoOrder" id="reqNoOrder" value="<?= $reqNoOrder ?>" style=" width:100%" disabled />
                                </div>
                                <br>
                                <br>
                                <br>
                                <label for="reqName" class="control-label col-md-3"> Date Of Service</label>
                                <div class="col-md-8">
                                    <input type="text" class="easyui-datebox textbox form-control" name="reqDateOfService" id="reqDateOfService" value="<?= $reqDateOfService ?>" style=" width:100%" />
                                </div>
                                <br>
                                <br>
                                <br>
                                <label for="reqName" class="control-label col-md-3">Location</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="reqDestination" cols="4" rows="3" style="width:100%;"><?= $reqDestination; ?></textarea>
                                </div>
                            </div>
                        </div>


                        <label for="reqPhone" class="control-label col-md-2">Type Of Service</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?

                                $arrDatas  = array(
                                    "Bolland Pull Test",
                                    "Commercial Driver Course Certificate",
                                    "Crane Load Test",
                                    "David Load Test",
                                    "David Load Test / Life Boat Repair",
                                    "Hull Damage Inspection ,Welding",
                                    "Hull Repair / Running Hull Repair",
                                    "Hull Button Cleaning Inspection",
                                    "Instalation Zinc Anode",
                                    "Leg on Jack Up, Cleaning Inspection",
                                    "Magnetic Particle Exmination",
                                    "Maintance Repair Damage",
                                    "Maintance Repair Single Moon",
                                    "Maintance Of Harbours Facility",
                                    "Man Power",
                                    "Piping Inspection Repair",
                                    "Platform Maintance",
                                    "Propeller Tail Shaft _Rudder Inspection",
                                    "Remove _install Zink Anode Prototype",
                                    "See Trial Test",
                                    "Seachest Cleaning Inspection",
                                    "Ultrasonic Measurement ( FLow Diagram )",
                                    "Ultrasonic Thickness Gauging",
                                    "Underwater Cleaning"

                                );
                                ?>
                                <div class="col-md-11">
                                    <div style="background-color: white;
                                    height: 200px;
                                    width: 100%;
                                    overflow-y: scroll;">
                                        <table style="width: 100%;color: black;font-weight: bold;" valign="top">
                                            <?
                                            for ($i = 0; $i < count($arrDatas); $i++) {
                                                $checked = '';
                                                $reqTypeOfServices = explode(",", $reqTypeOfService);
                                                for ($j = 0; $j < count($reqTypeOfServices); $j++) {
                                                    if ($arrDatas[$i] == $reqTypeOfServices[$j]) {
                                                        $checked = "checked";
                                                    }
                                                }

                                            ?>

                                                <tr>
                                                    <td style="padding: 5px"><input type="checkbox" class="form-control" name="reqTypeOfService[]" value="<?= $arrDatas[$i] ?>" <?= $checked ?>> </td>
                                                    <td style="padding: 5px"> <?= $arrDatas[$i] ?> </td>
                                                </tr>
                                            <?
                                            }
                                            ?>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-3" style="margin-left: -165px;">Scope of Work</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <textarea class="form-control" name="reqScopeOfWork" cols="4" rows="3" style="width:100%;"><?= $reqScopeOfWork; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-3" style="margin-left: -165px;"> Term and Condition </label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <textarea class="form-control tinyMCES"  name="reqTermAndCondition" cols="4" rows="3" style="width:100%;"><?= $reqTermAndCondition; ?></textarea>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-3" style="margin-left: -165px;"> Payment Method </label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <textarea class="form-control tinyMCES" name="reqPaymentMethod" cols="4" rows="3" style="width:100%;"><?= $reqPaymentMethod; ?></textarea>
                                </div>

                            </div>
                        </div>
                    </div>



                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-3" style="margin-left: -165px;"> Total Price </label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-2">

                                            <?
                                            $reqTotalPrice = explode(" ", $reqTotalPrice);
                                            ?>
                                            <input class="easyui-combobox form-control" style="width:100%" id="reqVasselCurrency" name="reqVasselCurrency" data-options="width:'90',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueDollar'" value="<?= $reqTotalPrice[0] ?>" />
                                        </div>

                                        <div class="col-md-3">
                                            <input name="reqTotalPrice1" id="reqTotalPrice1" onkeyup="numberWithCommas('reqTotalPrice1');nominal_kekata()" onchange="numberWithCommas('reqTotalPrice1');nominal_kekata()" value="<?= currencyToPage2($reqTotalPrice[1]) ?>" type="text" class="easyui-validatebox textbox form-control">
                                        </div>
                                        <div class="col-md-7">
                                            <input name="reqTotalPriceWord" id="reqTotalPriceWord" value="<?= $reqTotalPriceWord ?>" type="text" class="easyui-validatebox textbox form-control">
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Service Status</h3>
                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Status</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqStatus" data-options="width:'150',editable:false, valueField:'id',textField:'text',url:'combo_json/status'" value="<?= $reqStatus ?>" />
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Reason </label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input name="reqReason" value="<?= $reqReason ?>" type="text" class="easyui-validatebox textbox form-control">
                                </div>
                            </div>
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

    <script>
        function submitForm() {
            var win = $.messager.progress({
                title: 'Office Management  | PT Aquamarine Divindo',
                msg: 'proses data...'
            });

            $('#ff').form('submit', {
                url: 'web/offer_json/add_new',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    var datas = data.split('-');
                    show_toast('info', 'Information', 'Header success added <br>' + datas[1]);
                    // alert(data);
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/offer_add?reqId=" + datas[0]);
                    $.messager.progress('close');
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function() {
                // tinymce.remove();
            }, 1000);
        });
    </script>

    <script type="text/javascript">
        function company_vessel(id, name, type, clas) {
            $('#reqVesselId').val(id);
            $('#reqVesselName').val(name);
            $('#reqClassOfVessel').combobox('setValue', clas);
            $('#reqTypeOfVessel').combobox('setValue', type);
        }
    </script>

    <script type="text/javascript">
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

        function openCompany() {
            openAdd('app/loadUrl/app/template_load_company_id');

        }

        function send_email() {
            var bahasa = $('input[name="bahasa"]:checked').val();
            var id = $("#reqId").val();
            if (id == '') {
                $.messager.alert('Info', 'Please complate offer ', 'info');
                return;
            }
            var win = $.messager.progress({
                title: 'Office Management  | PT Aquamarine Divindo',
                msg: 'proses data...'
            });
            $.get("web/offer_json/sending_mail?reqId=" + id + "&reqBahasa=" + bahasa, function(data) {

                $.messager.alert('Info', data, 'info');
                $.messager.progress('close');
            });

        }

        function openVessel() {
            var companyId = $("#reqCompanyId").val();
            openAdd('app/loadUrl/app/template_load_vessel_id?reqCompanyId=' + companyId);
        }


        function loadAttacment() {
            openAdd('app/loadUrl/app/tempalate_attacment?reqOfferId=<?= $reqId ?>');
        }


        function cetakPdf() {
            var url = "app/loadUrl/report/template_report_offer_pdf?reqId=<?= $reqId ?>";
            var bahasa = $('input[name="bahasa"]:checked').val();
            if (bahasa == 'eng') {
                url = "app/loadUrl/report/template_report_offer_en_pdf?reqId=<?= $reqId ?>";
            }
            openAdd(url);
        }


        function tambahPenyebab() {

            $.get("app/loadUrl/app/tempalate_file?", function(data) {
                $("#tambahAttacment").append(data);
            });
        }
        // $( document ).ready(function() {
        //     $('input[type=file]').change(function () {
        //         console.log(this.files[0])
        //   })
        // });
    </script>
    <script type="text/javascript" src="libraries/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript">
        $('#reqVasselCurrency').combobox({
            onSelect: function(param) {
                nominal_kekata();
            }
        });

        function nominal_kekata() {
            var currenct = $('#reqVasselCurrency').combobox('getValue');
            var reqTotal = $("#reqTotalPrice1").val();
            var xx = reqTotal.replaceAll('.', '');
            if (xx == '') {
                xx = 0;
            }
            var text = '';
            if (currenct == 'IDR') {
                text = kekata_indo(xx) + " Rupiah ";
            } else {
                text = kekata_english(xx) + " Dollar ";
            }


            $("#reqTotalPriceWord").val(text);


        }
    </script>

    <script type="text/javascript">
        function terbilang(bilangan) {

            bilangan = String(bilangan);
            var angka = new Array('0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
            var kata = new Array('', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan');
            var tingkat = new Array('', 'Ribu', 'Juta', 'Milyar', 'Triliun');

            var panjang_bilangan = bilangan.length;

            /* pengujian panjang bilangan */
            if (panjang_bilangan > 15) {
                kaLimat = "Diluar Batas";
                return kaLimat;
            }

            /* mengambil angka-angka yang ada dalam bilangan, dimasukkan ke dalam array */
            for (i = 1; i <= panjang_bilangan; i++) {
                angka[i] = bilangan.substr(-(i), 1);
            }

            i = 1;
            j = 0;
            kaLimat = "";


            /* mulai proses iterasi terhadap array angka */
            while (i <= panjang_bilangan) {

                subkaLimat = "";
                kata1 = "";
                kata2 = "";
                kata3 = "";

                /* untuk Ratusan */
                if (angka[i + 2] != "0") {
                    if (angka[i + 2] == "1") {
                        kata1 = "Seratus";
                    } else {
                        kata1 = kata[angka[i + 2]] + " Ratus";
                    }
                }

                /* untuk Puluhan atau Belasan */
                if (angka[i + 1] != "0") {
                    if (angka[i + 1] == "1") {
                        if (angka[i] == "0") {
                            kata2 = "Sepuluh";
                        } else if (angka[i] == "1") {
                            kata2 = "Sebelas";
                        } else {
                            kata2 = kata[angka[i]] + " Belas";
                        }
                    } else {
                        kata2 = kata[angka[i + 1]] + " Puluh";
                    }
                }

                /* untuk Satuan */
                if (angka[i] != "0") {
                    if (angka[i + 1] != "1") {
                        kata3 = kata[angka[i]];
                    }
                }

                /* pengujian angka apakah tidak nol semua, lalu ditambahkan tingkat */
                if ((angka[i] != "0") || (angka[i + 1] != "0") || (angka[i + 2] != "0")) {
                    subkaLimat = kata1 + " " + kata2 + " " + kata3 + " " + tingkat[j] + " ";
                }

                /* gabungkan variabe sub kaLimat (untuk Satu blok 3 angka) ke variabel kaLimat */
                kaLimat = subkaLimat + kaLimat;
                i = i + 3;
                j = j + 1;

            }

            /* mengganti Satu Ribu jadi Seribu jika diperlukan */
            if ((angka[5] == "0") && (angka[6] == "0")) {
                kaLimat = kaLimat.replace("Satu Ribu", "Seribu");
            }

            return kaLimat + "Rupiah";
        }
    </script>
</div>