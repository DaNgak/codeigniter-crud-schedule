<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
    Buat Jadwal
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Buat Jadwal</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Dropdown Prodi -->
                    <div class="col-lg-3">
                        <div class="form-group">
                            <!-- <label for="prodi">Program Studi:</label> -->
                            <select name="prodi" id="prodi" class="form-control">
                                <option value="">--- Pilih Program Studi ---</option>
                                <?php foreach ($programStudi as $prodi): ?>
                                    <option value="<?= esc($prodi['id']) ?>"><?= esc($prodi['nama']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <!-- Dropdown Tahun Ajaran -->
                    <div class="col-lg-3">
                        <div class="form-group">
                            <!-- <label for="tahun_ajaran">Tahun Ajaran:</label> -->
                            <select name="tahun_ajaran" id="tahun_ajaran" class="form-control">
                                <option value="">--- Pilih Tahun Ajaran ---</option>
                                <?php foreach ($tahunAjaran as $tahun): ?>
                                    <option value="<?= esc($tahun['id']) ?>"><?= esc($tahun['periode']) ?> - <?= esc($tahun['semester']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <!-- Buttons -->
                    <div class="col-lg-6">
                        <div class="d-flex justify-content-between pl-0 pl-xl-5">
                            <button type="button" id="generateBtn" class="btn btn-primary">Generate Jadwal</button>
                            <button type="button" id="evaluasiBtn" class="btn btn-secondary" data-toggle="modal" data-target="#evaluasiModal" disabled>Evaluasi Jadwal</button>
                            <button type="button" id="perhitunganBtn" class="btn btn-info" data-toggle="modal" data-target="#perhitunganModal" disabled>Perhitungan</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mt-4" id="containerResult">
                        <div class="text-center">
                            Belum ada data
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Evaluasi Jadwal -->
<div class="modal fade" id="evaluasiModal" tabindex="-1" role="dialog" aria-labelledby="evaluasiModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="evaluasiModalLabel">Evaluasi Jadwal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Isi modal evaluasi jadwal -->
                Evaluasi Jadwal akan dilakukan di sini.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <!-- <button type="button" class="btn btn-primary" id="submitEvaluasi">Kirim</button> -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Perhitungan Generate -->
<div class="modal fade" id="perhitunganModal" tabindex="-1" role="dialog" aria-labelledby="perhitunganModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="perhitunganModalLabel">Perhitungan Generate</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Isi modal perhitungan generate -->
                Perhitungan Generate akan dilakukan di sini.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <!-- <button type="button" class="btn btn-primary" id="submitPerhitungan">Kirim</button> -->
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        $('#generateBtn').click(function() {
            const prodi = $('#prodi').val();
            const tahunAjaran = $('#tahun_ajaran').val();

            if (!prodi || !tahunAjaran) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Harap isi Program Studi dan Tahun Ajaran dahulu!',
                });
            } else {
                // Show loading alert without OK button and auto-close
                Swal.fire({
                    title: 'Sedang Memproses',
                    text: 'Tunggu sebentar...',
                    allowOutsideClick: false,
                    showConfirmButton: false, // Hide the OK button
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Kirim data ke server dengan AJAX
                $.ajax({
                    url: '<?= site_url('/dashboard/jadwal/generate') ?>',
                    type: 'POST',
                    data: {
                        program_studi_id: prodi,
                        tahun_ajaran_id: tahunAjaran
                    },
                    success: function(response) {
                        // Close the loading alert
                        Swal.close();
                        if (response.code === 200) {
                            Swal.fire({
                                title: response.message.title,
                                text: response.message.description,
                                icon: response.message.type || 'success',
                            }).then(() => {
                                // Prepare explanation and table content
                                const data = response.data;
                                const bestIndividual = data.best_individual;
                                
                                // Construct the explanation
                                let explanation = `
                                    <p class='m-0'><strong>========== HASIL ALGORITMA GENETIKA ==========</strong></p>
                                    <p class='m-0'><strong>FITNESS TERBAIK       :</strong> ${data.best_fitness}</p>
                                    <p class='m-0'><strong>GENERASI                  :</strong> ${data.best_generation}</p>
                                    <p class='m-0'><strong>EXECUTION TIME        :</strong> ${data.execution_time} detik</p>
                                    <p class='m-0'><strong>MEMORY USAGE          :</strong> ${data.memory_usage}</p>
                                    <p class='m-0'><strong>JUMLAH KONFLIK        :</strong> ${data.total_conflict}</p>
                                    <p class='m-0'>Catatan :</p>
                                    <p class='m-0 ${data.total_conflict > 0 ? 'text-danger' : ''}'>
                                        ${data.total_conflict > 0 
                                            && `Terdapat konflik total ${data.total_conflict}, Anda dapat memperbaiki pada tombol <strong>"Evaluasi Jadwal"</strong> atau melakukan generate ulang dengan klik tombol <strong>"Generate Jadwal"</strong> sampai tidak ada konflik pada saat melakukan generate jadwal`
                                        }
                                    </p>
                                    <p class='m-0'>Individu terbaik berhasil ditemukan pada generasi ke-${data.best_generation} dan individu ke-${data.best_individual_index}!</p>
                                    <p class='m-0'>Melakukan looping generasi ke ${data.generation_reached} dari max generasi yaitu ${data.max_generation}</p>
                                `;

                                // Construct the table
                                let tableHTML = '<table class="table table-bordered">';
                                tableHTML += '<thead><tr><th>No</th><th>Kelas - Kode</th><th>Mata Kuliah - Kode</th><th>Ruangan - Kode</th><th>Waktu Kuliah</th><th>Dosen</th></tr></thead><tbody>';
                                $.each(bestIndividual, function(index, individual) {
                                    const no = index + 1;
                                    tableHTML += `
                                        <tr>
                                            <td>${no}</td>
                                            <td>${individual.kelas.nama} - ${individual.kelas.kode}</td>
                                            <td>${individual.mata_kuliah.nama} - ${individual.mata_kuliah.kode}</td>
                                            <td>${individual.ruangan.nama} - ${individual.ruangan.kode}</td>
                                            <td>${individual.waktu_kuliah.id} ${individual.waktu_kuliah.hari} (${individual.waktu_kuliah.jam_mulai} - ${individual.waktu_kuliah.jam_selesai})</td>
                                            <td>${individual.dosen.id} ${individual.dosen.nama}</td>
                                        </tr>
                                    `;
                                });
                                tableHTML += '</tbody></table>';

                                // Display the explanation, save button, and table
                                $('#containerResult').html(`
                                    ${explanation}
                                    <button type="button" id="saveJadwalBtn" class="btn btn-primary my-3">Simpan Data Jadwal</button>
                                    ${tableHTML}
                                `);

                                $('#evaluasiBtn').prop('disabled', false);
                                $('#perhitunganBtn').prop('disabled', false);

                                // Check if debug_conflict exists, then display it in the modal
                                const debugConflict = response.data.debug_conflict;
                                if (debugConflict) {
                                    $('#evaluasiModal .modal-body').html(debugConflict);
                                }

                                // Check if debug_result exists, then display it in the modal
                                const debugResult = response.data.debug_result;
                                if (debugResult) {
                                    $('#perhitunganModal .modal-body').html(debugResult);
                                }

                                if (data.total_conflict > 0) {
                                    Swal.fire({
                                        title: 'Konflik Jadwal Ditemukan',
                                        text: `Terdapat ${data.total_conflict} konflik jadwal. Silahkan lihat detailnya pada tombol "Evaluasi Jadwal".`,
                                        icon: 'warning',
                                    });
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'Failed',
                                text: 'Gagal melakukan aksi, silahkan coba lagi.',
                                icon: 'warning',
                            });
                        }
                    },
                    error: function(xhr) {
                        // Close the loading alert
                        Swal.close();
                        const response = xhr.responseJSON;

                        // Handle 400 error (bad request, missing data)
                        if (xhr.status === 400) {
                            Swal.fire({
                                icon: response.message.type || 'warning',
                                title: response.message.title || 'Failed',
                                text: response.message.description || 'Data yang dikirim tidak valid atau ada yang kosong. Harap periksa kembali.',
                            });
                        }

                        // Handle 422 error (validation errors)
                        else if (xhr.status === 422) {
                            // Construct the error list
                            let errorList = '<ul>';
                            $.each(response.errors, function(key, value) {
                                errorList += '<li>' + value + '</li>';
                            });
                            errorList += '</ul>';

                            // Show SweetAlert2 with validation error messages
                            Swal.fire({
                                icon: 'warning',
                                title: 'Kesalahan Validasi',
                                html: errorList,  // Use html to display the error list
                            });
                        }

                        // Handle 500 error (internal server error) and other
                        else {
                            Swal.fire({
                                title: response.message.title || 'Error',
                                text: response.message.description || 'Terjadi kesalahan, silahkan coba lagi.',
                                icon: response.message.type || 'error',
                            });
                        }
                    }
                });
            }
        });
    });
</script>
<?= $this->endSection() ?>
