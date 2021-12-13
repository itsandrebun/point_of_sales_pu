<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\Produk;
use PDF;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kategori = Kategori::all()->pluck('nama_kategori', 'id_kategori');

        return view('produk.index', compact('kategori'));
    }

    public function data()
    {
        $produk = Produk::leftJoin('kategori', 'kategori.id_kategori', 'produk.id_kategori')
            ->select('produk.*', 'nama_kategori')
            ->where('produk.active',1)
            // ->orderBy('kode_produk', 'asc')
            ->get();

        for ($b=0; $b < count($produk); $b++) {
            $produk[ $b]['image'] = asset($produk[$b]['image']); 
        }

        return datatables()
            ->of($produk)
            ->addIndexColumn()
            ->addColumn('select_all', function ($produk) {
                return '
                    <input type="checkbox" name="id_produk[]" value="'. $produk->id_produk .'">
                ';
            })
            ->addColumn('kode_produk', function ($produk) {
                return '<span class="label label-success">'. $produk->kode_produk .'</span>';
            })
            // ->addColumn('image', function ($produk) {
            //     return  $produk->image;
            // })
            ->addColumn('harga_beli', function ($produk) {
                return format_uang($produk->harga_beli);
            })
            ->addColumn('harga_jual', function ($produk) {
                return format_uang($produk->harga_jual);
            })
            ->addColumn('stok', function ($produk) {
                return format_uang($produk->stok);
            })
            ->addColumn('aksi', function ($produk) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('produk.update', $produk->id_produk) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('produk.destroy', $produk->id_produk) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'kode_produk', 'select_all'])
            ->make(true);
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
        // $produk = Produk::latest()->first() ?? new Produk();

        $post_parameter = array(
            "nama_produk" => $request->nama_produk,
            "id_kategori" => $request->id_kategori,
            "merk" => $request->merk,
            "harga_jual" => $request->harga_jual,
            "stok" => $request->stok 
        );

        if($request->hasFile('image')){
            $extension = strtolower($request->file('image')->getClientOriginalExtension());
            $savedFilename = uniqid().'.'.$extension;
            $request->image->move(public_path('img/products/'), $savedFilename);
            $post_parameter['image'] = 'img/products/'.$savedFilename;
        }
        // 

        $produk_id = Produk::create($post_parameter)->id_produk;

        if($produk_id > 0){
            $kode_produk = 'P'. tambah_nol_didepan((int)$produk_id +1, 6);    
            Produk::find($produk_id)->update(['kode_produk' => $kode_produk]);
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $produk = Produk::find($id);

        return response()->json($produk);
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

    $post_parameter = array(
        "nama_produk" => $request->nama_produk,
        "id_kategori" => $request->id_kategori,
        "merk" => $request->merk,
        "harga_jual" => $request->harga_jual,
        "stok" => $request->stok 
    );
    if($request->hasFile('image')){
        $extension = strtolower($request->file('image')->getClientOriginalExtension());
        $savedFilename = uniqid().'.'.$extension;
        $request->image->move(public_path('img/products/'), $savedFilename);
        $post_parameter['image'] = 'img/products/'.$savedFilename;
    }                                
    
    $produk = Produk::find($id)->update($post_parameter);
    return redirect()->back();
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $produk = Produk::find($id)->update(['active' => -1]);

        return response(null, 204);
    }

    public function deleteSelected(Request $request)
    {
        Produk::whereIn('id_produk',$request->id_produk)->update(['active' => -1]);

        return response(null, 204);
    }

    public function cetakBarcode(Request $request)
    {
        $dataproduk = Produk::whereIn('id_produk',$request->id_produk)->get();

        $no  = 1;
        $pdf = PDF::loadView('produk.barcode', compact('dataproduk', 'no'));
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('produk.pdf');
    }

}