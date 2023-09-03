<?php
use Illuminate\Support\Facades\DB;

try {
    $results = DB::select('SELECT * FROM users');
    foreach ($results as $result) {
        echo "ID: " . $result->id . "<br>";
        echo "Name: " . $result->username . "<br>";
    }
    echo "Conexão com o banco de dados está funcionando!";
} catch (\Exception $e) {
    echo "Erro na conexão com o banco de dados: " . $e->getMessage();
}

?>