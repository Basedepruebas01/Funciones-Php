<?php

/* Nombre del programador
   Curso: Desarrollo Web con Php
   Evidencia 2: Taller: “Uso de funciones” */

// Función para sumar dos números
function sumar($num1, $num2){
    if ($num1 === "" || $num2 === "") {
        return "Error: ambos campos deben estar llenos.";
    }
    if (!is_numeric($num1) || !is_numeric($num2)) {
        return "Error: los valores deben ser numéricos.";
    }
    return $num1 + $num2;
}

// Función para restar dos números
function restar($num1, $num2){
    if ($num1 === "" || $num2 === "") {
        return "Error: ambos campos deben estar llenos.";
    }
    if (!is_numeric($num1) || !is_numeric($num2)) {
        return "Error: los valores deben ser numéricos.";
    }
    return $num1 - $num2;
}

// Función para multiplicar dos números
function multiplicar($num1, $num2){
    if ($num1 === "" || $num2 === "") {
        return "Error: ambos campos deben estar llenos.";
    }
    if (!is_numeric($num1) || !is_numeric($num2)) {
        return "Error: los valores deben ser numéricos.";
    }
    return $num1 * $num2;
}

// Función para dividir dos números
function dividir($num1, $num2){
    if ($num1 === "" || $num2 === "") {
        return "Error: ambos campos deben estar llenos.";
    }
    if (!is_numeric($num1) || !is_numeric($num2)) {
        return "Error: los valores deben ser numéricos.";
    }
    if ($num2 == 0) {
        return "Error: no se puede dividir entre cero.";
    }
    return $num1 / $num2;
}

?>
