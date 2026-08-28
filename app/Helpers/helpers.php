<?php

use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\DB;

if (!function_exists('generarNumeroDocumento')) {

    /**
     * Genera la numeración correlativa por tienda.
     * Formato: {PREFIJO}-{CODIGO_TIENDA}-{correlativo de 5 dígitos}
     * Ej: VTA-T01-00012, ING-T02-00001, TRF-T01-00003
     */
    function generarNumeroDocumento($prefijo, $tabla, $idTienda, $campoTienda = 'id_tienda')
    {
        $correlativo = (int) DB::table($tabla)->where($campoTienda, $idTienda)->max('correlativo');
        $correlativo++;

        $codigoTienda = DB::table('tiendas')->where('id_tienda', $idTienda)->value('codigo') ?: '00';

        return [
            'numero'      => $prefijo . '-' . $codigoTienda . '-' . str_pad($correlativo, 5, '0', STR_PAD_LEFT),
            'correlativo' => $correlativo,
        ];
    }
}

if (!function_exists('limpiarTextoEditor')) {

    /**
     * Limpia el HTML producido por el editor TinyMCE para mostrarlo
     * dentro de un contenedor <p>/texto simple: elimina un <p></p>
     * envolvente si existe, dejando el contenido interno.
     */
    function limpiarTextoEditor($text)
    {
        if (empty($text)) {
            return '';
        }

        $text = trim($text);

        // Elimina un único <p ...> ... </p> envolvente (y saltos/blancos alrededor)
        if (preg_match('/^<p[^>]*>(.*)<\/p>$/is', $text, $matches)) {
            return trim($matches[1]);
        }

        return $text;
    }
}

if (!function_exists('uploadImageOptimized')) {

    function uploadImageOptimized($file, $folder = 'general', $width = 1200, $quality = 80, $maxKb = 100)
    {
        $destinationPath = base_path('uploads/' . $folder);

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $extension = strtolower($file->getClientOriginalExtension());

        // SVG: GD no puede convertirlo, se guarda tal cual
        if ($extension === 'svg') {
            $fileName = time() . '_' . uniqid() . '.svg';
            $file->move($destinationPath, $fileName);

            return 'uploads/' . $folder . '/' . $fileName;
        }

        $fileName = time() . '_' . uniqid() . '.webp';

        $img = Image::gd()->read($file->getPathname());

        if ($width) {
            $img->scaleDown(width: $width);
        }

        $maxBytes = $maxKb * 1024;
        $encoded = $img->toWebp($quality);

        while ($quality > 30 && strlen($encoded) > $maxBytes) {
            $quality -= 10;
            $encoded = $img->toWebp($quality);
        }

        $encoded->save($destinationPath . '/' . $fileName);

        return 'uploads/' . $folder . '/' . $fileName;
    }
}