<?php

namespace App\Http\Controllers\Frontend\Spline;

use App\Http\Controllers\Controller;
use App\Models\Commande\Commande;
use App\Models\Product\Product;
use App\Repositories\Backend\Spline\Client\ClientRepository;
use App\Repositories\Frontend\Spline\Product\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;

class ProductController extends Controller
{

	protected $products;

    public function __construct(
        ProductRepository $products,
        ClientRepository $clients
    )
    {
        $this->products = $products;
        $this->clients = $clients;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
    	return view('frontend.spline.products.index');
    }

    public function getAllProducts()
    {   
        if (Auth::user()->hasRole('Administrator')) {
            $products = $this->products->getAll();
        }else{
            $products = $this->products->getByClient(Auth::user()->collaborateur->client_id);
        }

        return Datatables::of($products)
            ->addColumn('client', function($product){
                return $product->client->name;
            })
            ->addColumn('actions', function($product) {
            return '<a class="btn btn-xs btn-danger delete-product"  rel="tooltip"  title="Supprimer produit" data-toggle="modal" data-id="'.$product->id.'" data-produit=\''.json_encode($product).'\' href="#delete-modal"><i class="glyphicon glyphicon-trash"></i></a>';
        })
            ->make(true);;
    }


    public function getByClient(Request $request)
    {
        $id = $request->all()['id'];
        $products = $this->products->getByClient(intval($id));

        return $products;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $clients = null;
        if (Auth::user()->hasRole('Administrator')) {
            $clients = $this->clients->getAll();
        }

        return view('frontend.spline.products.create', [
            'clients' => $clients
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $req = $request->all();

        $product = new Product;
        $product->name = $req['nom_produit'];
        if (Auth::user()->hasRole('Administrator')) {
            $product->client_id = $req['client_id'];
        }else{
            $product->client_id = Auth::user()->collaborateur->client_id;
        }

        $product->save();
        
        return $product;
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
        if(count(Commande::where('product_id', $id)->get()) == 0 && $product = $this->products->find($id)){
            $this->products->delete($product); 
            return "success";
        }else{
            return "fail";
        }
        
    }
}
