<?php

namespace App\IntegracionGoogle;

use Google_Client;
use Google_Service_Drive;
use Google_Service_Oauth2;
use Google_Service_Sheets;
use Google_Service_Sheets_ValueRange;
use Google_Service_Drive_DriveFile;
use Google_Service_Drive_Permission;
use Google_Service_Docs;
use Google_Service_Docs_Request;
use Google_Service_Docs_BatchUpdateDocumentRequest;
use Google_Service_Sheets_BatchUpdateValuesRequest;
use App\Http\Controllers\AppBaseController;
use Google_Service_YouTube;
use Google_Service_Calendar;
use Exception; // Asegúrate de importar la clase Exception si no estás en el namespace global

class Google extends AppBaseController
{
    /**
     * Confugura el cliente de la API de Google
     *
     * @return void
     */
    public static function client()
    {
        // Crea una instancia deL Cliente de la API de Google
        $client = new Google_Client();
        // Nombre de la aplicación, esto se muestra a la hora de solicitar permisos por primer vez
        $client->setApplicationName('Google Drive API PHP Quickstart');
        // Alcances o permisos de la aplicación. Debe llamarse antes de createAuthUrl()
        $client->setScopes(array(Google_Service_Drive::DRIVE, Google_Service_Oauth2::USERINFO_EMAIL, Google_Service_Sheets::SPREADSHEETS_READONLY, 'https://www.googleapis.com/auth/youtube', Google_Service_Calendar::CALENDAR));
        // Archivo de configuración y credenciales de acceso a la API

        // Si existe. El archivo token.json almacena los tokens de acceso y actualización del usuario

        // Establece el archivo de configuración y credenciales de acceso a la API
        $credencialesPath = __DIR__ . '/credenciales_api_google.json';

        if (!file_exists($credencialesPath)) {
            throw new Exception('El archivo de credenciales no se encontró.');
        }
        $client->setAuthConfig($credencialesPath);


        $client->setAccessType('offline');
        // Título del modal para la solicitud de permisos al usuario
        $client->setPrompt('select_account consent');

        // Cargar token previamente autorizado desde un archivo
        $tokenPath = __DIR__."/token_google.json";

        // Si existe. El archivo token.json almacena los tokens de acceso y actualización del usuario
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }else{
            // throw new Exception('El archivo de credenciales token no se encontró.');
        }

        // Verdadero, Si no existe el token anterior o está caducado
        if ($client->isAccessTokenExpired()) {
            // Actualice el token si es posible, de lo contrario busque uno nuevo
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Valida si hay una variable con un código de autenticación
                if (isset($_GET['code'])) {
                    // Si existe el código de autenticación, se le asiga a la variable accessToken
                    $accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);
                    // Establece el token de acceso utilizado para las solicitudes
                    $client->setAccessToken($accessToken);
                } else {
                    // Solicitar autorización al usuario
                    $authUrl = $client->createAuthUrl();
                    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
                    exit;
                }
            }
            // Si no existe el archivo del token, se crea.
            if (!file_exists(dirname($tokenPath))) {
                // Crea la carpeta donde se alojará el token
                mkdir(dirname($tokenPath), 0700, true);
            }

            // Guardar el token en un archivo
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }

        // Retorna la API del cliente
        return $client;
    }

    /**
     * Instancia del servicio Drive
     *
     * @param Google_Client $client Cliente API de google
     * @return Google_Service_Drive
     */
    public function drive($client)
    {
        $drive = new Google_Service_Drive($client);
        return $drive;
    }

    /**
     * Instancia del servicio Drive File
     *
     * @param Google_Client $client Cliente API de google
     * @return Google_Service_Drive_DriveFile
     */
    public function driveFile($client)
    {
        $drive_file = new Google_Service_Drive_DriveFile($client);
        return $drive_file;
    }

    /**
     * Instancia del servicio Google_Service_Sheets
     *
     * @param Google_Client $client Cliente API de google
     * @return Google_Service_Sheets
     */
    public function sheets($client)
    {
        $sheets = new Google_Service_Sheets($client);
        return $sheets;
    }

    /**
     * Instancia del servicio Google_Service_Sheets_ValueRange
     *
     * @param Google_Client $client Cliente API de google
     * @return Google_Service_Sheets_ValueRange
     */
    public function sheets_value_range($client)
    {
        $sheets_value_range = new Google_Service_Sheets_ValueRange($client);
        return $sheets_value_range;
    }

    /**
     * Instancia del servicio Google_Service_Drive_Permission
     *
     * @param Google_Client $client Cliente API de google
     * @return Google_Service_Drive_Permission
     */
    public function drive_permission($client)
    {
        $drive_permission = new Google_Service_Drive_Permission($client);
        return $drive_permission;
    }

   /**
     * Instancia del servicio Google_Service_Docs
     *
     * @param Google_Client $client Cliente API de google
     * @return Google_Service_Docs
     */
    public function docs($client)
    {
        $docs = new Google_Service_Docs($client);
        return $docs;
    }

    /**
     * Instancia del servicio Google_Service_Docs_Request
     *
     * @param Google_Client $client Cliente API de google
     * @return Google_Service_Docs_Request
     */
    public function docs_request($client)
    {
        $docs_request = new Google_Service_Docs_Request($client);
        return $docs_request;
    }

    /**
     * Instancia del servicio Google_Service_Docs_BatchUpdateDocumentRequest
     *
     * @param Google_Client $requests
     * @return Google_Service_Docs_BatchUpdateDocumentRequest
     */
    public function docs_batch_update_document_request($requests)
    {
        $docs_update_request = new Google_Service_Docs_BatchUpdateDocumentRequest($requests);
        return $docs_update_request;
    }

    /**
     * Instancia del servicio Google_Service_Sheets_BatchUpdateValuesRequest
     *
     * @param Google_Client $requests
     * @return Google_Service_Sheets_BatchUpdateValuesRequest
     */
    public function sheets_batch_update_values_request($requests)
    {
        $sheets_update_request = new Google_Service_Sheets_BatchUpdateValuesRequest($requests);
        return $sheets_update_request;
    }

    /**
     * Instancia del servicio Google_Service_YouTube
     *
     * @param Google_Client $client Cliente API de google
     * @return Google_Service_YouTube
     */
    public function youtube($client)
    {
        $service_youtube = new Google_Service_YouTube($client);
        return $service_youtube;
    }

    /**
     * Instancia del servicio Google_Service_Sheets_Request
     *
     * @param Google_Client $client Cliente API de google
     * @return Google_Service_Sheets_Request
     */
    public function sheets_request($client)
    {
        $sheetsRequest = new Google_Service_Sheets_Request($client);
        return $sheetsRequest;
    }

    /**
     * Instancia del servicio Calendar
     *
     * @param Google_Client $client Cliente API de google
     * @return Google_Service_Calendar
     */
    public function calendar($client)
    {
        $calendar = new Google_Service_Calendar($client);
        return $calendar;
    }

}
