<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$reqId = $this->input->get('reqId');

// $this->load->model("Slider");
// $slider = new Slider();

// $reqId = $reqParse1;
// $slider->selectByParams(array("A.SLIDER_ID" => $reqId));
// $slider->firstRow();

?>
<!--
==================== Respmail ====================
Respmail is a response HTML email designed to work
on all major devices and responsive for smartphones
that support media queries.
** NOTE **
This template comes with a lot of standard features
that has been thoroughly tested on major platforms
and devices, it is extremely flexible to use and
can be easily customized by removing any row that
you do not need.
it is gauranteed to work 95% without any major flaws,
any changes or adjustments should thoroughly be
tested and reviewed to match with the general
structure.
** Profile **
Licensed under MIT (https://github.com/charlesmudy/responsive-html-email-template/blob/master/LICENSE)
Designed by Shina Charles Memud
Respmail v1.2 (http://charlesmudy.com/respmail/)
** Quick modification **
We are using width of 500 for the whole content,
you can change it any size you want (e.g. 600).
The fastest and safest way is to use find & replace
Sizes: [
		wrapper   : '500',
		columns   : '210',
		x-columns : [
						left : '90',
						right: '350'
				]
		}
	-->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="format-detection" content="telephone=no" /> <!-- disable auto telephone linking in iOS -->
    <title>Report Pdf </title>

    <script type='text/javascript' src="libraries/bootstrap/js/jquery-1.12.4.min.js"></script>


    <!-- DATATABLE -->
    <link rel="stylesheet" type="text/css" href="libraries/DataTables-1.10.7/extensions/Responsive/css/dataTables.responsive.css">
    <link rel="stylesheet" type="text/css" href="libraries/DataTables-1.10.7/examples/resources/syntax/shCore.css">
    <link rel="stylesheet" type="text/css" href="libraries/DataTables-1.10.7/examples/resources/demo.css">

    <script type="text/javascript" language="javascript" src="libraries/DataTables-1.10.7/media/js/jquery.js"></script>
    <script type="text/javascript" language="javascript" src="libraries/DataTables-1.10.7/media/js/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="libraries/DataTables-1.10.7/media/js/fnReloadAjax.js"></script>
    <script type="text/javascript" language="javascript" src="libraries/DataTables-1.10.7/extensions/Responsive/js/dataTables.responsive.js"></script>
    <script type="text/javascript" language="javascript" src="libraries/DataTables-1.10.7/examples/resources/syntax/shCore.js"></script>
    <script type="text/javascript" language="javascript" src="libraries/DataTables-1.10.7/examples/resources/demo.js"></script>

    <!--// plugin-specific resources //-->
    <script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
    <script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
    <script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
    <link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />
    <!-- EASYUI 1.4.5 -->
    <link rel="stylesheet" type="text/css" href="libraries/easyui/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="libraries/easyui/themes/icon.css">
    <script type="text/javascript" src="libraries/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="libraries/easyui/globalfunction.js"></script>
    <script type="text/javascript" src="libraries/easyui/kalender-easyui.js"></script>

<body>

    <center>
        <div class="row">
            <div class="col">
                <img src="<?= base_url(); ?>/images/aquamarine-logo.png" alt="">
            </div>
        </div>
    </center>

    <br>

    <center>
        <div class="row">
            <div class="col">
                <table>
                    <tr>
                        <td style="width: 100px;">Ref No Order</td>
                        <td style="width: 25px;">:</td>
                        <td style="width: 100px;">NO_ORDER</td>
                        <td style="width: 175px;"></td>
                        <td style="width: 143px;"></td>
                        <td style="width: 145px;"></td>
                        <td style="width: 100px;">Sidoarjo,</td>
                        <td style="width: 25px;"></td>
                        <td style="width: 100px;">DATE_OF_ORDER</td>
                    </tr>
                </table>
            </div>
        </div>
    </center>

    <br>

    <center>
        <div class="row">
            <div class="col">
                <table>
                    <tr>
                        <td style="width: 100px;">Dear.</td>
                        <td style="width: 25px;"></td>
                        <td style="width: 100px;"></td>
                        <td style="width: 175px;"></td>
                        <td style="width: 25px;"></td>
                        <td style="width: 175px;"></td>
                        <td style="width: 100px;"></td>
                        <td style="width: 25px;"></td>
                        <td style="width: 100px;"></td>
                    </tr>
                    <tr>
                        <td style="width: 100px;"></td>
                        <td style="width: 100px;">CONTACT_PERSON</td>
                        <td style="width: 100px;"></td>
                        <td style="width: 175px;"></td>
                        <td style="width: 25px;"></td>
                        <td style="width: 175px;"></td>
                        <td style="width: 100px;"></td>
                        <td style="width: 25px;"></td>
                        <td style="width: 100px;"></td>
                    </tr>

                    <tr>
                        <td style="width: 100px;"></td>
                        <td style="width: 100px;">COMPANY_NAME</td>
                        <td style="width: 100px;"></td>
                        <td style="width: 175px;"></td>
                        <td style="width: 25px;"></td>
                        <td style="width: 175px;"></td>
                        <td style="width: 100px;"></td>
                        <td style="width: 25px;"></td>
                        <td style="width: 100px;"></td>
                    </tr>

                    <tr>
                        <td style="width: 100px;"></td>
                        <td style="width: 100px;">M: MOBILE</td>
                        <td style="width: 100px;"></td>
                        <td style="width: 175px;"></td>
                        <td style="width: 25px;"></td>
                        <td style="width: 175px;"></td>
                        <td style="width: 100px;"></td>
                        <td style="width: 25px;"></td>
                        <td style="width: 100px;"></td>
                    </tr>

                </table>
            </div>
        </div>
    </center>

    <br>

    <center>
        <div class="row">
            <div class="col">
                <table>
                    <tr>
                        <td style="width: 111px;">Sincerely,</td>
                        <td style="width: 25px;"></td>
                        <td style="width: 100px;"></td>
                        <td style="width: 175px;"></td>
                        <td style="width: 133px;"></td>
                        <td style="width: 175px;"></td>
                        <td style="width: 100px;"></td>
                        <td style="width: 25px;"></td>
                        <td style="width: 100px;"></td>
                    </tr>
                </table>
            </div>
        </div>
    </center>

    <center>
        <div class="row">
            <div class="col">
                <table>
                    <tr>
                        <td style="width: 988px;" align="justify">Thank you for the trust of COMPANY_NAME to use TYPE_OF_SURVEY work in our company, based on information by phone, we hereby offer prices according to the details below:</td>
                    </tr>
                </table>
            </div>
        </div>
    </center>

    <br>

    <center>
        <div class="row">
            <div class="col">
                <table>
                    <tr>
                        <td style="width: 120px;">Name of Client</td>
                        <td style="width: 25px;">:</td>
                        <td style="width: 350px;">COMPANY_NAME</td>
                        <td style="width: 476px;"></td>
                    </tr>
                    <tr>
                        <td style="width: 120px;">Name Of Vessel</td>
                        <td style="width: 25px;">:</td>
                        <td style="width: 350px;">VESSEL_NAME </td>
                        <td style="width: 476px;"></td>
                    </tr>
                    <tr>
                        <td style="width: 120px;">Type Of Vessel</td>
                        <td style="width: 25px;">:</td>
                        <td style="width: 350px;">TYPE_OF_VESSEL</td>
                        <td style="width: 476px;"></td>
                    </tr>
                    <tr>
                        <td style="width: 120px;">Class</td>
                        <td style="width: 25px;">:</td>
                        <td style="width: 350px;">CLASS_OF_VESSEL</td>
                        <td style="width: 476px;"></td>
                    </tr>
                    <tr>
                        <td style="width: 120px;">Destination</td>
                        <td style="width: 25px;">:</td>
                        <td style="width: 350px;">DESTINATION_OF_SERVICE</td>
                        <td style="width: 476px;"></td>
                    </tr>
                    <tr>
                        <td style="width: 120px;">Date of Service</td>
                        <td style="width: 25px;">:</td>
                        <td style="width: 350px;">Tentative DATE_OF_SERVICE1</td>
                        <td style="width: 476px;"></td>
                    </tr>
                    <tr>
                        <td style="width: 120px;">Type of Service</td>
                        <td style="width: 25px;">:</td>
                        <td style="width: 350px;">TYPE_OF_SURVEY</td>
                        <td style="width: 476px;"></td>
                    </tr>
                    <tr>
                        <td style="width: 120px;">Price </td>
                        <td style="width: 25px;">:</td>
                        <td style="width: 350px;">IDR. TOTAL_PRICE / Vessel (TOTAL_WORD)</td>
                        <td style="width: 476px;"></td>
                    </tr>
                </table>
            </div>
        </div>
    </center>

    <br>

    <center>
        <div class="row">
            <div class="col">
                <table>
                    <tr>
                        <td style="width: 140px;">Scope of Work</td>
                        <td style="width: 25px;">:</td>
                        <td style="width: 812px;"></td>
                    </tr>
                </table>
            </div>
        </div>
    </center>

    <center>
        <div class="row">
            <div class="col">
                <table>
                    <tr>
                        <td style="width: 50px;"></td>
                        <td style="width: 140px;">SCOPE_OF_WORK</td>
                        <td style="width: 787px;"></td>
                    </tr>
                </table>
            </div>
        </div>
    </center>

    <center>
        <div class="row">
            <div class="col">
                <table>
                    <tr>
                        <td style="width: 140px;">Term & Conditions</td>
                        <td style="width: 25px;">:</td>
                        <td style="width: 812px;"></td>
                    </tr>
                </table>
            </div>
        </div>
    </center>

    <center>
        <div class="row">
            <div class="col">
                <table>
                    <tr>
                        <td style="width: 50px;"></td>
                        <td style="width: 140px;">TERM_AND_CONDITION</td>
                        <td style="width: 744px;"></td>
                    </tr>
                </table>
            </div>
        </div>
    </center>

    <center>
        <div class="row">
            <div class="col">
                <table>
                    <tr>
                        <td style="width: 140px;">Payment method</td>
                        <td style="width: 25px;">:</td>
                        <td style="width: 812px;"></td>
                    </tr>
                </table>
            </div>
        </div>
    </center>

    <center>
        <div class="row">
            <div class="col">
                <table>
                    <tr>
                        <td style="width: 50px;"></td>
                        <td style="width: 140px;">PAYMENT_METHOD</td>
                        <td style="width: 776px;"></td>
                    </tr>
                </table>
            </div>
        </div>
    </center>

    <br>

    <center>
        <div class="row">
            <div class="col">
                <table>
                    <tr>
                        <td style="width: 988px;" align="justify">Thank you for your attention, we hope that there will be good cooperation between our company and COMPANY_NAME, ask for a decision and to immediately send a Working Order (WO).</td>
                    </tr>
                </table>
            </div>
        </div>
    </center>

    <br>

    <center>
        <div class="row">
            <div class="col">
                <table>
                    <tr>
                        <td style="width: 140px;">Best Regards,</td>
                        <td style="width: 25px;"></td>
                        <td style="width: 812px;"></td>
                    </tr>
                </table>
            </div>
        </div>
    </center>

    <br>
    <br>
    <br>
    <br>

    <center>
        <div class="row">
            <div class="col">
                <table>
                    <tr>
                        <td style="width: 140px;">Eva Yuli</td>
                        <td style="width: 25px;"></td>
                        <td style="width: 812px;"></td>
                    </tr>
                </table>
            </div>
        </div>
    </center>

    <center>
        <div class="row">
            <div class="col">
                <table>
                    <tr>
                        <td style="width: 140px;">Marketing</td>
                        <td style="width: 25px;"></td>
                        <td style="width: 812px;"></td>
                    </tr>
                </table>
            </div>
        </div>
    </center>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <center>
        <div class="row">
            <div class="col">
                <table>
                    <tr>
                        <td style="width: 200px;">Head Office And Workshop</td>
                        <td style="width: 25px;">:</td>
                        <td style="width: 749px;"></td>
                    </tr>
                </table>
            </div>
        </div>
    </center>
    <center>
        <div class="row">
            <div class="col">
                <table>
                    <tr>
                        <td style="width: 350px;">KOMPLEK PERGUDANGAN 88 BLOK C5-C7</td>
                        <td style="width: 25px;"></td>
                        <td style="width: 599px;"></td>
                    </tr>
                </table>
            </div>
        </div>
    </center>
    <center>
        <div class="row">
            <div class="col">
                <table>
                    <tr>
                        <td style="width: 500px;">Jl Raya Sedati Gede No. 88 Sidoarjo, East Java - Indonesia , 61253</td>
                        <td style="width: 25px;"></td>
                        <td style="width: 449px;"></td>
                    </tr>
                </table>
            </div>
        </div>
    </center>
    <center>
        <div class="row">
            <div class="col">
                <table>
                    <tr>
                        <td style="width: 200px;">Phone</td>
                        <td style="width: 25px;">:</td>
                        <td style="width: 749px;">+628 123 123 123 (Hunting)</td>
                    </tr>
                </table>
            </div>
        </div>
    </center>
    <center>
        <div class="row">
            <div class="col">
                <table>
                    <tr>
                        <td style="width: 200px;">Fax</td>
                        <td style="width: 25px;">:</td>
                        <td style="width: 749px;">+628 123 123 123 </td>
                    </tr>
                </table>
            </div>
        </div>
    </center>
    <center>
        <div class="row">
            <div class="col">
                <table>
                    <tr>
                        <td style="width: 200px;">Email</td>
                        <td style="width: 25px;">:</td>
                        <td style="width: 749px;">inspection@aquamarinedivindo.com</td>
                    </tr>
                </table>
            </div>
        </div>
    </center>
    <center>
        <div class="row">
            <div class="col">
                <table>
                    <tr>
                        <td style="width: 200px;">Website</td>
                        <td style="width: 25px;">:</td>
                        <td style="width: 749px;">www.aquamarinedivindo.com</td>
                    </tr>
                </table>
            </div>
        </div>
    </center>

</body>

</html>