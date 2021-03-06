<div class="clearfix">
	<div class="row">
		<div class="col-lg-12">
			<div class="navbar navbar-inverse">
				<div class="container">
					<div class="navbar-header">
						<a class="navbar-brand" href="#">Surat Masuk</a>
					</div>
					<div class="navbar-collapse collapse navbar-inverse-collapse" style="margin-right: -20px">
						<ul class="nav navbar-nav">
							<li><a href="<?php echo base_URL(); ?>suratmasuk/add" class="btn-info"><i class="icon-plus-sign icon-white"> </i> Tambah Data</a></li>
						</ul>
						<ul class="nav navbar-nav navbar-right">
							<form class="navbar-form navbar-left" method="post" action="#">
								<?php 
								$options = array(
								      	'indeks' => 'Indeks',
								      	'kode' => 'Kode',
								      	'nomor_urut' => 'Nomor Urut',
								      	'tanggal_penyelesaian' => 'Tanggal Penyelesaian',
								      	'perihal' => 'Perihal',
								      	'isi_ringkas' => 'Isi Ringkas',
								      	'asal' => 'Asal Surat',
								      	'tanggal_surat' => 'Tanggal Surat',
								      	'nomor_surat' => 'Nomor Surat',
								      	'lampiran' => 'Lampiran',
								      	'diajukan_kepada' => 'Diajukan Kepada',
								      	'instruksi' => 'Instruksi'
								    );

								echo form_dropdown('berdasarkan', $options, '', 'id="berdasarkan" class="form-control" style="width: 190px"');
								?>
								<input type="text" class="form-control" name="cari" id="search" style="width: 200px" placeholder="Kata kunci pencarian ..." />
							</form>
						</ul>
					</div><!-- /.nav-collapse -->
				</div><!-- /.container -->
			</div><!-- /.navbar -->
		</div>
	</div>

	<table class="table table-bordered table-hover">

		<thead>
			<tr>
				<th>Indeks</th>
				<th>Kode</th>
				<th>Nomor Urut</th>
				<th>Tanggal Penyelesaian</th>
				<th>Perihal</th>
				<th>Isi Ringkas, File</th>
				<th>Asal Surat</th>
				<th>Tanggal Surat</th>
				<th>Nomor Surat</th>
				<th>Lampiran</th>
				<th width="15%">Diajukan Kepada</th>
				<th>Instruksi</th>
				<th width="1%">Aksi</th>
			</tr>
		</thead>
		
		<tbody id="finalResult"></tbody>

	</table>
</div>
<script>
	$(document).ready(function(){
		doSearch();
	  	$("#search").keyup(function(){
			doSearch($("#search").val());
	  	});
	});

	function doSearch(searchKey){
		var berdasarkan = $("#berdasarkan").val();
		$.ajax({
			type: "post",
			url: "<?php echo base_URL()?>suratmasuk/cari",
			data: { search : searchKey, berdasarkan : berdasarkan },
			success: 
			function(response){
				$('#finalResult').html("");
				var results = JSON.parse(response);
				if(results.length > 0){
					try{
						var items=[]; 	
						$.each(results, function(i,result){
							if(result.file != ""){
								result.file =
								"<br /><b>File : </b><i><a href=\"<?php echo base_URL()?>upload/surat_masuk/" + result.file + "\" target=\"_blank\">" + result.file + "</a>" + 
								"<br /><a href=\"<?php echo base_URL()?>suratmasuk/delete_file/" + result.id + "\" class=\"btn btn-warning btn-sm\" title=\"Hapus Data\" onclick=\"return confirm('Anda Yakin..?')\"><i class=\"icon-trash icon-remove\">  </i> Delete File</a><br />";
							}
							result.diajukan_kepada = "";
							if(result.diajukan_kepada_s) {
								result.diajukan_kepada += "<ul>";
								$.each(result.diajukan_kepada_s, function(i, diajukan_kepada){
									result.diajukan_kepada += "<li>" + diajukan_kepada.tujuan + "</li>";
								});
								result.diajukan_kepada += "</ul>";
							}
						    items.push(
						    	$('<tr/>').html(
						    		"<td>" + result.indeks + "</td>" +
									"<td>" + result.kode + "</td>" +
									"<td>" + result.nomor_urut + "</td>" +
									"<td>" + result.tanggal_penyelesaian + "</td>" +
									"<td>" + result.perihal + "</td>" +
									"<td>" + result.isi_ringkas + result.file + "</td>" +
									"<td>" + result.asal + "</td>" +
									"<td>" + result.tanggal_surat + "</td>" +
									"<td>" + result.nomor_surat + "</td>" +
									"<td>" + result.lampiran + "</td>" +
									"<td>" + result.diajukan_kepada + "</td>" +
									"<td>" + result.instruksi + "</td>" +
									"<td class=\"ctr\">" +
										"<div>" +
											"<a href=\"<?php echo base_URL()?>suratmasuk/edit/" + result.id + "\" class=\"btn btn-success btn-sm\" title=\"Edit Data\"><i class=\"icon-edit icon-white\"> </i> Edit</a><br />" +
											"<a href=\"<?php echo base_URL()?>suratmasuk/delete/" + result.id + "\" class=\"btn btn-warning btn-sm\" title=\"Hapus Data\" onclick=\"return confirm('Anda Yakin..?')\"><i class=\"icon-trash icon-remove\">  </i> Delete</a><br />" +
											"<a href=\"<?php echo base_URL()?>suratmasuk/cetak/" + result.id + "\" class=\"btn btn-info btn-sm\" target=\"_blank\" title=\"Cetak Disposisi\"><i class=\"icon-print icon-white\"> </i> Disposisi</a>" +
											"<a href=\"<?php echo base_URL()?>surattugas/index/" + result.id + "\" class=\"btn btn-success btn-sm\" title=\"Surat Tugas\"><i class=\"icon-print icon-white\"> </i> Surat Tugas</a>" +
										"</div>" +	
									"</td>"
									));
						});	
						$('#finalResult').append.apply($('#finalResult'), items);
					}catch(e) {		
						alert('Exception while request..');
					}		
				}else{
					$('#finalResult').html($('<tr/>').html(
						"<td colspan='13' align='center'>Data tidak ditemukan</td>"
						)
					);		
				}		
				
			},
			error: 
			function(){						
				alert('Error while request..');
			}
		});
	};
</script>

<?php
/* End of file surat_masuk.php */
/* Location: ./application/views/admin/surat_masuk.php */