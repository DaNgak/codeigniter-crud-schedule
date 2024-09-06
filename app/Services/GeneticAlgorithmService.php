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
                    $new_schedule = [
                        'kelas' => $kelas,
                        'mata_kuliah' => $matkul,
                        'ruangan' => $ruangList[array_rand($ruangList)],
                        'waktu_kuliah' => $waktuList[array_rand($waktuList)],
                        'dosen' => $dosenList[array_rand($dosenList)],
                    ];
                    $is_unique = true;
                    foreach ($schedules as $schedule) {
                        if ($new_schedule['kelas']['kode'] === $schedule['kelas']['kode'] &&
                            $new_schedule['mata_kuliah']['kode'] === $schedule['mata_kuliah']['kode'] &&
                            $new_schedule['ruangan']['kode'] === $schedule['ruangan']['kode'] &&
                            $new_schedule['waktu_kuliah']['hari'] === $schedule['waktu_kuliah']['hari'] &&
                            $new_schedule['waktu_kuliah']['jam_mulai'] === $schedule['waktu_kuliah']['jam_mulai'] &&
                            $new_schedule['waktu_kuliah']['jam_selesai'] === $schedule['waktu_kuliah']['jam_selesai'] &&
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
    function calculate_fitness($individual) {
        $conflicts = 0;
        $count = count($individual);
        $schedules = [];
        // Cek benturan waktu dan ruang antar jadwal
        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                if ($individual[$i]['ruangan']['kode'] === $individual[$j]['ruangan']['kode'] &&
                    $individual[$i]['waktu_kuliah']['hari'] === $individual[$j]['waktu_kuliah']['hari'] &&
                    $individual[$i]['waktu_kuliah']['jam_mulai'] === $individual[$j]['waktu_kuliah']['jam_mulai'] &&
                    $individual[$i]['waktu_kuliah']['jam_selesai'] === $individual[$j]['waktu_kuliah']['jam_selesai']) {
                    // Cek apakah dua kelas yang berbeda dijadwalkan di ruangan, waktu dan dosen yang sama
                    if ($individual[$i]['kelas']['kode'] !== $individual[$j]['kelas']['kode']) {
                        $conflicts++;
                    }
                }
                // Memeriksa keunikan jadwal dalam individu
                $schedule_key = "{$individual[$i]['kelas']['kode']}-{$individual[$i]['mata_kuliah']['kode']}-{$individual[$i]['ruangan']['kode']}-{$individual[$i]['waktu_kuliah']['hari']}-{$individual[$i]['waktu_kuliah']['jam_mulai']}-{$individual[$i]['waktu_kuliah']['jam_selesai']}-{$individual[$i]['dosen']['nama']}";
                if (in_array($schedule_key, $schedules)) {
                    $conflicts++;
                } else {
                    $schedules[] = $schedule_key;
                }
            }            
        }

        return 1 / (1 + $conflicts); // Fitness lebih tinggi jika konflik lebih sedikit
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
            $new_schedule['ruangan'] = $ruangList[array_rand($ruangList)];
            $new_schedule['waktu_kuliah'] = $waktuList[array_rand($waktuList)];
            $new_schedule['dosen'] = $dosenList[array_rand($dosenList)];

            $is_unique = true;
            foreach ($individual as $schedule) {
                if ($schedule !== $new_schedule &&
                    $new_schedule['kelas']['kode'] === $schedule['kelas']['kode'] &&
                    $new_schedule['mata_kuliah']['kode'] === $schedule['mata_kuliah']['kode'] &&
                    $new_schedule['ruangan']['kode'] === $schedule['ruangan']['kode'] &&
                    $new_schedule['waktu_kuliah']['hari'] === $schedule['waktu_kuliah']['hari'] &&
                    $new_schedule['waktu_kuliah']['jam_mulai'] === $schedule['waktu_kuliah']['jam_mulai'] &&
                    $new_schedule['waktu_kuliah']['jam_selesai'] === $schedule['waktu_kuliah']['jam_selesai'] &&
                    $new_schedule['dosen']['nama'] === $schedule['dosen']['nama']) {
                    $is_unique = false;
                    break;
                }
            }
        }

        $individual[$mutation_point] = $new_schedule;
        return $individual;
    }

    // Menjalankan algoritma genetika
    function genetic_algorithm($kelasList, $matkulList, $ruangList, $waktuList, $dosenList, $population_size, $generations) {
        $time_start = microtime(true); // Mulai waktu eksekusi
        $population = $this->create_population($population_size, $kelasList, $matkulList, $ruangList, $waktuList, $dosenList);
        $best_fitness = 0;
        $best_individual = null;
        $best_individual_index = 0;
        $best_generation = 0;
        $generation_reached = 0;

        for ($generation = 1; $generation <= $generations; $generation++) {
            $fitness_values = [];
            foreach ($population as $individual) {
                $fitness_values[] = $this->calculate_fitness($individual);
            }

            echo "=======Generasi $generation:\n =========== <br/>";
            foreach ($population as $index => $individual) {
                echo "Individu $index - Fitness: {$fitness_values[$index]}\n <br/>";
                // echo "<pre>";
                foreach ($individual as $schedule) {
                    echo " [{$schedule['kelas']['kode']}, {$schedule['mata_kuliah']['kode']}, {$schedule['ruangan']['kode']}, {$schedule['waktu_kuliah']['hari']} {$schedule['waktu_kuliah']['jam_mulai']} - {$schedule['waktu_kuliah']['jam_selesai']}, {$schedule['dosen']['nama']}] ||| \n";
                }                
                // echo "</pre><br/>";
                echo "<br/><br/>";
            }

            // Mencari fitness terbaik dalam populasi ini
            $max_fitness = max($fitness_values);
            if ($max_fitness >= $best_fitness) {
                $best_fitness = $max_fitness;
                $best_generation = $generation;
                $best_individual = $population[array_search($max_fitness, $fitness_values)];
                $best_individual_index = array_search($max_fitness, $fitness_values); // Simpan indeks individu terbaik
            }

            // Jika ada individu dengan fitness 1, berhenti
            if ($best_fitness == 1) {
                $generation_reached = $generation;
                // $best_generation = $generation;
                // $best_individual = $population[array_search($max_fitness, $fitness_values)];
                // $best_individual_index = array_search($max_fitness, $fitness_values); // Simpan indeks individu terbaik
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
        
        // Menampilkan hasil terbaik
        echo "<pre style='color:black; font-size:0.8rem'>========== HASIL ALGORITMA GENETIKA ========== \n";
        echo "\r\nFITNESS TERBAIK       : " . $best_fitness;
        echo "\r\nGENERASI              : " . $best_generation;
        echo "\r\nEXECUTION TIME        : " . $execution_time . " detik";
        echo "\r\nMEMORY USAGE          : " . round(memory_get_usage() / 1024 / 1024, 2) . " MB";
        echo "\r\nINDIVIDU TERBAIK      : \n";
        
        foreach ($best_individual as $schedule) {
            echo "[Kelas: {$schedule['kelas']['kode']}, Matkul: {$schedule['mata_kuliah']['kode']}, Ruang: {$schedule['ruangan']['kode']}, Waktu: {$schedule['waktu_kuliah']['hari']} {$schedule['waktu_kuliah']['jam_mulai']} - {$schedule['waktu_kuliah']['jam_selesai']} , Dosen: {$schedule['dosen']['nama']}]\n";
        }        

        echo "<div class='notic'><strong>";
        if ($best_fitness == 1) {
            // Jika fitness terbaik adalah 1, berarti individu terbaik ditemukan
            echo "\r\nPesan         : <span style='font-family-sans'>Individu terbaik berhasil ditemukan pada generasi ke-{$best_generation} dan individu ke-{$best_individual_index}!</span>";             
        } else {
            // Jika fitness terbaik belum mencapai 1, tampilkan informasi generasi dan individu terbaik
            echo "\r\nPesan         : <span style='font-family-sans'>Individu terbaik ditemukan pada generasi ke-{$best_generation} dan individu ke-{$best_individual_index}, namun fitness belum mencapai 1.</span>";             
        }
        echo "</strong></div><div>Melakukan looping generasi ke {$generation_reached} dari max generasi {$generations}</div></pre>";

        return $best_individual;
    }
}
