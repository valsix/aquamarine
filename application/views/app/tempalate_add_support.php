<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$no = rand();
?>



 <tr>
    <td>
        <input type="hidden" class="form-control" value="" id="reqSupportId<?=$no?>"> 

        <input class="form-control" value="" id="reqSupportName<?=$no?>">  </td>
        <td><input class="form-control" onkeypress='validate(event)' value="" id="reqSupportTelp<?=$no?>"> </td>
        <td><input class="form-control" value="" id="reqSupportEmail<?=$no?>"</td>
        <td id="tdparent<?=$no?>">

            <button type="button" class="btn btn-info " onclick="editing_support(<?=$no?>)"><i class="fa fa-plus fa-lg"> </i> </button>

            <button type="button" class="btn btn-danger hapusi" onclick="$(this).parent().parent().remove()"><i class="fa fa-trash-o fa-lg"> </i> </button> </td>
        </tr>

