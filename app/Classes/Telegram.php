<?php

namespace App\Classes;

class Telegram{
    public $token;
    public $chatId;
    public $photo;
    public $video;
    public $photoGroup;
    public $caption;
    public $baseUrl = "https://api.telegram.org/bot";
    public $baseFileUrl = "https://api.telegram.org/file/bot";
    public $baseFullUrl;
    public $method;
    public $params;

    public function __construct($token)
    {
        $this->token = $token;
        $this->baseFileUrl = $this->baseFileUrl. $this->token;
        $this->baseFullUrl = $this->baseUrl . $this->token;
    }

    public function setMethod($method){
        $this->method = $method;
    }

    public function setPhoto($photo){
        $this->photo = $photo;
    }

    public function setVideo($video){
        $this->video = $video;
    }

    public function setPhotoGroup($photoGroup){
        $this->photoGroup = $photoGroup;
    }

    public function setChatId($chatId){
        $this->chatId = $chatId;
    }

    public function setParams($params){
        $this->params = $params;
    }

    public function setCaption($caption){
        $this->caption = $caption;
    }


    public function sendMessage()
    {
        return $this->query();
    }

    public function getUrlPhoto($pathPhoto)
    {
        return $this->baseFileUrl.'/'.$pathPhoto;
    }
    public function getPathPhoto()
    {
        return $this->query();
    }




    public function query()
    {
        $this->baseFullUrl .= "/" . $this->method;

        if (!empty($this->params)) {
            $this->baseFullUrl .= "?" . http_build_query($this->params);
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->baseFullUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;


    }

    public function sendVideo()
    {

        $this->baseFullUrl .= "/" . $this->method;

        if (!empty($this->params)) {
            $this->baseFullUrl .= "?" . http_build_query($this->params);
        }

        $post_fields = array(
            'chat_id' => $this->chatId,
            'caption' => $this->caption,
            'video' => $this->video
        );


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type:multipart/form-data"
        ));
        curl_setopt($ch, CURLOPT_URL, $this->baseFullUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        $output = curl_exec($ch);
        return $output;
    }

    public function sendImage()
    {

        $this->baseFullUrl .= "/" . $this->method;

        if (!empty($this->params)) {
            $this->baseFullUrl .= "?" . http_build_query($this->params);
        }

        $post_fields = array(
            'chat_id' => $this->chatId,
            'caption' => $this->caption,
            'photo' => $this->photo
        );


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type:multipart/form-data"
        ));
        curl_setopt($ch, CURLOPT_URL, $this->baseFullUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        $output = curl_exec($ch);
        return $output;
    }

    public function sendImageGroup()
    {

        $this->baseFullUrl .= "/" . $this->method;

        if (!empty($this->params)) {
            $this->baseFullUrl .= "?" . http_build_query($this->params);
        }

        $post_fields = array(
            'chat_id' => $this->chatId,
            'media' => json_encode($this->photoGroup),
            'caption' => $this->caption,
        );


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type:multipart/form-data"
        ));
        curl_setopt($ch, CURLOPT_URL, $this->baseFullUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        $output = curl_exec($ch);
        
        dd($output);
        return $output;
    }
}
