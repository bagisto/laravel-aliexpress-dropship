<?php

namespace Webkul\Dropship\Repositories;

use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Eloquent\Repository;
use Webkul\Attribute\Repositories\AttributeOptionRepository;

/**
 * AliExpress Attribute Option Repository
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AliExpressAttributeOptionRepository extends Repository
{
    /**
     * AttributeOptionRepository object
     *
     * @var array
     */
    protected $attributeOptionRepository;

    /**
     * Create a new repository instance.
     *
     * @param Webkul\Attribute\Repositories\AttributeOptionRepository $attributeOptionRepository
     * @param Illuminate\Container\Container                          $app
     * @return void
     */
    public function __construct(
        AttributeOptionRepository $attributeOptionRepository,
        App $app
    )
    {
        $this->attributeOptionRepository = $attributeOptionRepository;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Dropship\Contracts\AliExpressAttributeOption';
    }

    /**
     * Checks if attribute options exist or create new one 
     *
     * @param AliExpressAttribute $aliExpressAttribute
     * @param array               $data
     * @return array
     */
    public function checkAttributeOptionsAvailabiliy($aliExpressAttribute, $data)
    {
        $aliExpressAttributeRepository = app('Webkul\Dropship\Repositories\AliExpressAttributeRepository');

        foreach ($data as $key => $optionData) {
            if ($aliExpressAttribute->attribute->swatch_type == 'image') {
                $aliExpressAttributeOption = $this->findOneWhere([
                        'ali_express_attribute_option_id' => $optionData['option_id'],
                        'ali_express_swatch_image' => $optionData['image'],
                    ]);
            } else {
                $aliExpressAttributeOption = $this->findOneWhere([
                        'ali_express_attribute_option_id' => $optionData['option_id'],
                        'ali_express_swatch_name' => $optionData['name']
                    ]);
            }

            if (! $aliExpressAttributeOption) {
                $attributeOption = null;

                if ($aliExpressAttribute->attribute->swatch_type != 'image') {
                    $attributeOption = $this->attributeOptionRepository->getModel()::whereTranslation('label', $optionData['name'])->first();

                    if (! $attributeOption) {
                        $attributeOption = $this->attributeOptionRepository->findOneWhere([
                                'attribute_id' => $aliExpressAttribute->attribute_id,
                                'admin_name' => $optionData['name']
                            ]);
                    }

                    if ($attributeOption && $attributeOption->attribute_id != $aliExpressAttribute->attribute_id) {
                        $attributeOption = null;
                    }
                }
                
                if (! $attributeOption) {
                    $attributeOptionLabels = [];

                    foreach (core()->getAllLocales() as $locale) {
                        $attributeOptionLabels[$locale->code] = [
                                'label' => $optionData['name']
                            ];
                    }

                    $attributeOption = $aliExpressAttribute->attribute->options()->create(array_merge($attributeOptionLabels, [
                            'admin_name' => $optionData['name'],
                            'sort_order' => $key
                        ]));

                    if ($aliExpressAttribute->attribute->swatch_type == 'image' && $optionData['image']) {
                        $path = 'attribute_option/' . str_random(40) . '.' . pathinfo($optionData['image'], PATHINFO_EXTENSION);

                        Storage::put($path, file_get_contents($optionData['image']));

                        $this->attributeOptionRepository->update([
                                'swatch_value' => $path
                            ], $attributeOption->id);
                    }
                }

                $aliExpressAttributeOption = $this->create([
                        'ali_express_swatch_name' => $optionData['name'],
                        'ali_express_swatch_image' => isset($optionData['image']) ? $optionData['image'] : '',
                        'ali_express_attribute_option_id' => $optionData['option_id'],
                        'ali_express_attribute_id' => $aliExpressAttribute->id,
                        'attribute_option_id' => $attributeOption->id,
                    ]);
            }
        }
    }
}