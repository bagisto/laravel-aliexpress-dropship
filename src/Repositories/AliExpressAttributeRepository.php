<?php

namespace Webkul\Dropship\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;

class AliExpressAttributeRepository extends Repository
{
    /**
     * Create a new repository instance.
     *
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @param  \Webkul\Attribute\Repositories\AttributeFamilyRepository  $attributeFamilyRepository
     * @param  \Webkul\Attribute\Repositories\AliExpressAttributeOptionRepository  $aliExpressAttributeOptionRepository
     * @param  \Illuminate\Container\Container  $app
     * @return void
     */
    public function __construct(
        protected AttributeRepository $attributeRepository,
        protected AttributeFamilyRepository $attributeFamilyRepository,
        protected AliExpressAttributeOptionRepository $aliExpressAttributeOptionRepository,
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
        return 'Webkul\Dropship\Contracts\AliExpressAttribute';
    }

    /**
     * Import Super Attributes
     *
     * @param array $superAttributes
     * @return array
     */
    public function importSuperAttributes($superAttributes)
    {
        $data = [];
        $attributeFamily = $this->attributeFamilyRepository->find(core()->getConfigData('dropship.settings.product.default_attribute_family'));

        $attributeGroup = $attributeFamily->attribute_groups()->first();

        $groupAttributeCount = $attributeGroup->custom_attributes()->count();

        foreach ($superAttributes as $attributeData) {
            $aliExpressAttribute = $this->findOnebyField('ali_express_attribute_id', $attributeData['attr_id']);
            if (strtolower(substr($attributeData['title'], 0, -1)) == "color" && $attributeData['swatch_type'] == "text") {
                $attributeCode = $this->getAttributeCodeByTitle($attributeData['title']);
                $attribute = $this->attributeRepository->findOneWhere(['code' => $attributeCode]);


                if ($attribute && $attribute->swatch_type != $attributeData['swatch_type']) {
                    $attributeCode = 'ali_express_text_' . $attributeCode;

                    $attribute = $this->attributeRepository->findOneWhere(['code' => $attributeCode]);
                }

                if (! $attribute) {
                    $label = substr($attributeData['title'], 0, -1);

                    $attributeLabels = [];
                    foreach (core()->getAllLocales() as $locale) {
                        $attributeLabels[$locale->code] = [
                                'name' => $label
                            ];
                    }
                    $attribute = $this->attributeRepository->create(array_merge($attributeLabels, [
                            'code' => $attributeCode,
                            'type' => 'select',
                            'swatch_type' => $attributeData['swatch_type'],
                            'admin_name' => substr($attributeData['title'], 0, -1),
                            'is_configurable' => 1,
                            'is_filterable' => 1,
                        ]));
                }

                $aliExpressAttribute = $this->create([
                        'ali_express_attribute_id' => $attributeData['attr_id'],
                        'attribute_id' => $attribute->id,
                    ]);
            }

            else if ($attributeData['swatch_type'] == "color") {
                $attributeCode = $this->getAttributeCodeByTitle($attributeData['title']);

                $attribute = $this->attributeRepository->findOneWhere(['code' => $attributeCode]);

                if ($attribute && $attribute->swatch_type != $attributeData['swatch_type']) {
                    $attributeCode = 'ali_express_' . $attributeCode.'_code';
                    $attribute = $this->attributeRepository->findOneWhere(['code' => $attributeCode]);
                }

                if (! $attribute) {
                    $label = substr($attributeData['title'], 0, -1);

                    $attributeLabels = [];
                    foreach (core()->getAllLocales() as $locale) {
                        $attributeLabels[$locale->code] = [
                                'name' => $label
                            ];
                    }
                    $attribute = $this->attributeRepository->create(array_merge($attributeLabels, [
                            'code' => $attributeCode,
                            'type' => 'select',
                            'swatch_type' => $attributeData['swatch_type'],
                            'admin_name' => substr($attributeData['title'], 0, -1),
                            'is_configurable' => 1,
                            'is_filterable' => 1,
                        ]));
                }

                $aliExpressAttribute = $this->create([
                    'ali_express_attribute_id' => $attributeData['attr_id'],
                    'attribute_id' => $attribute->id,
                ]);
            }
            else {
                if ($aliExpressAttribute) {
                    $attribute = $aliExpressAttribute->attribute;
                } else {
                    $attributeCode = $this->getAttributeCodeByTitle($attributeData['title']); //color
                    $attribute = $this->attributeRepository->findOneWhere(['code' => $attributeCode]);


                    if ($attribute && $attribute->swatch_type != $attributeData['swatch_type']) {
                        $attributeCode = 'ali_express_' . $attributeCode;

                        $attribute = $this->attributeRepository->findOneWhere(['code' => $attributeCode]);
                    }

                    if (! $attribute) {
                        $label = substr($attributeData['title'], 0, -1);

                        $attributeLabels = [];
                        foreach (core()->getAllLocales() as $locale) {
                            $attributeLabels[$locale->code] = [
                                    'name' => $label
                                ];
                        }
                        $attribute = $this->attributeRepository->create(array_merge($attributeLabels, [
                                'code' => $attributeCode,
                                'type' => 'select',
                                'swatch_type' => $attributeData['swatch_type'],
                                'admin_name' => substr($attributeData['title'], 0, -1),
                                'is_configurable' => 1,
                                'is_filterable' => 1,
                            ]));
                    }

                    $aliExpressAttribute = $this->create([
                            'ali_express_attribute_id' => $attributeData['attr_id'],
                            'attribute_id' => $attribute->id,
                        ]);
                }
            }

            if (! $attributeGroup->custom_attributes()->where('id', $attribute->id)->get()->count()) {
                $groupAttributeCount++;

                $attributeGroup->custom_attributes()->save($attribute, ['position' => $groupAttributeCount]);
            }

            $attributeOptionValue = $this->aliExpressAttributeOptionRepository->checkAttributeOptionsAvailabiliy($aliExpressAttribute, $attributeData['value']);

            $data[] = [
                'id' => $attribute->id,
                'title' => substr($attributeData['title'], 0, -1),
                'status' => 1
            ];
        }

        return $data;
    }

    /**
     * Returns attribute code by attribute title
     *
     * @param string $attributeTitle
     * @return mixed
     */
    public function getAttributeCodeByTitle($attributeTitle)
    {
        $attributeCode = substr($attributeTitle, 0, -1);

        $attributeCode = strtolower(preg_replace('/[^A-Za-z0-9\_]/', '', str_replace(' ', '_', $attributeCode)));

        return $attributeCode;
    }
}