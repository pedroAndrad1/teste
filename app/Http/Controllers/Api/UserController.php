<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Models\password_resets;
use App\Models\User;
use App\Mail\resetPassword;
use Carbon\Carbon;

class UserController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return response()->json($users);
    }

    public function senhaReset($token,Request $request)
    {   
        return response()->json(['token'=>$token,'email'=>$request->query('email')]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $request->validate([
           'email'=>'email|required',
           'password'=>'required|min:8',
           'name'=>'required'
       ]);

       $user = User::where('email','=',$request->email)->count();
        if($user > 0){
            return  response()->json(
                [
                    'Error'=>'Usuário ja cadastrado', 'success'=>false
                ]
            );
        }

        $user = new User;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->nome = $request->name;
        $user->nivel = 1;
        $user->exp = 0;
        $user->exp_cap = 100;
        $user->contribuiu = 0;

        $user->save();

        $user->email_verified_at = time();
        $user->save();
        return response()->json(['mensagem'=>'Usuario criado com sucesso', 'success'=>true]);
    }

    public function verificarEmail(EmailVerificationRequest $request){
        $request->fulfill();
        return;
    }

    public function esqueciSenha(Request $request){
        // $request->validate(['email'=>'required|email']);
        // $status = Password::sendResetLink(
        //     $request->only('email')
        // );
        // if($status === Password::RESET_LINK_SENT){
        //     return  response()->json(['mensagem'=>"Uma mensagem com o procedimento para gerar uma nova senha acaba de ser enviada para este email",'success'=>true]);
        // }
        // return  response()->json(['mensagem'=>"Houve um erro",'success'=>false]);

        $token = Str::random(24);

        $request->validate(['email'=>'required|email']);

        $email = $request->email;

        $user = User::firstWhere('email', $email);

        if(is_null($user)){
            return response()->json(["Não existe usuário com esse email"]);
        }

        $tokenn = new password_resets;
        $tokenn->email = $email;
        $tokenn->token = $token;
        $tokenn->save();

        $status = Mail::to($email)->send(new resetPassword($token,$user->nome));

        return response()->json([$status,$token]);


    }

    public function resetarSenha(Request $request){
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:8',
        ]);

            $token = password_resets::firstWhere('token', $request->token);

            if(is_null($token)){
                return response()->json(["error"=>'token não encontrado', 'success'=>false]);
            }

            $createdat = new Carbon($token->created_at);
            $now = new Carbon();
            $createdat2 = $token->created_at->addHour(2); 

            if(!$createdat2->greaterThan($now)){
                return response()->json(["error"=>"Token expirado"]);
            }

            $user = User::firstWhere('email',$token->email);

            $user->password = $request->password;
            $user->save();
    
            $user->setRememberToken(Str::random(60));
    
            return response()->json(['mensagem'=>'Senha Resetada com Sucesso']);; 
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
