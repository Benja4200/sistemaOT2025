<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Rut implements Rule
{
    public function passes($attribute, $value)
    {
        return $this->validateRut($value);
    }

    public function message()
    {
        return 'El :attribute ingresado no es un RUT válido.';
    }

    private function validateRut($rut)
    {
        // Eliminar puntos y guiones
        $rut = str_replace(['.', '-'], '', $rut);

        // Validar que el RUT tenga el formato correcto
        if (!preg_match('/^\d{7,8}[0-9K]$/', $rut)) {
            return false;
        }

        // Separar el número y el dígito verificador
        $numero = substr($rut, 0, -1);
        $dv = strtoupper(substr($rut, -1));

        // Calcular el dígito verificador
        $suma = 0;
        $multiplicador = 2;

        for ($i = strlen($numero) - 1; $i >= 0; $i--) {
            $suma += $numero[$i] * $multiplicador;
            $multiplicador = $multiplicador == 7 ? 2 : $multiplicador + 1;
        }

        $dvCalculado = 11 - ($suma % 11);
        if ($dvCalculado == 11) {
            $dvCalculado = '0';
        } elseif ($dvCalculado == 10) {
            $dvCalculado = 'K';
        }

        return $dv == $dvCalculado;
    }
}