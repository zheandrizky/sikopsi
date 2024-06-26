<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pinjam extends My_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->check_login();
        $this->load->model('Pinjam_model');
        $this->load->model('Saham_model');
        $this->load->model('Pengembalian_model');
    }

    public function index()
    {
        $data['title'] = "List Pinjam";
        $data['total_pinjam'] = $this->Pinjam_model->cek_total_pinjam();
        $data['total_bunga'] = $this->Pinjam_model->cek_total_bunga();
        $data['total_pengembalian'] = $this->Pengembalian_model->cek_total_pengembalian();
        $data['pinjam'] = $this->Pinjam_model->get_all_pinjam();
        $this->load->view('admin/template/upper.php', $data);
        $this->load->view('admin/pinjam/list.php', $data);
        $this->load->view('admin/template/lower.php');

        if ($this->session->userdata('jabatan') == 'anggota') {
            $total = $this->Saham_model->cek_total_saham();

            if ($total < 250000) {
                $this->session->set_userdata('message_type', 'info');
                $this->session->set_userdata('message', "Mohon untuk melunasi uang saham terlebih dahulu");
                redirect($_SERVER['HTTP_REFERER']);
            }
        }

    }

    public function add()
    {
        // Generate kode pinjam baru
        $last_kode_pinjam = $this->Pinjam_model->get_last_kode_pinjam();
        $new_kode_pinjam = $this->generate_new_kode_pinjam($last_kode_pinjam);

        // Ambil inputan lain
        $data = array(
            'kode_pinjam' => $new_kode_pinjam,
            'nik' => $this->session->userdata('nik'),
            'jumlah_pinjam' => $this->input->post('jumlah_pinjam'),
            'status_pengajuan_pinjam' => 'diproses',
            'keterangan_pengajuan_pinjam' => 'Saat ini, pembayaran saham Anda sedang diproses untuk diverifikasi. Proses ini memastikan bahwa semua informasi dan dokumen terkait terverifikasi dengan benar',
        );
        $this->Pinjam_model->add_pinjam($data);
        redirect('pinjam');

    }



    private function generate_new_kode_pinjam($last_kode_pinjam)
    {
        $number = (int) substr($last_kode_pinjam, 3) + 1;
        return 'PJM' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    public function update($kode_pinjam)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $config['upload_path'] = './assets/uploads/pinjam/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 1024;

            $this->upload->initialize($config);

            if (!$this->upload->do_upload('bukti_pembayaran')) {
                echo print_r('asjdjk');
                die;
                $data['error'] = $this->upload->display_errors();
                log_message('error', 'Upload Error: ' . $data['error']);
                $this->load->view('admin/template/upper.php', $data);
                $this->load->view('admin/pinjam/add.php', $data);
                $this->load->view('admin/template/lower.php');
            } else {
                $upload_data = $this->upload->data();
                $file_name = $upload_data['file_name'];
                $unique_file_name = time() . '_' . $file_name;

                // Logging before renaming
                log_message('debug', 'Attempting to rename ' . './assets/uploads/pinjam/' . $file_name . ' to ' . './assets/uploads/pinjam/' . $unique_file_name);

                if (rename('./assets/uploads/pinjam/' . $file_name, './assets/uploads/pinjam/' . $unique_file_name)) {
                    // Logging successful rename
                    log_message('debug', 'Successfully renamed to ' . $unique_file_name);

                    // Generate kode pinjam baru
                    $last_kode_pinjam = $this->Pinjam_model->get_last_kode_pinjam();
                    $new_kode_pinjam = $this->generate_new_kode_pinjam($last_kode_pinjam);

                    // Mendapatkan tanggal hari ini
                    $today = date('Y-m-d');

                    // Menambahkan 5 bulan ke tanggal hari ini
                    $jatuh_tempo = date('Y-m-d', strtotime('+5 months', strtotime($today)));
                    // hitung bunga pinjam
                    $jumlah_pinjam = $this->input->post('jumlah_pinjam');
                    $bunga_pinjaman = $jumlah_pinjam * 0.05;
                    // Ambil inputan lain
                    $data = array(
                        'jumlah_pinjam' => $jumlah_pinjam,
                        'status_pengajuan_pinjam' => $this->input->post('status_pengajuan_pinjam'),
                        'keterangan_pengajuan_pinjam' => $this->input->post('keterangan_pengajuan_pinjam'),
                    );

                    if ($this->input->post('status_pengajuan_pinjam') == 'diterima') {
                        $data['tgl_pinjam'] = $today;
                        $data['bukti_peminjaman'] = $unique_file_name;
                        $data['jatuh_tempo'] = $jatuh_tempo;
                        $data['bunga_pinjaman'] = $bunga_pinjaman;
                    }

                } else {
                    $data['error'] = 'Failed to rename the uploaded file.';
                    log_message('error', 'Rename Error: ' . $data['error']);
                    $this->load->view('admin/template/upper.php', $data);
                    $this->load->view('admin/pinjam/add.php', $data);
                    $this->load->view('admin/template/lower.php');
                }
            }
            $this->Pinjam_model->update_pinjam($kode_pinjam, $data);
            redirect('pinjam');
        } else {
            $data['title'] = "Detail Peminjaman";
            $data['pengembalian'] = $this->Pengembalian_model->get_pengembalian_by_kode_pinjam($kode_pinjam);

            // Dapatkan informasi pinjaman
            $pinjaman_info = $this->Pinjam_model->get_pinjaman_by_kode_pinjam($kode_pinjam);
            $pengembalian_sum = $this->Pengembalian_model->get_total_pengembalian_by_kode_pinjam($kode_pinjam);
            
            $data['kode_pinjam'] = $pinjaman_info['kode_pinjam'];
            $data['jumlah_pinjam'] = $pinjaman_info['jumlah_pinjam'];
            $data['total_pengembalian'] = $pengembalian_sum[0]['jumlah_pengembalian'];
            $data['bunga_pinjaman'] = $pinjaman_info['bunga_pinjaman'];
            $data['jatuh_tempo'] = $pinjaman_info['jatuh_tempo'];
            $data['bukti_peminjaman'] = $pinjaman_info['bukti_peminjaman'];
            $data['keterangan_pengajuan_pinjam'] = $pinjaman_info['keterangan_pengajuan_pinjam'];

            $this->load->view('admin/template/upper.php', $data);
            $this->load->view('admin/pinjam/detail.php', $data);
            $this->load->view('admin/template/lower.php');
        }
    }

}
