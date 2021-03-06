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
     * Application loaded component at run time. @see BeginBehavior - calls load() method.
     * Defaults time zone to configuration set in common configuration 'timeZone' setting.
     */
    class ZurmoTimeZoneHelper extends CApplicationComponent
    {
        /**
         * Systemwide time zone.
         */
        protected $_timeZone;

        /**
         * This is set from the value in the application common config file. It is used as the final fall back
         * if no other configuration settings are found.
         */
        public function setTimeZone($value)
        {
            assert('is_string($value)');
            if (new DateTimeZone($value) === false)
            {
                throw new NotSupportedException();
            }
            $this->_timeZone = $value;
        }

        //USE FOR TESTING ONLY.
        public function getTimeZone()
        {
            return $this->_timeZone;
        }

       /**
         * Loads time zone for current user.  This is called by BeginBehavior.
         */
        public function load()
        {
            Yii::app()->setTimeZone($this->getForCurrentUser());
        }

        /**
         * Get the time zone value for the current user
         * @return $timeZone - string.
         */
        public function getForCurrentUser()
        {
            if ( Yii::app()->user->userModel != null && Yii::app()->user->userModel->timeZone != null)
            {
                return Yii::app()->user->userModel->timeZone;
            }
            else
            {
                return $this->getGlobalValue();
            }
        }

        /**
         * Get the global configuration value.
         * @return string - time zone.
         */
        public function getGlobalValue()
        {
            if (null != $timeZone = ZurmoConfigurationUtil::getByModuleName('ZurmoModule', 'timeZone'))
            {
                return $timeZone;
            }
            else
            {
                return $this->_timeZone;
            }
        }

        /**
         * Set the global time zone configuration value.
         */
        public static function setGlobalValue($timeZone)
        {
            assert('is_string($timeZone)');
            ZurmoConfigurationUtil::setByModuleName('ZurmoModule', 'timeZone', $timeZone);
        }

        /**
         * Given a utc time stamp, convert the time stamp to a timezone adjusted time stamp.
         * The time zone is based on the current user's time zone.
         */
        public function convertFromUtcTimeStampForCurrentUser($utcTimeStamp)
        {
            assert('is_int($utcTimeStamp)');
            $timeZone = $this->getForCurrentUser();
            return DateTimeUtil::convertFromUtcUnixStampByTimeZone($utcTimeStamp, $timeZone);
        }

        /**
         * Given a local time stamp, convert the time stamp to UTC based on a timezone adjusted time stamp.
         * The time zone is based on the current user's time zone.
         */
        public function convertFromLocalTimeStampForCurrentUser($utcTimeStamp)
        {
            assert('is_int($utcTimeStamp)');
            $timeZone = $this->getForCurrentUser();
            return DateTimeUtil::convertFromLocalUnixStampByTimeZoneToUtcUnixStamp($utcTimeStamp, $timeZone);
        }

        public function isCurrentUsersTimeZoneConfirmed()
        {
            $keyName = 'timeZoneConfirmed';
            if ( false != ZurmoConfigurationUtil::getForCurrentUserByModuleName('UsersModule', $keyName))
            {
                return true;
            }
            return false;
        }

        public function confirmCurrentUsersTimeZone()
        {
            $keyName = 'timeZoneConfirmed';
            ZurmoConfigurationUtil::setForCurrentUserByModuleName('UsersModule', $keyName, true);
        }

        public function isTimeZoneSetForCurrentUser()
        {
            if (Yii::app()->user->userModel->timeZone != null)
            {
                return true;
            }
            return false;
        }
    }
?>