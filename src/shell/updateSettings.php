<?php

require_once 'abstract.php';

/**
 * @category   AvS
 * @package    AvS_FastSimpleImport
 * @author     Team Magento <team-magento@aoe.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software Licence 3.0 (OSL-3.0)
 * @link       https://github.com/avstudnitz/AvS_FastSimpleImport
 */
class AvS_FastSimpleImport_Shell_UpdateSettings extends Mage_Shell_Abstract
{
    /**
     * Config path to select attributes
     *
     * @var string
     */
    const CONFIG_PATH_CREATE_ATTRIBUTE_OPTIONS = 'fastsimpleimport/product/select_attributes';

    /**
     * Run script
     *
     * @return void
     */
    public function run()
    {
        $entityTypeId = (int) Mage::getModel('eav/entity')->setType(Mage_Catalog_Model_Product::ENTITY)->getTypeId();
        $attributesCollection = Mage::getResourceModel('eav/entity_attribute_collection')
            ->setEntityTypeFilter($entityTypeId)
            ->addFieldToFilter(
                'frontend_input',
                [
                    ['eq' => 'select'],
                    ['eq' => 'multiselect']
                ]
            )
            ->addFieldToFilter('is_maintained_by_configurator', 1)
            ->addSetInfo();

        $attributes = [];
        foreach ($attributesCollection as $attribute) {
            $attributes[] = $attribute->getAttributeCode();
        }

        if (!empty($attributes)) {
            $configModel = Mage::getConfig();
            $configModel->saveConfig(
                self::CONFIG_PATH_CREATE_ATTRIBUTE_OPTIONS,
                implode(',', $attributes)
            );
        }
    }
}

$shell = new AvS_FastSimpleImport_Shell_UpdateSettings();
$shell->run();
