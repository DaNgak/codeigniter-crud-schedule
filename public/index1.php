<?php

// Inisialisasi: Membuat populasi awal dari individu (misalnya, string biner).
// Evaluasi: Menghitung fitness dari setiap individu.
// Seleksi: Memilih individu berdasarkan fitness mereka menggunakan roulette wheel.
// Persilangan: Membuat individu baru dari pasangan orang tua.
// Mutasi: Memodifikasi individu baru untuk menjaga keragaman.
// Class definitions for Matkul, Ruang, and Waktu
 
class Matkul {
    public $kode;
    public $nama;
    public $dosen;
 
    public function __construct($kode, $nama) {
        $this->kode = $kode;
        $this->nama = $nama;
        // $this->dosen = $dosen;
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
 
// Dummy Data
 
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
    // new Waktu("Selasa", "08:00 - 10:00"),
    // new Waktu("Selasa", "11:00 - 13:00"),
    // new Waktu("Selasa", "14:00 - 16:00"),
    // new Waktu("Rabu", "08:00 - 10:00"),
    // new Waktu("Rabu", "11:00 - 13:00"),
    // new Waktu("Rabu", "14:00 - 16:00"),
    // new Waktu("Kamis", "08:00 - 10:00"),
    // new Waktu("Kamis", "11:00 - 13:00"),
    // new Waktu("Kamis", "14:00 - 16:00"),
    // new Waktu("Jumat", "08:00 - 10:00"),
    // new Waktu("Jumat", "11:00 - 13:00"),
    // new Waktu("Jumat", "14:00 - 16:00")
];
 
 
// Class AlgoritmaGenetika
 
class AlgoritmaGenetika {
    public $time_start, $time_end, $max_generation, $best_fitness;
    public $success = false;
    public $generation = 0;
    public $console = "";
    public $crommosom = [];
    public $fitness = [];
    public $probabilities = [];
    public $best_crommosom;
 
    private $matkulList;
    private $ruangList;
    private $waktuList;
 
    public function __construct($matkulList, $ruangList, $waktuList) {
        $this->matkulList = $matkulList;
        $this->ruangList = $ruangList;
        $this->waktuList = $waktuList;
    }
 
 
    function generate(){
        $this->time_start = microtime(true);
        $this->generate_cromosom();
 
        while (($this->generation < $this->max_generation) && ($this->success == false)) {
            $this->generation++;
            $this->console .= "<h3>Generation $this->generation</h3>";
            $this->calculate_all_fitness();
            $this->show_crommosom();
            $this->show_fitness();
 
            if (!$this->success) {
                $this->get_com_pro();
                $this->selection();
                $this->show_crommosom();
            }
 
            if (!$this->success) {
                $this->crossover();
                $this->show_crommosom();
                $this->show_fitness();
                $this->mutation(); // Mutation should occur after crossover
            }
        }
 
        $this->save_result();
        $this->time_end = microtime(true);
 
        $seconds = round($this->time_end - $this->time_start, 2);
        echo "<pre style='color:black; font-size:0.8rem'>\r\nFITNESS TERBAIK       : " . $this->best_fitness;
        echo "\r\nGENERASI              : " . $this->generation;
        echo "\r\nEXECUTION TIME        : " . $seconds . " detik";
        echo "\r\nMEMORY USAGE          : " . round(memory_get_usage() / 1024 / 1024);
        echo "\r\nCROMOSSOM TERBAIK     : " . $this->print_cros($this->crommosom[$this->best_crommosom]);
        echo "\n<div class='notic'><strong>";
        if (count($this->crommosom[$this->best_crommosom]) > 0) {
            echo "\r\nPesan         : <span style='font-family-sans'>Kromosom terbaik berhasil ditemukan!</span>";             
        }
        echo "</strong></div>";
        $this->get_debug();
    }
 
    function generate_cromosom(){
        // Generate initial population
        $this->crommosom = [];
        for ($i = 0; $i < 10; $i++) { // Assuming a population of 10 individuals
            $this->crommosom[] = $this->get_rand_crommosom();
        }
    }
 
    function show_crommosom(){
        $cros = $this->crommosom;
        $a = array();
        foreach($cros as $key => $val) {
            $a[] = $this->print_cros($val, $key);
        }
 
        $this->console .= implode(" \r\n", $a) . "\r\n";
    }
 
    function get_rand_crommosom(){
        // Randomly generate a cromosom
        $matkul = $this->matkulList;
        $ruang = $this->ruangList;
        $waktu = $this->waktuList;
 
        $cromosom = [];
        foreach ($matkul as $mk) {
            $random_ruang = $ruang[array_rand($ruang)];
            $random_waktu = $waktu[array_rand($waktu)];
            $cromosom[] = [$mk->kode, $random_ruang->kode, $random_waktu->hari, $random_waktu->jam];
        }
 
        return $cromosom;
    }
 
    function calculate_all_fitness(){
        foreach ($this->crommosom as $key => $crom) {
            $this->fitness[$key] = $this->calculate_fitness($crom);
        }
        $this->best_fitness = max(array_column($this->fitness, 'fitness'));
        $this->best_crommosom = array_search($this->best_fitness, array_column($this->fitness, 'fitness'));
        if ($this->best_fitness >= 1) { // Assume 1 is the ideal fitness
            $this->success = true;
        }
    }
 
    function calculate_fitness($crom){
        $clash_dosen = $this->get_clash_dosen($crom);
        $clash_ruang = $this->get_clash_ruang($crom);
 
        // Calculate fitness (lower clash means higher fitness)
        $fitness = 1 / (1 + $clash_dosen + $clash_ruang);
 
        return ['fitness' => $fitness, 'clash' => ['dosen' => $clash_dosen, 'ruang' => $clash_ruang]];
    }
 
    function get_clash_dosen($crom = array()) {
        $clashes = 0;
        foreach ($crom as $i => $gen1) {
            foreach ($crom as $j => $gen2) {
                if ($i !== $j && $gen1[0] == $gen2[0] && $this->is_time_clash($gen1, $gen2)) {
                    $clashes++;
                }
            }
        }
        return $clashes;
    }
 
    function is_time_clash($gen1, $gen2) {
        return $gen1[2] === $gen2[2] && $gen1[3] === $gen2[3];
    }
 
    function get_clash_ruang($crom = array()){
        $clashes = 0;
        foreach ($crom as $i => $gen1) {
            foreach ($crom as $j => $gen2) {
                if ($i !== $j && $gen1[1] == $gen2[1] && $this->is_time_clash($gen1, $gen2)) {
                    $clashes++;
                }
            }
        }
        return $clashes;
    }
 
    function show_fitness(){
        $this->console .= "<pre style='color:black; font-size:0.8rem'>";
        foreach ($this->fitness as $key => $fit) {
            $this->console .=  "Fitnes dari kromosom [$key]: " . $fit['fitness'] . "<br/>";
        }
        $this->console .=  "</pre><br/><hr/>";
    }
 
    function get_com_pro(){
        $total_fitness = $this->get_total_fitness();
        foreach ($this->fitness as $key => $fit) {
            $this->probabilities[$key] = $fit['fitness'] / $total_fitness;
        }
    }
 
    function get_total_fitness(){
        return array_sum(array_column($this->fitness, 'fitness'));
    }
 
    function get_probability(){
        // Already handled in get_com_pro
    }
 
    function selection(){
        $selected = [];
        for ($i = 0; $i < count($this->crommosom); $i++) {
            $rand = $this->get_rand(count($this->crommosom) - 1);
            $selected[] = $this->choose_selection($rand);
        }
        $this->crommosom = $selected;
    }
 
    function get_rand($max = 0){
        return mt_rand(0, $max);
    }
 
    function choose_selection($rand_numb = 0){
        $cumulative = 0;
        foreach ($this->probabilities as $key => $prob) {
            $cumulative += $prob;
            if ($rand_numb <= $cumulative) {
                return $this->crommosom[$key];
            }
        }
        return end($this->crommosom); // In case rounding issues occur
    }
 
    function crossover(){
        $offsprings = [];
        for ($i = 0; $i < count($this->crommosom) / 2; $i++) {
            $key1 = mt_rand(0, count($this->crommosom) - 1);
            $key2 = mt_rand(0, count($this->crommosom) - 1);
            $offsprings[] = $this->get_crossover($key1, $key2);
        }
        $this->crommosom = $offsprings;
    }
 
    function get_crossover($key1, $key2){
        $point = mt_rand(1, count($this->crommosom[$key1]) - 2);
        $child1 = array_merge(array_slice($this->crommosom[$key1], 0, $point), array_slice($this->crommosom[$key2], $point));
        $child2 = array_merge(array_slice($this->crommosom[$key2], 0, $point), array_slice($this->crommosom[$key1], $point));
 
        return $this->calculate_fitness($child1)['fitness'] > $this->calculate_fitness($child2)['fitness'] ? $child1 : $child2;
    }
 
    function mutation(){
        foreach ($this->crommosom as $key => $crom) {
            if (mt_rand(1, 100) <= 5) { // 5% mutation rate
                $this->crommosom[$key] = $this->get_rand_crommosom();
            }
        }
    }
 
    function save_result(){
        $result = $this->print_cros($this->crommosom[$this->best_crommosom]);
        file_put_contents('result.txt', $result);
    }
 
    // Ini adalah fungsi untuk menampilkan kromosom (Matkul, Ruang, Waktu)
    function print_cros($val = array(), $key = 0) {
        // Pastikan clash_dosen dan clash_ruang adalah array
        $clash_dosen = isset($this->fitness[$key]['clash']['dosen']) ? (array) $this->fitness[$key]['clash']['dosen'] : [];
        $clash_ruang = isset($this->fitness[$key]['clash']['ruang']) ? (array) $this->fitness[$key]['clash']['ruang'] : [];
 
        // Gabungkan semua potensi tabrakan dari beberapa sumber.
        $clash = array_merge($clash_dosen, $clash_ruang);
 
        $arr = array();
        foreach ($val as $k => $v) {
            if (in_array($k, (array) $clash)) {
                $arr[] = '<span class="text-danger">[' . implode(',', $v) . ']</span>';
            } else {
                $arr[] = '[' . implode(',', $v) . ']';
            }
        }
        return "Kromosom [$key]: ( " . implode(",", $arr) . ")";
    }
 
 
    function get_debug(){
        // Output any necessary debug information
        echo $this->console;
    }
}
 
// Example usage
$algo = new AlgoritmaGenetika($matkulList, $ruangList, $waktuList);
$algo->max_generation = 100;
$algo->generate();