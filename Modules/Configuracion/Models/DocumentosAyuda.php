<?php

namespace Modules\Configuracion\Models;

use App\Http\Controllers\GoogleController;
use Illuminate\Database\Eloquent\Model;

class DocumentosAyuda extends Model
{
    public $table = 'configuracion_general_documentos_ayuda';

    public $fillable = [
        'nombre',
        'descripcion',
        'documento',
        'videos_url',
        'imagen_previa',
        'users_id'
    ];

    protected $casts = [
        'nombre' => 'string',
        'descripcion' => 'string',
        'documento' => 'string',
        'videos_url' => 'string',
        'imagen_previa' => 'string'
    ];

    public static array $rules = [
        'nombre' => 'nullable|string|max:200',
        'descripcion' => 'nullable|string',
        'videos_url' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    protected $appends = ["videos_url_value", "detalles_ayuda"];


    public function getVideosUrlValueAttribute() {
        // Formatea las urls de los videos separados por coma (,) retornando un arreglo con nombre de clave video_url
        $videos_url = $this->videos_url ? '[["video_url" =>'. str_replace(',', '"],["video_url" => "', '"'.$this->videos_url.'"').']]' : '[]';
        return eval("return ".$videos_url.";");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\User::class, 'users_id');
    }

    /**
     * Append para obtener el las características de la ayuda mas que todo cuando tiene al menos un video como ayuda
     *
     * @author Seven Soluciones Informáticas S.A.S. - Mar. 12 - 2024
     * @version 1.0.0
     *
     * @return string $detalles_ayuda, información como la categoría, imagen preview, duración del video, para mostrar en las tarjetas de ayudas
     */
    public function getDetallesAyudaAttribute() {

        $detalles_ayuda = [];
        // Valida si tiene al menos una URL de un video de YouTube
        if($this->videos_url) {
            // Crea una instancia del controlador de Google para usar el servicio de YouTube
            $google_youtube = new GoogleController();
            // Obtiene el video de la primer posición, en caso de que sean varios
            $video = explode(",", $this->videos_url)[0];
            // Se obtiene el ID del video de YouTube
            $id_video = explode("=", $video);
            $id_video_yotube = end($id_video);
            // Se obtiene la información del video de YouTube según el id_video
            $videoItem = $google_youtube->obtenerDetallesVideoYouTube($id_video);
            if (!empty($videoItem )) {
                // Se obtiene la vista previa del video
                $thumbnailUrl = $videoItem['snippet']['thumbnails']['medium']['url'] ?? '';
                // Se obtiene la duración del video en un formato no legible
                $durationString = $videoItem['contentDetails']['duration'];
                // Se formatea la duración del video a un formato entendible (HH:mm:ss - 00:00:00)
                $duration = $google_youtube->parseVideoDuracion($durationString);
                $detalles_ayuda = ["categoria" => "Videos", "miniatura" => $thumbnailUrl, "duracion_video" => $duration];
            }   
        } else if($this->documento) {
            // Se le asigna la categoría de documento, si no tiene videos en la ayuda
            $detalles_ayuda = ["categoria" => "Documento"];
        }
        // Si la ayuda contiene videos y documento adjunto, la categoría son ambos
        if($this->videos_url && $this->documento) {
            $detalles_ayuda["categoria"]  = "Videos, documento";
        }
        // Retorna la información general de la ayuda
        return $detalles_ayuda;
    }

}
