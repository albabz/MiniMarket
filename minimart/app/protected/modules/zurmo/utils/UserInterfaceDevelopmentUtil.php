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
     * Helper class for testing various aspects of the user interface. Creates demo data that aides in development
     * testing.
     */
    class UserInterfaceDevelopmentUtil
    {
        public static function makePaginationData()
        {
            $account        = new Account();
            $account->owner = Yii::app()->user->userModel;
            $account->name  = 'Full Load Account';
            $saved          = $account->save();
            if (!$saved)
            {
                throw new NotSupportedException();
            }
            //Load past meetings that will show up as latest activities
            for ($i = 0; $i < 15; $i++)
            {
                $meeting = new Meeting();
                $meeting->name             = 'MyMeeting ' . $i;
                $meeting->owner            = Yii::app()->user->userModel;
                $startStamp                = DateTimeUtil::convertTimestampToDbFormatDateTime(time()  - 10000 - ($i * 3600 * 24));
                $meeting->startDateTime    = $startStamp;
                $meeting->activityItems->add($account);
                $saved = $meeting->save();
                if (!$saved)
                {
                    throw new NotSupportedException();
                }
            }
            //Load upcoming tasks
            for ($i = 0; $i < 15; $i++)
            {
                $task = new Task();
                $task->name             = 'MyTask ' . $i;
                $task->completed        = false;
                $task->owner            = Yii::app()->user->userModel;
                $dueDateStamp           = DateTimeUtil::convertTimestampToDbFormatDateTime(time()  + 10000 + ($i * 3600 * 24));
                $task->dueDateTime    = $dueDateStamp;
                $task->activityItems->add($account);
                $saved = $task->save();
                if (!$saved)
                {
                    throw new NotSupportedException();
                }
            }
            //Load 20 so there is sufficient data for list view pagination testing
            for ($i = 0; $i < 20; $i++)
            {
                $account        = new Account();
                $account->owner = Yii::app()->user->userModel;
                $account->name  = 'List View Pagination Test Account ' . $i;
                $saved          = $account->save();
                if (!$saved)
                {
                    throw new NotSupportedException();
                }
            }
        }

        public static function makeMassDeleteData()
        {
            $account        = new Account();
            $account->owner = Yii::app()->user->userModel;
            $account->name  = 'Mass Delete Full Load Account';
            $saved          = $account->save();
            if (!$saved)
            {
                throw new NotSupportedException();
            }
            //Load past meetings that will show up as latest activities
            for ($i = 0; $i < 15; $i++)
            {
                $meeting = new Meeting();
                $meeting->name             = 'MyMeeting ' . $i;
                $meeting->owner            = Yii::app()->user->userModel;
                $startStamp                = DateTimeUtil::convertTimestampToDbFormatDateTime(time()  - 10000 - ($i * 3600 * 24));
                $meeting->startDateTime    = $startStamp;
                $meeting->activityItems->add($account);
                $saved = $meeting->save();
                if (!$saved)
                {
                    throw new NotSupportedException();
                }
            }
            //Load upcoming tasks
            for ($i = 0; $i < 15; $i++)
            {
                $task = new Task();
                $task->name             = 'MyTask ' . $i;
                $task->completed        = false;
                $task->owner            = Yii::app()->user->userModel;
                $dueDateStamp           = DateTimeUtil::convertTimestampToDbFormatDateTime(time()  + 10000 + ($i * 3600 * 24));
                $task->dueDateTime    = $dueDateStamp;
                $task->activityItems->add($account);
                $saved = $task->save();
                if (!$saved)
                {
                    throw new NotSupportedException();
                }
            }
            //Load 20 so there is sufficient data for list view pagination testing
            for ($i = 0; $i < 20; $i++)
            {
                $account        = new Account();
                $account->owner = Yii::app()->user->userModel;
                $account->name  = 'Mass Delete Test Account ' . $i;
                $saved          = $account->save();
                if (!$saved)
                {
                    throw new NotSupportedException();
                }
            }
        }
    }
?>