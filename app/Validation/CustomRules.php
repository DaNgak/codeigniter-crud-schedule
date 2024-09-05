<?php

namespace App\Validation;

class CustomRules 
{
 /**
     * Memeriksa apakah nilai ada di tabel model yang ditentukan.
     *
     * @param string $value Nilai yang harus diperiksa
     * @param string $params Parameter yang berisi nama model dan atribut, dipisahkan dengan koma
     * @param array $data Data input (tidak digunakan di sini, bisa diabaikan)
     *
     * @return bool True jika data ada, false jika tidak
     */
    public function check_exists(string $value, string $params, array $data): bool
    {
        // Pisahkan parameter menjadi nama model dan atribut
        list($modelName, $attribute) = explode(',', $params);

        // Ubah nama model menjadi huruf besar
        $modelName = ucfirst($modelName);

        // Buat nama lengkap modelnya (\App\Models\NamaModel)
        $fullModelClass = "\\App\\Models\\" . $modelName . "Model";

        // return var_dump($fullModelClass);
        
        // Cek apakah class model ada
        if (!class_exists($fullModelClass)) {
            return false; // Model tidak valid
        }

        // Buat instance dari model
        $model = new $fullModelClass();

        // Pastikan atribut ada di tabel menggunakan where()
        $result = $model->where($attribute, $value)->first();

        // Return true jika data ditemukan, false jika tidak
        return $result !== null;
    }
}
