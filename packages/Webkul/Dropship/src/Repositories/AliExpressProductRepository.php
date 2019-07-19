<?php

namespace Webkul\Dropship\Repositories;

use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Core\Eloquent\Repository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Dropship\Repositories\AliExpressAttributeOptionRepository;

/**
 * Seller AliExpress Product Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AliExpressProductRepository extends Repository
{
    /**
     * ProductRepository object
     *
     * @var Object
     */
    protected $productRepository;

    /**
     * ProductInventoryRepository object
     *
     * @var Object
     */
    protected $productInventoryRepository;

    /**
     * AttributeRepository object
     *
     * @var Object
     */
    protected $attributeRepository;

    /**
     * ProductAttributeValueRepository object
     *
     * @var Object
     */
    protected $productAttributeValueRepository;

    /**
     * AliExpressProductImageRepository object
     *
     * @var Object
     */
    protected $aliExpressProductImageRepository;

    /**
     * AliExpressAttributeRepository object
     *
     * @var Object
     */
    protected $aliExpressAttributeRepository;

    /**
     * AliExpressAttributeOptionRepository object
     *
     * @var Object
     */
    protected $aliExpressAttributeOptionRepository;

    /**
     * Create a new controller instance.
     *
     * @param Webkul\Product\Repositories\ProductRepository                    $productRepository
     * @param Webkul\Product\Repositories\ProductInventoryRepository           $productInventoryRepository
     * @param Webkul\Attribute\Repositories\AttributeRepository                $attributeRepository
     * @param Webkul\Product\Repositories\ProductAttributeValueRepository      $productAttributeValueRepository
     * @param Webkul\Product\Repositories\AliExpressProductImageRepository     $aliExpressProductImageRepository
     * @param Webkul\Dropshop\Repositories\AliExpressAttributeRepository       $aliExpressAttributeRepository
     * @param Webkul\Dropshop\Repositories\AliExpressAttributeOptionRepository $aliExpressAttributeOptionRepository
     * @param Illuminate\Container\Container                                   $app
     * @return void
     */
    public function __construct(
        ProductRepository $productRepository,
        ProductInventoryRepository $productInventoryRepository,
        AttributeRepository $attributeRepository,
        ProductAttributeValueRepository $productAttributeValueRepository,
        AliExpressProductImageRepository $aliExpressProductImageRepository,
        AliExpressAttributeRepository $aliExpressAttributeRepository,
        AliExpressAttributeOptionRepository $aliExpressAttributeOptionRepository,
        App $app
    )
    {
        $this->productRepository = $productRepository;

        $this->productInventoryRepository = $productInventoryRepository;

        $this->attributeRepository = $attributeRepository;

        $this->productAttributeValueRepository = $productAttributeValueRepository;

        $this->aliExpressProductImageRepository = $aliExpressProductImageRepository;

        $this->aliExpressAttributeRepository = $aliExpressAttributeRepository;

        $this->aliExpressAttributeOptionRepository = $aliExpressAttributeOptionRepository;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Dropship\Contracts\AliExpressProduct';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            Event::fire('dropship.catalog.ali-express-product.create.before');

            $product = $this->productRepository->create([
                    'sku' => $data['id'],
                    'type' => isset($data['super_attributes']) && ! empty($data['super_attributes']) ? 'configurable' : 'simple',
                    'attribute_family_id' => core()->getConfigData('dropship.settings.product.default_attribute_family')
                ]);

            $optionalProductData = [];
            if ($product->type != 'configurable' && $inventorySource = core()->getConfigData('dropship.settings.product_quantity.default_inventory_source')) {
                if (core()->getConfigData('dropship.settings.product_quantity.product_quantity') == 1) {
                    $qty = $data['qty'] ?? 0;
                } else {
                    $qty = core()->getConfigData('dropship.settings.product_quantity.custom_quantity');
                }

                $optionalProductData['inventories'] = [
                        $inventorySource => $qty
                    ];
            }

            $price = core()->convertToBasePrice($data['price'], $data['currency']);

            if (! is_null(core()->getConfigData('dropship.settings.product_price.price'))) {
                if (core()->getConfigData('dropship.settings.product_price.price') == 2
                    && ! is_null(core()->getConfigData('dropship.settings.product_price.custom_price'))) {
                    $price = core()->getConfigData('dropship.settings.product_price.custom_price');
                } else if (core()->getConfigData('dropship.settings.product_price.price') == 3
                    && ! is_null(core()->getConfigData('dropship.settings.product_price.increase_price'))) {
                    $price += (($price / 100) * core()->getConfigData('dropship.settings.product_price.increase_price'));
                } else if (core()->getConfigData('dropship.settings.product_price.price') == 4
                    && ! is_null(core()->getConfigData('dropship.settings.product_price.decrease_price'))) {
                    $price -= (($price / 100) * core()->getConfigData('dropship.settings.product_price.decrease_price'));
                }
            }

            $product = $this->productRepository->update(array_merge($optionalProductData, [
                    'channel' => core()->getConfigData('dropship.settings.product.default_channel'),
                    'locale' => core()->getConfigData('dropship.settings.product.default_locale'),
                    'name' => $data['name'],
                    'price' => $price,
                    'status' => core()->getConfigData('dropship.settings.product.product_status'),
                    'visible_individually' => 1,
                    'description' => isset($data['description_url']) && $data['description_url'] != ''
                            ? $this->getHtmlByUrl('https://' . $data['description_url'])
                            : '',
                    'short_description' => $data['name'],
                    'meta_title' => $data['meta_title'],
                    'meta_description' => $data['meta_description'],
                    'meta_keywords' => $data['meta_keywords'],
                    'categories' => [core()->getConfigData('dropship.settings.product.default_category')],
                    'tax_category_id' => core()->getConfigData('dropship.settings.product.default_tax_category'),
                    'url_key' => $data['id'],
                    'new' => core()->getConfigData('dropship.settings.product.set_new'),
                    'featured' => core()->getConfigData('dropship.settings.product.set_featured'),
                    "weight" => core()->getConfigData('dropship.settings.product.weight') ?? 0
                ]), $product->id);

            $attributeRepository = app('Webkul\Attribute\Repositories\AttributeRepository');

            if (isset($data['super_attributes']) && ! empty($data['super_attributes'])) {
                foreach ($data['super_attributes'] as $attributeData) {
                    $aliExpressAttribute = $this->aliExpressAttributeRepository->findOnebyField('ali_express_attribute_id', $attributeData['attr_id']);

                    if ($aliExpressAttribute) {
                        $attribute = $attributeRepository->find($aliExpressAttribute->attribute_id);
                    } else {
                        $attributeCode = $this->aliExpressAttributeRepository->getAttributeCodeByTitle($attributeData['title']);

                        $attribute = $attributeRepository->findOneWhere(['code' => $attributeCode]);
                    }

                    $product->super_attributes()->attach($attribute->id);
                }
            }

            if (isset($data['image_thumbnails'])) {
                $this->aliExpressProductImageRepository->uploadImages($data['image_thumbnails'], $product);
            }

            $aliExpressProduct = parent::create([
                    'product_id' => $product->id,
                    'ali_express_product_id' => $data['id'],
                    'ali_express_product_url' => $data['url'],
                    'ali_express_product_description_url' => isset($data['description_url']) && $data['description_url'] != ''
                            ? $data['description_url']
                            : ''
                ]);

            Event::fire('dropship.catalog.ali-express-product.create.after', $aliExpressProduct);
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
        DB::commit();

        return $aliExpressProduct;
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        if (! core()->getConfigData('dropship.settings.auto_updation.quantity')
            && ! core()->getConfigData('dropship.settings.auto_updation.price'))
            return;

        $aliExpressProduct = $this->find($id);

        if (! $aliExpressProduct || ! $aliExpressProduct->ali_express_product_url)
            return;

        $htmlObject = $this->getHtmlObj($this->getHtmlByUrl($aliExpressProduct->ali_express_product_url));
        if (empty($htmlObject))
            return;

        $skuProducts = $this->getAliExpressSkuProducts($htmlObject);

        if (! isset($skuProducts[0]->skuVal))
            return;

        if ($aliExpressProduct->product->type == 'configurable') {
            foreach ($skuProducts as $skuProduct) {
                $aliExpressChildProduct = $this->findOneWhere([
                        'parent_id' => $aliExpressProduct->id,
                        'combination_id' => str_replace(',', '_', $skuProduct->skuPropIds)
                    ]);

                if ($aliExpressChildProduct) {
                    $this->updatePriceInventory($skuProduct->skuVal, $aliExpressChildProduct);

                    $aliExpressChildProduct->touch();
                }
            }
        } else {
            $this->updatePriceInventory($skuProducts[0]->skuVal, $aliExpressProduct);

            $aliExpressProduct->touch();
        }
    }

    /**
     * @param array  $data
     * @param object $aliExpressProduct
     * @return mixed
     */
    public function updatePriceInventory($skuProduct, $aliExpressProduct)
    {
        if (core()->getConfigData('dropship.settings.auto_updation.quantity')
            && $inventorySource = core()->getConfigData('dropship.settings.product_quantity.default_inventory_source')) {
            if (core()->getConfigData('dropship.settings.product_quantity.product_quantity') == 1) {
                $qty = $skuProduct->availQuantity ?? 0;
            } else {
                $qty = core()->getConfigData('dropship.settings.product_quantity.custom_quantity');
            }

            Event::fire('catalog.product.update.before', $aliExpressProduct->product->id);

            $this->productInventoryRepository->saveInventories([
                    'inventories' => [$inventorySource => $qty]
                ], $aliExpressProduct->product);

            Event::fire('catalog.product.update.after', $aliExpressProduct->product()->first());
        }

        if (core()->getConfigData('dropship.settings.auto_updation.price')) {
            Event::fire('catalog.product.update.before', $aliExpressProduct->product->id);

            $price = core()->convertToBasePrice($skuProduct->actSkuCalPrice, 'USD');

            if (! is_null(core()->getConfigData('dropship.settings.product_price.price'))) {
                if (core()->getConfigData('dropship.settings.product_price.price') == 2
                    && ! is_null(core()->getConfigData('dropship.settings.product_price.custom_price'))) {
                    $price = core()->getConfigData('dropship.settings.product_price.custom_price');
                } else if (core()->getConfigData('dropship.settings.product_price.price') == 3
                    && ! is_null(core()->getConfigData('dropship.settings.product_price.increase_price'))) {
                    $price += (($price / 100) * core()->getConfigData('dropship.settings.product_price.increase_price'));
                } else if (core()->getConfigData('dropship.settings.product_price.price') == 4
                    && ! is_null(core()->getConfigData('dropship.settings.product_price.decrease_price'))) {
                    $price -= (($price / 100) * core()->getConfigData('dropship.settings.product_price.decrease_price'));
                }
            }

            $attribute = $this->attributeRepository->findOneByField('code', 'price');

            $attributeValue = $this->productAttributeValueRepository->findOneWhere([
                    'product_id' => $aliExpressProduct->product->id,
                    'attribute_id' => $attribute->id,
                    'channel' => null,
                    'locale' => null
                ]);

            if (! $attributeValue) {
                $this->productAttributeValueRepository->create([
                        'product_id' => $aliExpressProduct->product->id,
                        'attribute_id' => $attribute->id,
                        'value' => $price,
                        'channel' => null,
                        'locale' => null
                    ]);
            } else {
                $this->productAttributeValueRepository->update([
                    ProductAttributeValue::$attributeTypeFields[$attribute->type] => $price
                    ], $attributeValue->id);
            }

            Event::fire('catalog.product.update.after', $aliExpressProduct->product()->first());
        }
    }

    /**
     * @param mixed $aliExpressProduct
     * @param array $data
     * @return mixed
     */

    public function createVariant($aliExpressProduct, $data = [])
    {
        $aliExpressAttributeOption = "";

        Event::fire('catalog.product.update.before', $aliExpressProduct->product_id);
        $aliExpresSuperAttributeOptionIds = explode('_', $data['custom_option']['comb']);
        $aliExpresSuperAttributeOptionNames = explode('+', $data['custom_option']['text']);
        $aliExpresSuperAttributeOptionImage = $data['custom_option']['img'];


        $superAttributeOptionids = [];

        foreach ($aliExpresSuperAttributeOptionIds as $key => $aliExpressSuperAttributeOptionId) {
            if ($aliExpresSuperAttributeOptionImage != "") {
                $aliExpressAttributeOption = $this->aliExpressAttributeOptionRepository->findOneWhere([
                    'ali_express_attribute_option_id' => $aliExpressSuperAttributeOptionId,
                    'ali_express_swatch_image' => $aliExpresSuperAttributeOptionImage
                ]);
            }

            if ($aliExpressAttributeOption == "") {
                $aliExpressAttributeOption = $this->aliExpressAttributeOptionRepository->findOneWhere([
                    'ali_express_attribute_option_id' => $aliExpressSuperAttributeOptionId,
                    'ali_express_swatch_name' => $aliExpresSuperAttributeOptionNames[$key]
                ]);
            }

            if ($aliExpressAttributeOption == "") {
                $aliExpressAttributeOption = $this->aliExpressAttributeOptionRepository->findOneByField(
                        'ali_express_attribute_option_id', $aliExpressSuperAttributeOptionId
                );
            }

            $attributeOption = $aliExpressAttributeOption->attribute_option;
            $superAttributeOptionids[$attributeOption->attribute_id] = $attributeOption->id;

        }
        $optionalProductData = [];

        if ($inventorySource = core()->getConfigData('dropship.settings.product_quantity.default_inventory_source')) {
            if (core()->getConfigData('dropship.settings.product_quantity.product_quantity') == 1) {
                $qty = $data['custom_option']['qty'] ?? 0;
            } else {
                $qty = core()->getConfigData('dropship.settings.product_quantity.custom_quantity');
            }
            $optionalProductData['inventories'] = [
                    $inventorySource => $qty
                ];
        }
        $price = core()->convertToBasePrice($data['custom_option']['price'], $data['currency']);

        if (! is_null(core()->getConfigData('dropship.settings.product_price.price'))) {
            if (core()->getConfigData('dropship.settings.product_price.price') == 2
                && ! is_null(core()->getConfigData('dropship.settings.product_price.custom_price'))) {
                $price = core()->getConfigData('dropship.settings.product_price.custom_price');
            } else if (core()->getConfigData('dropship.settings.product_price.price') == 3
                && ! is_null(core()->getConfigData('dropship.settings.product_price.increase_price'))) {
                $price += (($price / 100) * core()->getConfigData('dropship.settings.product_price.increase_price'));
            } else if (core()->getConfigData('dropship.settings.product_price.price') == 4
                && ! is_null(core()->getConfigData('dropship.settings.product_price.decrease_price'))) {
                $price -= (($price / 100) * core()->getConfigData('dropship.settings.product_price.decrease_price'));
            }
        }
        $variant = $this->productRepository->createVariant($aliExpressProduct->product, $superAttributeOptionids, array_merge($optionalProductData, [
                "sku" => $aliExpressProduct->product->sku . '-variant-' . implode('-', $superAttributeOptionids),
                "name" => $aliExpressProduct->product->name . ' ' . $data['custom_option']['text'],
                "price" => $price,
                "weight" => core()->getConfigData('dropship.settings.product.weight') ?? 0,
                "status" => 1
            ]));
        $aliExpressVariant = parent::create([
                'product_id' => $variant->id,
                'parent_id' => $aliExpressProduct->id,
                'combination_id' => $data['custom_option']['comb']
            ]);
        Event::fire('catalog.product.update.after', $variant->parent);
        return $variant;
    }


    /**
     * @param DOMDocument $htmlObj
     * @return array
     */
    public function getAliExpressSkuProducts($htmlObj)
    {
        $xp = new \DOMXPath($htmlObj);

        $scripts = $xp->query("//script");

        foreach ($scripts as $script) {
            if (preg_match('#var skuProducts=(.*?)];#', $script->nodeValue, $matches)) {
                $skuProducts = json_decode($matches[1] . ']');
            }
        }

        return $skuProducts;
    }

    /**
     * @param string $html
     * @return string
     */
    public function getHtmlObj($html)
    {
        $newDom = new \DOMDocument();

        libxml_use_internal_errors(true);

        $newDom->loadHTML($html);

        return $newDom;
    }

    /**
     * @param string $url
     * @return string
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
}