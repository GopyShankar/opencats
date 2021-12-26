<?php
/*
 * CATS
 * Home Module
 *
 * Copyright (C) 2005 - 2007 Cognizo Technologies, Inc.
 *
 *
 * The contents of this file are subject to the CATS Public License
 * Version 1.1a (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.catsone.com/.
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
 *
 * $Id: HomeUI.php 3810 2007-12-05 19:13:25Z brian $
 */

include_once('./lib/NewVersionCheck.php');
include_once('./lib/CommonErrors.php');
include_once('./lib/Dashboard.php');
include_once('./lib/DateUtility.php');

class HomeUI extends UserInterface
{
    public function __construct()
    {
        parent::__construct();

        $this->_authenticationRequired = true;
        $this->_moduleDirectory = 'home';
        $this->_moduleName = 'home';
        $this->_moduleTabText = 'Dashboard';
        $this->_subTabs = array();
    }


    public function handleRequest()
    {
        $action = $this->getAction();
        
        //@todo get role_slug from session 
        //super_admin,account_manager,internal_employee,candidate,admin,team_lead,individual_contributor,hr
        $role_slug=$_SESSION['CATS']->getUserrole();
        if (!eval(Hooks::get('HOME_HANDLE_REQUEST'))) return;

        switch ($action)
        {
            case 'quickSearch':
                include_once('./lib/Search.php');
                include_once('./lib/StringUtility.php');

                $this->quickSearch();
                break;

            case 'deleteSavedSearch':
                include_once('./lib/Search.php');

                $this->deleteSavedSearch();
                break;

            case 'addSavedSearch':
                include_once('./lib/Search.php');

                $this->addSavedSearch();
                break;

            /* FIXME: undefined function getAttachment()
            case 'getAttachment':
                include_once('./lib/Attachments.php');

                $this->getAttachment();
                break;
            */    

            case 'viewByDate':
                if ($this->isGetBack())
                {
                    
                    $periodString = $this->getTrimmedInput('period', $_GET);
                    
                    switch ($role_slug)
                    {
                        case 'account_manager':
                            if(!empty($periodString)){
                                $this->salesDashboard();
                            }else{
                                /* formats start and end date for searching */
                                $dataUtility = new DateUtility($this->_siteID);
                                $startDate = $dataUtility->formatSearchDate(
                                    $_GET['startMonth'], $_GET['startDay'], $_GET['startYear']
                                );
                                $this->salesDashboardOne($startDate);
                            }
                        break;    
                    }
                }

                break; 

            case 'home':
            default:
                switch ($role_slug)
                {
                    // case 'super_admin':
                    //     $this->superAdminDashboard();
                    //     break;
                    // case 'admin':
                    //     $this->adminDashboard();
                    //     break;
                    // case 'hr':
                    //     $this->hrDashboard();
                    //     break;
                    // case 'team_lead':
                    //     $this->teamLeadDashboard();
                    //     break;
                    case 'internal_employee':
                        $this->recruiterDashboard();
                        break;
                    case 'individual_contributor':
                        $this->salesDashboardOne();
                        break;
                    // case 'candidate':
                    //     $this->candidateDashboard();
                    //     break;
                    case 'account_manager':
                        $todayDate = date('Y-m-d');
                        $this->salesDashboardOne($todayDate);
                        break;
                    default:
                        $this->home();
                        break;
                }
                break;
        }
    }

    private function home()
    {
        if (!eval(Hooks::get('HOME'))) return;
        
        NewVersionCheck::getNews();
        
        $dashboard = new Dashboard($this->_siteID);
        $placedRS = $dashboard->getPlacements();
        
        $calendar = new Calendar($this->_siteID);
        $upcomingEventsHTML = $calendar->getUpcomingEventsHTML(7, UPCOMING_FOR_DASHBOARD);
        
        $calendar = new Calendar($this->_siteID);
        $upcomingEventsFupHTML = $calendar->getUpcomingEventsHTML(7, UPCOMING_FOR_DASHBOARD_FUP);
        
        /* Important cand datagrid */
        
        $dataGridProperties = array(
            'rangeStart'    => 0,
            'maxResults'    => 15,
            'filterVisible' => false
        );
        
        $dataGrid = DataGrid::get("home:ImportantPipelineDashboard", $dataGridProperties);
        
        $this->_template->assign('dataGrid', $dataGrid);
        
        $dataGridProperties = array(
            'rangeStart'    => 0,
            'maxResults'    => 15,
            'filterVisible' => false
        );
        
        /* Only show a month of activities. */
        $dataGridProperties['startDate'] = '';
        $dataGridProperties['endDate'] = '';
        $dataGridProperties['period'] = 'DATE_SUB(CURDATE(), INTERVAL 1 MONTH)';
        
        $dataGrid2 = DataGrid::get("home:CallsDataGrid", $dataGridProperties);
        
        $this->_template->assign('dataGrid2', $dataGrid2);
        
        $this->_template->assign('active', $this);
        $this->_template->assign('placedRS', $placedRS);
        $this->_template->assign('upcomingEventsHTML', $upcomingEventsHTML);
        $this->_template->assign('upcomingEventsFupHTML', $upcomingEventsFupHTML);
        $this->_template->assign('wildCardQuickSearch', '');
        $this->_template->display('./modules/home/Home.tpl');
    }
    private function recruiterDashboard()
    {        
         if (!eval(Hooks::get('HOME'))) return;
        
        NewVersionCheck::getNews();
        
        $dashboard = new Dashboard($this->_siteID);
        $placedRS = $dashboard->getPlacements();
        
        $this->_template->assign('active', $this);
        $this->_template->assign('placedRS', $placedRS);
       
        $this->_template->display('./modules/home/RecruiterDashboard.tpl');
    }
    
    private function salesDashboardOne($dateVal)
    {
        
        if (!eval(Hooks::get('HOME'))) return;
        
        NewVersionCheck::getNews();
        
        $dashboard = new Dashboard($this->_siteID);
        $placedRS = $dashboard->getPlacements();


        $val1 = $dashboard->getCandidatesSelection($dateVal);
        $val2 = $dashboard->getTotalProfileSent($dateVal,555);
        $val3 = $dashboard->getShortListData($dateVal);

        $chart1 = [];
        if(!empty($val1)){
            array_push($chart1,$val1[0]['counts']);
        }else{
            array_push($chart1,0);
        }

        if(!empty($val2)){
            array_push($chart1,$val2[0]['dataCount']);
        }else{
            array_push($chart1,0);
        }

        if(!empty($val3)){
            array_push($chart1,$val3[0]['dataCount']);
        }else{
            array_push($chart1,0);
        }

        if(!(int)implode($chart1)){
            $chart1 = [];
        }
    

        $chart2Val = $dashboard->getStatusCount($dateVal,'chart2');
        $chart3Val = $dashboard->getStatusCount($dateVal,'chart3');
        $chart4Val = $dashboard->getStatusCount($dateVal,'chart4');

        $statusLt = [525,550,560,570,580,590];
        $progressLt = [555,975,675,940,945,955];
        $responseLt = [910,920,930,980,990];

        if(!empty($chart2Val)){

            foreach ($statusLt as  $value) {
                $newval= array_values(array_filter($chart2Val,function($v,$k) use($value){return $v['STATUS'] == $value;},ARRAY_FILTER_USE_BOTH));
            
                if(!empty($newval)){
                    $chart2ValList[] = $newval[0]['PERCENTAGE'];
                }else{
                    $chart2ValList[] = 0;
                }
            }
    
        }else{
            $chart2ValList = [0,0,0,0,0];
        }

        if(!(int)implode($chart2ValList)){
            $chart2 = [];
        }else{
            $chart2 = $chart2ValList;
        }
        

        
        if(!empty($chart3Val)){

            foreach ($progressLt as  $value) {
                $newval= array_values(array_filter($chart3Val,function($v,$k) use($value){return $v['STATUS'] == $value;},ARRAY_FILTER_USE_BOTH));
            
                if(!empty($newval)){
                    $chart3ValList[] = $newval[0]['PERCENTAGE'];
                }else{
                    $chart3ValList[] = 0;
                }
            }   
        }else{
            $chart3ValList = [0,0,0,0,0];
        }

        $chart3 = $chart3ValList;

        if(!empty($chart4Val)){

            foreach ($responseLt as  $value) {
                $newval= array_values(array_filter($chart4Val,function($v,$k) use($value){return $v['STATUS'] == $value;},ARRAY_FILTER_USE_BOTH));
            
                if(!empty($newval)){
                    $chart4ValList[] = $newval[0]['PERCENTAGE'];
                }else{
                    $chart4ValList[] = 0;
                }
            }   
        }else{
            $chart4ValList = [0,0,0,0,0];
        }
        
        

        if(!(int)implode($chart4ValList)){
            $chart4 = [];
        }else{
            $chart4 = $chart4ValList;
        }


        $quickLinks = $this->getQuickLinks();

        $this->_template->assign('quickLinks', $quickLinks);
        $this->_template->assign('active', $this);
        $this->_template->assign('placedRS', $placedRS);

        $this->_template->assign('chart1', $chart1);
        $this->_template->assign('chart2', $chart2);
        $this->_template->assign('chart3', $chart3);
        $this->_template->assign('chart4', $chart4);
       
        $this->_template->display('./modules/home/SalesDashboardOne.tpl');
    }

    private function salesDashboard()
    {
        if (!eval(Hooks::get('HOME'))) return;
        
        NewVersionCheck::getNews();

        $periodString = $this->getTrimmedInput('period', $_GET);

        
        $dashboard = new Dashboard($this->_siteID);
        $placedRS = $dashboard->getPlacements();

        $val1 = $dashboard->getCandidatesSelectionBulk($periodString);
        $val2 = $dashboard->getTotalProfileSentBulk($periodString,555);
        $val3 = $dashboard->getShortListDataBulk($periodString);

        $mergeVal = array_merge($val1,$val2,$val3);

        $keys = array('date_list'=>0,'counts'=>0,'dataCount'=>0,'statusCount'=>0);
        // $keys = array();
        // foreach(new RecursiveIteratorIterator(new RecursiveArrayIterator($mergeVal)) as $key => $val) $keys[$key] = 0;
        $data = array();
        foreach($mergeVal as $values) {
            $data[] = array_merge($keys, $values);
        }

        foreach ($data as $key => $value) {
            $currDate = $value['date_list'];
            $val1= array_values(array_filter($data,function($v,$k) use($currDate){return $v['date_list'] == $currDate;},ARRAY_FILTER_USE_BOTH));
            if(!empty($val1)){
                foreach($val1 as $setVal){
                    
                    if(!empty($setVal['count'])){
                        $data[$key]['count'] = $setVal['count']; 
                    }

                    if(!empty($setVal['dataCount'])){
                        $data[$key]['dataCount'] = $setVal['dataCount'];
                    }

                    if(!empty($setVal['statusCount'])){
                        $data[$key]['statusCount'] = $setVal['statusCount'];
                    }
                }
            }
            
        }

        foreach ($data as $key => $value) {
            $currDate = $value['date_list'];
            $val1= array_values(array_filter($data,function($v,$k) use($currDate){return $v['date_list'] == $currDate;},ARRAY_FILTER_USE_BOTH));
            
            if(count($val1)>1){
                unset($data[$key]);
            }
            
        }

        arsort($data);
        $chart1 = array_values($data);
        
        $monthListName = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec'];
        
        if(!empty($chart1)){
            foreach ($chart1 as $key => $value) {
                $currDate = $value['date_list'];
                $val1= array_values(array_filter($monthListName,function($v,$k) use($currDate){return $k == $currDate;},ARRAY_FILTER_USE_BOTH));
                if(!empty($val1)){
                    $chart1[$key]['date_list'] = $val1[0];
                }
            }
        }

        if(empty($chart1)){
            $chart1 = [];
        }

        $val525 = $dashboard->getStatusCountBulk($periodString,525);
        $val550 = $dashboard->getStatusCountBulk($periodString,550);
        $val560 = $dashboard->getStatusCountBulk($periodString,560);
        $val570 = $dashboard->getStatusCountBulk($periodString,570);
        $val580 = $dashboard->getStatusCountBulk($periodString,580);
        $val590 = $dashboard->getStatusCountBulk($periodString,590);


        if(!empty($val525)){
            foreach ($val525 as $key => $value) {
                $val525[$key]['val525'] = $value['PERCENTAGE'];
                unset($val525[$key]['PERCENTAGE']);
            }
        }

        if(!empty($val550)){
            foreach ($val550 as $key => $value) {
                $val550[$key]['val550'] = $value['PERCENTAGE'];
                unset($val550[$key]['PERCENTAGE']);
            }
        }

        if(!empty($val560)){
            foreach ($val560 as $key => $value) {
                $val560[$key]['val560'] = $value['PERCENTAGE'];
                unset($val560[$key]['PERCENTAGE']);
            }
        }

        if(!empty($val570)){
            foreach ($val570 as $key => $value) {
                $val570[$key]['val570'] = $value['PERCENTAGE'];
                unset($val570[$key]['PERCENTAGE']);
            }
        }

        if(!empty($val580)){
            foreach ($val580 as $key => $value) {
                $val580[$key]['val580'] = $value['PERCENTAGE'];
                unset($val580[$key]['PERCENTAGE']);
            }
        }

        if(!empty($val590)){
            foreach ($val590 as $key => $value) {
                $val590[$key]['val590'] = $value['PERCENTAGE'];
                unset($val590[$key]['PERCENTAGE']);
            }
        }


        $mergeVal1 = array_merge($val525,$val550,$val560,$val570,$val580,$val590);


        $keys = array('date_list'=>0,'val525'=>0,'val550'=>0,'val560'=>0,'val570'=>0,'val580'=>0,'val590'=>0);
        $data = array();
        foreach($mergeVal1 as $values) {
            $data[] = array_merge($keys, $values);
        }

        foreach ($data as $key => $value) {
            $currDate = $value['date_list'];
            $val1= array_values(array_filter($data,function($v,$k) use($currDate){return $v['date_list'] == $currDate;},ARRAY_FILTER_USE_BOTH));
            if(!empty($val1)){
                foreach($val1 as $setVal){
                    
                    if(!empty($setVal['val525'])){
                        $data[$key]['val525'] = $setVal['val525']; 
                    }

                    if(!empty($setVal['val550'])){
                        $data[$key]['val550'] = $setVal['val550'];
                    }

                    if(!empty($setVal['val560'])){
                        $data[$key]['val560'] = $setVal['val560'];
                    }

                    if(!empty($setVal['val570'])){
                        $data[$key]['val570'] = $setVal['val570'];
                    }

                    if(!empty($setVal['val580'])){
                        $data[$key]['val580'] = $setVal['val580'];
                    }

                    if(!empty($setVal['val590'])){
                        $data[$key]['val590'] = $setVal['val590'];
                    }
                }
            }
            
        }

        foreach ($data as $key => $value) {
            $currDate = $value['date_list'];
            $val1= array_values(array_filter($data,function($v,$k) use($currDate){return $v['date_list'] == $currDate;},ARRAY_FILTER_USE_BOTH));
            
            if(count($val1)>1){
                unset($data[$key]);
            }
            
        }

        arsort($data);
        $chart2 = array_values($data);

        if(!empty($chart2)){
            foreach ($chart2 as $key => $value) {
                $currDate = $value['date_list'];
                $val1= array_values(array_filter($monthListName,function($v,$k) use($currDate){return $k == $currDate;},ARRAY_FILTER_USE_BOTH));
                if(!empty($val1)){
                    $chart2[$key]['date_list'] = $val1[0];
                }
            }
        }

        if(empty($chart2)){
            $chart2 = [];
        }


        $val555 = $dashboard->getStatusCountBulk($periodString,555);
        $val675 = $dashboard->getStatusCountBulk($periodString,675);
        $val940 = $dashboard->getStatusCountBulk($periodString,940);
        $val945 = $dashboard->getStatusCountBulk($periodString,945);
        $val955 = $dashboard->getStatusCountBulk($periodString,955);
        $val975 = $dashboard->getStatusCountBulk($periodString,975);


        if(!empty($val555)){
            foreach ($val555 as $key => $value) {
                $val555[$key]['val555'] = $value['PERCENTAGE'];
                unset($val555[$key]['PERCENTAGE']);
            }
        }

        if(!empty($val675)){
            foreach ($val675 as $key => $value) {
                $val675[$key]['val675'] = $value['PERCENTAGE'];
                unset($val675[$key]['PERCENTAGE']);
            }
        }

        if(!empty($val940)){
            foreach ($val940 as $key => $value) {
                $val940[$key]['val940'] = $value['PERCENTAGE'];
                unset($val940[$key]['PERCENTAGE']);
            }
        }

        if(!empty($val945)){
            foreach ($val945 as $key => $value) {
                $val945[$key]['val945'] = $value['PERCENTAGE'];
                unset($val945[$key]['PERCENTAGE']);
            }
        }

        if(!empty($val955)){
            foreach ($val955 as $key => $value) {
                $val955[$key]['val955'] = $value['PERCENTAGE'];
                unset($val955[$key]['PERCENTAGE']);
            }
        }

        if(!empty($val975)){
            foreach ($val975 as $key => $value) {
                $val975[$key]['val975'] = $value['PERCENTAGE'];
                unset($val975[$key]['PERCENTAGE']);
            }
        }


        $mergeVal1 = array_merge($val555,$val675,$val940,$val945,$val955,$val975);


        $keys = array('date_list'=>0,'val555'=>0,'val675'=>0,'val940'=>0,'val945'=>0,'val955'=>0,'val975'=>0);
        $data = array();
        foreach($mergeVal1 as $values) {
            $data[] = array_merge($keys, $values);
        }

        foreach ($data as $key => $value) {
            $currDate = $value['date_list'];
            $val1= array_values(array_filter($data,function($v,$k) use($currDate){return $v['date_list'] == $currDate;},ARRAY_FILTER_USE_BOTH));
            if(!empty($val1)){
                foreach($val1 as $setVal){
                    
                    if(!empty($setVal['val555'])){
                        $data[$key]['val555'] = $setVal['val555']; 
                    }

                    if(!empty($setVal['val675'])){
                        $data[$key]['val675'] = $setVal['val675'];
                    }

                    if(!empty($setVal['val940'])){
                        $data[$key]['val940'] = $setVal['val940'];
                    }

                    if(!empty($setVal['val945'])){
                        $data[$key]['val945'] = $setVal['val945'];
                    }

                    if(!empty($setVal['val955'])){
                        $data[$key]['val955'] = $setVal['val955'];
                    }

                    if(!empty($setVal['val975'])){
                        $data[$key]['val975'] = $setVal['val975'];
                    }
                }
            }
            
        }

        foreach ($data as $key => $value) {
            $currDate = $value['date_list'];
            $val1= array_values(array_filter($data,function($v,$k) use($currDate){return $v['date_list'] == $currDate;},ARRAY_FILTER_USE_BOTH));
            
            if(count($val1)>1){
                unset($data[$key]);
            }
            
        }

        arsort($data);
        $chart3 = array_values($data);

        if(!empty($chart3)){
            foreach ($chart3 as $key => $value) {
                $currDate = $value['date_list'];
                $val1= array_values(array_filter($monthListName,function($v,$k) use($currDate){return $k == $currDate;},ARRAY_FILTER_USE_BOTH));
                if(!empty($val1)){
                    $chart3[$key]['date_list'] = $val1[0];
                }
            }
        }

        if(empty($chart3)){
            $chart3 = [];
        }

        $val910 = $dashboard->getStatusCountBulk($periodString,910);
        $val920 = $dashboard->getStatusCountBulk($periodString,920);
        $val930 = $dashboard->getStatusCountBulk($periodString,930);
        $val980 = $dashboard->getStatusCountBulk($periodString,980);
        $val990 = $dashboard->getStatusCountBulk($periodString,990);


        if(!empty($val910)){
            foreach ($val910 as $key => $value) {
                $val910[$key]['val910'] = $value['PERCENTAGE'];
                unset($val910[$key]['PERCENTAGE']);
            }
        }

        if(!empty($val920)){
            foreach ($val920 as $key => $value) {
                $val920[$key]['val920'] = $value['PERCENTAGE'];
                unset($val920[$key]['PERCENTAGE']);
            }
        }

        if(!empty($val930)){
            foreach ($val930 as $key => $value) {
                $val930[$key]['val930'] = $value['PERCENTAGE'];
                unset($val930[$key]['PERCENTAGE']);
            }
        }

        if(!empty($val980)){
            foreach ($val980 as $key => $value) {
                $val980[$key]['val980'] = $value['PERCENTAGE'];
                unset($val980[$key]['PERCENTAGE']);
            }
        }

        if(!empty($val990)){
            foreach ($val990 as $key => $value) {
                $val990[$key]['val990'] = $value['PERCENTAGE'];
                unset($val990[$key]['PERCENTAGE']);
            }
        }


        $mergeVal1 = array_merge($val910,$val920,$val930,$val980,$val990);


        $keys = array('date_list'=>0,'val910'=>0,'val920'=>0,'val930'=>0,'val980'=>0,'val990'=>0);
        $data = array();
        foreach($mergeVal1 as $values) {
            $data[] = array_merge($keys, $values);
        }

        foreach ($data as $key => $value) {
            $currDate = $value['date_list'];
            $val1= array_values(array_filter($data,function($v,$k) use($currDate){return $v['date_list'] == $currDate;},ARRAY_FILTER_USE_BOTH));
            if(!empty($val1)){
                foreach($val1 as $setVal){
                    
                    if(!empty($setVal['val910'])){
                        $data[$key]['val910'] = $setVal['val910']; 
                    }

                    if(!empty($setVal['val920'])){
                        $data[$key]['val920'] = $setVal['val920'];
                    }

                    if(!empty($setVal['val930'])){
                        $data[$key]['val930'] = $setVal['val930'];
                    }

                    if(!empty($setVal['val980'])){
                        $data[$key]['val980'] = $setVal['val980'];
                    }

                    if(!empty($setVal['val990'])){
                        $data[$key]['val990'] = $setVal['val990'];
                    }
                }
            }
            
        }

        foreach ($data as $key => $value) {
            $currDate = $value['date_list'];
            $val1= array_values(array_filter($data,function($v,$k) use($currDate){return $v['date_list'] == $currDate;},ARRAY_FILTER_USE_BOTH));
            
            if(count($val1)>1){
                unset($data[$key]);
            }
            
        }

        arsort($data);
        $chart4 = array_values($data);

        if(!empty($chart4)){
            foreach ($chart4 as $key => $value) {
                $currDate = $value['date_list'];
                $val1= array_values(array_filter($monthListName,function($v,$k) use($currDate){return $k == $currDate;},ARRAY_FILTER_USE_BOTH));
                if(!empty($val1)){
                    $chart4[$key]['date_list'] = $val1[0];
                }
            }
        }

        if(empty($chart4)){
            $chart4 = [];
        }

        $invitedList = $dashboard->invitedList();
        $selectedList = $dashboard->selectedList();

        $monthList = [1,2,3,4,5,6,7,8,9,10,11,12];

        foreach ($monthList as $value) {
            $invList = array_values(array_filter($invitedList,function($v,$k) use($value){return $v['displayMonth'] == $value;},ARRAY_FILTER_USE_BOTH));
            
            if(!empty($invList)){
                $invitedLists[] = $invList[0]['COUNT'];
            }else{
                $invitedLists[] = 0;
            }

            $selList = array_values(array_filter($selectedList,function($v,$k) use($value){return $v['displayMonth'] == $value;},ARRAY_FILTER_USE_BOTH));
            
            if(!empty($selList)){
                $selectedLists[] = $selList[0]['COUNT'];
            }else{
                $selectedLists[] = 0;
            }
        }

        $quickLinks = $this->getQuickLinks();

        $this->_template->assign('quickLinks', $quickLinks);
        $this->_template->assign('active', $this);
        $this->_template->assign('placedRS', $placedRS);

        $this->_template->assign('chart1', $chart1);
        $this->_template->assign('chart2', $chart2);
        $this->_template->assign('chart3', $chart3);
        $this->_template->assign('chart4', $chart4);
        
        $this->_template->assign('invitedList', $invitedLists);
        $this->_template->assign('selectedList', $selectedLists);
       
        $this->_template->display('./modules/home/SalesDashboard.tpl');
    }

    private function getQuickLinks()
    {
        $today = array(
            'month' => date('n'),
            'day'   => date('j'),
            'year'  => date('Y')
        );

        $yesterdayTimeStamp = DateUtility::subtractDaysFromDate(time(), 1);
        $yesterday = array(
            'month' => date('n', $yesterdayTimeStamp),
            'day'   => date('j', $yesterdayTimeStamp),
            'year'  => date('Y', $yesterdayTimeStamp)
        );

        $baseURL = sprintf(
            '%s?m=home&amp;a=viewByDate&amp;getback=getback',
            CATSUtility::getIndexName()
        );

        $quickLinks[0] = sprintf(
            '<a href="%s&amp;startMonth=%s&amp;startDay=%s&amp;startYear=%s&amp;endMonth=%s&amp;endDay=%s&amp;endYear=%s">Today</a>',
            $baseURL,
            $today['month'],
            $today['day'],
            $today['year'],
            $today['month'],
            $today['day'],
            $today['year']
        );

        $quickLinks[1] = sprintf(
            '<a href="%s&amp;startMonth=%s&amp;startDay=%s&amp;startYear=%s&amp;endMonth=%s&amp;endDay=%s&amp;endYear=%s">Yesterday</a>',
            $baseURL,
            $yesterday['month'],
            $yesterday['day'],
            $yesterday['year'],
            $yesterday['month'],
            $yesterday['day'],
            $yesterday['year']
        );

        $quickLinks[2] = sprintf(
            '<a href="%s&amp;period=lastweek">Last Week</a>',
            $baseURL
        );

        $quickLinks[3] = sprintf(
            '<a href="%s&amp;period=lastmonth">Last Month</a>',
            $baseURL
        );

        $quickLinks[4] = sprintf(
            '<a href="%s&amp;period=lastsixmonths">Last 6 Months</a>',
            $baseURL
        );

        $quickLinks[5] = sprintf(
            '<a href="%s&amp;period=all">All</a>',
            $baseURL
        );

        return implode(' | ', $quickLinks);
    }
    

    private function deleteSavedSearch()
    {
        if (!isset($_GET['searchID']))
        {
            CommonErrors::fatal(COMMONERROR_BADINDEX, $this, 'No search ID specified.');
        }

        if (!isset($_GET['currentURL']))
        {
            CommonErrors::fatal(COMMONERROR_BADFIELDS, $this, 'No current URL specified.');
        }

        $searchID   = $_GET['searchID'];
        $currentURL = $_GET['currentURL'];

        if (!eval(Hooks::get('HOME_DELETE_SAVED_SEARCH_PRE'))) return;

        $savedSearches = new SavedSearches($this->_siteID);
        $savedSearches->remove($searchID);

        if (!eval(Hooks::get('HOME_DELETE_SAVED_SEARCH_POST'))) return;

        CATSUtility::transferRelativeURI($currentURL);
    }

    private function addSavedSearch()
    {
        if (!isset($_GET['searchID']))
        {
            CommonErrors::fatal(COMMONERROR_BADINDEX, $this, 'No search ID specified.');
        }

        if (!isset($_GET['currentURL']))
        {
            CommonErrors::fatal(COMMONERROR_BADFIELDS, $this, 'No current URL specified.');
        }

        $searchID   = $_GET['searchID'];
        $currentURL = $_GET['currentURL'];

        if (!eval(Hooks::get('HOME_ADD_SAVED_SEARCH_PRE'))) return;

        $savedSearches = new SavedSearches($this->_siteID);
        $savedSearches->save($searchID);

        if (!eval(Hooks::get('HOME_ADD_SAVED_SEARCH_POST'))) return;

        CATSUtility::transferRelativeURI($currentURL);
    }

    private function quickSearch()
    {
        /* Bail out to prevent an error if the GET string doesn't even contain
         * a field named 'quickSearchFor' at all.
         */
        if (!isset($_GET['quickSearchFor']))
        {
            CommonErrors::fatal(COMMONERROR_BADFIELDS, $this, 'No query string specified.');
        }

        $query = trim($_GET['quickSearchFor']);
        $wildCardQuickSearch = $query;

        $search = new QuickSearch($this->_siteID);
        $candidatesRS = $search->candidates($query);
        $companiesRS  = $search->companies($query);
        $contactsRS   = $search->contacts($query);
        $jobOrdersRS  = $search->jobOrders($query);
        //$listsRS      = $search->lists($query);

        if (!empty($candidatesRS))
        {
            foreach ($candidatesRS as $rowIndex => $row)
            {
                if (!empty($candidatesRS[$rowIndex]['ownerFirstName']))
                {
                    $candidatesRS[$rowIndex]['ownerAbbrName'] = StringUtility::makeInitialName(
                        $candidatesRS[$rowIndex]['ownerFirstName'],
                        $candidatesRS[$rowIndex]['ownerLastName'],
                        false,
                        LAST_NAME_MAXLEN
                    );
                }
                else
                {
                    $candidatesRS[$rowIndex]['ownerAbbrName'] = 'None';
                }

                if (empty($candidatesRS[$rowIndex]['phoneHome']))
                {
                    $candidatesRS[$rowIndex]['phoneHome'] = 'None';
                }

                if (empty($candidatesRS[$rowIndex]['phoneCell']))
                {
                    $candidatesRS[$rowIndex]['phoneCell'] = 'None';
                }
            }
        }

        if (!empty($companiesRS))
        {
            foreach ($companiesRS as $rowIndex => $row)
            {
                if (!empty($companiesRS[$rowIndex]['ownerFirstName']))
                {
                    $companiesRS[$rowIndex]['ownerAbbrName'] = StringUtility::makeInitialName(
                        $companiesRS[$rowIndex]['ownerFirstName'],
                        $companiesRS[$rowIndex]['ownerLastName'],
                        false,
                        LAST_NAME_MAXLEN
                    );
                }
                else
                {
                    $companiesRS[$rowIndex]['ownerAbbrName'] = 'None';
                }

                if (empty($companiesRS[$rowIndex]['phone1']))
                {
                    $companiesRS[$rowIndex]['phone1'] = 'None';
                }
            }
        }

        if (!empty($contactsRS))
        {
            foreach ($contactsRS as $rowIndex => $row)
            {

                if ($contactsRS[$rowIndex]['isHotContact'] == 1)
                {
                    $contactsRS[$rowIndex]['linkClassContact'] = 'jobLinkHot';
                }
                else
                {
                    $contactsRS[$rowIndex]['linkClassContact'] = 'jobLinkCold';
                }

                if ($contactsRS[$rowIndex]['leftCompany'] == 1)
                {
                    $contactsRS[$rowIndex]['linkClassCompany'] = 'jobLinkDead';
                }
                else if ($contactsRS[$rowIndex]['isHotCompany'] == 1)
                {
                    $contactsRS[$rowIndex]['linkClassCompany'] = 'jobLinkHot';
                }
                else
                {
                    $contactsRS[$rowIndex]['linkClassCompany'] = 'jobLinkCold';
                }

                if (!empty($contactsRS[$rowIndex]['ownerFirstName']))
                {
                    $contactsRS[$rowIndex]['ownerAbbrName'] = StringUtility::makeInitialName(
                        $contactsRS[$rowIndex]['ownerFirstName'],
                        $contactsRS[$rowIndex]['ownerLastName'],
                        false,
                        LAST_NAME_MAXLEN
                    );
                }
                else
                {
                    $contactsRS[$rowIndex]['ownerAbbrName'] = 'None';
                }

                if (empty($contactsRS[$rowIndex]['phoneWork']))
                {
                    $contactsRS[$rowIndex]['phoneWork'] = 'None';
                }

                if (empty($contactsRS[$rowIndex]['phoneCell']))
                {
                    $contactsRS[$rowIndex]['phoneCell'] = 'None';
                }
            }
        }

        if (!empty($jobOrdersRS))
        {
            foreach ($jobOrdersRS as $rowIndex => $row)
            {
                if ($jobOrdersRS[$rowIndex]['startDate'] == '00-00-00')
                {
                    $jobOrdersRS[$rowIndex]['startDate'] = '';
                }

                if ($jobOrdersRS[$rowIndex]['isHot'] == 1)
                {
                    $jobOrdersRS[$rowIndex]['linkClass'] = 'jobLinkHot';
                }
                else
                {
                    $jobOrdersRS[$rowIndex]['linkClass'] = 'jobLinkCold';
                }

                if (!empty($jobOrdersRS[$rowIndex]['recruiterAbbrName']))
                {
                    $jobOrdersRS[$rowIndex]['recruiterAbbrName'] = StringUtility::makeInitialName(
                        $jobOrdersRS[$rowIndex]['recruiterFirstName'],
                        $jobOrdersRS[$rowIndex]['recruiterLastName'],
                        false,
                        LAST_NAME_MAXLEN
                    );
                }
                else
                {
                    $jobOrdersRS[$rowIndex]['recruiterAbbrName'] = 'None';
                }

                if (!empty($jobOrdersRS[$rowIndex]['ownerFirstName']))
                {
                    $jobOrdersRS[$rowIndex]['ownerAbbrName'] = StringUtility::makeInitialName(
                        $jobOrdersRS[$rowIndex]['ownerFirstName'],
                        $jobOrdersRS[$rowIndex]['ownerLastName'],
                        false,
                        LAST_NAME_MAXLEN
                    );
                }
                else
                {
                    $jobOrdersRS[$rowIndex]['ownerAbbrName'] = 'None';
                }
            }
        }

        $this->_template->assign('active', $this);
        $this->_template->assign('jobOrdersRS', $jobOrdersRS);
        $this->_template->assign('candidatesRS', $candidatesRS);
        $this->_template->assign('companiesRS', $companiesRS);
        $this->_template->assign('contactsRS', $contactsRS);
        //$this->_template->assign('listsRS', $listsRS);
        $this->_template->assign('wildCardQuickSearch', $wildCardQuickSearch);

        if (!eval(Hooks::get('HOME_QUICK_SEARCH'))) return;

        $this->_template->display('./modules/home/SearchEverything.tpl');
    }
}

?>
