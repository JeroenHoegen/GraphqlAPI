<?php

namespace App\Http\Controllers;

use App\Models\Webshop;
use Illuminate\Http\Request;
use Auth;

class WebshopController extends Controller
{

    public function update($id)
    {
        $user = Auth::user();
        $product = Webshop::find($id);
        return view('webshop.update', compact('user', 'product'))->with('title', 'Edit Webshop');
    }

    public function edit(Request $request)
    {
        $id = $request->input('id');
        Webshop::where('id', $id)->update([
                'id' => $request->input('id'),
                'url' => $request->input('url'),
                'customer_key' => $request->input('customer_key'),
                'customer_secret' => $request->input('customer_secret'),
                'type' => $request->input('type'),
        ]);
        $notification = [
            'message' => 'Webshop is updated successfully.!',
            'alert-type' => 'success'
        ];

        return redirect('/home')->with($notification);
    }

    public function destroy($id)
    {
        Webshop::where('id', $id)->delete($id);
        $notification = [
            'message' => 'Webshop Deleted Sucessfully.!',
            'alert-type' => 'info'
        ];
        return redirect('/home')->with($notification);
    }

    public function add(Request $request) {
        return view('webshop.create');
    }

    public function create(Request $request)
    {
        Webshop::insert([
            'id' => $request->input('id'),
            'url' => $request->input('url'),
            'customer_key' => $request->input('customer_key'),
            'customer_secret' => $request->input('customer_secret'),
            'type' => $request->input('type'),
        ]);

        $notification = [
            'message' => 'Webshop added succesfully!',
            'alert-type' => 'info'
        ];

        return view('/home')->with([
            "notification" => $notification
        ]);

    }


}
