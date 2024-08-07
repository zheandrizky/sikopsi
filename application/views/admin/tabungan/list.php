<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Tabungan</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active"><?php echo $title; ?></li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <div class="row">
                <div class="col-md-6">
                  <h3 class="card-title">Daftar Transaksi Tabungan</h3>
                </div>
                <div class="col-md-6 text-right">
                  <?php if ($this->session->userdata('jabatan') == 'anggota'): ?>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-lg-add">
                      Add
                    </button>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="modal fade" id="modal-lg-add">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Tambah Transaksi Tabungan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <?php echo form_open_multipart('tabungan/add'); ?>
                  <div class="modal-body">
                    <div class="form-group">
                      <label for="jumlah">Jumlah yang dibayarkan</label>
                      <input type="number" class="form-control" placeholder="0" name="jumlah" required>
                    </div>
                    <div class="form-group">
                      <label for="bukti_pembayaran">Bukti Pembayaran</label>
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="customFile" name="bukti_pembayaran" required
                          onchange="previewImage()">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                      </div>
                    </div>
                    <img id="preview" src="#" alt="Preview" style="max-width: 200px; max-height: 200px; display: none;">
                    <!-- /.card-body -->
                  </div>
                  <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                  <?php echo form_close(); ?>

                </div>
                <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
            </div>

            <!-- /.card-header -->
            <div class="card-body">
              <div class="row mb-3">
                <div class="col-md-4">
                  <strong>Total Tabungan:</strong> <?php echo idrFormat($total_tabungan) ?>
                </div>
              </div>
              <label for="start_date">Start Date:</label>
              <input type="date" id="start_date">
              <label for="end_date">End Date:</label>
              <input type="date" id="end_date">
              <br>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Kode Tabungan</th>
                    <th>Nama</th>
                    <th>Jumlah Pembayaran</th>
                    <th>Tanggal Nabung</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($tabungan as $t) { ?>
                    <tr>
                      <td><?php echo $t['kode_tabungan']; ?></td>
                      <td><?php echo $t['nama']; ?></td>
                      <td><?php echo idrFormat($t['jumlah_nabung']); ?></td>
                      <td><?php echo $t['tanggal_nabung']; ?></td>
                      <td>
                        <?php
                        if ($t['status_pembayaran_tabungan'] == 'diproses') {
                          $color = 'warning';
                        } elseif ($t['status_pembayaran_tabungan'] == 'ditolak') {
                          $color = 'danger';
                        } else {
                          $color = 'success';
                        }
                        ?>
                        <span
                          class='badge bg-<?php echo $color; ?>'><?php echo $t['status_pembayaran_tabungan']; ?></span>
                      </td>
                      <td>
                        <button type="button"
                          class="btn btn-<?php echo ($this->session->userdata('jabatan') == 'anggota' || $this->session->userdata('jabatan') == 'ketua') ? 'primary' : 'warning'; ?>"
                          data-toggle="modal" data-target="#modal-lg-<?php echo $t['kode_tabungan'] ?>">
                          <i
                            class="fas fa-<?php echo ($this->session->userdata('jabatan') == 'anggota' || $this->session->userdata('jabatan') == 'ketua') ? 'eye' : 'pencil-alt'; ?>"></i>
                          <?php echo ($this->session->userdata('jabatan') == 'anggota' || $this->session->userdata('jabatan') == 'ketua') ? 'Detail' : 'Manage'; ?>
                        </button>
                      </td>
                    </tr>
                    <div class="modal fade" id="modal-lg-<?php echo $t['kode_tabungan'] ?>">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h4 class="modal-title">Detail Tabungan <?php echo $t['kode_tabungan'] ?></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <?php echo form_open('tabungan/update/' . $t['kode_tabungan']); ?>
                            <input type="hidden" name="kode_tabungan" value="<?php echo $t['kode_tabungan'] ?>">
                            <div class="form-group">
                              <label for="jumlah_nabung">Jumlah yang dibayarkan</label>
                              <input type="text" class="form-control" placeholder="0"
                                value="<?php echo idrFormat($t['jumlah_nabung']); ?>" name="jumlah_nabung" readonly>
                            </div>
                            <div class="form-group">
                              <label for="bukti">Bukti Pembayaran</label><br>
                              <img id="preview"
                                src="<?php echo base_url('assets/uploads/tabungan/' . $t['bukti_pembayaran_tabungan']); ?>"
                                alt="Preview" style="max-width: 200px; max-height: 200px;"><br>
                              <a href="<?php echo base_url('assets/uploads/tabungan/' . $t['bukti_pembayaran_tabungan']); ?>"
                                target="_blank">Lihat Full Gambar</a>
                            </div>
                            <div class="form-group col-sm-6">
                              <label>Status</label>
                              <select id="status-<?php echo $t['kode_tabungan'] ?>" name="status_pembayaran_tabungan"
                                class="form-control select2" style="width: 100%;" onchange="ubahKeterangan(this)" <?php echo ($this->session->userdata('jabatan') != 'pengurus') ? 'disabled' : ''; ?>>
                                <option value="diproses" <?php echo ($t['status_pembayaran_tabungan'] == 'diproses') ? 'selected' : ''; ?>>Diproses</option>
                                <option value="ditolak" <?php echo ($t['status_pembayaran_tabungan'] == 'ditolak') ? 'selected' : ''; ?>>Ditolak</option>
                                <option value="diterima" <?php echo ($t['status_pembayaran_tabungan'] == 'diterima') ? 'selected' : ''; ?>>Diterima</option>
                              </select>
                            </div>
                            <div class="form-group">
                              <label>Keterangan</label>
                              <textarea id="keterangan-<?php echo $t['kode_tabungan'] ?>"
                                name="keterangan_pembayaran_tabungan" class="form-control" rows="3"
                                placeholder="Masukkan keterangan..." <?php echo ($this->session->userdata('jabatan') != 'pengurus') ? 'readonly' : ''; ?>><?php echo $t['keterangan_pembayaran_tabungan']; ?></textarea>
                            </div>
                            <!-- /.card-body -->
                          </div>
                          <div class="modal-footer justify-content-between">
                            <?php if ($this->session->userdata('jabatan') == 'anggota' && $t['status_pembayaran_tabungan'] == 'diproses'): ?>
                              <button type="button" class="btn btn-danger" data-toggle="modal"
                                data-target="#modal-delete-<?php echo $t['kode_tabungan'] ?>"> <i class="fas fa-trash"></i>
                                Hapus Pengajuan </button>
                            <?php endif ?>
                            <?php if ($this->session->userdata('jabatan') == 'pengurus') { ?>
                              <button type="submit" class="btn btn-primary">Save changes</button>
                            <?php } ?>
                            <?php echo form_close(); ?>
                          </div>
                        </div>
                        <!-- /.modal-content -->
                      </div>
                      <!-- /.modal-dialog -->
                    </div>
                    <!-- Modal Delete -->
                    <div class="modal fade" id="modal-delete-<?php echo $t['kode_tabungan'] ?>">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h4 class="modal-title">Konfirmasi Hapus Data Tabungan</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p>Apakah Anda yakin ingin menghapus data tabungan ini?</p>
                          </div>
                          <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <a href="<?php echo base_url('tabungan/delete/' . $t['kode_tabungan']); ?>"
                              class="btn btn-danger">Ya, Hapus</a>
                          </div>
                        </div>
                        <!-- /.modal-content -->
                      </div>
                      <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
                  <?php } ?>

                </tbody>
                <tfoot>
                  <tr>
                    <th>Kode Tabungan</th>
                    <th>Nama</th>
                    <th>Jumlah Pembayaran</th>
                    <th>Tanggal Nabung</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>