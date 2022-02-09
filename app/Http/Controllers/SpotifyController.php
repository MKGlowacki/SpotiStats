<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use SpotifyWebAPI\Session as SpotifySession;
use SpotifyWebAPI\SpotifyWebAPI;





class SpotifyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['public']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return view('spotify.index');

    }

    public function logIn(){
        $session = new SpotifySession(
            'e8813fba4dc3462bb7f024be10a0be89',
            '6d07ea20bd9443acb46faae7495be365',
            'http://localhost:8000/logIn'
        );
        
        
        $api = new SpotifyWebAPI();
        
        if (isset($_GET['code'])) {
            $session->requestAccessToken($_GET['code']);
            $api->setAccessToken($session->getAccessToken());
        
            $user_info = $api->me();

           // print_r($session->getScope());
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

            // print_r($top_tracks_short->items[0]->name);
            // print_r($top_tracks_short->items[0]->album->name);
            // print_r($top_tracks_short->items[0]->artists[0]->name);


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

        
        return $result;
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
