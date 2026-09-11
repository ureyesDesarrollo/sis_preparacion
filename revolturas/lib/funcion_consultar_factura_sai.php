<?php
// funcion_consultar_factura_sai.php

function consultarFacturaSai($folio)
{
    if (empty($folio)) {
        return [
            'success' => false,
            'error' => 'Folio no puede estar vacío'
        ];
    }

    $url = 'http://192.168.1.104:5000/api/facturas-venta/' . urlencode($folio);

    $api_key = 'SAI-REQ-2026-MI-CLAVE-SEGURA';

    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "X-API-Key: $api_key",
            "Accept: application/json"
        ],
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);

        return [
            'success' => false,
            'error' => 'Error cURL: ' . $error
        ];
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    $response = json_decode($response, true);

    if (isset($response['detail'])) {
        return [
            'success' => false,
            'error' => $response['detail']
        ];
    }

    if ($httpCode < 200 || $httpCode >= 300) {
        return [
            'success' => false,
            'error' => 'Error al consultar la factura'
        ];
    }

    return [
        'success' => true,
        'data' => $response
    ];
}