<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cl_moral=DB::table('client_morale')->get()->first();
        $cl_ph=DB::table('client_physique')->get()->first();
        $type=$request->input('type');

        if($cl_moral && $type=="morale"){
            return response()->json($cl_moral,200);
        }else if($cl_ph && $type=="physique"){
            return response()->json($cl_ph,200);
        }else{
            return response()->json(['response'=>'client non trouvé'],404);
        }


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
        $choix=$request->input('type');
        $code_client=rand(500,100000);
        $code_client_physique=rand(500,100000);
        $mand=$request->input('mandataire');

        $cl=DB::table('client')->insert([
            'code_client'=>$code_client,
            'nom_client'=>$request->input('nom_client'),
            'prenoms_client'=>$request->input('prenoms_client'),
            'tel_client'=>$request->input('tel_client'),
            'date_nais_client'=>$request->input('date_nais_client'),
            'email_client'=>$request->input('email_client'),
            'idpiece'=>$request->input('idpiece')
        ]);

        if ($cl && $choix=="physique"){

            $physique=DB::table('client_physique')->insert([
                'code_client_physique'=>$code_client_physique,
                'sexe'=>$request->input('sexe'),
                'adresse'=>$request->input('adresse'),
                'profession'=>$request->input('profession'),
                'nationalite'=>$request->input('nationalite'),
                'code_client'=>$code_client
            ]);



            if($physique){

                $res=array('response'=>'client physique  crée','succes'=>true);
                return response()->json($res,200);

            }elseif ($mand=="oui"){

                $code_mand=rand(4020,1523600);
                $manda=DB::table('mandataire')->insert([
                    'code_mand'=>$code_mand,
                    'nom_mand'=>$request->input('nom_mand'),
                    'prenoms_mand'=>$request->input('prenoms_mand'),
                    'date_nais_mand'=>$request->input('date_nais_mand'),
                    'email_mand'=>$request->input('email_mand'),
                    'adresse_mand'=>$request->input('adresse'),
                    'lieu_nais_mand'=>$request->input('lieu_nais_mand'),
                    'code_client'=>$code_client
                ]);

                    if($mand){
                        $res=array('response'=>'mandataire ajouté','succes'=>'true');
                        return response()->json($res,200);
                    }else{
                        $res=array('response'=>'echec','succes'=>'false');
                        return response()->json($res,404);
                    }

            } else{
                $res=array('response'=>'echec','succes'=>'false');
                return response()->json($res,404);
            }


        }elseif ($cl && $choix=="morale") {

            $morale = DB::table('client_morale')->insert([
                'code_client_morale' =>rand(500,100000),
                'registre_commerce' => $request->input('registre_commerce'),
                'code_client'=>$code_client

            ]);



            if ($morale) {
                $res = array('response' => 'client morale crée', 'succes' => true);
                return response()->json($res, 200);

            }elseif ($mand=="oui"){

                $code_mand=rand(4020,1523600);
                $manda=DB::table('mandataire')->insert([
                    'code_mand'=>$code_mand,
                    'nom_mand'=>$request->input('nom_mand'),
                    'prenoms_mand'=>$request->input('prenoms_mand'),
                    'date_nais_mand'=>$request->input('date_nais_mand'),
                    'email_mand'=>$request->input('email_mand'),
                    'adresse_mand'=>$request->input('adresse'),
                    'lieu_nais_mand'=>$request->input('lieu_nais_mand'),
                    'code_client'=>$code_client
                ]);

                if($mand){
                    $res=array('response'=>'mandataire ajouté','succes'=>'true');
                    return response()->json($res,200);
                }else{
                    $res=array('response'=>'echec','succes'=>'false');
                    return response()->json($res,404);
                }



            } else {
                $res = array('response' => 'client physique crée', 'succes' => false);
                return response()->json($res, 404);
            }

        }else{
            $res = array('response' => 'echec', 'succes' => false);
            return response()->json($res, 404);
        }



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
        $choix=$request->input('type');
        $cde_physique=$request->input('code_client_physique');
        $cde_morale=$request->input('code_client_morale');
        $Tmorale=DB::table('client_morale')->where('code_client_morale',$cde_morale)->select('code_client_morale')->get();
        $Tphysique=DB::table('client_physique')->where('code_client_physique',$cde_physique)->select('code_client_physique')->get();
        $Tclient=DB::table('client')->where('code_client',$request->input('code_client'));

        if($Tphysique && $choix="physique"){

                    $physiq=DB::table('client_physique')->where('code_client_physique',$cde_physique)->update([
                        'sexe'=>$request->input('sexe'),
                        'adresse'=>$request->input('adresse'),
                        'profession'=>$request->input('profession'),
                        'nationalite'=>$request->input('nationalite')
                    ]);

            }elseif ($Tmorale && $choix="morale"){

            DB::table('client_morale')->update([
                'code_client_morale' =>rand(500,100000),
                'registre_commerce' => $request->input('registre_commerce'),

            ]);
        }elseif ($Tclient){

            DB::table('client')->where('code_client',$request->input('code_client'))->update([
                'nom_client'=>$request->input('nom_client'),
                'prenoms_client'=>$request->input('prenoms_client'),
                'tel_client'=>$request->input('tel_client'),
                'date_nais_client'=>$request->input('date_nais_client'),
                'email_client'=>$request->input('email_client'),
            ]);

        }
        else{

            $res=array('response'=>'echec','succes'=>true);
            return response()->json($res,404);

        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
