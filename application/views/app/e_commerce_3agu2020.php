<div class="col-md-12">

	<div class="judul-halaman"> Send Template</div>

	<!--<div class="judul-halaman-bawah">&nbsp;</div>-->
	<div class="konten-area">
		<div id="bluemenu" class="aksi-area" style="margin-left: 35px">
			<span><a id="btnAdd" onclick="add_email_template()"><i class="fa fa-fw fa-envelope-o" aria-hidden="true" ></i> Add Email Template</a></span>
			<span><a id="btnEdit" onclick="edit_email_template()"><i class="fa fa-fw fa-file-text" aria-hidden="true"></i> Edit Email Template</a></span>
			<span><a id="btnDelete" onclick="load_email_template()" ><i class="fa fa-fw fa-folder" aria-hidden="true"></i> Load Template</a></span>
			<!-- <span><a id="btnPosting"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> Posting</a></span> -->
		</div>




		<div class="col-md-12">


			<form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

				<table style="width: 93%;padding: 20px;color: white;margin-right: 20px;margin-left: 20px">
					<tr>
						<td rowspan="3" style="width: 100px;padding: 10px;height: 200px">
							<button type="button" onclick="load_company()" class="btn btn-success" style="width: 100%;height: 100%"><i class="fa fa-envelope-o"></i> Send </button> </td>
						<td style="width: 100px"> <button class="btn btn-default" type="button" onclick="load_company()"> Name</button> </td>
						<td> <input type="text" class="form-control" name="reqName" id="reqName" disabled="" />

								 <input type="hidden" class="form-control" name="reqIdCompany" id="reqIdCompany" disabled="" />
						 </td>
					</tr>

					<tr>
						<td><button type="button" class="btn btn-default"> CC</button></td>
						<td><input type="text" class="form-control" name="" /></td>
					</tr>
					<tr>
						<td>Subject</td>
						<td><input type="text" class="form-control" name="" />
							<input type="hidden" name="reqId" id="reqId" value="">
						</td>
					</tr>


				</table>


				<div class="form-group">

					<div class="col-md-12">
						<div class="form-group">
							<div class="col-md-12">
								<div id="add_load">
								<textarea name="reqDescription" id="reqDescription" style="width: 100%;height: 400px" class="tinyMCES" ><?= $reqDescription; ?></textarea>
							</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>



	</div>

	<script type="text/javascript">
		
		function add_email_template(){
			openAdd('app/loadUrl/app/template_email_template');
		}
		function edit_email_template(){
			var reqId =$("#reqId").val();

			if(reqId==''){
					return;
			}
			openAdd('app/loadUrl/app/template_email_template?reqId='+reqId);
		}
		function load_company(){
			openAdd('app/loadUrl/app/template_load_company');
		}
		function load_email_template(){
			openAdd('app/loadUrl/app/template_load_email');
		}
		$( document ).ready(function() {
					// $('#reqDescription').html('Test Data Arik');
					// tinymce.get("reqDescription").setContent('Test Data Arik');
					// tinymce.get('title').setContent('<p>This is my new content!</p>');
					});
	</script>

	<script type="text/javascript">
			
			function company_pilihan(kode){
				$('#reqIdCompany').val(kode);
					$.get("web/customer_json/get_company_name?reqId="+kode, function (data) {
						$("#reqName").val(data);
						}); 
			}

			function terpilih_template(id){

				$("#reqId").val(id);
				$.get("web/template_email_json/load_template_body?reqId="+id, function (data) {
					
					$(tinymce.get('reqDescription').getBody()).html(data);
					
					
				}); 

			}

	</script>