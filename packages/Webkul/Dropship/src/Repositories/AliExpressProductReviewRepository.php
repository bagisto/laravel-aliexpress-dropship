<?php

namespace Webkul\Dropship\Repositories;

use Illuminate\Container\Container as App;
use Carbon\Carbon;
use Symfony\Component\DomCrawler\Crawler;
use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Repositories\ProductReviewRepository;

/**
 * AliExpress Product Review Repository
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AliExpressProductReviewRepository extends Repository
{
    /**
     * AliExpressProductRepository object
     *
     * @var Object
     */
    protected $aliExpressProductRepository;

    /**
     * ProductReviewRepository object
     *
     * @var Object
     */
    protected $productReviewRepository;

    /**
     * Create a new controller instance.
     *
     * @param Webkul\Product\Repositories\AliExpressProductRepository $aliExpressProductRepository
     * @param Webkul\Product\Repositories\ProductReviewRepository     $productReviewRepository
     * @param Illuminate\Container\Container                          $app
     * @return void
     */
    public function __construct(
        AliExpressProductRepository $aliExpressProductRepository,
        ProductReviewRepository $productReviewRepository,
        App $app
    )
    {
        $this->aliExpressProductRepository = $aliExpressProductRepository;

        $this->productReviewRepository = $productReviewRepository;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Dropship\Contracts\AliExpressProductReview';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $productReview = $this->productReviewRepository->create([
                'product_id' => $data['product_id'],
                'name' => $data['name'],
                'rating' => $data['rating'],
                'status' => 'approved',
                'title' => ' ',
                'comment' => $data['comment'] ?? 'N/A'
            ]);

        $productReview->created_at = $data['created_at'];
        $productReview->updated_at = $data['updated_at'];

        $productReview->save();

        $aliExpressProductReview = parent::create([
                'product_review_id' => $productReview->id,
                'ali_express_review_id' => $data['ali_express_review_id'],
            ]);

        return $aliExpressProductReview;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function importReviews(array $data)
    {
        if (! core()->getConfigData('catalog.products.review.guest_review'))
            return;

        if ($data['review_type'] == 2)
            return;

        $crawler = new Crawler();
        $crawler = $this->getDomCrawler($this->getHtmlByUrl("https:" . $data['review_url']));

        $reviewParams = [
                'productId' => $crawler->filter('input#productId')->attr('value'),
                'ownerMemberId' => $crawler->filter('input#ownerMemberId')->attr('value'),
                'memberType' => $crawler->filter('input#memberType')->attr('value')
            ];

        $saveReviewCount = 0;

        $reviews = [];

        for ($i = 1; ; $i++) {
            if ($data['review_type'] == 3) {
                if ($saveReviewCount < $data['custom_review_count']) {
                    $reviewParams['page'] = $i;

                    $reviewsDomCrawler = $this->getDomCrawler($this->submitReviewsPage($reviewParams));

                    $result = $this->getReviewsData($reviewsDomCrawler);

                    $saveReviewCount += count(current($result));

                    if (! count($result)) {
                        break;
                    }

                    $reviews = array_merge($reviews, $result);
                } else {
                    break;
                }
            } else {
                $reviewParams['page'] = $i;

                $reviewsDomCrawler = $this->getDomCrawler($this->submitReviewsPage($reviewParams));

                $result = $this->getReviewsData($reviewsDomCrawler);

                if (! count($result)) {
                    break;
                }

                $reviews = array_merge($reviews, $result);
            }
        }

        $saveReviewCount = 0;

        foreach (array_reverse($reviews) as $review) {
            if ($data['review_type'] == 3) {
                if ($saveReviewCount == $data['custom_review_count']) {
                    break;
                }

                $saveReviewCount++;
            }

            $this->create(array_merge(['product_id' => $data['product_id']], $review));
        }
    }

    /**
     * @param object $reviewsDomCrawler
     * @return array
     */
    public function getReviewsData($reviewsDomCrawler)
    {
        $count = 0;

        $reviews = [];

        $reviewsDomCrawler->filter('.feedback-list-wrap .feedback-item')->each(function (Crawler $crawler) use(&$reviews, &$count) {
            $rating = str_replace('%', '', str_replace('width:', '', $crawler->filter('.star-view span')->attr('style')));

            $date = Carbon::parse($crawler->filter('.r-time')->text());

            $reviews[$count] = [
                    'ali_express_review_id' => $crawler->filter('.feedback-id')->attr('value'),
                    'name' => trim($crawler->filter('.user-name')->text()),
                    'comment' => trim($crawler->filter('.buyer-feedback')->text()),
                    'rating' => (5 / (100 / $rating)),
                    'created_at' => $date->format('Y-m-d H:i:s'),
                    'updated_at' => $date->format('Y-m-d H:i:s')
                ];

            $count++;
        });

        return $reviews;
    }

    /**
     * @param array $reviewParams
     * @return array
     */
    public function submitReviewsPage($reviewParams)
    {
        $formUrl = "https://feedback.aliexpress.com/display/productEvaluation.htm";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $formUrl);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($reviewParams));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    /**
     * @param string $url
     * @return array
     */
    public function getHtmlByUrl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $data = curl_exec($ch);

        curl_close($ch);

        return $data;
    }

    /**
     * @param string $html
     * @return object
     */
    public function getDomCrawler($html)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html, 'UTF-8');

        return $crawler;
    }
}