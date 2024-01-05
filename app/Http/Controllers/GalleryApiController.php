<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Models\Post;
use Illuminate\Support\Facades\Http;


/**
* @OA\Info(
* description="Anisah Salma Rafida",
* version="0.0.1",
* title="Belajar API Gallery PPW2 Pertemuan 13...",
* termsOfService="http://swagger.io/terms/",
* @OA\Contact(
* email="ansararara@gmail.com"
* ),
* @OA\License(
* name="Apache 2.0",
* url="http://www.apache.org/licenses/LICENSE-2.0.html"
* )
* )
*/

class GalleryAPIController extends Controller
{
    /**
    * @OA\Get(
        * path="/api/getgallery",
        * tags={"Get Data Gallery"},
        * summary="Mengambil Data Gallery",
        * description="Data Gallery",
        * operationId="GetGallery",
    * @OA\Response(
            * response="default",
            * description="successful operation"
        * )
    * )
    */

    public function getGallery()
    {
        $post = Post::all();
        return response()->json(["data" => $post]);
    }

    public function index(){
        $response = Http::get('http://127.0.0.1:9000/api/getgallery');
        $datas = $response->object()->data;
        return view('APIGallery.index', compact('datas'));
    }

    public function create()
    {
        return view('APIGallery.create');
    }

    /**
 * @OA\Post(
 *     path="/api/penyimpananGallery",
 *     tags={"Upload Gambar"},
 *     summary="Mengunggah Gambar",
 *     description="Mengunggah gambar.",
 *     operationId="postGallery",
 *     @OA\RequestBody(
 *         required=true,
 *         description="Data untuk mengunggah gambar",
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="title",
 *                     description="Judul Upload",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="description",
 *                     description="Deskripsi Gambar",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="picture",
 *                     description="File Gambar",
 *                     type="string",
 *                     format="binary"
 *                 ),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Berhasil dijalankan"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validasi Gagal. Mungkin ada data yang tidak sesuai format atau tidak lengkap."
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Terjadi kesalahan server."
 *     )
 * )
 */


 public function penyimpananGallery(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'picture' => 'image|nullable|max:1999'
            ]);
            if ($request->hasFile('picture')) {
            $filenameWithExt = $request->file('picture')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('picture')->getClientOriginalExtension();
            $basename = uniqid() . time();
        
            $filenameSimpan = "{$basename}.{$extension}";
            $path = $request->file('picture')->storeAs('posts_image', $filenameSimpan);


            } else {
            $filenameSimpan = 'noimage.png';
            }

            $post = new Post;
            $post->picture = $filenameSimpan;
            $post->title = $request->input('title');
            $post->description = $request->input('description');
            $post->save();
            
            return redirect()->route('DapatGallery')->with('success', 'Berhasil menambahkan data baru');
           
    }


    public function createThumbnail($path, $width, $height)
    {
    $img = Image::make($path)->resize($width, $height, function ($constraint) {
        $constraint->aspectRatio();
    });
    $img->save($path);
    }


    // /**
//  * @OA\Post(
//  *     path="/api/deleteGallery/{id}",
//  *     tags={"Hapus Upload"},
//  *     summary="Hapus Upload",
//  *     description="Hapus Uploadddd",
//  *     operationId="hapusUpload",
//  *     @OA\Parameter(
//  *         name="id",
//  *         description="id upload",
//  *         required=true,
//  *         in="path",
//  *         @OA\Schema(
//  *             type="string"
//  *         )
//  *     ),
//  *     @OA\Response(
//  *         response="default",
//  *         description="successful operation"
//  *     )
//  * )
//  */
    



}