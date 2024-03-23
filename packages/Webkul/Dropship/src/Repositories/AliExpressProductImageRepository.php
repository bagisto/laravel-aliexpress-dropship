<?php

namespace Webkul\Dropship\Repositories;

use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Repositories\ProductImageRepository;

class AliExpressProductImageRepository extends Repository
{
    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Product\Repositories\ProductImageRepository  $productImageRepository
     * @param  \Illuminate\Container\Container  $app
     * @return void
     */
    public function __construct(
        protected ProductImageRepository $productImageRepository,
        App $app
    )
    {
        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Dropship\Contracts\AliExpressProductImage';
    }

    /**
     * @param array $images
     * @param mixed $product
     * @return mixed
     */
    public function uploadImages($images, $product)
    {
        $previousImageIds = $product->images()->pluck('id');

        foreach ($images as $image) {
            $aliExpressProductImage = $this->findOneByField('url', $image);

            if ($aliExpressProductImage) {
                if (is_numeric($index = $previousImageIds->search($aliExpressProductImage->product_image_id))) {
                    $previousImageIds->forget($index);
                }

                continue;
            } else {
                $imagePath = isset($_SERVER['HTTPS']) ? 'https://' . $image : 'http://' . $image;

                $path = 'product/' . $product->id . '/' . str_random(40) . '.' . pathinfo($image, PATHINFO_EXTENSION);

                Storage::put($path, file_get_contents($imagePath));

                $productImage = $this->productImageRepository->create([
                    'path'       => $path,
                    'product_id' => $product->id,
                ]);

                $this->create([
                    'url'              => $image,
                    'product_image_id' => $productImage->id,
                ]);
            }
        }

        foreach ($previousImageIds as $imageId) {
            if ($imageModel = $this->productImageRepository->find($imageId)) {
                Storage::delete($imageModel->path);

                $this->productImageRepository->delete($imageId);
            }
        }
    }
}