<?php

/**
 * Controller por defecto si no se usa el routes
 *
 */
Load::models('tweet','configuracion');  // carga modelos
Load::lib('TwitterAPIExchange'); // carga libreria Twitter Api

class BotController extends AppController
{

    public function index(){
        
        //obtengo tweet de la bd ramdom
        $tweet = new Tweet();
        $tweet = $tweet->find_by_sql("SELECT  * FROM tweet WHERE 1 ORDER BY RAND() LIMIT 1");
        
        //cargo data configuracion
            $campo_oauth_access_token = Load::model('configuracion')->find_first("campo='oauth_access_token'");
            $campo_oauth_access_token_secret = Load::model('configuracion')->find_first("campo='oauth_access_token_secret'");
            $campo_consumer_key = Load::model('configuracion')->find_first("campo='consumer_key'");
            $campo_consumer_secret = Load::model('configuracion')->find_first("campo='consumer_secret'");
        
        $url = "https://api.twitter.com/1.1/statuses/update.json";
        $requestMethod = 'POST';

        // configuracion de la cuenta
        
        $settings = array(
            'oauth_access_token' => "$campo_oauth_access_token->valor",
            'oauth_access_token_secret' => "$campo_oauth_access_token_secret->valor",
            'consumer_key' => "$campo_consumer_key->valor",
            'consumer_secret' => "$campo_consumer_secret->valor"
        );

        // establecer el mensaje
        $postfields = array('status' => "$tweet->cuerpo $tweet->tags $tweet->url");
        // establecer el media_id
            //$postfields['media_ids'] = $this->getMediaId($settings);

        // crea la coneccion con Twitter
        $twitter = new TwitterAPIExchange($settings);

        // envia el tweet
        $twitter->buildOauth($url, $requestMethod)
                ->setPostfields($postfields)
                ->performRequest();        

        $this->mensaje = "$tweet->cuerpo $tweet->tags $tweet->url";
        $this->title = 'Tweet';

    }
    
    
   


     function getMediaId($settings) {

        $url = 'https://upload.twitter.com/1.1/media/upload.json';
        $method = 'POST';
        $twitter = new TwitterAPIExchange($settings);

        $file = file_get_contents('https://fbcdn-sphotos-c-a.akamaihd.net/hphotos-ak-xfa1/t31.0-8/13497888_1756404947961474_736124472970440206_o.jpg');
        $data = base64_encode($file);

        $params = array(
            'media_data' => $data
        );

        try {
            $data = $twitter->request($url, $method, $params);
        } catch (Exception $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "\n";
            // hacer algo
            return null;
        }

        // para obtener más facilmente el media_id
        $obj = json_decode($data, true);

        // media_id en formato string
        return $obj ["media_id_string"];
    }




//$mensaje = "Zona de Camping!! En MIRALAGOS CONDOMINIO CAMPESTRE";
//
//$t = new Twitter();
//$respuesta = $t->sendTweets($mensaje);
//$json = json_decode($respuesta);
//
//
//echo '<meta charset="utf-8">';
//echo "Tweet Enviado por: ".$json->user->name." (@".$json->user->screen_name.")";
//echo "<br>";
//echo "Tweet: ".$json->text;
//echo "<br>";
//echo "Tweet ID: ".$json->id_str;
//echo "<br>";
//echo "Fecha Envio: ".$json->created_at;

    
   
}
