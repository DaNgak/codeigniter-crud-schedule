<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
    Generate Jadwal
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Generate Jadwal</h6>
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
                                    <option value="<?= esc($tahun['id']) ?>"><?= esc($tahun['tahun_awal']) ?>/<?= esc($tahun['tahun_akhir']) ?> - <?= esc($tahun['semester']) ?></option>
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
<div class="modal fade" id="evaluasiModal" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="evaluasiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="evaluasiModalLabel">Evaluasi Jadwal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Isi modal evaluasi jadwal -->
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
                <!-- Perhitungan Generate akan dilakukan di sini. -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <!-- <button type="button" class="btn btn-primary" id="submitPerhitungan">Kirim</button> -->
            </div>
        </div>
    </div>
</div>

<!-- Modal fixing Evaluasi Jadwal -->
<div class="modal fade" id="bestIndividualModal" data-backdrop="static" data-keyboard="false"  role="dialog" tabindex="-1" aria-labelledby="bestIndividualModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bestIndividualModalLabel">Edit Baris Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Dynamic content will be injected here -->
                <!-- Buatkan select dropdown untuk menampilkan data yang dari masing-masing prodi -->
                <!-- Hidden Input for Kelas -->
                <input type="hidden" id="kelasInputSelect" name="kelasInputSelect">

                <!-- Div to display Kelas -->
                <div class="form-group">
                    <label for="kelasInputDisplay">Kelas</label>
                    <div id="kelasInputDisplay" class="form-control" style="background-color: whitesmoke;"></div>
                </div>

                <!-- Hidden Input for Mata Kuliah -->
                <input type="hidden" id="mataKuliahInputSelect" name="mataKuliahInputSelect">

                <!-- Div to display Mata Kuliah -->
                <div class="form-group">
                    <label for="mataKuliahInputDisplay">Mata Kuliah</label>
                    <div id="mataKuliahInputDisplay" class="form-control" style="background-color: whitesmoke;"></div>
                </div>

                <!-- Dropdown for Ruangan -->
                <div class="form-group">
                    <label for="ruanganSelect">Ruangan</label>
                    <select id="ruanganSelect" class="form-control">
                        <option value="">--- Pilih Ruangan ---</option>
                    </select>
                </div>

                <!-- Dropdown for Waktu Kuliah -->
                <div class="form-group">
                    <label for="waktuKuliahSelect">Waktu Kuliah</label>
                    <select id="waktuKuliahSelect" class="form-control">
                        <option value="">--- Pilih Waktu Kuliah ---</option>
                    </select>
                </div>

                <!-- Dropdown for Dosen -->
                <div class="form-group">
                    <label for="dosenSelect">Dosen</label>
                    <select id="dosenSelect" class="form-control">
                        <option value="">--- Pilih Dosen ---</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="saveConflictChanges">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    // Declare global variables
    let bestIndividual = null;
    let totalConflict = null;

    // Fungsi untuk memformat jam ke format "HH:MM"
    function formatWaktu(jam) {
        return jam.substring(0, 5); // Mengambil hanya 5 karakter pertama (HH:MM)
    }

    // Function to update table with new data
    function updateTable(data) {
        // Assume you have a function to re-render your table
        // This depends on how you're managing the table (like DataTables, etc.)
        // Example:
        $('#yourTableId').DataTable().clear().rows.add(data).draw();
    }

    $(document).ready(function() {

        // Button 'Simpan' click event
        $('#saveConflictChanges').on('click', function () {
            // Get the index from the data-index-update attribute on the button
            let indexToUpdate = $(this).data('index-update'); // Get the dynamic index

            // Clone bestIndividual
            let updatedBestIndividual = { ...bestIndividual };

            // Ensure we're updating the correct index
            if (updatedBestIndividual[indexToUpdate]) {
                // Update the fields in the specified index
                updatedBestIndividual[indexToUpdate].kelas.id = $('#kelasInputSelect').val();
                updatedBestIndividual[indexToUpdate].mata_kuliah.id = $('#mataKuliahInputSelect').val();
                updatedBestIndividual[indexToUpdate].ruangan.id = $('#ruanganSelect').val();
                updatedBestIndividual[indexToUpdate].waktu_kuliah.id = $('#waktuKuliahSelect').val();
                updatedBestIndividual[indexToUpdate].dosen.id = $('#dosenSelect').val();
            } else {
                console.error(`Index ${indexToUpdate} does not exist in bestIndividual`);
            }

            // Prepare the payload to send
            let inputData = JSON.stringify(updatedBestIndividual);

            // Send AJAX request to check for conflict
            $.ajax({
                url: '/dashboard/jadwal/generate/check-conflict',
                type: 'POST',
                data: {
                    input_data: inputData
                },
                success: function (response) {
                    if (response.code === 200) {
                        // Sweet Alert for success
                        Swal.fire({
                            title: 'Berhasil',
                            text: 'Sukses memperbaiki konflik!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Update data bestIndividual with the updated one
                            bestIndividual = { ...updatedBestIndividual };

                            // Update table with new bestIndividual data
                            updateTable(bestIndividual);
                        });
                    } else {
                        Swal.fire({
                            title: 'Failed',
                            text: 'Terjadi masalah saat memperbaiki konflik!',
                            icon: 'warning',
                        });
                    }
                },
                error: function (xhr) {
                    // Handle error
                    const response = xhr.responseJSON;
                            
                    if (xhr.status === 400) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Failed',
                            text: response.message || 'Data yang dikirim tidak valid atau ada yang kosong.',
                        });
                    } else if (xhr.status === 422) {
                        let errorList = '<ul>';
                        $.each(response.errors, function(key, value) {
                            errorList += '<li>' + value + '</li>';
                        });
                        errorList += '</ul>';

                        Swal.fire({
                            icon: 'warning',
                            title: 'Kesalahan Validasi',
                            html: errorList,
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Terjadi kesalahan, silahkan coba lagi.',
                            icon: 'error',
                        });
                    }
                }
            });
        });

        // Event handler for conflict buttons
        $(document).on('click', '.conflict-btn', function() {
            console.log("Test Button Conflict", $(this).data('index'))
            const editIndex = $(this).data('index');
            const individual = bestIndividual[editIndex];
            const programStudiId = $('#prodi').val();

            // // Construct the modal content with individual data
            // let modalContent = `
            //     <p><strong>Kelas:</strong> ${individual.kelas.nama} - ${individual.kelas.kode}</p>
            //     <p><strong>Mata Kuliah:</strong> ${individual.mata_kuliah.nama} - ${individual.mata_kuliah.kode}</p>
            //     <p><strong>Ruangan:</strong> ${individual.ruangan.nama} - ${individual.ruangan.kode}</p>
            //     <p><strong>Waktu Kuliah:</strong> ${individual.waktu_kuliah.id} ${individual.waktu_kuliah.hari} (${individual.waktu_kuliah.jam_mulai} - ${individual.waktu_kuliah.jam_selesai})</p>
            //     <p><strong>Dosen:</strong> ${individual.dosen.id} ${individual.dosen.nama}</p>
            // `;

            // // Set the content to the modal body
            // $('#bestIndividualModal .modal-body').html(modalContent);
            // $('#bestIndividualModal').modal('show');

            // Set disabled inputs for Kelas and Mata Kuliah
            // $('#kelasInput').val(`${individual.kelas.nama} - ${individual.kelas.kode}`);
            // $('#mataKuliahInput').val(`${individual.mata_kuliah.nama} - ${individual.mata_kuliah.kode}`);

            // Set hidden inputs for Kelas and Mata Kuliah
            $('#kelasInputSelect').val(individual.kelas.id);
            $('#mataKuliahInputSelect').val(individual.mata_kuliah.id);

            // Display Kelas and Mata Kuliah in div.form-control
            $('#kelasInputDisplay').text(`${individual.kelas.nama} (${individual.kelas.kode})`);
            $('#mataKuliahInputDisplay').text(`${individual.mata_kuliah.nama} (${individual.mata_kuliah.kode})`);

            // Fetch options for Ruangan, Waktu Kuliah, and Dosen via AJAX
            $.ajax({
                url: `/dashboard/jadwal/dropdown/getOptionsByProgramStudi/${programStudiId}`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.code === 200) {
                        // Populate Ruangan dropdown
                        $('#ruanganSelect').empty().append('<option value="">--- Pilih Ruangan ---</option>');
                        $.each(response.data.ruangan, function(key, value) {
                            const selected = value.kode === individual.ruangan.kode ? 'selected' : '';
                            $('#ruanganSelect').append(`<option value="${value.id}" ${selected}>(${value.kode}) ${value.nama}</option>`);
                        });

                        // Populate Waktu Kuliah dropdown
                        $('#waktuKuliahSelect').empty().append('<option value="">--- Pilih Waktu Kuliah ---</option>');
                        $.each(response.data.waktu_kuliah, function(key, value) {
                            const selected = value.id === individual.waktu_kuliah.id ? 'selected' : '';
                            $('#waktuKuliahSelect').append(`<option value="${value.id}" ${selected}>${value.hari} (${formatWaktu(value.jam_mulai)} - ${formatWaktu(value.jam_selesai)})</option>`);
                        });

                        // Populate Dosen dropdown
                        $('#dosenSelect').empty().append('<option value="">--- Pilih Dosen ---</option>');
                        $.each(response.data.dosen, function(key, value) {
                            const selected = value.id === individual.dosen.id ? 'selected' : '';
                            $('#dosenSelect').append(`<option value="${value.id}" ${selected}>${value.nama} - (${value.nomer_pegawai})</option>`);
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
                    const response = xhr.responseJSON;
                            
                    if (xhr.status === 400) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Failed',
                            text: response.message || 'Data yang dikirim tidak valid atau ada yang kosong.',
                        });
                    } else if (xhr.status === 422) {
                        let errorList = '<ul>';
                        $.each(response.errors, function(key, value) {
                            errorList += '<li>' + value + '</li>';
                        });
                        errorList += '</ul>';

                        Swal.fire({
                            icon: 'warning',
                            title: 'Kesalahan Validasi',
                            html: errorList,
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Terjadi kesalahan, silahkan coba lagi.',
                            icon: 'error',
                        });
                    }
                }
            });

            // Show the modal
            $('#bestIndividualModal').modal('show');
        });

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
                                bestIndividual = data.best_individual;
                                totalConflict = data.total_conflict;
                                
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
                                            ? `Terdapat konflik total ${data.total_conflict}, Anda dapat memperbaiki pada tombol <strong>"Evaluasi Jadwal"</strong> atau melakukan generate ulang dengan klik tombol <strong>"Generate Jadwal"</strong> sampai tidak ada konflik pada saat melakukan generate jadwal`
                                            : ''
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
                                            <td>${individual.waktu_kuliah.hari} (${formatWaktu(individual.waktu_kuliah.jam_mulai)} - ${formatWaktu(individual.waktu_kuliah.jam_selesai)})</td>
                                            <td>${individual.dosen.nama} - ${individual.dosen.nomer_pegawai}</td>
                                        </tr>
                                    `;
                                });
                                tableHTML += '</tbody></table>';

                                // Display the explanation, save button, and table
                                $('#containerResult').html(`
                                    ${explanation}
                                    <button type="button" id="saveJadwalBtn" class="btn btn-primary my-3" ${data.total_conflict > 0 ? 'text-danger' : ''}'
                                        ${data.total_conflict > 0  ? 'disabled' : ''}>Simpan Data Jadwal</button>
                                    ${tableHTML}
                                `);

                                $('#evaluasiBtn').prop('disabled', false);
                                $('#perhitunganBtn').prop('disabled', false);

                                // Check if debug_conflict exists, then display it in the modal
                                const debugConflict = response.data.debug_conflict;
                                if (debugConflict) {
                                    let conflictButtonsHTML = '';
                                    // Loop through conflict_index and generate buttons
                                    if (data.conflict_index && data.conflict_index.length > 0) {
                                        $.each(data.conflict_index, function(loopIndex, conflictPair) {
                                            const index1 = conflictPair[0] + 1; // Adjust index to human-readable (1-based)
                                            const index2 = conflictPair[1] + 1;

                                            conflictButtonsHTML += `
                                                <br/>
                                                <h5 class="fs-5 fw-medium mb-0">Konflik ke-${loopIndex + 1}</h5>
                                                <button type="button" class="btn btn-info conflict-btn me-3" data-index="${conflictPair[0]}" data-bs-toggle="modal" data-bs-target="#bestIndividualModal">
                                                    Data baris ke-${index1}
                                                </button>
                                                <button type="button" class="btn btn-info conflict-btn" data-index="${conflictPair[1]}" data-bs-toggle="modal" data-bs-target="#bestIndividualModal">
                                                    Data baris ke-${index2}
                                                </button>
                                                <br/>
                                            `;
                                        });
                                    }
                                    $('#evaluasiModal .modal-body').html(debugConflict + conflictButtonsHTML);
                                } else {
                                    $('#evaluasiModal .modal-body').html('Tidak ada konflik karena total konflik ' + data.total_conflict);
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

        // Event for Save Schedule button
        $('#containerResult').on('click', '#saveJadwalBtn', function() {
            const prodi = $('#prodi').val();
            const tahunAjaran = $('#tahun_ajaran').val();

            // Check if the schedule data exists
            if (!bestIndividual || totalConflict === null) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Harap melakukan generate jadwal terlebih dahulu!',
                });
                return;
            }

            // Check for conflicts
            if (totalConflict > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Konflik Jadwal',
                    text: 'Terdapat konflik pada jadwal. Silahkan perbaiki di evaluasi jadwal atau generate ulang hingga konflik menjadi 0.',
                });
                return;
            }

            // Confirm save action
            Swal.fire({
                title: 'Konfirmasi Simpan',
                text: 'Apakah Anda yakin ingin menyimpan jadwal ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Prepare schedule data
                    let jadwal = bestIndividual.map((item) => {
                        return {
                            kelas: item.kelas.id,
                            mata_kuliah: item.mata_kuliah.id,
                            ruangan: item.ruangan.id,
                            waktu_kuliah: item.waktu_kuliah.id,
                            dosen: item.dosen.id,
                        };
                    });

                    // Send AJAX request to save schedule
                    $.ajax({
                        url: '<?= site_url("/dashboard/jadwal/generate/store") ?>',
                        type: 'POST',
                        data: { 
                            jadwal: jadwal,
                            program_studi_id: prodi,
                            tahun_ajaran_id: tahunAjaran
                        },
                        success: function(response) {
                            if (response.code === 200) {
                                Swal.fire({
                                    title: response.message.title,
                                    text: response.message.description,
                                    icon: response.message.type || 'success',
                                }).then(() => {
                                    window.location.href = "<?= site_url('/dashboard/jadwal') ?>";
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
                            const response = xhr.responseJSON;
                            
                            if (xhr.status === 400) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Failed',
                                    text: response.message || 'Data yang dikirim tidak valid atau ada yang kosong.',
                                });
                            } else if (xhr.status === 422) {
                                let errorList = '<ul>';
                                $.each(response.errors, function(key, value) {
                                    errorList += '<li>' + value + '</li>';
                                });
                                errorList += '</ul>';

                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Kesalahan Validasi',
                                    html: errorList,
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Terjadi kesalahan, silahkan coba lagi.',
                                    icon: 'error',
                                });
                            }
                        }
                    });
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>
