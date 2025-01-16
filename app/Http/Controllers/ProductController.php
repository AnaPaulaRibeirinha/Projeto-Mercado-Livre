<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accessToken = session('mercado_livre_access_token');

        if (!$accessToken) {
            return redirect()->route('oauth.redirect');
        }

        try {
            $client = new Client();
            $response = $client->get('https://api.mercadolibre.com/sites/MLB/search?q=smartphone', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
            ]);

            $data = json_decode($response->getBody(), true);


            return view('home', ['products' => $data['results']]);
        } catch (\Exception $e) {
            return back()->withErrors('Erro ao carregar produtos: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'category_id' => 'required|string',
            'image' => 'nullable|image',
        ]);


        $product = Product::create($data);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images', 'public');
        } else {
            $data['image'] = null; 
        }

        $client = new Client();
        try {
            $response = $client->post('https://api.mercadolibre.com/items', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('MERCADO_LIVRE_TOKEN'),
                ],
                'json' => [
                    'title' => $product->name,
                    'category_id' => $product->category_id,
                    'price' => $product->price,
                    'available_quantity' => $product->quantity,
                    'pictures' => [
                        ['source' => asset('storage/' . $product->image)],
                    ],
                ],
            ]);

            $responseBody = json_decode($response->getBody(), true);
            return view('products.response', compact('responseBody'));
        } catch (\Exception $e) {
            return back()->withErrors('Erro ao enviar produto para o Mercado Livre: ' . $e->getMessage());
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
