<?php

namespace App\Http\Api\UtenzaController;

use App\Http\Api\User\UserResource;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UtenzaController extends Controller
{

    public function store(UtenzaRequest $request){
        if($request->validationData()){
            $data = $request->validationData();
            $data['password'] = Hash::make($data['password']);
            $user =  User::create($data);
           return (new UserResource($user))->response($request)->setStatusCode(201);
        }

    }

    /**
     * @throws \Exception
     */
    public function update(UtenzaUpdateRequest $request){
        if($request->validationData()){
            $user = $request->user();
            if(!Hash::check($request->input('old_password'),$user->password))
                throw new \Exception("The old password does not match",403);

            $user->update([
               "password" => Hash::make($request->input('password')),
               "updated_at" => Carbon::now()
            ]);
            return (new UserResource($user))->response($request)->setStatusCode(200);
        }

    }


}
