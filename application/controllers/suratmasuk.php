<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SuratMasuk extends CI_Controller {

	function __construct() {
		parent::__construct();
		if ( ! $this->session->userdata('is_logged_in')) {
			redirect('login');
		}
		$this->load->model('surat_masuk');
	}
	
	function index() {
		$data["results"] 	= $this->surat_masuk->select();
		$data['page']		= "surat_masuk";
		
		$this->load->view('admin/aaa', $data);
	}

	function add() {
		$data['page'] = "f_surat_masuk";
		$this->load->view('admin/aaa', $data);
	}

	function cari() {
		$key		= $this->input->post('search');
		$results 	= $this->surat_masuk->select($key);
		echo json_encode ($results);
	}

	function cetak() {
		$id = $this->uri->segment(3);
		$a['results'] = $this->surat_masuk->select_by_id($id);
		$this->load->view('admin/cetak_disposisi', $a);
	}

	function delete() {
		$id	= $this->uri->segment(3);
		$this->surat_masuk->delete($id);
		redirect('suratmasuk');
	}

	function do_add($data){
		$this->surat_masuk->add($data);
	}

	function do_edit($id, $data){
		$this->surat_masuk->update($id, $data);
	}

	function edit() {
		$id					= $this->uri->segment(3);
		$data['results']	= $this->surat_masuk->select_by_id($id);
		$data['page']		= "f_surat_masuk";
		$this->load->view('admin/aaa', $data);
	}

	function form_process(){
		$config['upload_path'] 		= './upload/surat_masuk';
		$config['allowed_types'] 	= '*';
		$config['max_size']			= '2000';
		$config['max_width']  		= '3000';
		$config['max_height'] 		= '3000';

		$this->load->library('upload', $config);

		$id	= addslashes($this->input->post('idp'));

		$data = array(
            'indeks' 				=> addslashes($this->input->post('indeks')),
			'kode' 					=> addslashes($this->input->post('kode')),
            'nomor_urut' 			=> addslashes($this->input->post('nomor_urut')),
            'tanggal_penyelesaian' 	=> addslashes($this->input->post('tanggal_penyelesaian')),
            'isi_ringkas'			=> addslashes($this->input->post('isi_ringkas')), 
            'asal' 					=> addslashes($this->input->post('asal')),
            'tanggal_surat'			=> addslashes($this->input->post('tanggal_surat')),
            'nomor_surat' 			=> addslashes($this->input->post('nomor_surat')),
            'lampiran' 				=> addslashes($this->input->post('lampiran')),  
            'diajukan_kepada'	 	=> '',
            'instruksi'			 	=> addslashes($this->input->post('instruksi'))
            );

		$diajukan_kepada_sekretaris 	= addslashes($this->input->post('diajukan_kepada_sekretaris'));
		if(isset($diajukan_kepada_sekretaris)){
			$data['diajukan_kepada'] = $data['diajukan_kepada'].'<p>'.$diajukan_kepada_sekretaris.'</p>';
		}

		$diajukan_kepada_kepala_dinas 	= addslashes($this->input->post('diajukan_kepada_kepala_dinas'));
		if(isset($diajukan_kepada_kepala_dinas)){
			$data['diajukan_kepada'] = $data['diajukan_kepada'].'<p>'.$diajukan_kepada_kepala_dinas.'</p>';
		}

		if ($this->upload->do_upload('file_surat')) {
			$upload_data = $this->upload->data();
			$data['file'] = $upload_data['file_name'];
		}

		if($this->uri->segment(3) == "edit"){
			$this->do_edit($id, $data);
		} else if($this->uri->segment(3) == "add") {
			$this->do_add($data);
		}

		redirect('suratmasuk');
	}

}
/* End of file suratmasuk.php */
/* Location: ./application/controllers/suratmasuk.php */