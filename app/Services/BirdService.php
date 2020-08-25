<?php 

namespace App\Services;
use App\Models\Bird;
use App\User;

use Illuminate\Database\Eloquent\Model;

class BirdService extends Model {
    public static function getAllBirds($filter){
        if(isset($filter['verified'])) {
            return Bird::with(['verifyByUser', 'createdByUser'])
                        ->where('is_verified', ($filter['verified'] === "true") ? 1 : 0)->get();
        }

        return Bird::with(['verifyByUser', 'createdByUser'])->where('is_verified', 1)->get();
    }

    public static function getBirdById($id){
        return Bird::find($id);
    }

    public static function createBird($birdRequest){
        $bird = new Bird;
        $bird = self::buildBirdObj($bird, $birdRequest);
        $bird->is_verified = 0;
        $bird->verify_by = 0;
        $bird->created_by = auth()->user()->id;
        $status = $bird->save();

        if($status){
            $imagePath = self::storeImage($birdRequest['image'], $birdRequest['extension'], $bird->id);
            if($imagePath){
                $bird->image = $imagePath;
                $bird->save();
            }
        }

        return $status ? self::getBirdById($bird->id) : null;
    }

    public static function updateBird($bird, $birdRequest){
        $bird = self::buildBirdObj($bird, $birdRequest);
        $status = $bird->save();

        if($status){
            $imagePath = self::storeImage($birdRequest['image'], $birdRequest['extension'], $bird->id);
            if($imagePath){
                $bird->image = $imagePath;
                $bird->save();
            }
        }
        
        return $status ? self::getBirdById($bird->id) : null;
    }

    public static function buildBirdObj($bird, $birdRequest){
        $bird->local_name = $birdRequest['local_name'];
        $bird->residential_status = $birdRequest['residential_status'];
        $bird->diet = $birdRequest['diet'];
        $bird->updated_by = auth()->user()->id;
        return $bird;
    }

    public static function updateBirdVerifyFlag($bird, $birdRequest){
        $verifyStausFlag = Bird::$verifyStatusFlag;
        if($verifyStausFlag && isset($verifyStausFlag[$birdRequest['status']])){
            $bird->is_verified = $verifyStausFlag[$birdRequest['status']];
            $bird->verify_by = auth()->user()->id;
            $bird->save();
            return $bird;
        }
        return null;
    }

    public static function storeImage($image, $ext, $id){
        $img_path = null;
        if(isset($image) && isset($ext) && isset($id)) {
            $img_path = "/" . env('BASE_PATH', 'images') . "/" . env('BIRD_IMAGE_PATH', 'birds');
            $outputFile = "bird_" . rand(10,100). "_" . $id . "." . $ext;
            $result = self::base64StringToImage($image, $_SERVER['DOCUMENT_ROOT'] . $img_path . "/", $outputFile);
            if($result == 1) {
                return $img_path . "/" . $outputFile;
            }
        }
        return null;
    }

    private static function base64StringToImage($base64String, $writeFolder, $outputFile) {
        try {
            $file = fopen($writeFolder . $outputFile, "wb");
            if (!$file) {
                return 0;
            }
            $writeStatus = fwrite($file, base64_decode($base64String));
            if (!$writeStatus) {
                return 0;
            }
            fclose($file);
        } catch (Exception $e) {
            fclose($file);
        }
        return 1;
    }
}