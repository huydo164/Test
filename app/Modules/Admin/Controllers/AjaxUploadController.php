<?php
/*
* @Created by: DUYNX
* @Author    : nguyenduypt86@gmail.com
* @Date      : 08/2019
* @Version   : 1.0
*/
namespace App\Modules\Admin\Controllers;

use App\Library\PHPDev\ThumbImg;
use App\Library\PHPDev\Upload;
use App\Modules\Models\Demo;
use App\Modules\Models\Phone;
use App\Modules\Models\Photo;
use App\Modules\Models\Product;
use App\Modules\Models\Statics;
use Illuminate\Support\Facades\Request;
use App\Library\PHPDev\CGlobal;
use App\Library\PHPDev\FuncLib;

class AjaxUploadController extends BaseAdminController {
    function upload(){
        $action = addslashes(Request::get('act', ''));
        switch( $action ){
            case 'upload_image' :
                $this->upload_image();
                break;
            case 'remove_image' :
                $this->remove_image();
                break;
            case 'get_image_insert_content' :
                $this->get_image_insert_content();
                break;
            default:
                $this->nothing();
                break;
        }
    }
    //Default
    function nothing(){
        die("Nothing to do...");
    }
    //Upload
    function upload_image() {
        $id_hiden =  Request::get('id', 0);
        $type = Request::get('type', 1);
        $pos = Request::get('pos', -1);
        $dataImg = $_FILES["multipleFile"];
        $aryData = array();
        $aryData['intIsOK'] = -1;
        $aryData['msg'] = "Data not exists!";
        switch( $type ){
            case 6 ://Img
                $aryData = $this->uploadImageToFolder($dataImg, $id_hiden, CGlobal::FOLDER_PHOTO, $type);
                break;

            case 7:
                $aryData = $this->uploadImageToFolder($dataImg, $id_hiden, CGlobal::FOLDER_PRODUCT, $type);
                break;

            case 8 ://Img Phone
                $aryData = $this->uploadImageToFolder($dataImg, $id_hiden, CGlobal::FOLDER_PHONE, $type);
                break;

            case 9 ://Img
                $aryData = $this->uploadImageToFolder($dataImg, $id_hiden, CGlobal::FOLDER_DEMO, $type);
                break;

            case 10:
                $aryData = $this->uploadImageToFolder($dataImg, $id_hiden, CGlobal::FOLDER_STATICS, $type);
                break;

            default:
                break;
        }
        echo json_encode($aryData);
        exit();
    }
    function uploadImageToFolder($dataImg, $id_hiden, $folder, $type){

        $aryData = array();
        $aryData['intIsOK'] = -1;
        $aryData['msg'] = "Upload Img!";
        $item_id = 0;

        if (!empty($dataImg)) {

            if($id_hiden == 0){
                switch($type){
                    case 6://Img
                        $new_row['photo_created'] = time();
                        $new_row['photo_status'] = 0;
                        $item_id = Photo::addData($new_row);
                        break;

                    case 7:
                        $new_row['product_created'] = time();
                        $new_row['product_status'] = 0;
                        $item_id = Product::addData($new_row);
                        break;

                    case 8:
                        $new_row['phone_created'] = time();
                        $new_row['phone_status'] = 0;
                        $item_id = Phone::addData($new_row);
                        break;

                    case 9:
                        $new_row['demo_created'] = time();
                        $new_row['demo_status'] = 0;
                        $item_id = Demo::addData($new_row);
                        break;

                    case 10:
                        $new_row['statics_created'] = time();
                        $new_row['statics_status'] = 0;
                        $item_id = Statics::addData($new_row);
                        break;

                    default:
                        break;
                }
            }elseif($id_hiden > 0){
                $item_id = $id_hiden;
            }

            if($item_id > 0){
                $aryError = $tmpImg = array();

                $file_name = Upload::uploadFile('multipleFile',
                    $_file_ext = 'jpg,jpeg,png,gif',
                    $_max_file_size = 10*1024*1024,
                    $_folder = $folder.'/'.$item_id,
                    $type_json=0);

                if ($file_name != '' && empty($aryError)){

                    $tmpImg['name_img'] = $file_name;
                    $tmpImg['id_key'] = rand(10000, 99999);

                    switch($type) {
                        case 6://Img Photo
                            $result = Photo::getById($item_id);
                            if ($result != null) {
                                $aryTempImages = ($result->photo_image_other != '') ? unserialize($result->photo_image_other) : array();

                                $aryTempImages[] = $file_name;

                                $new_row['photo_image_other'] = serialize($aryTempImages);
                                Photo::updateData($item_id, $new_row);

                                $path_image = $file_name;

                                $arrSize = CGlobal::$arrSizeImg;
                                if (isset($arrSize['4'])) {
                                    $size = explode('x', $arrSize['4']);
                                    if (!empty($size)) {
                                        $x = (int)$size[0];
                                        $y = (int)$size[1];
                                    } else {
                                        $x = $y = 400;
                                    }
                                }
                                $url_thumb = ThumbImg::thumbBaseNormal(CGlobal::FOLDER_PHOTO, $item_id, $file_name, $x, $y, '', true, true);
                                $tmpImg['src'] = $url_thumb;
                            }
                            break;

                        case 7:
                            $result = Product::getById($item_id);
                            if ($result != null) {
                                $aryTempImages = ($result->product_image_other != '') ? unserialize($result->product_image_other) : array();

                                $aryTempImages[] = $file_name;

                                $new_row['product_image_other'] = serialize($aryTempImages);
                                Product::updateData($item_id, $new_row);
                                $path_image = $file_name;
                                $arrSize = CGlobal::$arrSizeImg;
                                if (isset($arrSize['4'])) {
                                    $size = explode('x', $arrSize['4']);
                                    if (!empty($size)) {
                                        $x = (int)$size[0];
                                        $y = (int)$size[1];
                                    } else {
                                        $x = $y = 400;
                                    }
                                }
                                $url_thumb = ThumbImg::thumbBaseNormal(CGlobal::FOLDER_PRODUCT, $item_id, $file_name, $x, $y, '', true, true);
                                $tmpImg['src'] = $url_thumb;
                            }
                            break;

                        case 8:
                            $result = Phone::getById($item_id);
                            if ($result != null) {
                                $aryTempImages = ($result->phone_image_other != '') ? unserialize($result->phone_image_other) : array();

                                $aryTempImages[] = $file_name;

                                $new_row['phone_image_other'] = serialize($aryTempImages);
                                Phone::updateData($item_id, $new_row);

                                $path_image = $file_name;

                                $arrSize = CGlobal::$arrSizeImg;
                                if (isset($arrSize['4'])) {
                                    $size = explode('x', $arrSize['4']);
                                    if (!empty($size)) {
                                        $x = (int)$size[0];
                                        $y = (int)$size[1];
                                    } else {
                                        $x = $y = 400;
                                    }
                                }
                                $url_thumb = ThumbImg::thumbBaseNormal(CGlobal::FOLDER_PHONE, $item_id, $file_name, $x, $y, '', true, true);
                                $tmpImg['src'] = $url_thumb;
                            }
                            break;

                        case 9:
                            $result = Demo::getById($item_id);
                            if ($result != null) {
                                $aryTempImages = ($result->demo_image_other != '') ? unserialize($result->demo_image_other) : array();

                                $aryTempImages[] = $file_name;

                                $new_row['demo_image_other'] = serialize($aryTempImages);
                                Demo::updateData($item_id, $new_row);

                                $path_image = $file_name;

                                $arrSize = CGlobal::$arrSizeImg;
                                if (isset($arrSize['4'])) {
                                    $size = explode('x', $arrSize['4']);
                                    if (!empty($size)) {
                                        $x = (int)$size[0];
                                        $y = (int)$size[1];
                                    } else {
                                        $x = $y = 400;
                                    }
                                }
                                $url_thumb = ThumbImg::thumbBaseNormal(CGlobal::FOLDER_DEMO, $item_id, $file_name, $x, $y, '', true, true);
                                $tmpImg['src'] = $url_thumb;
                            }
                            break;

                        case 10:
                            $result = Statics::getById($item_id);
                            if ($result != null) {
                                $aryTempImages = ($result->statics_image_other != '') ? unserialize($result->statics_image_other) : array();

                                $aryTempImages[] = $file_name;

                                $new_row['statics_image_other'] = serialize($aryTempImages);
                                Statics::updateData($item_id, $new_row);

                                $path_image = $file_name;

                                $arrSize = CGlobal::$arrSizeImg;
                                if (isset($arrSize['4'])) {
                                    $size = explode('x', $arrSize['4']);
                                    if (!empty($size)) {
                                        $x = (int)$size[0];
                                        $y = (int)$size[1];
                                    } else {
                                        $x = $y = 400;
                                    }
                                }
                                $url_thumb = ThumbImg::thumbBaseNormal(CGlobal::FOLDER_STATICS, $item_id, $file_name, $x, $y, '', true, true);
                                $tmpImg['src'] = $url_thumb;
                            }
                            break;

                        default:
                            break;
                    }

                    $aryData['intIsOK'] = 1;
                    $aryData['id_item'] = $item_id;
                    $aryData['info'] = $tmpImg;
                }
            }
        }
        return $aryData;
    }
    function remove_image(){
        $id = (int)Request::get('id', 0);
        $nameImage = Request::get('nameImage', '');
        $type = (int)Request::get('type', 1);
        $pos = Request::get('pos', -1);
        $aryData = array();
        $aryData['intIsOK'] = -1;
        $aryData['msg'] = "Remove Img!";
        $aryData['nameImage'] = $nameImage;
        switch( $type ){

            case 6://Img Statics
                $folder_image = 'uploads/'.CGlobal::FOLDER_PHOTO;

                if($id > 0 && $nameImage != '' && $folder_image != ''){
                    $delete_action = $this->delete_image_item($id, $nameImage, $folder_image, $type);

                    if($delete_action == 1){
                        $aryData['intIsOK'] = 1;
                        $aryData['msg'] = "Remove Img!";
                    }
                }
                break;

            case 7:
                $folder_image = 'uploads/' .CGlobal::FOLDER_PRODUCT;
                if ($id > 0 && $nameImage != '' && $folder_image!= ''){
                    $delete_action = $this->delete_image_item($id, $nameImage, $folder_image, $type);
                    if ($delete_action == 1){
                        $aryData['intIsOK'] = 1;
                        $aryData['msg'] = "Remove Img!";
                    }
                }
                break;

            case 8:
                $folder_image = 'uploads/'.CGlobal::FOLDER_PHONE;

                if($id > 0 && $nameImage != '' && $folder_image != ''){
                    $delete_action = $this->delete_image_item($id, $nameImage, $folder_image, $type);

                    if($delete_action == 1){
                        $aryData['intIsOK'] = 1;
                        $aryData['msg'] = "Remove Img!";
                    }
                }
                break;

            case 9:
                $folder_image = 'uploads/'.CGlobal::FOLDER_DEMO;

                if($id > 0 && $nameImage != '' && $folder_image != ''){
                    $delete_action = $this->delete_image_item($id, $nameImage, $folder_image, $type);

                    if($delete_action == 1){
                        $aryData['intIsOK'] = 1;
                        $aryData['msg'] = "Remove Img!";
                    }
                }
                break;

            case 10:
                $folder_image = 'uploads/'. CGlobal::FOLDER_STATICS;

                if ($id > 0 && $nameImage != '' && $folder_image != ''){
                    $delete_action = $this->delete_image_item($id, $nameImage, $folder_image, $type);

                    if ($delete_action == 1){
                        $aryData['intIsOK'] = 1;
                        $aryData['msg'] = "Remove Img!";
                    }
                }
                break;

            default:
                break;
        }
        echo json_encode($aryData);
        exit();
    }
    function delete_image_item($id, $nameImage, $folder_image, $type){
        $delete_action = 0;
        $aryImages  = array();
        switch( $type ){
            case 6://Img Photo
                $result = Photo::getById($id);
                if($result != null){
                    $aryImages = unserialize($result->photo_image_other);
                }
                break;

            case 7:
                $result = Product::getById($id);
                if ($result != null){
                    $aryImages = unserialize($result->product_image_other);
                }
                break;

            case 8:
                $result = Phone::getById($id);
                if ($result != null){
                    $aryImages = unserialize($result->phone_image_other);
                }
                break;

            case 9:
                $result = Demo::getById($id);
                if ($result != null){
                    $aryImages = unserialize($result->demo_image_other);
                }
                break;

            case 10:
                $result = Statics::getById($id);
                if ($result != null){
                    $aryImages = unserialize($result->statics_iamge_other);
                }
                break;

            default:
                $folder_image = '';
                break;
        }
        if(is_array($aryImages) && count($aryImages) > 0) {
            foreach ($aryImages as $k => $v) {
                if($v === $nameImage){
                    $this->unlinkFileAndFolder($nameImage, $id, $folder_image, true);
                    unset($aryImages[$k]);
                    if(!empty($aryImages)){
                        $aryImages = serialize($aryImages);
                    }else{
                        $aryImages = '';
                    }
                    switch( $type ){
                        case 6://Img Photo
                            $new_row['photo_image_other'] = $aryImages;
                            Photo::updateData($id, $new_row);
                            break;

                        case 7:
                            $new_row['product_image_other'] = $aryImages;
                            Product::updateData($id, $new_row);
                            break;

                        case 8:
                            $new_row['phone_image_other'] = $aryImages;
                            Phone::updateData($id, $new_row);
                            break;

                        case 9:
                            $new_row['demo_image_other'] = $aryImages;
                            Demo::updateData($id, $new_row);
                            break;

                        case 10:
                            $new_row['statics_image_other'] = $aryImages;
                            Statics::updateData($id, $new_row);
                            break;

                        default:
                            $folder_image = '';
                            break;
                    }
                    $delete_action = 1;
                    break;
                }
            }
        }
        //xoa khi chua update vao db, anh moi up load
        if($delete_action == 0){
            $this->unlinkFileAndFolder($nameImage, $id, $folder_image, true);
            $delete_action = 1;
        }
        return $delete_action;
    }
    function unlinkFileAndFolder($file_name = '', $id = 0, $folder = '', $is_delDir = 0){
        if($file_name != '') {
            //Remove Img In Database
            $paths = '';
            if($folder != '' && $id >0){
                $path = FuncLib::getRootPath().'/'.$folder.'/'.$id;
            }
            if($file_name != ''){
                if($path != ''){
                    if(is_file($path.'/'.$file_name)){
                        @unlink($path.'/'.$file_name);
                    }
                }
            }
            //Remove Folder Empty
            if($is_delDir) {
                if($path != ''){
                    if(is_dir($path)) {
                        @rmdir($path);
                    }
                }
            }
        }
    }
    function get_image_insert_content(){

        $id_hiden = (int)Request::get('id_hiden', 0);
        $type = (int)Request::get('type', 1);
        $aryData = array();
        $aryData['intIsOK'] = -1;
        $aryData['msg'] = "Data not exists!";

        if($id_hiden > 0){
            switch( $type ){
                case 6://Img Photo
                    $aryData = $this->getImgContent($id_hiden, CGlobal::FOLDER_PHOTO, $type);
                    break;

                case 7:
                    $aryData = $this->getImgContent($id_hiden, CGlobal::FOLDER_PRODUCT, $type);
                    break;

                case 8:
                    $aryData = $this->getImgContent($id_hiden, CGlobal::FOLDER_PHONE, $type);
                    break;

                case 9:
                    $aryData = $this->getImgContent($id_hiden, CGlobal::FOLDER_DEMO, $type);
                    break;

                case 10:
                    $aryData = $this->getImgContent($id_hiden, CGlobal::FOLDER_STATICS, $type);
                    break;

                default:
                    break;
            }
        }
        echo json_encode($aryData);
        exit();
    }
    function getImgContent($id_hiden, $folder, $type){

        $aryImages = array();
        $aryData = array();

        switch( $type ){
            case 6://Img photo
                $result = Photo::getById($id_hiden);
                if($result != null){
                    $aryImages = ($result->photo_image_other != '') ? unserialize($result->photo_image_other) : array();
                }
                break;

            case 7:
                $result = Product::getById($id_hiden);
                if ($result != null){
                    $aryImages = ($result->product_image_other != '') ? unserialize($result->product_image_other) : array();
                }
                break;

            case 8:
                $result = Phone::getById($id_hiden);
                if ($result != null){
                    $aryImages = ($result->phone_image_other != '') ? unserialize($result->phone_image_other) : array();
                }
                break;

            case 9:
                $result = Demo::getById($id_hiden);
                if ($result != null){
                    $aryImages = ($result->demo_image_other != '') ? unserialize($result->demo_image_other) : array();
                }
                break;

            case 10:
                $result = Statics::getById($id_hiden);
                if ($result != null){
                    $aryImages = ($result->statics_image_other != '') ? unserialize($result->statics_image_other) : array();
                }
                break;

            default:
                break;
        }

        if(is_array($aryImages) && !empty($aryImages)){
            foreach($aryImages as $k => $item){
                $aryData['item'][$k]['large'] = ThumbImg::thumbBaseNormal($folder, $id_hiden, $item, 800, 800, '', true, true);
                $aryData['item'][$k]['small'] = ThumbImg::thumbBaseNormal($folder, $id_hiden, $item, 400, 400, '', true, true);
            }
        }

        $aryData['intIsOK'] = 1;
        $aryData['msg'] = "Data exists!";
        return $aryData;
    }
}
