# === CONFIGURACIÓN ===
$carpetaPDF = "C:\wamp64\www\sis_preparacion\revolturas\reportes\pdfs"
$fechaHoy = (Get-Date).ToString("yyyy-MM-dd")

# Archivos esperados
$archivo1 = "producto_terminado_empacado_$fechaHoy.pdf"
$archivo2 = "producto_terminado_sin_empacar_$fechaHoy.pdf"
$rutaArchivo1 = Join-Path $carpetaPDF $archivo1
$rutaArchivo2 = Join-Path $carpetaPDF $archivo2

# Ruta del PHP
$phpPath = "C:\wamp64\bin\php\php8.3.6\php.exe"
$scriptPHP = "C:\wamp64\www\sis_preparacion\revolturas\reportes\enviar_correo.php"
$logPath = "C:\wamp64\www\sis_preparacion\revolturas\reportes\verifica_reporte.log"

# === VERIFICACIÓN ===
if (-Not (Test-Path $rutaArchivo1) -or -Not (Test-Path $rutaArchivo2)) {
    Write-Output "❌ [$fechaHoy] Faltan reportes:"
    if (-Not (Test-Path $rutaArchivo1)) { Write-Output " - Falta: $archivo1" }
    if (-Not (Test-Path $rutaArchivo2)) { Write-Output " - Falta: $archivo2" }

    Write-Output "⏳ Ejecutando el script PHP..."
    Start-Process -FilePath $phpPath -ArgumentList "`"$scriptPHP`"" -NoNewWindow -Wait

    $log = "$(Get-Date -Format 'yyyy-MM-dd HH:mm:ss') - Ejecutado script por falta de archivos: $archivo1 / $archivo2"
    Add-Content -Path $logPath -Value $log
} else {
    Write-Output "✅ [$fechaHoy] Ambos archivos existen. No se ejecuta el script."
}
