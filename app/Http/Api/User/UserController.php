<?php

namespace App\Http\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserIpToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Laravel\Passport\RefreshTokenRepository;
use Laravel\Passport\TokenRepository;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @throws \Exception
     *
     * @OA\OpenApi(
     *    @OA\Info(
     *             title="Lista Api",
     *             version="1.0",
     *             description="Lista Api"
     *    ),
     *
     *       @OA\Tag(
     *                name="Table",
     *                description="Gestione lista table"
     *            ),
     * @OA\Tag(
     *                 name="Auth",
     *                 description="Api autenticazione per generare il token e per il refresh token"
     *             ),
     *
     *  @OA\Server(
     *             url="http://localhost:8000/",
     *             description="Ambiente di sviluppo"
     *         ),
     *
     *      )
     *
     *
     * @OA\SecurityScheme(
     *            securityScheme="bearerAuth",
     *            type="http",
     *            scheme="bearer",
     *            bearerFormat="JWT"
     *        )
     * ################################
     *
     * @OA\Post (
     *      path="/api/login",
     *             summary="Login",
     *             description="Login",
     *             tags={"Auth"},
     *      @OA\RequestBody(
     *                 required=true,
     *                 description="Dati per la login",
     *      @OA\JsonContent(
     *                     type="object",
     *
     *                         @OA\Property(property="email", type="string"),
     *                         @OA\Property(property="password", type="string"),
     *      ),
     *      ),
     *      @OA\Response(
     *                 response=200,
     *                 description="Ritorna i dati dell'utente e il token necessario per le chiamate",
     *
     *             ),
     *  )
     *
     */
    public function login(UserRequest $request){
        if($request->validationData()){
            if(Auth::attempt(["email" => $request->input('email'),'password' => $request->input('password')])){
                $user = Auth::user();
                return $this->create($user)->response($request)->setStatusCode(200);
            }else
            throw new \Exception("User not found",Response::HTTP_NOT_FOUND);
        }

    }

    protected function create(User $user): UserResource
    {
        $oauthAccess = $user->tokens()->get();
        $oauthAccess->each(function ($item) {
            $item->revoked = true;
            $item->save();
        });
        $token = $user->createToken( preg_replace('/\s+/', '_', strtolower($user->name)), ['*']);
        $expiredAt = Carbon::now()->addHours(1);
        $token->token->expires_at = $expiredAt;
        $token->token->save();
        $resource = new UserResource($user);
        $refreshTokenRepository = app(RefreshTokenRepository::class);
        $refreshToken = $refreshTokenRepository->create([
            'id' => \Illuminate\Support\Str::random(40),
            'access_token_id' => $token->token->id,
            'revoked' => false,
            'expires_at' => Carbon::now()->addHours(1),
        ]);

        $resource->additional(["data"=>[
            "access_token"=> $token->accessToken,
            "refresh_token" =>$refreshToken->id,
            "expires_at" => $expiredAt->format('Y-m-d H:i:s')
        ]]);
        return $resource;
    }

    /**
     * @throws \Exception
     * @OA\Post (
     *       path="/api/refresh",
     *              summary="Refresh token",
     *              description="Refresh token",
     *              tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *       @OA\RequestBody(
     *                  required=true,
     *                  description="Dati per la refresh",
     *       @OA\JsonContent(
     *                      type="object",
     *
     *                          @OA\Property(property="refresh_token", type="string"),
     *       ),
     *       ),
     *       @OA\Response(
     *                  response=200,
     *                  description="Ritorna i dati dell'utente e il token aggiornato necessario per le chiamate",
     *
     *              ),
     *   )
     *
     */
    public function refresh(UserRefreshRequest $request){
        if($request->validationData()){
            $refreshTokenRepository = app(RefreshTokenRepository::class);
            $refreshToken = $refreshTokenRepository->find($request->input('refresh_token'));
            if (!$refreshToken) {
                throw  new \Exception('Invalid refresh token', 404);
            }
            if ($refreshToken->revoked || $refreshToken->expires_at < \Illuminate\Support\Carbon::now()) {
                throw  new \Exception('Refresh token expired', 406);
            }
            $refreshTokenRepository->revokeRefreshToken($refreshToken->id);
            return $this->create($request->user())->response($request)->setStatusCode(200);
        }

    }

    /**
     * @throws \Exception
     * @OA\Get (
     *      path="/api/logout",
     *              summary="Logout",
     *              description="Logout",
     *              tags={"Auth"},
     *      security={{"bearerAuth":{}}},
     * @OA\Response(
     *                   response=200,
     *                   description="Ritorna status code 200 se la logout è anadata a buon fine",
     *
     *               ),
     *  )
     *
     */
    public function logout (){
        try{
            $user = \request()->user();
            $oauthAccess = $user->tokens()->get();
            $oauthAccess->each(function ($item) {
                $item->revoked = true;
                $item->save();
                $refresh =Passport::refreshToken()->accessToken()->get();
                $refresh->map(function ($value) {
                    $value->revoked = true;
                    $value->save();
                });
            });
            return (new Response())->setStatusCode(201)->send();
        }catch (\Exception $e){
            throw new \Exception($e->getMessage(),500);
        }
    }



}
