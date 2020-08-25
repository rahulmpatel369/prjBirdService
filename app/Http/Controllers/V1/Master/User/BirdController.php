<?php 

namespace App\Http\Controllers\V1\Master\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Bird;
use App\Services\BirdService;

class BirdController extends Controller {
    public function getAllBirds(Request $request){
        $birds = BirdService::getAllBirds([]);
        if($birds) return $this->response('Success', $birds);
        return $this->errorResponse('Data not Found', [], self::$HTTP_NO_CONTENT);
    }

    public function getUnverifiedBirds() { 
        $birds = BirdService::getAllBirds(['verified' => false]);
        if($birds) return $this->response('Success', $birds);
        return $this->errorResponse('Data not Found', [], self::$HTTP_NO_CONTENT);
    }
    
    public function createBird(Request $request){
        $validator = Validator::make($request->all(), Bird::addRules());

        if($validator->fails()) return $this->errorResponse('Validation Error', $validator->errors());

        $bird = BirdService::createBird($request->all());
        if($bird) return $this->response('Success', $bird);
        return $this->response('Failed', [], self::$HTTP_NO_CONTENT);
    }

    public function updateBird(Bird $bird, Request $request){
        $validator = Validator::make($request->all(), Bird::addRules($bird->id));
        
        if($validator->fails()) return $this->errorResponse('Validation Error', $validator->errors());

        $bird = BirdService::updateBird($bird, $request->all());
        if($bird) return $this->response('Success', $bird);
        return $this->response('Failed', [], self::$HTTP_NO_CONTENT);
    }

    public function destroyBird(Bird $bird){            
        $bird = BirdService::deleteBird($bird);
        if($bird) return $this->response('Success', [], self::$HTTP_NO_CONTENT);
        return $this->response('Failed', [], self::$HTTP_NO_CONTENT);
    }

    public function updateBirdVefiyStatus(Bird $bird, Request $request){
        $validator = Validator::make($request->all(), ['status' => 'required|in:verified,unverified']);

        if($validator->fails()) return $this->errorResponse('Validation Error', $validator->errors());
        $bird = BirdService::updateBirdVerifyFlag($bird, $request->all());
        if($bird) return $this->response('Success', $bird);
        return $this->response('Failed', [], self::$HTTP_NO_CONTENT);
    }
}