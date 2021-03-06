<?php

namespace App\Http\Controllers;

use App\Mail\GetThingsMail;
use App\Models\Place;
use App\Models\Thing;
use App\Models\UseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Expr\Cast\Object_;

class UseController extends Controller
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create($id)
    {
        $use = Thing::findOrFail($id);
        $places = Place::all();
        $free_count = 0;
        if (UseModel::where('thing_id', $id)->exists()) {
            $uses = UseModel::where('thing_id', $id)->get();
            for ($i=0 ;$i<count($uses);$i++) {
                $free_count = $free_count + $uses[$i]->amount;
            }
        }
        $users = User::all();
        $free_count = $use->amount - $free_count;
        return view("uses.create", ['use' => $use, 'places' => $places, 'free_count' => $free_count, 'users'=> $users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'thing_id'=> ['required'],
            'amount'=>['required'],
            'place_id'=>['required'],
            'user_id'=>['required']
        ]);

        $use = new UseModel();

        $use -> thing_id = $data['thing_id'];
        $use -> place_id = $data['place_id'];
        $use -> amount = $data['amount'];
        $use -> user_id = $data['user_id'];
        $use->save();

        $thing = Thing::findorFail($use->thing_id);


        Mail::to(User::findorFail($use->user_id)->email)->send(new GetThingsMail($thing));

        return redirect('/things/'.$use->thing_id);
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function destroy($thing_id, $place_id, $user_id)
    {
        UseModel::where('thing_id', $thing_id)->where('place_id', $place_id)->where('user_id', $user_id)->delete();
        return redirect('/things/'.$thing_id);
    }
}
