<?php

use Intervention\Image\Facades\Image;
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

if (!function_exists('uploadImageOptimized')) {

    function uploadImageOptimized($file, $folder = 'general', $width = 1200, $quality = 80, $maxKb = 100)
    {
        $destinationPath = public_path('uploads/' . $folder);

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

        $img = Image::make($file);

        if ($width) {
            $img->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        $img->encode('webp', $quality);

        // Baja la calidad de forma progresiva hasta que pese menos del objetivo
        $maxBytes = $maxKb * 1024;

        while ($quality > 30 && strlen($img->getEncoded()) > $maxBytes) {
            $quality -= 10;
            $img->encode('webp', $quality);
        }

        $img->save($destinationPath . '/' . $fileName, $quality);

        return 'uploads/' . $folder . '/' . $fileName;
    }
}