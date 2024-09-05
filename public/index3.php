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

class Kelas {
    public $kode;
    public $nama;

    public function __construct($kode, $nama) {
        $this->kode = $kode;
        $this->nama = $nama;
    }
}

class Dosen {
    public $nama;
    public $nomer_pegawai;

    public function __construct($nama, $nomer_pegawai) {
        $this->nama = $nama;
        $this->nomer_pegawai = $nomer_pegawai;
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
    new Ruang("R5", "Ruang 5"),
    // new Ruang("R6", "Ruang 6"),
    // new Ruang("R7", "Ruang 7"),
    // new Ruang("R8", "Ruang 8"),
    // new Ruang("R9", "Ruang 9"),
];

// Membuat data dummy Waktu untuk hari Senin hingga Jumat
$waktuList = [
    // Senin
    new Waktu("Senin", "08:00 - 10:00"),
    new Waktu("Senin", "11:00 - 13:00"),
    new Waktu("Senin", "14:00 - 16:00"),
    // Selasa
    new Waktu("Selasa", "08:00 - 10:00"),
    new Waktu("Selasa", "11:00 - 13:00"),
    new Waktu("Selasa", "14:00 - 16:00"),
    // Rabu
    new Waktu("Rabu", "08:00 - 10:00"),
    new Waktu("Rabu", "11:00 - 13:00"),
    new Waktu("Rabu", "14:00 - 16:00"),
    // Kamis
    // new Waktu("Kamis", "08:00 - 10:00"),
    // new Waktu("Kamis", "11:00 - 13:00"),
    // new Waktu("Kamis", "14:00 - 16:00"),
    // Jumat
    // new Waktu("Jumat", "09:00 - 11:00"),
    // new Waktu("Jumat", "14:00 - 16:00"),
];

// Membuat data dummy Kelas
$kelasList = [
    new Ruang("TI-A", "Kelas TI A"),
    new Ruang("TI-B", "Kelas TI B"),
    // new Ruang("TI-C", "Kelas TI C"),
    // // new Ruang("TI-D", "Kelas TI D"),
    // // new Ruang("TI-E", "Kelas TI E")
];

// Membuat data dummy Dosen
$dosenList = [
    new Dosen("Dr. A", "1234567890"),
    new Dosen("Dr. B", "1234567891"),
    new Dosen("Dr. C", "1234567892"),
    new Dosen("Dr. D", "1234567893"),
    new Dosen("Dr. E", "1234567894"),
    // new Dosen("Dr. F", "1234567895"),
    // new Dosen("Dr. G", "1234567896"),
    // new Dosen("Dr. H", "1234567897"),
    // new Dosen("Dr. I", "1234567898"),
    // new Dosen("Dr. J", "1234567899"),
];

/// Inisialisasi individu
function create_individual($kelasList, $matkulList, $ruangList, $waktuList, $dosenList) {
    $individu = [];

    // Loop melalui setiap kelas dan atur mata kuliah mereka
    foreach ($kelasList as $kelas) {
        foreach ($matkulList as $matkul) {
            $individu[] = [
                'kelas' => $kelas,
                'matkul' => $matkul,
                'ruang' => $ruangList[array_rand($ruangList)],
                'waktu' => $waktuList[array_rand($waktuList)],
                'dosen' => $dosenList[array_rand($dosenList)],
            ];
        }
    }

    return $individu;
}

// Membuat populasi
function create_population($size, $kelasList, $matkulList, $ruangList, $waktuList, $dosenList) {
    $population = [];
    for ($i = 0; $i < $size; $i++) {
        $population[] = create_individual($kelasList, $matkulList, $ruangList, $waktuList, $dosenList);
    }
    return $population;
}

// Evaluasi fitness
function calculate_fitness($individual) {
    $conflicts = 0;
    $count = count($individual);

    // Cek benturan waktu dan ruang antar jadwal
    for ($i = 0; $i < $count; $i++) {
        for ($j = $i + 1; $j < $count; $j++) {
            if ($individual[$i]['ruang']->kode === $individual[$j]['ruang']->kode &&
                $individual[$i]['waktu']->hari === $individual[$j]['waktu']->hari &&
                $individual[$i]['waktu']->jam === $individual[$j]['waktu']->jam) {
                // Cek apakah dua kelas yang berbeda dijadwalkan di ruangan, waktu dan dosen yang sama
                if ($individual[$i]['kelas']->kode !== $individual[$j]['kelas']->kode) {
                    $conflicts++;
                }
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
    $individual[$mutation_point]['ruang'] = $ruangList[array_rand($ruangList)];
    $individual[$mutation_point]['waktu'] = $waktuList[array_rand($waktuList)];
    $individual[$mutation_point]['dosen'] = $dosenList[array_rand($dosenList)];

    return $individual;
}

// Menjalankan algoritma genetika
function genetic_algorithm($kelasList, $matkulList, $ruangList, $waktuList, $dosenList, $population_size, $generations) {
    $time_start = microtime(true); // Mulai waktu eksekusi
    $population = create_population($population_size, $kelasList, $matkulList, $ruangList, $waktuList, $dosenList);
    $best_fitness = 0;
    $best_individual = null;
    $best_individual_index = 0;
    $best_generation = 0;
    $generation_reached = 0;

    for ($generation = 1; $generation <= $generations; $generation++) {
        $fitness_values = [];
        foreach ($population as $individual) {
            $fitness_values[] = calculate_fitness($individual);
        }

        echo "=======Generasi $generation:\n =========== <br/>";
        foreach ($population as $index => $individual) {
            echo "Individu $index - Fitness: {$fitness_values[$index]}\n <br/>";
            // echo "<pre>";
            foreach ($individual as $schedule) {
                echo " [{$schedule['kelas']->kode}, {$schedule['matkul']->kode}, {$schedule['ruang']->kode}, {$schedule['waktu']->hari} {$schedule['waktu']->jam}, {$schedule['dosen']->nama}] ||| \n";
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
            $parent1 = roulette_wheel_selection($population, $fitness_values);
            $parent2 = roulette_wheel_selection($population, $fitness_values);
            list($child1, $child2) = crossover($parent1, $parent2);

            $child1 = mutate($child1, $ruangList, $waktuList, $dosenList);
            $child2 = mutate($child2, $ruangList, $waktuList, $dosenList);

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
        echo "[Kelas: {$schedule['kelas']->kode}, Matkul: {$schedule['matkul']->kode}, Ruang: {$schedule['ruang']->kode}, Waktu: {$schedule['waktu']->hari} {$schedule['waktu']->jam} , Dosen: {$schedule['dosen']->nama}]\n";
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

// Parameter dan eksekusi
$population_size = 10;
$generations = 100;

genetic_algorithm($kelasList, $matkulList, $ruangList, $waktuList, $dosenList, $population_size, $generations);