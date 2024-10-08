<?php

class Matkul {
    public $kode;
    public $nama;

    public function __construct($kode, $nama) {
        $this->kode = $kode;
        $this->nama = $nama;
    }
}

class Ruang {
    public $kode;
    public $nama;

    public function __construct($kode, $nama) {
        $this->kode = $kode;
        $this->nama = $nama;
    }
}

class Waktu {
    public $hari;
    public $jam;

    public function __construct($hari, $jam) {
        $this->hari = $hari;
        $this->jam = $jam;
    }
}

// Membuat data dummy Matkul
$matkulList = [
    new Matkul("MK1", "Matematika"),
    new Matkul("MK2", "Fisika"),
    new Matkul("MK3", "Kimia"),
    new Matkul("MK4", "Biologi"),
    new Matkul("MK5", "Sejarah"),
    new Matkul("MK6", "Geografi"),
    new Matkul("MK7", "Bahasa Indonesia"),
    new Matkul("MK8", "Bahasa Inggris"),
    new Matkul("MK9", "Seni"),
    new Matkul("MK10", "Pendidikan Jasmani")
];

// Membuat data dummy Ruang
$ruangList = [
    new Ruang("R1", "Ruang 1"),
    new Ruang("R2", "Ruang 2"),
    new Ruang("R3", "Ruang 3"),
    new Ruang("R4", "Ruang 4"),
    new Ruang("R5", "Ruang 5")
];

// Membuat data dummy Waktu
$waktuList = [
    new Waktu("Senin", "08:00 - 10:00"),
    new Waktu("Senin", "11:00 - 13:00"),
    new Waktu("Senin", "14:00 - 16:00"),
];

// Inisialisasi populasi
function create_individual($matkulList, $ruangList, $waktuList) {
    $individu = [];
    foreach ($matkulList as $matkul) {
        $individu[] = [
            'matkul' => $matkul,
            'ruang' => $ruangList[array_rand($ruangList)],
            'waktu' => $waktuList[array_rand($waktuList)],
        ];
    }
    return $individu;
}

function create_population($size, $matkulList, $ruangList, $waktuList) {
    $population = [];
    for ($i = 0; $i < $size; $i++) {
        $population[] = create_individual($matkulList, $ruangList, $waktuList);
    }
    return $population;
}

// Evaluasi fitness
function calculate_fitness($individual) {
    $conflicts = 0;
    $count = count($individual);

    for ($i = 0; $i < $count; $i++) {
        for ($j = $i + 1; $j < $count; $j++) {
            if ($individual[$i]['ruang']->kode === $individual[$j]['ruang']->kode &&
                $individual[$i]['waktu']->hari === $individual[$j]['waktu']->hari &&
                $individual[$i]['waktu']->jam === $individual[$j]['waktu']->jam) {
                $conflicts++;
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
function mutate($individual, $ruangList, $waktuList) {
    $mutation_point = rand(0, count($individual) - 1);
    $individual[$mutation_point]['ruang'] = $ruangList[array_rand($ruangList)];
    $individual[$mutation_point]['waktu'] = $waktuList[array_rand($waktuList)];

    return $individual;
}

// Menjalankan algoritma genetika
function genetic_algorithm($matkulList, $ruangList, $waktuList, $population_size, $generations) {
    $time_start = microtime(true); // Mulai waktu eksekusi
    $population = create_population($population_size, $matkulList, $ruangList, $waktuList);
    $best_fitness = 0;
    $best_individual = null;
    $generation_reached = 0;

    for ($generation = 1; $generation <= $generations; $generation++) {
        $fitness_values = [];
        foreach ($population as $individual) {
            $fitness_values[] = calculate_fitness($individual);
        }

        echo "=======Generasi $generation:\n =========== <br/>";
        foreach ($population as $index => $individual) {
            echo "Individu $index - Fitness: {$fitness_values[$index]}\n <br/>";
            foreach ($individual as $schedule) {
                echo " [{$schedule['matkul']->kode}, {$schedule['ruang']->kode}, {$schedule['waktu']->hari} {$schedule['waktu']->jam}\n], ";
            }
            echo "\n<br/><br/>";
        }

        // Mencari fitness terbaik dalam populasi ini
        $max_fitness = max($fitness_values);
        if ($max_fitness > $best_fitness) {
            $best_fitness = $max_fitness;
            $best_individual = $population[array_search($max_fitness, $fitness_values)];
            $best_individual_index = array_search($max_fitness, $fitness_values); // Simpan indeks individu terbaik
        }

        // Jika ada individu dengan fitness 1, berhenti
        if ($best_fitness == 1) {
            $generation_reached = $generation;
            $best_individual_index = array_search($max_fitness, $fitness_values); // Simpan indeks individu terbaik
            break;
        }

        // Seleksi dan pembentukan populasi baru
        $new_population = [];
        while (count($new_population) < $population_size) {
            $parent1 = roulette_wheel_selection($population, $fitness_values);
            $parent2 = roulette_wheel_selection($population, $fitness_values);
            list($child1, $child2) = crossover($parent1, $parent2);

            $child1 = mutate($child1, $ruangList, $waktuList);
            $child2 = mutate($child2, $ruangList, $waktuList);

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
    echo "\r\nGENERASI              : " . $generation_reached;
    echo "\r\nEXECUTION TIME        : " . $execution_time . " detik";
    echo "\r\nMEMORY USAGE          : " . round(memory_get_usage() / 1024 / 1024, 2) . " MB";
    echo "\r\nINDIVIDU TERBAIK      : \n";
    
    foreach ($best_individual as $schedule) {
        echo "[Matkul: {$schedule['matkul']->kode}, Ruang: {$schedule['ruang']->kode}, Waktu: {$schedule['waktu']->hari} {$schedule['waktu']->jam}]\n";
    }

    echo "<div class='notic'><strong>";
    if ($best_fitness == 1) {
        // Jika fitness terbaik adalah 1, berarti individu terbaik ditemukan
        echo "\r\nPesan         : <span style='font-family-sans'>Individu terbaik berhasil ditemukan pada generasi ke-{$generation_reached} dan individu ke-{$best_individual_index}!</span>";             
    } else {
        // Jika fitness terbaik belum mencapai 1, tampilkan informasi generasi dan individu terbaik
        echo "\r\nPesan         : <span style='font-family-sans'>Individu terbaik ditemukan pada generasi ke-{$generation_reached} dan individu ke-{$best_individual_index}, namun fitness belum mencapai 1.</span>";             
    }
    echo "</strong></div></pre>";
    
}

// Parameter dan eksekusi
$population_size = 10;
$generations = 50;

genetic_algorithm($matkulList, $ruangList, $waktuList, $population_size, $generations);