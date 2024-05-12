<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Product;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use XMLReader;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {   

        $products = Product::filter(request(['title','weight','description', 'category', 'sort', 'dir']))
            ->paginate(30)
            ->withQueryString();
        return view('products.index', [
            'products' => $products
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function import(): View
    {
        return view('products.import');
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        if (!$receiver->isUploaded()) {
            // file not uploaded
        }

        $fileReceived = $receiver->receive(); // receive file
        if ($fileReceived->isFinished()) { // file uploading is complete / all chunks are uploaded
            $file = $fileReceived->getFile(); // get file
            $extension = $file->getClientOriginalExtension();
            $fileName = str_replace('.'.$extension, '', $file->getClientOriginalName()); //file name without extenstion
            $fileName .= '_' . md5(time()) . '.' . $extension; // a unique file name

            $file->move(storage_path('app/files'), $fileName);
            // parse xml and save data to our database
            $filePath = 'app\\files\\' . $fileName;
            self::importProducts(storage_path($filePath));
            return [
                'filename' => $fileName
            ];
        }

        // otherwise return percentage information
        $handler = $fileReceived->handler();
        return [
            'done' => $handler->getPercentageDone(),
            'status' => true
        ];
    }

    protected static function importProducts($file) {
        // Open the XML file
        $reader = new XMLReader();
        $reader->open($file);

        // Iterate through the XML until we reach a <product> node
        $productData = [];
        while ($reader->read()) {
            if ($reader->nodeType == XMLReader::ELEMENT && $reader->name === 'product') {
                // Read the contents of the <product> node
                $productXML = $reader->readOuterXml();

                // Create a new XMLReader instance to parse the inner <product> node
                $innerReader = new XMLReader();
                $innerReader->xml($productXML);

                // Read inner <product> node content
                $currentProduct = [];
                $numberOfAttributes = 0;
                while ($innerReader->read()) {
                    if ($innerReader->nodeType == XMLReader::ELEMENT && $innerReader->name !== 'product') {
                        $numberOfAttributes++;
                        $innerNodeName = $innerReader->name;
                        $innerNodeValue = $innerReader->readString();
                        $attributeName = $innerNodeName;
                        if ($innerNodeName == 'name') {
                            $attributeName = 'title';
                        }
                        $currentProduct[$attributeName] = $innerNodeValue;
                    }
                    

                    if (!empty($currentProduct) && $numberOfAttributes == 4) {
                        $productData[] = $currentProduct;
                        if (count($productData) >= 100) {
                            Product::insert($productData);
                            $productData = [];
                        }
                        $numberOfAttributes = 0;
                        $currentProduct = []; 
                    }
                    
                }
                // Close the inner XMLReader
                $innerReader->close();
            }
        }
        // Close the main XMLReader
        $reader->close();
        if (!empty($productData)) {
            Product::insert($productData);
            $productData = [];
        }
    }
}
