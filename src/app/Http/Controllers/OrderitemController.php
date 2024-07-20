<?php

namespace App\Http\Controllers;

use App\Models\Orderitem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderitemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Orderitem::all();
        if ($data->isEmpty()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Data not found',
                ]
            );
        }
        
        Log::info('showing all order');
        return response()->json(
            [
                'success' => true,
                'message' => 'Success retrieved data',
                'data' => [
                    'attributes' => $data
                ]
            ]);
    }

    public function showDataJoin()
    {
        $data = Orderitem::with(['order', 'product'])->get();
        
        return response()->json([
            'success' => true,
            'message' => 'Success retrieved data',
            'data' => [
                'attributes' => $data
            ]
        ]);
    }

    public function show($id)
    {
        $data = Orderitem::find($id);
        if (!$data) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Data not found',
                ]
            );
        }

        Log::info('showing orderitem by id');

        return response()->json(
            [
                'success' => true,
                'message' => 'Success retrieved data',
                'data' => [
                    'attributes' => $data
                ]
            ]);
    }

    public function showIdJoin($id)
    {
        $findId = Orderitem::find($id);
        $data = Orderitem::where('id', $id)->with(array('order'=>function($query){
            $query->select();
        }))->with(array('product'=>function($query){
            $query->select();
        }))->get();
        if(!$findId) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Parameter not found',
                ]
            );
        }

        Log::info('showing order item with post comment by id ');

        return response()->json(
            [
                'success' => true,
                'message' => 'Success retrieved data',
                'data' => [
                    'attributes' => $data
                ]
            ]);
    }

    public function add(Request $request)
    {
        $this->validate($request, [
            'data.attributes.order_id' => 'required|exists:orders,id',
            'data.attributes.product_id' => 'required|exists:products,id',
            'data.attributes.quantity' => 'required',
        ]);
        $data = new Orderitem();
        $data->order_id = $request->input('data.attributes.order_id');
        $data->product_id = $request->input('data.attributes.product_id');
        $data->quantity = $request->input('data.attributes.quantity'); // Fixed missing semicolon
        $data->save();
    
        Log::info('Adding Order Item');
    
        return response()->json([
            'success' => true,
            'message' => 'Successfully Added',
            'data' => [
                'attributes' => $data
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'data.attributes.order_id' => 'required | exists:orders,id',
            'data.attributes.product_id' => 'required | exists:products,id',
            'data.attributes.quantity' =>  'required',
        ]);

        $data = Orderitem::find($id);
        if ($data) { 
        $data->order_id = $request->input('data.attributes.order_id');
        $data->product_id = $request->input('data.attributes.product_id');
        $data->quantity = $request->input('data.attributes.quantity');
        $data->save();

        Log::info('Update order item by id');

        return response()->json(
            [
                'success' => true,
                'message' => 'Success Update',
                'data' => [
                    'attributes' => $data
                ]
            ]);
    }else {
        return response()->json([
          'success' => false,
          'message' => 'Not Found',  
        ]);
    }
}

public function delete($id){
    $data = Orderitem::find($id);
    if($data){
        $data->delete();

        Log::info ('Deleting orderitem by id');
        return response()->json([
            'success' => true,
            'message' => 'Success Deleted',
            'data' => [
                'attributes' => $data
            ]
        ]);
    }else{
        return response()->json([
            'success' => false,
            'message' => 'Not Found',
        ]);
}
}

}