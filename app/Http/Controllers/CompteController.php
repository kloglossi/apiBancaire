<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Integer;

class CompteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $code_guichet=DB::table('agence')
            ->where('idagence',$request->input('guichet'))
            ->select('code_guichet')->value('code_guichet');

        $num_compte="CI023".rand(1000,6666);
        $choix=$request->input('compte');
        $code_cl=DB::table('client')
            ->where('email_client',$request->input('email_client'))
            ->select('code_client')
            ->value('code_guichet');

        $cpte=DB::table('compte')->insert([
            'num_compte'=>$num_compte,
            'solde_compte'=>10000.0,
            'code_rib'=>455,
            'code_client'=>$code_cl,
            'idagence'=>$request->input('idagence')
        ]);

        if($cpte && $choix=="courant"){

            $cpte_courant=DB::table('compte_courant')->insert(
                [
                    'num_compte_courant'=>"CMP".rand(10000,99000),
                    'decouvert'=>$request->input('decouvert'),
                    'num_compte'=>$num_compte
                ]
            );
            if ($cpte_courant){
                $res=array('response'=>'Compte courant  crée','succes'=>true);
                return response()->json($res,200);
            }else{
                $res=array('response'=>'echec','succes'=>'false');
                return response()->json($res,404);
            }

        }elseif ($cpte && $choix=="epargne"){
            $cpte_epargne=DB::table('compte_epargne')->insert(
                [
                    'num_compte_epargne'=>"EMP".rand(10000,99000),
                    'taux'=>$request->input('taux'),
                    'num_compte'=>$num_compte
                ]
            );
            if ($cpte_epargne){
                $res=array('response'=>'Compte D'/'epargne  crée','succes'=>true);
                return response()->json($res,200);
            }else{
                $res=array('response'=>'echec','succes'=>'false');
                return response()->json($res,404);
            }

        }elseif ($cpte && $choix=="livret"){
            $livret=DB::table('livret_jeune')->insert(
                [
                    'num_livret_jeune'=>"CMP".rand(10000,99000),
                    'num_compte'=>$num_compte
                ]
            );
            if ($livret){
                $res=array('response'=>'Livret Jeune crée crée','succes'=>true);
                return response()->json($res,200);
            }else{
                $res=array('response'=>'echec','succes'=>'false');
                return response()->json($res,404);
            }

        }else{
            $res=array('response'=>'echec','succes'=>'false');
            return response()->json($res,404);
        }

    }

   public function rib(int $banque,int $guichet, int $compte){
            return 97 - ((89 * $banque + 15 * $guichet + 3 * $compte) % 97);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
