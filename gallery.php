<?php

define('KEY', '6af71f6e2fbd1c55127cbc682b58c336');
define('SECRET', '8069420d479d254b');
define('USERID', '142907207@N04');
define('API', 'https://api.flickr.com/services/rest/?');



//=========================
// getPhotoList
//=========================
function getPhotoList(){
    $param = array(
        'method' => 'flickr.people.getPhotos',
        'api_key' => KEY,
        'user_id' => USERID,
        'per_page' => isset($_REQUEST['per_page']) ? $_REQUEST['per_page'] : null,
        'page' => isset($_REQUEST['page']) ? $_REQUEST['page'] : null,
        'format' => 'json',
        'nojsoncallback' => 1
    );
    $param = http_build_query($param);
    $url = API.$param;

    $res = file_get_contents($url);
    $res = json_decode($res, true);

    if($res['stat'] == 'ok'){
        $photos = $res['photos']['photo'];
        $i = 0;
        foreach ($photos as $photo){
            if(isset($photo['id'])){
                $res['photos']['photo'][$i]['sizes'][] = getPhotoPath($photo['id']);
            }
            $i = $i+1;
        }
    }

    return $res;
}
//=========================
// getPhotoPath
//=========================
function getPhotoPath($photo_id){
    $param = array(
        'method' => 'flickr.photos.getSizes',
        'api_key' => KEY,
        'photo_id' => $photo_id,
        'user_id' => USERID,
        'format' => 'json',
        'nojsoncallback' => 1
    );
    $param = http_build_query($param);
    $url = API.$param;

    $res = file_get_contents($url);
    $res = json_decode($res, true);
    if($res['stat'] == 'ok'){
        return $res['sizes']['size'];
    }
    return [];
}
//=========================
// API
//=========================
function getMyGallery(){
    $res = getPhotoList();
    echo json_encode($res, true);
}
getMyGallery();
