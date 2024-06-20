<?
$reqKode = $this->input->post("reqKode");

if (!empty($reqKode)) {
?>
	<script type="text/javascript">
		$(document).ready(function() {
			company_pilihan('<?= $reqKode ?>', 1);
		});
	</script>
<?

}
$reqDescription = '<p><img src="https://i.imgur.com/p8uMT4E.png" alt="" /></p><p><a href="http://aqumarine.id">http://aqumarine.id</a></p>';
?>

<div class="col-md-12">
	<div class="judul-halaman"> Website</div>

	<!--<div class="judul-halaman-bawah">&nbsp;</div>-->
	<div class="konten-area">
		<div id="bluemenu" class="aksi-area">
			<!-- <span><a id="btnAdd" onclick="add_email_template()"><i class="fa fa-fw fa-envelope-o" aria-hidden="true"></i> Add Email Template</a></span>
			<span><a id="btnEdit" onclick="edit_email_template()"><i class="fa fa-fw fa-file-text" aria-hidden="true"></i> Edit Email Template</a></span>
			<span><a id="btnDelete" onclick="load_email_template()"><i class="fa fa-fw fa-folder" aria-hidden="true"></i> Load Template</a></span>
			<span><a id="btnLoadAttacment" onclick="load_attacment_template()"><i class="fa fa-fw fa-folder" aria-hidden="true"></i> Add Attacment</a></span> -->
			<span><a id="btnLoadAttacment" onclick="load_history()"><i class="fa fa-fw fa-folder" aria-hidden="true"></i> History</a></span>
			<!-- <span><a id="btnPosting"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> Posting</a></span> -->
			<button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>
			<button type="button" class="btn btn-default pull-right " style="margin-right: 10px" onclick="clearForm()"><i class="fa fa-refresh fa-lg"></i> <b>Clear</b></button>
			<br>
			<br>
		</div>

		<div class="col-md-12">
			<form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">
				<table>
					<tr>
						<td rowspan="4" style="width: 100px; padding: 10px 10px 10px 0px; height: 200px">
							<button type="button" onclick="submitForm()" class="btn btn-success" style="width: 100%;height: 100%"><i class="fa fa-envelope-o"></i> Send </button> </td>
						<td style="width: 100px"> <button class="btn btn-default" type="button" onclick="load_company(1)"> Name</button> </td>
						<td style="width: 800px"> <input type="text" class="form-control tagsinput" name="reqName1" id="reqName1" style="width: 100%" placeholder="ex: Bpk Aqumarine [aqumarine@gmail.com],Ibu Aqumarine [ aqumarine@yahoo.com ]  ( , ) to space" />

							<input type="hidden" class="form-control" name="reqIdCompany1" id="reqIdCompany1" disabled="" />
						</td>
					</tr>

					<tr>
						<td><button type="button" class="btn btn-default" onclick="load_company(2)"> CC</button></td>
						<td><input type="text" class="form-control tagsinput" name="reqName2" id="reqName2" disabled="" placeholder="ex: Bpk Aqumarine [aqumarine@gmail.com],Ibu Aqumarine [ aqumarine@yahoo.com ]  ( , ) to space" />
							<!-- <input type="text" class="form-control tagsinput" name="reqName3" id="reqName3" disabled="" placeholder="Place email manualy in here ex: aqumarine@gmail.com [Bpk Aqumarine],aqumarine@yahoo.com [ Ibu Aqumarine ]  ( , ) to space" />
 -->
							<input type="hidden" class="form-control" name="reqIdCompany2" id="reqIdCompany2" disabled="" /></td>
					</tr>
					<tr>
						<td><button type="button" class="btn btn-default" onclick="load_company(3)"> BCC</button></td>
						<td><input type="text" class="form-control tagsinput" name="reqName3" id="reqName3" disabled="" placeholder="ex: Bpk Aqumarine [aqumarine@gmail.com],Ibu Aqumarine [ aqumarine@yahoo.com ]  ( , ) to space" />
							<!-- <input type="text" class="form-control tagsinput" name="reqName3" id="reqName3" disabled="" placeholder="Place email manualy in here ex: aqumarine@gmail.com [Bpk Aqumarine],aqumarine@yahoo.com [ Ibu Aqumarine ]  ( , ) to space" />
 -->
							<input type="hidden" class="form-control" name="reqIdCompany3" id="reqIdCompany3" disabled="" /></td>
					</tr>
					<tr>
						<td><label>Subject</label></td>
						<td><input type="text" class="form-control" name="reqSubject" />
							<input type="hidden" name="reqId" id="reqId" value="">
						</td>
					</tr>


				</table>
				<div class="form-group">
					<label class="control-label col-md-2">Attactments</label>
					<div class="col-md-8">
						<div class="form-group">
							<div class="col-md-12">
								<div>
									<input type="hidden" id="reqIdLampiran" name="reqIdLampiran">
								</div>
								<div id="addLampiran">

								</div>
							</div>
						</div>
					</div>


					<div class="form-group">

						<div class="col-md-12">
							<div class="form-group">
								<div class="col-md-12">
									<div id="add_load">
										<textarea name="reqDescription" id="reqDescription" style="width: 100%;height: 400px" class="tinyMCES"><?= $reqDescription; ?></textarea>
									</div>
								</div>
							</div>
						</div>
					</div>

			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	function add_email_template() {
		openAdd('app/loadUrl/app/template_email_template');
	}

	function edit_email_template() {
		var reqId = $("#reqId").val();

		if (reqId == '') {
			return;
		}
		openAdd('app/loadUrl/app/template_email_template?reqId=' + reqId);
	}

	function load_company(id) {
		openAdd('app/loadUrl/app/template_load_company?reqId=' + id);
	}

	function load_email_template() {
		openAdd('app/loadUrl/app/template_load_email');
	}

	function load_history() {
		openAdd('app/loadUrl/app/template_load_website');
	}
	$(document).ready(function() {

		// $('#reqName').tagsinput('add', 'some tag', {preventPost: true});
		// $('#reqName1').tagsinput('add','asd');
		// $('#reqName1').tagsinput('add', 'A');
		// $('#reqDescription').html('Test Data Arik');
		// tinymce.get("reqDescription").setContent('Test Data Arik');
		// tinymce.get('title').setContent('<p>This is my new content!</p>');
	});
</script>

<script type="text/javascript">
	function company_pilihan(kode, ids) {
		$('#reqIdCompany' + ids).val(kode);
		// tagsinput('add', value);


		$.get("web/customer_json/get_company_name?reqId=" + kode, function(data) {
			// $("#reqName" + ids).tagsinput('add', { "value": 1 , "text": "Amsterdam"      });
			// console.log(data);
			$(document).ready(function() {
				$('#reqName' + ids).tagsinput('add', data);
				// $('#reqName1').val('Arik,test');
				// $("#reqName" + ids).val(String(data));
			});
		});
	}

	function terpilih_template(id) {

		$("#reqId").val(id);
		$.get("web/template_email_json/load_template_body?reqId=" + id, function(data) {

			$(tinymce.get('reqDescription').getBody()).html(data);
		});
	}

	function clearForm() {
		$('#ff').form('clear');
		$(tinymce.get('reqDescription').getBody()).html('');
	}
</script>

<script type="text/javascript">
	function submitForm() {
		var win = $.messager.progress({
			title: 'Office Management  | PT Aquamarine Divindo',
			msg: 'proses data...'
		});
		$('#ff').form('submit', {
			url: 'web/customer_json/sending_mail_website',
			onSubmit: function() {
				return $(this).form('enableValidation').form('validate');
			},
			success: function(data) {
				var datas = data.split('-');
				$.messager.progress('close');
				// alert(data);
				$.messager.alertLink('Info', data, 'info', "app/index/website?");
			}
		});
	}
</script>

<script type="text/javascript">
	function addLampiran(kode) {
		// console.log(kode);
		var kodes = $("#reqIdLampiran").val();
		kodes = kodes + kode;
		var arrVal = kodes.split(',');
		var unixKode = [];
		$.each(arrVal, function(i, el) {
			if ($.inArray(el, unixKode) === -1) unixKode.push(el);
		});
		// console.log(unixKode);
		var kodei = '';
		for (var i = 0; i < unixKode.length; i++) {
			if (unixKode[i] != '') {
				kodei = kodei + unixKode[i] + ",";
			}
		}

		$("#addLampiran").empty();
		$("#reqIdLampiran").val(kodei);
		$.get("web/document_attacment_json/add_path_lampiran?reqId=" + kodei, function(data) {

			$("#addLampiran").append(data);
		});

	}
</script>