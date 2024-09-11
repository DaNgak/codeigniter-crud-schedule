<?php

namespace App\Services;

class GeneticAlgorithmService
{
    // private $kelasModel, $mataKuliahModel, $ruanganModel, $dosenModel, $waktuKuliahModel;

    // public function __construct()
    // {
    //     // Inisialisasi model yang diperlukan
    //     $this->kelasModel = new KelasModel();
    //     $this->mataKuliahModel = new MataKuliahModel();
    //     $this->ruanganModel = new RuanganModel();
    //     $this->dosenModel = new DosenModel();
    //     $this->waktuKuliahModel = new WaktuKuliahModel();
    // }
    
    /// Inisialisasi individu
    function create_individual($kelasList, $matkulList, $ruangList, $waktuList, $dosenList) {
        $individu = [];
        $schedules = []; // Untuk melacak jadwal yang sudah ada

        // Loop melalui setiap kelas dan atur mata kuliah mereka
        foreach ($kelasList as $kelas) {
            foreach ($matkulList as $matkul) {
                $is_unique = false;

                while (!$is_unique) {
                    $waktu_kuliah = $waktuList[array_rand($waktuList)];

                    // Gabungkan jam_mulai dan jam_selesai
                    $jam_kuliah = $waktu_kuliah['jam_mulai'] . ' - ' . $waktu_kuliah['jam_selesai'];

                    $new_schedule = [
                        'kelas' => $kelas,
                        'mata_kuliah' => $matkul,
                        'ruangan' => $ruangList[array_rand($ruangList)],
                        'waktu_kuliah' => array_merge($waktu_kuliah, ['jam' => $jam_kuliah]), // Merge atribut 'jam'
                        'dosen' => $dosenList[array_rand($dosenList)],
                    ];

                    $is_unique = true;
                    foreach ($schedules as $schedule) {                        
                        if ($new_schedule['kelas']['kode'] === $schedule['kelas']['kode'] &&
                            $new_schedule['mata_kuliah']['kode'] === $schedule['mata_kuliah']['kode'] &&
                            $new_schedule['ruangan']['kode'] === $schedule['ruangan']['kode'] &&
                            $new_schedule['waktu_kuliah']['hari'] === $schedule['waktu_kuliah']['hari'] &&
                            $new_schedule['waktu_kuliah']['jam'] === $schedule['waktu_kuliah']['jam'] && // Cek 'jam' gabungan
                            $new_schedule['dosen']['nama'] === $schedule['dosen']['nama']) {
                            $is_unique = false;
                            break;
                        }
                    }
                }

                $individu[] = $new_schedule;
                $schedules[] = $new_schedule; // Tambahkan jadwal yang valid
            }
        }
        
        return $individu;
    }

    // Membuat populasi
    function create_population($size, $kelasList, $matkulList, $ruangList, $waktuList, $dosenList) {
        $population = [];
        for ($i = 0; $i < $size; $i++) {
            $population[] = $this->create_individual($kelasList, $matkulList, $ruangList, $waktuList, $dosenList);
        }
        return $population;
    }

    // Evaluasi fitness
    // function calculate_fitness($individual) {
    //     $conflicts = 0;
    //     $count = count($individual);
    //     $schedules = [];

    //     // Cek benturan waktu dan ruang antar jadwal
    //     for ($i = 0; $i < $count; $i++) {
    //         for ($j = $i + 1; $j < $count; $j++) {
    //             if ($individual[$i]['ruangan']['kode'] === $individual[$j]['ruangan']['kode'] &&
    //                 $individual[$i]['waktu_kuliah']['hari'] === $individual[$j]['waktu_kuliah']['hari'] &&
    //                 $individual[$i]['waktu_kuliah']['jam'] === $individual[$j]['waktu_kuliah']['jam'] && // Cek 'jam' gabungan
    //                 $individual[$i]['dosen']['nama'] === $individual[$j]['dosen']['nama']) { 
    //                 // Cek apakah dua kelas yang berbeda dijadwalkan di ruangan, waktu dan dosen yang sama
    //                 if ($individual[$i]['kelas']['kode'] !== $individual[$j]['kelas']['kode']) {
    //                     $conflicts++;
    //                 }
    //             }
    //             // Memeriksa keunikan jadwal dalam individu
    //             $schedule_key = "{$individual[$i]['kelas']['kode']}-{$individual[$i]['mata_kuliah']['kode']}-{$individual[$i]['ruangan']['kode']}-{$individual[$i]['waktu_kuliah']['hari']}-{$individual[$i]['waktu_kuliah']['jam']}-{$individual[$i]['dosen']['nama']}";
    //             if (in_array($schedule_key, $schedules)) {
    //                 $conflicts++;
    //             } else {
    //                 $schedules[] = $schedule_key;
    //             }
    //         }            
    //     }

    //     return 1 / (1 + $conflicts); // Fitness lebih tinggi jika konflik lebih sedikit
    // }

    function calculate_fitness($individual) {
        // $conflicts = 0;
        // $count = count($individual);
    
        // // Cek benturan waktu, ruang, dan dosen antar jadwal
        // for ($i = 0; $i < $count; $i++) {
        //     for ($j = $i + 1; $j < $count; $j++) {
        //         // Cek jika jadwal bertabrakan
        //         if ($individual[$i]['ruangan']['kode'] === $individual[$j]['ruangan']['kode'] &&
        //             $individual[$i]['waktu_kuliah']['hari'] === $individual[$j]['waktu_kuliah']['hari'] &&
        //             $individual[$i]['waktu_kuliah']['jam'] === $individual[$j]['waktu_kuliah']['jam'] &&
        //             $individual[$i]['dosen']['nama'] === $individual[$j]['dosen']['nama']) {
                    
        //             // Cek apakah dua kelas yang berbeda dijadwalkan di ruangan, waktu dan dosen yang sama
        //             if ($individual[$i]['kelas']['kode'] !== $individual[$j]['kelas']['kode']) {
        //                 $conflicts++;
        //             }
        //         }
        //     }
        // }
    
        // // Menghindari keunikan jadwal dalam individu
        // $schedules = [];
        // foreach ($individual as $schedule) {
        //     $schedule_key = "{$schedule['kelas']['kode']}-{$schedule['mata_kuliah']['kode']}-{$schedule['ruangan']['kode']}-{$schedule['waktu_kuliah']['hari']}-{$schedule['waktu_kuliah']['jam']}-{$schedule['dosen']['nama']}";
        //     if (in_array($schedule_key, $schedules)) {
        //         $conflicts++; // Menandakan adanya konflik jika jadwal duplikat ditemukan
        //     } else {
        //         $schedules[] = $schedule_key;
        //     }
        // }
    
        // // Fitness lebih tinggi jika konflik lebih sedikit
        // return 1 / (1 + $conflicts);

        // Panggil calculate_conflict untuk menghitung konflik
        $conflict_result = $this->calculate_conflict($individual);

        // Jumlah konflik diambil dari hasil calculate_conflict
        $conflicts = $conflict_result['conflict'];

        // Fitness lebih tinggi jika konflik lebih sedikit
        return 1 / (1 + $conflicts);
    }

    // Seleksi menggunakan roulette wheel
    function roulette_wheel_selection($population, $fitness_values) {
        $total_fitness = array_sum($fitness_values);
        $random_value = mt_rand() / mt_getrandmax() * $total_fitness;

        $cumulative_fitness = 0;
        foreach ($population as $index => $individual) {
            $cumulative_fitness += $fitness_values[$index];
            if ($cumulative_fitness >= $random_value) {
                return $individual;
            }
        }
        return $population[array_rand($population)]; // Jika tidak ada yang terpilih, pilih acak
    }

    // Persilangan (Crossover)
    function crossover($parent1, $parent2) {
        $crossover_point = rand(0, count($parent1) - 1);

        $child1 = array_merge(array_slice($parent1, 0, $crossover_point), array_slice($parent2, $crossover_point));
        $child2 = array_merge(array_slice($parent2, 0, $crossover_point), array_slice($parent1, $crossover_point));

        return [$child1, $child2];
    }

    // Mutasi
    function mutate($individual, $ruangList, $waktuList, $dosenList) {
        $mutation_point = rand(0, count($individual) - 1);
        $new_schedule = $individual[$mutation_point];

        // Pilih jadwal baru yang unik
        $is_unique = false;
        while (!$is_unique) {
            $waktu_kuliah = $waktuList[array_rand($waktuList)];

            // Gabungkan jam_mulai dan jam_selesai
            $jam_kuliah = $waktu_kuliah['jam_mulai'] . ' - ' . $waktu_kuliah['jam_selesai'];

            $new_schedule['ruangan'] = $ruangList[array_rand($ruangList)];
            $new_schedule['waktu_kuliah'] = array_merge($waktu_kuliah, ['jam' => $jam_kuliah]); // Merge atribut 'jam'
            $new_schedule['dosen'] = $dosenList[array_rand($dosenList)];
            
            $is_unique = true;
            foreach ($individual as $schedule) {
                if ($schedule !== $new_schedule &&
                    $new_schedule['kelas']['kode'] === $schedule['kelas']['kode'] &&
                    $new_schedule['mata_kuliah']['kode'] === $schedule['mata_kuliah']['kode'] &&
                    $new_schedule['ruangan']['kode'] === $schedule['ruangan']['kode'] &&
                    $new_schedule['waktu_kuliah']['hari'] === $schedule['waktu_kuliah']['hari'] &&
                    $new_schedule['waktu_kuliah']['jam'] === $schedule['waktu_kuliah']['jam'] &&
                    $new_schedule['dosen']['nama'] === $schedule['dosen']['nama']) {
                    $is_unique = false;
                    break;
                }
            }
        }

        $individual[$mutation_point] = $new_schedule;
        return $individual;
    }

    // function calculate_conflict($individual) {
    //     $conflicts = 0;
    //     $count = count($individual);

    //     // Membuat array untuk melacak konflik
    //     $ruangan_with_time_map = [];
    //     $kelas_with_time_map = [];
    //     $kelas_with_dosen_map = [];
        
    //     foreach ($individual as $index => $schedule) {
    //         $ruangan_time_key = "{$schedule['ruangan']['kode']}-{$schedule['waktu_kuliah']['hari']}-{$schedule['waktu_kuliah']['jam']}";
    //         $kelas_time_key = "{$schedule['kelas']['kode']}-{$schedule['waktu_kuliah']['hari']}-{$schedule['waktu_kuliah']['jam']}";
    //         $kelas_dosen_key = "{$schedule['kelas']['kode']}-{$schedule['dosen']['nama']}-{$schedule['waktu_kuliah']['hari']}-{$schedule['waktu_kuliah']['jam']}";
            
    //         // Format data
    //         $formatted_data = function($data) {
    //             return "[Kelas: {$data['kelas']['kode']}, Matkul: {$data['mata_kuliah']['kode']}, Ruang: {$data['ruangan']['kode']}, Waktu: {$data['waktu_kuliah']['hari']} ({$data['waktu_kuliah']['jam']}) {$data['waktu_kuliah']['jam_mulai']} - {$data['waktu_kuliah']['jam_selesai']}, Dosen: {$data['dosen']['nama']}]";
    //         };
            
    //         // Cek benturan ruangan
    //         if (isset($ruangan_with_time_map[$ruangan_time_key])) {
    //             $conflicts++;
    //             $conflict_details[] = "Konflik Ke-" . $conflicts;
    //             $conflict_details[] = "Konflik Ruangan: Baris " . ($index + 1) . " dan Baris " . ($ruangan_with_time_map[$ruangan_time_key] + 1);
    //             $conflict_details[] = "Data 1: " . $formatted_data($individual[$index]);
    //             $conflict_details[] = "Data 2: " . $formatted_data($individual[$ruangan_with_time_map[$ruangan_time_key]]);
    //             $conflict_details[] = ""; // Menambahkan baris kosong untuk pemisah
    //         } else {
    //             $ruangan_with_time_map[$ruangan_time_key] = $index;
    //         }
    
    //         // Cek benturan kelas
    //         if (isset($kelas_with_time_map[$kelas_time_key])) {
    //             $conflicts++;
    //             $conflict_details[] = "Konflik Ke-" . $conflicts;
    //             $conflict_details[] = "Konflik Kelas: Baris " . ($index + 1) . " dan Baris " . ($kelas_with_time_map[$kelas_time_key] + 1);
    //             $conflict_details[] = "Data 1: " . $formatted_data($individual[$index]);
    //             $conflict_details[] = "Data 2: " . $formatted_data($individual[$kelas_with_time_map[$kelas_time_key]]);
    //             $conflict_details[] = ""; // Menambahkan baris kosong untuk pemisah
    //         } else {
    //             $kelas_with_time_map[$kelas_time_key] = $index;
    //         }
    
    //         // Cek benturan kelas dan dosen
    //         if (isset($kelas_with_dosen_map[$kelas_dosen_key])) {
    //             $conflicts++;
    //             $conflict_details[] = "Konflik Ke-" . $conflicts;
    //             $conflict_details[] = "Konflik Kelas dan Dosen: Baris " . ($index + 1) . " dan Baris " . ($kelas_with_dosen_map[$kelas_dosen_key] + 1);
    //             $conflict_details[] = "Data 1: " . $formatted_data($individual[$index]);
    //             $conflict_details[] = "Data 2: " . $formatted_data($individual[$kelas_with_dosen_map[$kelas_dosen_key]]);
    //             $conflict_details[] = ""; // Menambahkan baris kosong untuk pemisah
    //         } else {
    //             $kelas_with_dosen_map[$kelas_dosen_key] = $index;
    //         }
    //     }
    
    //     // Mengembalikan hasil
    //     $console = "<pre>" . implode("\n", $conflict_details) . "</pre>";
        
    //     // Fitness lebih tinggi jika konflik lebih sedikit
    //     return [
    //         'conflict' => $conflicts,
    //         'debug_conflict' => $console
    //     ];
    // }
    function calculate_conflict($individual) {
        $conflicts = 0;
        $ruangan_with_time_map = [];
        $dosen_with_time_map = [];
        $conflict_details = [];
        foreach ($individual as $index => $schedule) {
            // Create unique keys for room-time and instructor-time conflicts
            $ruangan_time_key = "{$schedule['ruangan']['kode']}-{$schedule['waktu_kuliah']['hari']}-{$schedule['waktu_kuliah']['jam']}";
            $dosen_time_key = "{$schedule['dosen']['nama']}-{$schedule['waktu_kuliah']['hari']}-{$schedule['waktu_kuliah']['jam']}";
    
            // Format the schedule data for better readability in conflicts
            $formatted_data = function($data) {
                return "[Kelas: {$data['kelas']['kode']}, Matkul: {$data['mata_kuliah']['kode']}, Ruang: {$data['ruangan']['kode']}, Waktu: ({$data['waktu_kuliah']['id']}) {$data['waktu_kuliah']['hari']}/{$data['waktu_kuliah']['jam']}, Dosen: {$data['dosen']['nama']}]";
            };
            
            // Check for room-time conflicts
            if (isset($ruangan_with_time_map[$ruangan_time_key])) {
                $conflicts++;
                $conflict_details[] = "Konflik Ke-" . $conflicts;
                $conflict_details[] = "Konflik Ruangan: Baris " . ($index + 1) . " dan Baris " . ($ruangan_with_time_map[$ruangan_time_key] + 1);
                $conflict_details[] = "Data 1: " . $formatted_data($individual[$index]);
                $conflict_details[] = "Data 2: " . $formatted_data($individual[$ruangan_with_time_map[$ruangan_time_key]]);
                $conflict_details[] = ""; // Add empty line as a separator
            } else {
                // No conflict, store this room-time pairing
                $ruangan_with_time_map[$ruangan_time_key] = $index;
            }
    
            // Check for instructor-time conflicts
            if (isset($dosen_with_time_map[$dosen_time_key])) {
                $conflicts++;
                $conflict_details[] = "Konflik Ke-" . $conflicts;
                $conflict_details[] = "Konflik Dosen: Baris " . ($index + 1) . " dan Baris " . ($dosen_with_time_map[$dosen_time_key] + 1);
                $conflict_details[] = "Data 1: " . $formatted_data($individual[$index]);
                $conflict_details[] = "Data 2: " . $formatted_data($individual[$dosen_with_time_map[$dosen_time_key]]);
                $conflict_details[] = ""; // Add empty line as a separator
            } else {
                // No conflict, store this instructor-time pairing
                $dosen_with_time_map[$dosen_time_key] = $index;
            }
        }
    
        // Return conflict details
        $console = "<pre>" . implode("\n", $conflict_details) . "</pre>";
    
        return [
            'conflict' => $conflicts,
            'debug_conflict' => $console
        ];
    }

    // // Menjalankan algoritma genetika (debug)
    // function genetic_algorithm($kelasList, $matkulList, $ruangList, $waktuList, $dosenList, $population_size, $max_generation) {
    //     $time_start = microtime(true); // Mulai waktu eksekusi
    //     $population = $this->create_population($population_size, $kelasList, $matkulList, $ruangList, $waktuList, $dosenList);
    //     $best_fitness = 0;
    //     $best_individual = null;
    //     $best_individual_index = 0;
    //     $best_generation = 0;
    //     $generation_reached = 0;

    //     for ($generation = 1; $generation <= $max_generation; $generation++) {
    //         $fitness_values = [];
    //         foreach ($population as $individual) {
    //             $fitness_values[] = $this->calculate_fitness($individual);
    //         }

    //         echo "=======Generasi $generation: =========== <br/>";
    //         foreach ($population as $index => $individual) {
    //             echo "Individu $index - Fitness: {$fitness_values[$index]}<br/>";
    //             echo "<pre>";
    //             foreach ($individual as $schedule) {
    //                 echo " [{$schedule['kelas']['kode']}, {$schedule['mata_kuliah']['kode']}, {$schedule['ruangan']['kode']}, {$schedule['waktu_kuliah']['hari']} {$schedule['waktu_kuliah']['jam']}, {$schedule['dosen']['nama']}] ||| \n";
    //             }                
    //             echo "</pre><br/>";
    //             // echo "<br/><br/>";
    //         }

    //         // Mencari fitness terbaik dalam populasi ini
    //         $max_fitness = max($fitness_values);
    //         if ($max_fitness >= $best_fitness) {
    //             $best_fitness = $max_fitness;
    //             $best_generation = $generation;
    //             $best_individual = $population[array_search($max_fitness, $fitness_values)];
    //             $best_individual_index = array_search($max_fitness, $fitness_values); // Simpan indeks individu terbaik
    //         }

    //         // Jika ada individu dengan fitness 1, berhenti
    //         if ($best_fitness == 1) {
    //             $generation_reached = $generation;
    //             // $best_generation = $generation;
    //             // $best_individual = $population[array_search($max_fitness, $fitness_values)];
    //             // $best_individual_index = array_search($max_fitness, $fitness_values); // Simpan indeks individu terbaik
    //             break;
    //         }

    //         // Seleksi dan pembentukan populasi baru
    //         $new_population = [];
    //         while (count($new_population) < $population_size) {
    //             $parent1 = $this->roulette_wheel_selection($population, $fitness_values);
    //             $parent2 = $this->roulette_wheel_selection($population, $fitness_values);
    //             list($child1, $child2) = $this->crossover($parent1, $parent2);

    //             $child1 = $this->mutate($child1, $ruangList, $waktuList, $dosenList);
    //             $child2 = $this->mutate($child2, $ruangList, $waktuList, $dosenList);

    //             $new_population[] = $child1;
    //             if (count($new_population) < $population_size) {
    //                 $new_population[] = $child2;
    //             }
    //         }

    //         $population = $new_population;
    //         $generation_reached = $generation; // Menyimpan generasi terakhir yang dicapai
    //     }

    //     // Setelah algoritma selesai, hitung waktu eksekusi dan tampilkan hasilnya
    //     $time_end = microtime(true);
    //     $execution_time = $time_end - $time_start;
    //     // Menghitung konflik dan menyimpan detailnya
    //     $conflict_result = $this->calculate_conflict($best_individual);
        
    //     // Menampilkan hasil terbaik
    //     echo "<pre style='color:black; font-size:0.8rem'>========== HASIL ALGORITMA GENETIKA ========== \n";
    //     echo "\r\nFITNESS TERBAIK       : " . $best_fitness;
    //     echo "\r\nGENERASI              : " . $best_generation;
    //     echo "\r\nEXECUTION TIME        : " . $execution_time . " detik";
    //     echo "\r\nMEMORY USAGE          : " . round(memory_get_usage() / 1024 / 1024, 2) . " MB";
    //     echo "\r\nJUMLAH KONFLIK        : " . $conflict_result['conflict'];
    //     echo "\r\nINDIVIDU TERBAIK      : \n";

    //     foreach ($best_individual as $index => $schedule) {
    //         $number = $index+=1;
    //         echo "[{$number}][Kelas: {$schedule['kelas']['kode']}, Matkul: {$schedule['mata_kuliah']['kode']}, Ruang: {$schedule['ruangan']['kode']}, Waktu: ({$schedule['waktu_kuliah']['id']}) {$schedule['waktu_kuliah']['hari']}/{$schedule['waktu_kuliah']['jam']}, Dosen: {$schedule['dosen']['nama']}]\n";
    //     }        

    //     echo "<div class='notic'><strong>";
    //     if ($best_fitness == 1) {
    //         // Jika fitness terbaik adalah 1, berarti individu terbaik ditemukan
    //         echo "\r\nPesan         : <span style='font-family-sans'>Individu terbaik berhasil ditemukan pada generasi ke-{$best_generation} dan individu ke-{$best_individual_index}!</span>";             
    //     } else {
    //         // Jika fitness terbaik belum mencapai 1, tampilkan informasi generasi dan individu terbaik
    //         echo "\r\nPesan         : <span style='font-family-sans'>Individu terbaik ditemukan pada generasi ke-{$best_generation} dan individu ke-{$best_individual_index}, namun fitness belum mencapai 1.</span>";             
    //     }
    //     echo "</strong></div>\n<div>Melakukan looping generasi ke {$generation_reached} dari max generasi {$max_generation}</div></pre>";

    //     echo "<br/>============ Konflik ============";
    //     echo $conflict_result['debug_conflict']; // Menampilkan detail konflik

    //     return $best_individual;
    // }

    // Menjalankan algoritma genetika (api)
    function execute($kelasList, $matkulList, $ruangList, $waktuList, $dosenList, $population_size, $max_generation) {
        $time_start = microtime(true); // Mulai waktu eksekusi
        $population = $this->create_population($population_size, $kelasList, $matkulList, $ruangList, $waktuList, $dosenList);
        $best_fitness = 0;
        $best_individual = null;
        $best_individual_index = 0;
        $best_generation = 0;
        $generation_reached = 0;
        $debug_generation = ""; // Untuk menyimpan informasi tiap generasi
    
        for ($generation = 1; $generation <= $max_generation; $generation++) {
            $fitness_values = [];
            foreach ($population as $individual) {
                $fitness_values[] = $this->calculate_fitness($individual);
            }
    
            // Simpan informasi generasi saat ini ke dalam variabel
            $debug_generation .= "=======Generasi $generation: =========== <br/>";
            foreach ($population as $index => $individual) {
                $debug_generation .= "Individu $index - Fitness: {$fitness_values[$index]}<br/>";
                $debug_generation .= "<pre>";
                foreach ($individual as $schedule) {
                    $debug_generation .= " [{$schedule['kelas']['kode']}, {$schedule['mata_kuliah']['kode']}, {$schedule['ruangan']['kode']}, {$schedule['waktu_kuliah']['hari']} {$schedule['waktu_kuliah']['jam']}, {$schedule['dosen']['nama']}] ||| \n";
                }
                $debug_generation .= "</pre><br/>";
            }
    
            // Mencari fitness terbaik dalam populasi ini
            $max_fitness = max($fitness_values);
            if ($max_fitness >= $best_fitness) {
                $best_fitness = $max_fitness;
                $best_generation = $generation;
                $best_individual = $population[array_search($max_fitness, $fitness_values)];
                $best_individual_index = array_search($max_fitness, $fitness_values);
            }
    
            // Jika ada individu dengan fitness 1, berhenti
            if ($best_fitness == 1) {
                $generation_reached = $generation;
                break;
            }
    
            // Seleksi dan pembentukan populasi baru
            $new_population = [];
            while (count($new_population) < $population_size) {
                $parent1 = $this->roulette_wheel_selection($population, $fitness_values);
                $parent2 = $this->roulette_wheel_selection($population, $fitness_values);
                list($child1, $child2) = $this->crossover($parent1, $parent2);
    
                $child1 = $this->mutate($child1, $ruangList, $waktuList, $dosenList);
                $child2 = $this->mutate($child2, $ruangList, $waktuList, $dosenList);
    
                $new_population[] = $child1;
                if (count($new_population) < $population_size) {
                    $new_population[] = $child2;
                }
            }
    
            $population = $new_population;
            $generation_reached = $generation; // Menyimpan generasi terakhir yang dicapai
        }
    
        // Setelah algoritma selesai, hitung waktu eksekusi dan tampilkan hasilnya
        $time_end = microtime(true);
        $execution_time = $time_end - $time_start;
    
        // Menghitung konflik dan menyimpan detailnya
        $conflict_result = $this->calculate_conflict($best_individual);
        
        // Menyimpan hasil terbaik dalam variabel
        $debug_result = "<pre style='color:black; font-size:0.8rem'>========== HASIL ALGORITMA GENETIKA ========== \n";
        $debug_result .= "\r\nFITNESS TERBAIK       : " . $best_fitness;
        $debug_result .= "\r\nGENERASI              : " . $best_generation;
        $debug_result .= "\r\nEXECUTION TIME        : " . $execution_time . " detik";
        $debug_result .= "\r\nMEMORY USAGE          : " . round(memory_get_usage() / 1024 / 1024, 2) . " MB";
        $debug_result .= "\r\nJUMLAH KONFLIK        : " . $conflict_result['conflict'];
        $debug_result .= "\r\nINDIVIDU TERBAIK      : \n";
        
        foreach ($best_individual as $index => $schedule) {
            $number = $index + 1;
            $debug_result .= "[{$number}][Kelas: {$schedule['kelas']['kode']}, Matkul: {$schedule['mata_kuliah']['kode']}, Ruang: {$schedule['ruangan']['kode']}, Waktu: ({$schedule['waktu_kuliah']['id']}) {$schedule['waktu_kuliah']['hari']}/{$schedule['waktu_kuliah']['jam']}, Dosen: {$schedule['dosen']['nama']}]\n";
        }
    
        $debug_result .= "<div class='notic'><strong>";
        if ($best_fitness == 1) {
            $debug_result .= "\r\nPesan         : <span style='font-family-sans'>Individu terbaik berhasil ditemukan pada generasi ke-{$best_generation} dan individu ke-{$best_individual_index}!</span>";
        } else {
            $debug_result .= "\r\nPesan         : <span style='font-family-sans'>Individu terbaik ditemukan pada generasi ke-{$best_generation} dan individu ke-{$best_individual_index}, namun fitness belum mencapai 1.</span>";
        }
        $debug_result .= "</strong></div>\n<div>Melakukan looping generasi ke {$generation_reached} dari max generasi {$max_generation}</div></pre>";
    
        // Menyiapkan array hasil
        return [
            'best_fitness' => $best_fitness,
            'best_generation' => $best_generation,
            'best_individual_index' => $best_individual_index,
            'generation_reached' => $generation_reached,
            'max_generation' => $max_generation,
            'execution_time' => $execution_time,
            'memory_usage' => round(memory_get_usage() / 1024 / 1024, 2) . ' MB',
            'total_conflict' => $conflict_result['conflict'],
            'debug_conflict' =>  $conflict_result['conflict'] !== 0 ? $conflict_result['debug_conflict'] : null,
            'best_individual' => $best_individual,
            'debug_result' => $debug_result,
            'debug_generation' => $debug_generation
        ];
    }
    
}
