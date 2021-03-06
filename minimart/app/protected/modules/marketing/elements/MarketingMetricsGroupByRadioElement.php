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
     * Element for deciding the grouping day, week, or month for a marketing metrics portlet
     */
    class MarketingMetricsGroupByRadioElement extends Element
    {
        /**
         * Renders the setting as a links. Also adds hidden variable to form to ensure form can get serialized
         * during an ajax call.
         * @return A string containing the element's content.
         */
        protected function renderControlEditable()
        {
            assert('$this->model instanceof MarketingOverallMetricsForm');
            $hiddenInputName     = 'marketingMetricsGroupByNotUsedName';
            $hiddenInput         = ZurmoHtml::hiddenField($hiddenInputName);
            $content = '';
            foreach ($this->getValuesAndLabels() as $value => $label)
            {
                $class    = $this->form->getId() . 'marketingMetricsGroupByLink';
                if ($this->model->{$this->attribute} == $value)
                {
                    $class .= ' active';
                }
                $content .= ZurmoHtml::link($label, '#', array('data-value' => $value, 'class' => $class));
            }
            $content = ZurmoHtml::tag('div', array('class' => 'pills'), $content);
            $content = $hiddenInput . $content;
            return $content;
        }

        /**
         * @throws NotImplementedException
         */
        protected function renderControlNonEditable()
        {
            throw new NotImplementedException();
        }

        /**
         * Override to ensure label is pointing to the right input id
         * @return A string containing the element's label
         * @throws NotImplementedException
         */
        protected function renderLabel()
        {
            if ($this->form === null)
            {
                throw new NotImplementedException();
            }
            $for = ZurmoHtml::ID_PREFIX . $this->getEditableInputId();
            return $this->form->labelEx($this->model, $this->attribute, array('for' => $for));
        }

        /**
         * @return array
         */
        public function getEditableHtmlOptions()
        {
            $htmlOptions = array(
                'name'      => $this->getEditableInputName(),
                'id'        => $this->getEditableInputId(),
                'separator' => '',
                'template'  => '{input}{label}',
            );
            return $htmlOptions;
        }

        /**
         * @return array
         */
        protected function getValuesAndLabels()
        {
            return array(MarketingOverallMetricsForm::GROUPING_TYPE_DAY   => Zurmo::t('Core', 'Day'),
                         MarketingOverallMetricsForm::GROUPING_TYPE_WEEK  => Zurmo::t('Core', 'Week'),
                         MarketingOverallMetricsForm::GROUPING_TYPE_MONTH => Zurmo::t('Core', 'Month'));
        }
    }
?>