<?php

namespace Webkul\Dropship\Repositories;

use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Repositories\ProductVideoRepository;

/**
 * Seller AliExpress Product Video Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AliExpressProductVideoRepository extends Repository
{
    /**
     * ProductImageRepository object
     *
     * @var array
     */
    protected $productVideoRepository;

    /**
     * Create a new controller instance.
     *
     * @param Webkul\Product\Repositories\ProductVideoRepository $productVideoRepository
     * @param Illuminate\Container\Container                     $app
     * @return void
     */
    public function __construct(
        ProductVideoRepository $productVideoRepository,
        App $app
    )
    {
        $this->productVideoRepository = $productVideoRepository;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Dropship\Contracts\AliExpressProductVideo';
    }

    /**
     * @param array $images
     * @param mixed $product
     * @return mixed
     */
    public function uploadVideos($video, $product)
    {
        $previousVideoIds = $product->videos()->pluck('id');

        $aliExpressProductVideo = $this->findOneByField('url', $video);

        if ($aliExpressProductVideo) {
                if (is_numeric($index = $previousVideoIds->search($aliExpressProductVideo->product_video_id))) {
                    $previousVideoIds->forget($index);
                }
        } else {
            $path = 'product/' . $product->id . '/' . str_random(40) . '.' . pathinfo($video, PATHINFO_EXTENSION);

            Storage::put($path, file_get_contents($video));

            $productVideo = $this->productVideoRepository->create([
                    'type' => 'video',
                    'path' => $path,
                    'product_id' => $product->id
                ]);

            $this->create([
                'url' => $video,
                'product_video_id' => $productVideo->id
            ]);
        }

        // foreach ($previousVideoIds as $videoId) {
        //     if ($videoModel = $this->productVideoRepository->find($videoId)) {
        //         Storage::delete($videoModel->path);

        //         $this->productVideoRepository->delete($videoId);
        //     }
        // }
    }
}