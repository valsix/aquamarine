<tr>
                                    <td> <input name="reqVasselName[]" type="text" class="easyui-validatebox textbox form-control">
                                        <input name="reqVasselId[]" type="hidden" class="easyui-validatebox textbox form-control"></td>
                                    <td> <input name="reqVasselDimension_l[]" type="number" class="easyui-validatebox textbox form-control"> </td>
                                    <td> <input name="reqVasselDimension_b[]" type="number" class="easyui-validatebox textbox form-control"> </td>
                                    <td> <input name="reqVasselDimension_d[]" type="number" class="easyui-validatebox textbox form-control"> </td>
                                    <td> 
                                        <input class="easyui-combobox form-control combos" style="width:100%" name="reqVasselType_vessel[]"  data-options="width:'90',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueTypeOfVessel'" />

                                        </td>
                                    <td>
                                        <input class="easyui-combobox form-control combos" style="width:100%" name="reqVasselClass_vessel[]"  data-options="width:'90',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueClassOfVessel'" />

                                      </td>
                                    <td> 

                                        <input class="easyui-combobox form-control combos" style="width:100%" name="reqVasselType_survey[]"  data-options="width:'90',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueClassOfSurvey'" />
                                        </td>
                                    <td> <input name="reqVasselLocation_survey[]" type="text" class="easyui-validatebox textbox form-control"> </td>
                                    <td> <input name="reqVasselContact_person[]" type="text" class="easyui-validatebox textbox form-control"> </td>
                                    <td> 
                                        <input class="easyui-combobox form-control combos" style="width:100%" name="reqVasselType_survey[]"  data-options="width:'90',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueDollar'" />
                                        
                                         <input name="reqVasselValue_survey[]" type="number" class="easyui-validatebox textbox form-control" value="<?=$reqValueSurvey?>"> </td>
                                    <td>
                                       

                                        <input name="reqVasselSurveyor_name[]" value="<?=$reqSurveyorName?>" type="text" class="easyui-validatebox textbox form-control">
                                    </td>
                                    <td> <input name="reqVasselSurveyor_phone[]" value="<?=$reqSurveyorPhone?>" type="text" class="easyui-validatebox textbox form-control"> </td>
                                    <td style="text-align: center;">    <a onclick="$(this).parent().parent().remove();" ><i class="fa fa-trash fa-lg"></i></a>   </td>
                                    </tr>
                                    <script type="text/javascript">
                                            $(".combos").combobox();
                                    </script>