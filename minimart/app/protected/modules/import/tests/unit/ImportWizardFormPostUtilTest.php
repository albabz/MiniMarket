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

    class ImportWizardFormPostUtilTest extends ZurmoBaseTest
    {
        public function testSanitizePostByTypeForSavingMappingData()
        {
            $language = Yii::app()->getLanguage();
            $this->assertEquals($language, 'en');
            $postData = array(
                'column_0' => array('attributeIndexOrDerivedType' => 'date',
                                    'mappingRulesData' => array(
                                        'DefaultValueModelAttributeMappingRuleForm' =>
                                            array('defaultValue' => '5/4/2011'))),
                'column_1' => array('attributeIndexOrDerivedType' => 'dateTime',
                                    'mappingRulesData' => array(
                                        'DefaultValueModelAttributeMappingRuleForm' =>
                                            array('defaultValue' => '5/4/2011 5:45 PM'))),
            );
            $sanitizedPostData = ImportWizardFormPostUtil::
                                 sanitizePostByTypeForSavingMappingData('ImportModelTestItem', $postData);
            $compareDateTime   = DateTimeUtil::
                                 convertDateTimeLocaleFormattedDisplayToDbFormattedDateTimeWithSecondsAsZero('5/4/2011 5:45 PM');
            $compareData = array(
                'column_0' => array('attributeIndexOrDerivedType' => 'date',
                                    'mappingRulesData' => array(
                                        'DefaultValueModelAttributeMappingRuleForm' =>
                                            array('defaultValue' => '2011-05-04'))),
                'column_1' => array('attributeIndexOrDerivedType' => 'dateTime',
                                    'mappingRulesData' => array(
                                        'DefaultValueModelAttributeMappingRuleForm' =>
                                            array('defaultValue' => $compareDateTime))),
            );
            $this->assertEquals($compareData, $sanitizedPostData);

            //now do German (de) to check a different locale.
            Yii::app()->setLanguage('de');
            $postData = array(
                'column_0' => array('attributeIndexOrDerivedType' => 'date',
                                    'mappingRulesData' => array(
                                        'DefaultValueModelAttributeMappingRuleForm' =>
                                            array('defaultValue' => '04.05.2011'))),
                'column_1' => array('attributeIndexOrDerivedType' => 'dateTime',
                                    'mappingRulesData' => array(
                                        'DefaultValueModelAttributeMappingRuleForm' =>
                                            array('defaultValue' => '04.05.2011 17:45'))),
            );
            $sanitizedPostData = ImportWizardFormPostUtil::
                                 sanitizePostByTypeForSavingMappingData('ImportModelTestItem', $postData);
            $compareDateTime   = DateTimeUtil::
                                 convertDateTimeLocaleFormattedDisplayToDbFormattedDateTimeWithSecondsAsZero('04.05.2011 17:45');
            $compareData = array(
                'column_0' => array('attributeIndexOrDerivedType' => 'date',
                                    'mappingRulesData' => array(
                                        'DefaultValueModelAttributeMappingRuleForm' =>
                                            array('defaultValue' => '2011-05-04'))),
                'column_1' => array('attributeIndexOrDerivedType' => 'dateTime',
                                    'mappingRulesData' => array(
                                        'DefaultValueModelAttributeMappingRuleForm' =>
                                            array('defaultValue' => $compareDateTime))),
            );
            $this->assertEquals($compareData, $sanitizedPostData);

            //reset language back to english
            Yii::app()->setLanguage('en');

            //test sanitizing a bad datetime
            $postData = array(
                'column_0' => array('attributeIndexOrDerivedType' => 'dateTime',
                                    'mappingRulesData' => array(
                                        'DefaultValueModelAttributeMappingRuleForm' =>
                                            array('defaultValue' => 'wang chung'))),
            );
            $sanitizedPostData = ImportWizardFormPostUtil::
                                 sanitizePostByTypeForSavingMappingData('ImportModelTestItem', $postData);
            $this->assertNull($sanitizedPostData['column_0']['mappingRulesData']
                                                ['DefaultValueModelAttributeMappingRuleForm']['defaultValue']);
            //sanitize an empty datetime
            $postData = array(
                'column_0' => array('attributeIndexOrDerivedType' => 'dateTime',
                                    'mappingRulesData' => array(
                                        'DefaultValueModelAttributeMappingRuleForm' =>
                                            array('defaultValue' => ''))),
            );
            $sanitizedPostData = ImportWizardFormPostUtil::
                                 sanitizePostByTypeForSavingMappingData('ImportModelTestItem', $postData);
            $this->assertEmpty($sanitizedPostData['column_0']['mappingRulesData']
                                                ['DefaultValueModelAttributeMappingRuleForm']['defaultValue']);
        }
    }
?>
