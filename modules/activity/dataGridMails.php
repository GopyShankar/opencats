<?php 
/*
 * CATS
 * Contacts Datagrid
 *
 * CATS Version: 0.9.4 Countach
 *
 * Copyright (C) 2005 - 2007 Cognizo Technologies, Inc.
 *
 *
 * The contents of this file are subject to the CATS Public License
 * Version 1.1a (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.catsone.com/. Software distributed under the License is
 * distributed on an "AS IS" basis, WITHOUT WARRANTY OF ANY KIND, either
 * express or implied. See the License for the specific language governing
 * rights and limitations under the License.
 *
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 *
 * The Original Code is "CATS Standard Edition".
 *
 * The Initial Developer of the Original Code is Cognizo Technologies, Inc.
 * Portions created by the Initial Developer are Copyright (C) 2005 - 2007
 * (or from the year in which this file was created to the year 2007) by
 * Cognizo Technologies, Inc. All Rights Reserved.
 *
 * $Id: dataGrids.php 3566 2007-11-12 09:46:35Z will $
 */
 
include_once('./lib/ActivityEntries.php');
include_once('./lib/Hooks.php');
include_once('./lib/InfoString.php');
include_once('./lib/Width.php');

class ActivityDataGridMail extends DataGrid
{
    protected $_siteID;


    // FIXME: Fix ugly indenting - ~400 character lines = bad.
    public function __construct($siteID, $parameters)
    {   
        /* Pager configuration. */
        $this->_tableWidth = new Width(100, '%');
        $this->_defaultAlphabeticalSortBy = 'lastName';
        $this->ajaxMode = false;
        $this->showExportCheckboxes = true; //BOXES WILL NOT APPEAR UNLESS SQL ROW exportID IS RETURNED!
        $this->showActionArea = true;
        $this->showChooseColumnsBox = true;
        $this->allowResizing = true;
        $this->dateCriterion = '';
        
        if (isset($parameters['period']) && !empty($parameters['period']))
        {
            $this->dateCriterion .= ' AND activity_mail.date_created >= ' . $parameters['period'] . ' ';
        }
        else
        {
            if (isset($parameters['startDate']) && !empty($parameters['startDate']))
            {
                $this->dateCriterion .= ' AND activity_mail.date_created >= \'' .$parameters['startDate'].'\' ';
            }
            
            if (isset($parameters['endDate']) && !empty($parameters['endDate']))
            {
                $this->dateCriterion .= ' AND activity_mail.date_created <= \''.$parameters['endDate'].'\' ';
            }
        }

        $this->defaultSortBy = 'dateCreatedSort';
        $this->defaultSortDirection = 'DESC';
        $this->_defaultColumns = array();
        $this->_defaultColumns = array( 
            array('name' => 'Date', 'width' => 110),
            array('name' => 'Mail Address', 'width' => 125),
            array('name' => 'Activity', 'width' => 65),
            array('name' => 'Notes', 'width' => 240),
            array('name' => 'Sent By', 'width' => 60),
        );


        $this->_db = DatabaseConnection::getInstance();
        $this->_siteID = $siteID;
        $this->_assignedCriterion = "";
        $this->_dataItemIDColumn = 'company.company_id';

        $this->_classColumns = array( 
            'Date' =>           array('pagerRender'    => 'return $rsData[\'dateCreated\'];', 
                                      'sortableColumn' => 'dateCreatedSort',
                                      'pagerWidth'     => 110,
                                      'pagerOptional'  => true,
                                      'alphaNavigation'=> true,
                                      'filter' => 'activity_mail.date_created'),
                                                             
             'Mail Address' =>      array('pagerRender'    => '$ret = $rsData[\'mail_address\']; return $ret;', 
                                     'sortableColumn'  => 'mail_address',
                                     'pagerWidth'      => 65,
                                     'pagerOptional'   => true,
                                     'alphaNavigation' => true,
                                     'filter'          => 'activity_mail.mail_address'),        

             'Activity' =>      array('pagerRender'    => '$ret = $rsData[\'typeDescription\']; return $ret;', 
                                     'sortableColumn'  => 'typeDescription',
                                     'pagerWidth'      => 65,
                                     'pagerOptional'   => true,
                                     'alphaNavigation' => true,
                                     'filter'          => 'activity_type.short_description'),  
                                     
             'Notes' =>      array('pagerRender'    => 'return $rsData[\'notes\'];', 
                                     'sortableColumn'  => 'notes',
                                     'pagerWidth'      => 240,
                                     'pagerOptional'   => true,
                                     'alphaNavigation' => true,
                                     'filter'    => 'activity_mail.notes'),

            'Sent By' =>         array(
                                     'pagerRender'      => 'return StringUtility::makeInitialName($rsData[\'enteredByFirstName\'], $rsData[\'enteredByLastName\'], false, LAST_NAME_MAXLEN);',
                                     'exportRender'     => 'return $rsData[\'enteredByFirstName\'] . " " .$rsData[\'enteredByLastName\'];',
                                     'sortableColumn'     => 'enteredBySort',
                                     'pagerWidth'    => 60,
                                     'alphaNavigation' => true,
                                     'filter'         => 'CONCAT(entered_by_user.last_name, entered_by_user.first_name)'), 
        );
        
        parent::__construct("activity:ActivityDataGrid", $parameters);
    }
        
    /**
     * Returns the sql statment for the pager.
     *
     * @return array clients data
     */
    public function getSQL($selectSQL, $joinSQL, $whereSQL, $havingSQL, $orderSQL, $limitSQL, $distinct = '')
    {   
        $sql = sprintf(
            "SELECT SQL_CALC_FOUND_ROWS %s
                activity_mail.activity_mail_id AS activityMailID,
                activity_mail.site_id AS siteID,
                activity_mail.mail_address AS mail_address,
                activity_mail.notes AS notes,
                activity_type.short_description AS typeDescription,
                DATE_FORMAT(
                    activity_mail.date_created, '%%m-%%d-%%y (%%h:%%i %%p)'
                ) AS dateCreated,
                activity_mail.date_created AS dateCreatedSort,
                entered_by_user.first_name AS enteredByFirstName,
                entered_by_user.last_name AS enteredByLastName,
                CONCAT(entered_by_user.last_name, entered_by_user.first_name) AS enteredBySort
            FROM
                activity_mail
            LEFT JOIN user AS entered_by_user
                ON activity_mail.entered_by = entered_by_user.user_id
            LEFT JOIN activity_type
                ON activity_mail.type = activity_type.activity_type_id
            WHERE
                activity_mail.site_id = %s
                %s
                %s
            UNION
            SELECT %s
                activity_mail.activity_mail_id AS activityMailID,
                activity_mail.site_id AS siteID,
                activity_mail.mail_address AS mail_address,
                activity_mail.notes AS notes,
                activity_type.short_description AS typeDescription,
                DATE_FORMAT(
                    activity_mail.date_created, '%%m-%%d-%%y (%%h:%%i %%p)'
                ) AS dateCreated,
                activity_mail.date_created AS dateCreatedSort,
                entered_by_user.first_name AS enteredByFirstName,
                entered_by_user.last_name AS enteredByLastName,
                CONCAT(entered_by_user.last_name, entered_by_user.first_name) AS enteredBySort
            FROM
                activity_mail
            LEFT JOIN user AS entered_by_user
                ON activity_mail.entered_by = entered_by_user.user_id
            LEFT JOIN activity_type
                ON activity_mail.type = activity_type.activity_type_id
            WHERE
                
                activity_mail.site_id = %s
                %s
                %s
            %s
            %s
            %s",
            $distinct,
            $this->_siteID,
            $this->dateCriterion,
            (strlen($whereSQL) > 0) ? ' AND ' . $whereSQL : '',
            $distinct,
            $this->_siteID,
            $this->dateCriterion,
            (strlen($whereSQL) > 0) ? ' AND ' . $whereSQL : '',
            (strlen($havingSQL) > 0) ? ' HAVING ' . $havingSQL : '',
            $orderSQL,
            $limitSQL
        );

        return $sql;
    }
}

?>