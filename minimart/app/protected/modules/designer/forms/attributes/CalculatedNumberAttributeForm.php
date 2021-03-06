<?php
    /*********************************************************************************
     * Zurmo is a customer relationship management program developed by
     * Zurmo, Inc. Copyright (C) 2014 Zurmo Inc.
     *
     * Zurmo is free software; you can redistribute it and/or modify it under
     * the terms of the GNU Affero General Public License version 3 as published by the
     * Free Software Foundation with the addition of the following permission added
     * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
     * IN WHICH THE COPYRIGHT IS OWNED BY ZURMO, ZURMO DISCLAIMS THE WARRANTY
     * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
     *
     * Zurmo is distributed in the hope that it will be useful, but WITHOUT
     * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
     * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
     * details.
     *
     * You should have received a copy of the GNU Affero General Public License along with
     * this program; if not, see http://www.gnu.org/licenses or write to the Free
     * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
     * 02110-1301 USA.
     *
     * You can contact Zurmo, Inc. with a mailing address at 27 North Wacker Drive
     * Suite 370 Chicago, IL 60606. or at email address contact@zurmo.com.
     *
     * The interactive user interfaces in original and modified versions
     * of this program must display Appropriate Legal Notices, as required under
     * Section 5 of the GNU Affero General Public License version 3.
     *
     * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
     * these Appropriate Legal Notices must retain the display of the Zurmo
     * logo and Zurmo copyright notice. If the display of the logo is not reasonably
     * feasible for technical reasons, the Appropriate Legal Notices must display the words
     * "Copyright Zurmo Inc. 2014. All rights reserved".
     ********************************************************************************/

    /**
     * Form for working with calculated number derived attributes.
     */
    class CalculatedNumberAttributeForm extends AttributeForm
    {
        public $id;

        public $formula;

        protected $modelClassName;

        public function __construct(RedBeanModel $model = null, $attributeName = null)
        {
            assert('$attributeName === null || is_string($attributeName)');
            assert('$model === null || !$model->isAttribute($attributeName)');
            if ($model !== null)
            {
                if ($attributeName != null)
                {
                    $metadata              = CalculatedDerivedAttributeMetadata::
                                             getByNameAndModelClassName($attributeName, get_class($model));
                    $unserializedMetadata  = unserialize($metadata->serializedMetadata);
                    $this->id              = $metadata->id;
                    $this->attributeName   = $metadata->name;
                    $this->attributeLabels = $unserializedMetadata['attributeLabels'];
                    $this->formula         = $unserializedMetadata['formula'];
                }
                else
                {
                    $unserializedMetadata = array();
                }
                $this->modelClassName = get_class($model);
            }
        }

        public function rules()
        {
            return array_merge(parent::rules(), array(
                array('formula',        'required'),
                array('formula',        'validateFormula'),
            ));
        }

        public function attributeLabels()
        {
            return array_merge(parent::attributeLabels(), array(
                'formula' => Zurmo::t('DesignerModule', 'Formula'),
            ));
        }

        public static function getAttributeTypeDisplayName()
        {
            return Zurmo::t('DesignerModule', 'Calculated Number');
        }

        public static function getAttributeTypeDisplayDescription()
        {
            return Zurmo::t('DesignerModule', 'A calculated number based on other field values');
        }

        public function getAttributeTypeName()
        {
            return 'CalculatedNumber';
        }

        public function validateFormula($attribute, $params)
        {
            assert('$attribute == "formula"');
            assert('$this->modelClassName != null');
            if (!CalculatedNumberUtil::isFormulaValid($this->{$attribute}, $this->modelClassName))
            {
                $this->addError('formula', Zurmo::t('DesignerModule', 'The formula is invalid.'));
            }
        }

        /**
         * (non-PHPdoc)
         * @see AttributeForm::validateAttributeNameDoesNotExists()
         */
        public function validateAttributeNameDoesNotExists()
        {
            assert('$this->modelClassName != null');
            try
            {
                $models = CalculatedDerivedAttributeMetadata::
                          getByNameAndModelClassName($this->attributeName, $this->modelClassName);
                if (count($models) > 0)
                {
                    $this->addError('attributeName', Zurmo::t('DesignerModule', 'A field with this name is already used.'));
                }
            }
            catch (NotFoundException $e)
            {
            }
        }

        /**
         * @see AttributeForm::getModelAttributeAdapterNameForSavingAttributeFormData()
         */
        public static function getModelAttributeAdapterNameForSavingAttributeFormData()
        {
            return 'CalculatedNumberModelDerivedAttributesAdapter';
        }

        public function canUpdateAttributeProperty($propertyName)
        {
            if ($propertyName == 'attributeName' && $this->id != null)
            {
                return false;
            }
            return true;
        }
    }
?>
