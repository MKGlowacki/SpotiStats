<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Track;
use App\Models\SpotifyUser;
use Illuminate\Support\Facades\Session;
use SpotifyWebAPI\Session as SpotifySession;
use SpotifyWebAPI\SpotifyWebAPI;
use SpotifyWebAPI\SpotifyWebAPIException;
use Illuminate\Support\Facades\DB;

class SpotifyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = auth()->user()->id;
        $user = User::find($userId);

        $topshort = array();
        $topmedium = array();
        $toplong = array();
        
        $i = 0;
        foreach($user->tracks as $key => $track){
            if($i < 10){
                array_push($topshort, $track); 
            }
            elseif($i >= 10 && $i<20){
                array_push($topmedium, $track); 
            }
            else{
                array_push($toplong, $track); 
            }
            $i++;
        };
        

        $result = view('spotify.index')
                ->with('user',$user->spotifyUser)
                ->with('tracks_short', $topshort)
                ->with('tracks_medium', $topmedium)
                ->with('tracks_long', $toplong);

        return $result;

    }

    public function logIn(){
        $session = new SpotifySession(
            'e8813fba4dc3462bb7f024be10a0be89',
            '6d07ea20bd9443acb46faae7495be365',
            'http://localhost:8000/logIn'
        );
        

        try{ 
            $api = new SpotifyWebAPI();
            
            if (isset($_GET['code'])) {
                $session->requestAccessToken($_GET['code']);
                $api->setAccessToken($session->getAccessToken());
                
            
                $user_info = $api->me();

            
                $top_tracks_short = $api->getMyTop('tracks', [
                    'limit' => 10,
                    'offset' => 0,
                    'time_range' => 'short_term'
                ]);
                $top_tracks_medium = $api->getMyTop('tracks', [
                    'limit' => 10,
                    'offset' => 0,
                    'time_range' => 'medium_term'
                ]);
                $top_tracks_long = $api->getMyTop('tracks', [
                    'limit' => 10,
                    'offset' => 0,
                    'time_range' => 'long_term'
                ]);

                $top_tracks = array($top_tracks_short, $top_tracks_medium, $top_tracks_long);

                DB::delete('delete from tracks');
                

                if(empty(DB::select('select id from spotify_users where id='.$user_info->id))){
                    $spotifyUser = new SpotifyUser;
                    $spotifyUser->id = $user_info->id;
                    $spotifyUser->name = $user_info->display_name;
                    $spotifyUser->email = $user_info->email;
                    $spotifyUser->user_id = auth()->user()->id;
                    $spotifyUser->image_url = $user_info->images[0]->url;
                    $spotifyUser->save();
                }

                

                $i = 1;
                foreach($top_tracks as $key => $top){
                    foreach($top->items as $key => $track_s){

                        $artist = '';
                        foreach($track_s->artists as $key => $a){
                            $artist .= $a->name.' ';
                        }


                        $track = new Track;
                        $track->name = $track_s->name;
                        $track->album = $track_s->album->name;
                        $track->artist = $artist;
                        $track->image_url = $track_s->album->images[2]->url;
                        $track->user_id = auth()->user()->id;
                        $track->position = $i;
                        $i++;

                        $track->save();
                    }
                }

                    



            } else {
                $options = [
                    'scope' => [
                        'user-read-email',
                        'user-top-read'
                    ],
                ];
            
                header('Location: ' . $session->getAuthorizeUrl($options));
                die();
            }

            $result = view('spotify.index')
                ->with('user',$user_info)
                ->with('tracks_short', $top_tracks_short->items)
                ->with('tracks_medium', $top_tracks_medium->items)
                ->with('tracks_long', $top_tracks_long->items);

            

        } catch(SpotifyWebAPIException $e){
             
            $result = redirect('/logIn');
            
        }

   
        
        return redirect('/');
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
        //
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
