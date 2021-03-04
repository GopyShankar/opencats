<?php
/*
 * CATS
 * Careers Module
 *
 * Copyright (C) 2005 - 2007 Cognizo Technologies, Inc.
 * All rights reserved.
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
 * $Id: CareersUI.php 3812 2007-12-05 21:33:28Z andrew $
 */

include_once('./lib/CareerPortal.php');
include_once('./lib/JobOrders.php');
include_once('./lib/Candidates.php');
include_once('./lib/Site.php');
include_once('./lib/Companies.php');
include_once('./lib/Contacts.php');
include_once('./lib/Users.php');
include_once('./lib/FileUtility.php');
include_once('./lib/ActivityEntries.php');
include_once('./lib/DocumentToText.php');
include_once('./lib/DatabaseConnection.php');
include_once('./lib/DatabaseSearch.php');
include_once('./lib/CommonErrors.php');
include_once('./lib/Questionnaire.php');
include_once('./lib/DocumentToText.php');
include_once('./lib/FileUtility.php');
include_once('./lib/ParseUtility.php');

class CareersUI extends UserInterface
{
    public function __construct()
    {
        parent::__construct();

        $this->_authenticationRequired = false;
        $this->_moduleDirectory = 'careers';
        $this->_moduleName = 'careers';
    }


    public function handleRequest()
    {
        $action = $this->getAction();

        switch ($action)
        {
            default:
                $this->careersPage();
                break;
        }
    }

    private function careersPage()
    {
        global $careerPage;

        /* Get information on what site we are in, our environment, etc. */

        $site = new Site(-1);

        $siteID = $site->getFirstSiteID();

        if (!eval(Hooks::get('CAREERS_SITEID'))) return;

        $siteRS = $site->getSiteBySiteID($siteID);

        if (!isset($siteRS['name']))
        {
            die('An error has occurred:  No site exists with this site name.');
        }

        $siteName = $siteRS['name'];

        /* Get information on the current template. */

        $careerPortalSettings = new CareerPortalSettings($siteID);
        $careerPortalSettingsRS = $careerPortalSettings->getAll();

        $templateName = $careerPortalSettingsRS['activeBoard'];
        $enabled = $careerPortalSettingsRS['enabled'];

        if ($enabled == 0)
        {
            // FIXME: Generate valid XHTML error pages. Create an error/fatal method!
            die('<html><body><!-- Job Board Disabled --></body></html>');
        }

        if (isset($_GET['templateName']))
        {
            $templateName = $_GET['templateName'];
        }

        $template = $careerPortalSettings->getTemplate($templateName);

        /* At this point the entire template is loaded, we just need to add data to the
           template for the specific page. */

        /* Get all public job orders for this site. */
        $jobOrders = new JobOrders($siteID);
        $rs = $jobOrders->getAll(JOBORDERS_STATUS_ACTIVE, -1, -1, -1, false, true);

        $useCookie = true;

        // Get the get or post page request
        $p = isset($_GET['p']) ? $_GET['p'] : '';
        $p = isset($_POST['p']) ? $_POST['p'] : $p;

        // Get the get or post sub-page request
        $pa = isset($_GET['pa']) ? $_GET['pa'] : '';
        $pa = isset($_POST['pa']) ? $_POST['pa'] : $pa;

        $isRegistrationEnabled = $careerPortalSettingsRS['candidateRegistration'];

        switch ($pa)
        {
            case 'logout':
                if ($isRegistrationEnabled)
                {
                    // Remove the saved information cookie
                    setcookie($this->getCareerPortalCookieName($siteID), '');
                    $useCookie = false;
                }
                break;

            case 'updateProfile':
                if ($isRegistrationEnabled)
                {
                    $p = 'registeredCandidateProfile';
                }
                break;
        }

        if ($p == 'showAll')
        {
            $template['Content'] = $template['Content - Search Results'];

            $template['Content'] = str_replace('<numberOfSearchResults>', count($rs), $template['Content']);
            $template['Content'] = str_replace('<registeredCandidate>', $useCookie && $isRegistrationEnabled ? $this->getRegisteredCandidateBlock($siteID, $template['Content - Candidate Registration']) : '', $template['Content']);

            if ($careerPortalSettingsRS['allowBrowse'] == 1)
            {
                /* Legacy. */
                $template['Content'] = str_replace('<searchResultsTableUnformatted>', $this->getResultsTable($rs, $careerPortalSettingsRS, true), $template['Content']);

                while (strpos($template['Content'], '<searchResultsTable') !== false)
                {
                    $searchResultsTablePosition = strpos($template['Content'], '<searchResultsTable');

                    $temp = substr($template['Content'], $searchResultsTablePosition + strlen('<searchResultsTable'));
                    $searchResultsTableParameters = trim(substr($temp, 0, strpos($temp, '>') - 1));

                    $tableHTML = $this->getResultsTable($rs, $careerPortalSettingsRS, false, $searchResultsTableParameters);

                    $template['Content'] = substr($template['Content'], 0, $searchResultsTablePosition - 1) . $tableHTML . substr($temp, strpos($temp, '>') + 1);
                }
            }
            else
            {
                $template['Content'] = str_replace('<searchResultsTable>', 'Sorry, Job Listings have been disabled by the '.$siteName.' administrator.', $template['Content']);
            }
        }
        else if ($p == 'search')
        {
        }
        else if ($p == 'registeredCandidateProfile' && $isRegistrationEnabled)
        {
            $content = $template['Content - Candidate Profile'];

            // Get information about the candidate from the cookie
            $fields = $this->getCookieFields($siteID);
            $candidate = $this->ProcessCandidateRegistration($siteID, $template['Content - Candidate Registration'], $fields);
            if ($candidate === false)
            {
                echo '<html><body>You have not registered yet.  Please wait while we direct you to the job list...<script>setTimeout("document.location.href=\'?m=careers&&p=showAll\';", 1500);</script></body></html>';
                die();
            }

            // Get the candidate's latest resume attachment (if exists)
            $attachmentsLib = new Attachments($siteID);
            $attachments = $attachmentsLib->getAll(DATA_ITEM_CANDIDATE, $candidate['candidateID']);

            $latestDate = 0;
            $latestAttachment = false;
            foreach ($attachments as $attachment)
            {
                if (preg_match('/^([0-9]{2})-([0-9]{2})-([0-9]{2}) \(([0-9]{2}):([0-9]{2}):([0-9]{2}) [A-Z]{2}\)$/',
                    $attachment['dateCreated'], $matches))
                {
                    $epoch = strtotime( strval($matches[1]) . '/' . strval($matches[2]) . '/' . strval($matches[3]) );

                    if ($epoch > $latestDate)
                    {
                        $latestDate = $epoch;
                        $latestAttachment = $attachment['attachmentID'];
                    }
                }
            }

            // Get their latest resume
            if ($latestAttachment !== false)
            {
                $candidatesLib = new Candidates($siteID);
                $myResume = $candidatesLib->getResume($latestAttachment);
            }

            /* Replace input fields. */
            $content = str_replace('<input-firstName>', '<input name="firstName" id="firstName" class="inputBoxName" value="' . $candidate['firstName'] . '" />', $content);
            $content = str_replace('<input-lastName>', '<input name="lastName" id="lastName" class="inputBoxName" value="' . $candidate['lastName'] . '" />', $content);
            $content = str_replace('<input-address>', '<textarea name="address" id="address" class="inputBoxArea">'. $candidate['address'] .'</textarea>', $content);
            $content = str_replace('<input-city>', '<input name="city" id="city" class="inputBoxNormal" value="' . $candidate['city'] . '" />', $content);
            $content = str_replace('<input-state>', '<input name="state" id="state" class="inputBoxNormal" value="' . $candidate['state'] . '" />', $content);
            $content = str_replace('<input-zip>', '<input name="zip" id="zip" class="inputBoxNormal" value="' . $candidate['zip'] . '" />', $content);
            $content = str_replace('<input-phoneWork>', '<input name="phoneWork" id="phoneWork" class="inputBoxNormal" value="' . $candidate['phoneWork'] . '" />', $content);
            $content = str_replace('<input-email1>', '<input name="email1" id="email1" class="inputBoxNormal" value="' . $candidate['email1'] . '" />', $content);
            $content = str_replace('<input-phoneHome>', '<input name="phoneHome" id="phoneHome" class="inputBoxNormal" value="' . $candidate['phoneHome'] . '" />', $content);
            $content = str_replace('<input-phoneCell>', '<input name="phoneCell" id="phoneCell" class="inputBoxNormal" value="' . $candidate['phoneCell'] . '" />', $content);
            $content = str_replace('<input-bestTimeToCall>', '<input name="bestTimeToCall" id="bestTimeToCall" class="inputBoxNormal" value="' . $candidate['bestTimeToCall'] . '" />', $content);
            $content = str_replace('<input-keySkills>', '<input name="keySkills" id="keySkills" class="inputBoxNormal" value="' . $candidate['keySkills'] . '" />', $content);
            $content = str_replace('<input-source>', '<input name="source" id="source" class="inputBoxNormal" value="' . $candidate['source'] . '" />', $content);
            $content = str_replace('<input-currentEmployer>', '<input name="currentEmployer" id="currentEmployer" class="inputBoxNormal" value="' . $candidate['currentEmployer'] . '" />', $content);
            $content = str_replace('<input-resume>',
                '<strong>My Resume</strong><br />'
                . '<textarea name="resumeContents" class="inputBoxArea" style="width: 400px; height: 200px;" readonly>'
                . ($latestAttachment !== false ? DatabaseSearch::fulltextDecode($myResume['text']) : '') .'</textarea>'
                . '<br /><br /><strong>Upload new resume:</strong><br /> '
                . '<input type="file" name="file" id="file" type="file" class="inputBoxFile" size="45" />',
                $content
            );
            $content = str_replace('<input-submit>', '<input type="submit" name="submitButton" id="submitButton" class="submitButton" onclick="document.getElementById(\'submitButton\').disabled=true;" value="Save Profile" style="width: 150px;" />', $content);

            $content = sprintf(
                '<form name="updateForm" id="updateForm" enctype="multipart/form-data" method="post" '
                . 'action="%s?m=careers&p=onRegisteredCandidateProfile&attachmentID=%d">',
                CATSUtility::getIndexName(),
                $latestAttachment ? $latestAttachment : -1
            ) . $content . '</form>'
            . (isset($_GET[$id='isPostBack']) && !strcmp($_GET[$id], 'yes') ? '<script language="javascript" type="text/javascript">setTimeout(\'alert("Your changes have been saved!")\',25);</script>' : '');

            $template['Content'] = $content;
        }
        else if ($p == 'onRegisteredCandidateProfile' && $isRegistrationEnabled)
        {
            // Get information about the candidate from the cookie
            $fields = $this->getCookieFields($siteID);
            $candidate = $this->ProcessCandidateRegistration($siteID, $template['Content - Candidate Registration'], $fields, true);
            if ($candidate === false)
            {
                echo '<html><body>You have not registered yet.  Please wait while we direct you to the job list...<script>setTimeout("document.location.href=\'?m=careers&&p=showAll\';", 1500);</script></body></html>';
                die();
            }

            // Get the fields (if included in the template) to update
            $fields = array('firstName', 'lastName', 'email1', 'phoneHome', 'phoneCell', 'phoneWork', 'address',
                'city', 'state', 'zip', 'keySkills', 'currentEmployer', 'bestTimeToCall'
            );
            $fieldValues = array();

            foreach ($fields as $field)
            {
                if (isset($_POST[$field]) && $_POST[$field] != '')
                {
                    eval('$'.$field.' = trim($_POST[\''.$field.'\']);');
                    $fieldValues[$field] = $_POST[$field];
                }
                else
                {
                    eval('$'.$field.' = $candidate[\''.$field.'\'];');
                    $fieldValues[$field] = $candidate[$field];
                }
            }

            // Get the attachment to replace (if exists)
            $attachmentID = isset($_GET[$id='attachmentID']) ? $_GET[$id] : -1;
            $attachmentID = $attachmentID != -1 ? $attachmentID : false;

            $attachmentsLib = new Attachments($siteID);
            $candidatesLib = new Candidates($siteID);

            // Update the candidate's information
            $candidatesLib->update(
                $candidate['candidateID'],
                $candidate['isActive'] ? true : false,
                $firstName,
                $candidate['middleName'],
                $lastName,
                $email1,
                $email1,
                $phoneHome,
                $phoneCell,
                $phoneWork,
                $address,
                $city,
                $state,
                $zip,
                $candidate['source'],
                $keySkills,
                $candidate['dateAvailable'],
                $currentEmployer,
                $candidate['canRelocate'],
                $candidate['currentPay'],
                $candidate['desiredPay'],
                $candidate['notes'],
                $candidate['webSite'],
                $bestTimeToCall,
                $candidate['owner'],
                $candidate['isHot'] ? true : false,
                $email1,
                $email1,
                $candidate['eeoGender'],
                $candidate['eeoEthnicType'],
                $candidate['eeoVeteranType'],
                $candidate['eeoDisabilityStatus']
            );

            $uploadResume = FileUtility::getUploadFileFromPost($siteID, 'careerportaladd', 'file');
            if ($uploadResume !== false)
            {
                $uploadPath = FileUtility::getUploadFilePath($siteID, 'careerportaladd', $uploadResume);
                if ($uploadPath !== false)
                {
                    // Replace most current resume with new uploaded resume
                    $attachmentsLib->delete($attachmentID, true);
                    $attachmentCreator = new AttachmentCreator($siteID);
                    $attachmentCreator->createFromFile(DATA_ITEM_CANDIDATE, $candidate['candidateID'],
                        $uploadPath, false, '', true, true
                    );
                }
            }

            // Set the cookie again, since some information used to verify may be changed
            $storedVal = '';
            foreach ($fieldValues as $tag => $tagData)
            {
                $storedVal .= sprintf('"%s"="%s"', urlencode($tag), urlencode($tagData));
            }
            @setcookie($this->getCareerPortalCookieName($siteID), $storedVal, time()+60*60*24*7*2);

            $template['Content'] = '<div id="careerContent"><br /><br /><h1>Please wait while you are redirected to your updated profile...</h1></div>';
            CATSUtility::transferRelativeURI('m=careers&p=showAll&pa=updateProfile&isPostBack=yes');
        }
        else if ($p == 'candidateRegistration' && $isRegistrationEnabled)
        {
            $content = $template['Content - Candidate Registration'];

            $jobID = intval($_GET['ID']);
            $jobOrderData = $jobOrders->get($jobID);
            $js = '';

            $content = str_replace(array('<applyContent>','</applyContent>'), '', $content);

            $content = str_replace('<input-submit>', '<input type="submit" id="submitButton" name="submitButton" value="Continue to Application" />', $content);
            $content = str_replace('<input-new>', '<input type="radio" id="isNewYes" name="isNew" value="yes" onchange="isCandidateRegisteredChange();" checked />', $content);
            $content = str_replace('<input-registered>', '<input type="radio" id="isNewNo" name="isNew" value="no" onchange="isCandidateRegisteredChange();" />', $content);
            $content = str_replace('<input-rememberMe>', '<input type="checkbox" id="rememberMe" name="rememberMe" value="yes" checked />', $content);
            $content = str_replace('<title>', $jobOrderData['title'], $content);

            // Process html-ish fields like <input-firstName> into the proper form
            $content = preg_replace(
                '/\<input\-([A-Za-z0-9]+)\>/',
                '<input type="text" class="inputBoxNormal" style="width: 270px;" name="$1" id="$1" onfocus="onFocusFormField(this)" />',
                $content
            );

            if (count($fields = $this->getCookieFields($siteID)))
            {
                $js = '<script language="javascript" type="text/javascript">' . "\n"
                    . 'function populateSavedFields() { var obj; obj = document.getElementById(\'isNewNo\'); '
                    . 'if (obj) { obj.checked = true; enableFormFields(true); } ' . "\n";
                foreach ($fields as $tagName => $tagValue)
                {
                    $js .= sprintf(
                        'if (obj = document.getElementById(\'%s\')) obj.value = \'%s\';%s',
                        urldecode($tagName),
                        str_replace("'", "\\'", urldecode($tagValue)),
                        "\n"
                    );
                }
                $js .= "}\n</script>\n";
            }

            // Insert the form block
            $content = sprintf(
                '%s<form name="register" id="register" method="post" onsubmit="return validateCandidateRegistration()" '
                . 'action="%s?m=careers&p=applyToJob&ID=%d">'
                . '<input type="hidden" name="applyToJobSubAction" value="processLogin" />',
                $js,
                CATSUtility::getIndexName(),
                $jobID
            ) . $content . '<script>enableFormFields(false); ' . ($js != '' ? 'populateSavedFields();' : '')
            . '</script></form>';

            $template['Content'] = $content;
        }
        else if ($p == 'applyToJob' || isset($_POST[$id='applyToJobSubAction']) && $_POST[$id] != '')
        {
            $candidateID = isset($_POST[$id='candidateID']) ? $_POST[$id] : '';
            // Pre-populations
            $firstName = isset($_POST[$id='firstName']) ? $_POST[$id] : '';
            $lastName = isset($_POST[$id='lastName']) ? $_POST[$id] : '';
            $address = isset($_POST[$id='address']) ? $_POST[$id] : '';
            $city = isset($_POST[$id='city']) ? $_POST[$id] : '';
            $state = isset($_POST[$id='state']) ? $_POST[$id] : '';
            $zip = isset($_POST[$id='zip']) ? $_POST[$id] : '';
            $phone = isset($_POST[$id='phone']) ? $_POST[$id] : '';
            $email = isset($_POST[$id='email']) ? $_POST[$id] : '';
            $phoneHome = isset($_POST[$id='phoneHome']) ? $_POST[$id] : '';
            $phoneCell = isset($_POST[$id='phoneCell']) ? $_POST[$id] : '';
            $bestTimeToCall = isset($_POST[$id='bestTimeToCall']) ? $_POST[$id] : '';
            $email2 = isset($_POST[$id='email2']) ? $_POST[$id] : '';
            $emailconfirm = isset($_POST[$id='emailconfirm']) ? $_POST[$id] : '';
            $keySkills = isset($_POST[$id='keySkills']) ? $_POST[$id] : '';
            $source = isset($_POST[$id='source']) ? $_POST[$id] : '';
            $employer = isset($_POST[$id='employer']) ? $_POST[$id] : '';
            // for <input-resumeUploadPreview>
            $resumeContents = isset($_POST[$id='resumeContents']) ? $_POST[$id] : '';
            $resumeFileLocation = isset($_POST[$id='file']) ? $_POST[$id] : '';
            

            $erName1 = isset($_POST[$id='erName1']) ? $_POST[$id] : '';
            $erDoj1 = isset($_POST[$id='erDoj1']) ? $_POST[$id] : '';
            $erDor1 = isset($_POST[$id='erDor1']) ? $_POST[$id] : '';
            $erName2 = isset($_POST[$id='erName2']) ? $_POST[$id] : '';
            $erDoj2 = isset($_POST[$id='erDoj2']) ? $_POST[$id] : '';
            $erDor2 = isset($_POST[$id='erDor2']) ? $_POST[$id] : '';
            $erName3 = isset($_POST[$id='erName3']) ? $_POST[$id] : '';
            $erDoj3 = isset($_POST[$id='erDoj3']) ? $_POST[$id] : '';
            $erDor3 = isset($_POST[$id='erDor3']) ? $_POST[$id] : '';
            $ectcConfirm = isset($_POST[$id='ectcConfirm']) ? $_POST[$id] : '';
            $doj = isset($_POST[$id='doj']) ? $_POST[$id] : '';

            $currentErName = isset($_POST[$id='currentErName']) ? $_POST[$id] : '';
            $currentErDoj = isset($_POST[$id='currentErDoj']) ? $_POST[$id] : '';
            $currentErDor = isset($_POST[$id='currentErDor']) ? $_POST[$id] : '';
            $board10th = isset($_POST[$id='board10th']) ? $_POST[$id] : '';
            $passYr10th = isset($_POST[$id='passYr10th']) ? $_POST[$id] : '';
            $precent10th = isset($_POST[$id='precent10th']) ? $_POST[$id] : '';
            $board12th = isset($_POST[$id='board12th']) ? $_POST[$id] : '';
            $passYr12th = isset($_POST[$id='passYr12th']) ? $_POST[$id] : '';
            $precent12th = isset($_POST[$id='precent12th']) ? $_POST[$id] : '';
            $insName = isset($_POST[$id='insName']) ? $_POST[$id] : '';
            $degreeCourse = isset($_POST[$id='degreeCourse']) ? $_POST[$id] : '';
            $degreePassYr = isset($_POST[$id='degreePassYr']) ? $_POST[$id] : '';
            $degreePrecent = isset($_POST[$id='degreePrecent']) ? $_POST[$id] : '';

            $payslipFileLocation = isset($_POST[$id='payslip_file']) ? $_POST[$id] : '';
            $previousEmpFileLocation = isset($_POST[$id='previousEmp_file']) ? $_POST[$id] : '';

            $currCTC = isset($_POST[$id='currCTC']) ? $_POST[$id] : ''; 


            // for returning candidates
            $candidateID = -1;

            if ($isRegistrationEnabled)
            {
                // Check if the user is registered and logged in
                $cookieFields = $this->getCookieFields($siteID);
                $candidate = $this->ProcessCandidateRegistration($siteID, $template['Content - Candidate Registration'], $cookieFields, true);
                if ($candidate !== false)
                {
                    // The candidate is registered
                    $firstName = $candidate['firstName']; $lastName = $candidate['lastName'];
                    $address = $candidate['address'];
                    $city = $candidate['city'];
                    $state = $candidate['state'];
                    $zip = $candidate['zip'];
                    $phone = $candidate['phoneWork'];
                    $phoneHome = $candidate['phoneHome'];
                    $phoneCell = $candidate['phoneCell'];
                    $email = $candidate['email1'];
                    $email2 = $candidate['email2'];
                    $emailconfirm = $email;
                    $keySkills = $candidate['keySkills'];
                    $source = $candidate['source'];
                    $employer = $candidate['currentEmployer'];
                    $candidateID = $candidate['candidateID'];
                    
                    $currCTC = $candidate['currentPay'];
                    $erName1 = $candidate['employer1_name'];
                    $erDoj1 = $candidate['employer1_doj'];
                    $erDor1 = $candidate['employer1_dor'];
                    $erName2 = $candidate['employer2_name'];
                    $erDoj2 = $candidate['employer2_doj'];
                    $erDor2 = $candidate['employer2_dor'];
                    $erName3 = $candidate['employer3_name'];
                    $erDoj3 = $candidate['employer3_doj'];
                    $erDor3 = $candidate['employer3_dor'];
                    $ectcConfirm = $candidate['ectc_confirmation'];
                    $doj = $candidate['doj'];

                    $currentErName = $candidate['current_er_name'];
                    $currentErDoj = $candidate['current_er_doj'];
                    $currentErDor = $candidate['current_er_dor'];
                    $board10th = $candidate['board10th'];
                    $passYr10th = $candidate['passYr10th'];
                    $precent10th = $candidate['precent10th'];
                    $board12th = $candidate['board12th'];
                    $passYr12th = $candidate['passYr12th'];
                    $precent12th = $candidate['precent12th'];
                    $insName = $candidate['insName'];
                    $degreeCourse = $candidate['degreeCourse'];
                    $degreePassYr = $candidate['degreePassYr'];
                    $degreePrecent = $candidate['degreePrecent'];

                    $candidateID = $candidate['candidateID'];
                }
            }

            /**
             * SUB-ACTIONS
             * These actions are called as postbacks, such as loading a resume file into the
             * "contents" textarea on the application page. All post data remains intact and
             * re-populates the fields giving the illusion of AJAX.
             */
            if (isset($_POST[$id='applyToJobSubAction']) && strlen($subAction = $_POST[$id]))
            {
                $jobID = $_GET['ID'];
                if(strcmp($subAction, 'processLogin')){}else{
                    $checkData = $this->checkCandidatesData($siteID,$_POST);
                    
                    if(isset($_POST['isNew']) && !strcmp($_POST['isNew'], 'no') && $isRegistrationEnabled){
                        if($checkData =='false' && $isRegistrationEnabled){
                            // CATSUtility::transferRelativeURI('m=careers&p=candidateRegistration&ID='.$jobID);
                            echo '<html><body>Because you have registered before, please complete all the fields to login.If you haven\'t registered before, please select I have not registered on this website<script>setTimeout("document.location.href=\'?m=careers&&p=candidateRegistration&&ID='.$jobID.'\';", 1500);</script></body></html>';
                            die();
                        }    
                    }else{
                        if($checkData =='true' && $isRegistrationEnabled){
                            // CATSUtility::transferRelativeURI('m=careers&p=candidateRegistration&ID='.$jobID);
                            echo '<html><body>Because you have registered before, please complete all the fields to login.If you haven\'t registered before, please select I have not registered on this website<script>setTimeout("document.location.href=\'?m=careers&&p=candidateRegistration&&ID='.$jobID.'\';", 1500);</script></body></html>';
                            die();
                        }
                    }
                }
                // Check if a candidate has registered and has indicated it
                if (!strcmp($subAction, 'processLogin') &&
                    isset($_POST['isNew']) && !strcmp($_POST['isNew'], 'no') && $isRegistrationEnabled)
                {
                    $candidate = $this->ProcessCandidateRegistration($siteID, $template['Content - Candidate Registration']);
                    
                    if ($candidate !== false)
                    {
                        // Rewrite here, I'll fix it later
                        $firstName = $candidate['firstName']; $lastName = $candidate['lastName'];
                        $address = $candidate['address'];
                        $city = $candidate['city'];
                        $state = $candidate['state'];
                        $zip = $candidate['zip'];
                        $phone = $candidate['phoneWork'];
                        $phoneHome = $candidate['phoneHome'];
                        $phoneCell = $candidate['phoneCell'];
                        $email = $candidate['email1'];
                        $email2 = $candidate['email2'];
                        $emailconfirm = $email;
                        $keySkills = $candidate['keySkills'];
                        $source = $candidate['source'];
                        $employer = $candidate['currentEmployer'];
                        $candidateID = $candidate['candidateID'];

                        $currCTC = $candidate['currentPay'];
                        $erName1 = $candidate['employer1_name'];
                        $erDoj1 = $candidate['employer1_doj'];
                        $erDor1 = $candidate['employer1_dor'];
                        $erName2 = $candidate['employer2_name'];
                        $erDoj2 = $candidate['employer2_doj'];
                        $erDor2 = $candidate['employer2_dor'];
                        $erName3 = $candidate['employer3_name'];
                        $erDoj3 = $candidate['employer3_doj'];
                        $erDor3 = $candidate['employer3_dor'];
                        $ectcConfirm = $candidate['ectc_confirmation'];
                        $doj = $candidate['doj'];

                        $currentErName = $candidate['current_er_name'];
                        $currentErDoj = $candidate['current_er_doj'];
                        $currentErDor = $candidate['current_er_dor'];
                        $board10th = $candidate['board10th'];
                        $passYr10th = $candidate['passYr10th'];
                        $precent10th = $candidate['precent10th'];
                        $board12th = $candidate['board12th'];
                        $passYr12th = $candidate['passYr12th'];
                        $precent12th = $candidate['precent12th'];
                        $insName = $candidate['insName'];
                        $degreeCourse = $candidate['degreeCourse'];
                        $degreePassYr = $candidate['degreePassYr'];
                        $degreePrecent = $candidate['degreePrecent'];

                        $candidateID = $candidate['candidateID'];


                    }
                }

                // Check if a file has been uploaded, if so populate the contents textarea
                if (($uploadFile = FileUtility::getUploadFileFromPost($siteID, 'careerportaladd', 'resumeFile')) !== false)
                {
                    $uploadFilePath = FileUtility::getUploadFilePath($siteID, 'careerportaladd', $uploadFile);
                    $uploadFileLocationPath = implode(",",$uploadFilePath);
                    if ($uploadFilePath !== false)
                    {
                        $d2t = new DocumentToText();
                        $docType = $d2t->getDocumentType($uploadFilePath);
                        if ($d2t->convert($uploadFilePath, $docType) !== false)
                        {
                            $resumeContents = $d2t->getString();
                            // Remove nasty things like _rATr in favor of @
                            $resumeContents = DatabaseSearch::fulltextDecode($resumeContents);
                        }
                        else
                        {
                            $resumeContents = 'Unable to load your resume contents. Your resume will '
                                . 'still be uploaded and attached to your application.';
                        }
                        $resumeFileLocation = $uploadFile;
                    }
                }
                
                // Check if a file has been uploaded, if so populate the contents textarea
                if (($uploadPayslipFile = FileUtility::getUploadFileFromPost($siteID, 'careerportaladd', 'payslipFile')) !== false)
                {
                    $uploadPayslipFilePath = FileUtility::getUploadFilePath($siteID, 'careerportaladd', $uploadPayslipFile);
                    $uploadPayslipFileLocationPath = implode(",",$uploadPayslipFilePath);
                    if ($uploadPayslipFilePath !== false)
                    {
                        $d2t = new DocumentToText();
                        $docType = $d2t->getDocumentType($uploadPayslipFilePath);
                        if ($d2t->convert($uploadPayslipFilePath, $docType) !== false)
                        {
                            $resumeContents = $d2t->getString();
                            // Remove nasty things like _rATr in favor of @
                            $resumeContents = DatabaseSearch::fulltextDecode($resumeContents);
                        }
                        else
                        {
                            $resumeContents = 'Unable to load your resume contents. Your resume will '
                                . 'still be uploaded and attached to your application.';
                        }
                        $payslipFileLocation = $uploadPayslipFile;
                    }
                }

                // Check if a file has been uploaded, if so populate the contents textarea
                if (($uploadPreviousEmpFile = FileUtility::getUploadFileFromPost($siteID, 'careerportaladd', 'previousEmpFile')) !== false)
                {
                    $uploadPreviousEmpFilePath = FileUtility::getUploadFilePath($siteID, 'careerportaladd', $uploadPreviousEmpFile);
                    $uploadPreviousEmpFileLocationPath = implode(",",$uploadPreviousEmpFilePath);
                    if ($uploadPreviousEmpFilePath !== false)
                    {
                        $d2t = new DocumentToText();
                        $docType = $d2t->getDocumentType($uploadPreviousEmpFilePath);
                        if ($d2t->convert($uploadPreviousEmpFilePath, $docType) !== false)
                        {
                            $resumeContents = $d2t->getString();
                            // Remove nasty things like _rATr in favor of @
                            $resumeContents = DatabaseSearch::fulltextDecode($resumeContents);
                        }
                        else
                        {
                            $resumeContents = 'Unable to load your resume contents. Your resume will '
                                . 'still be uploaded and attached to your application.';
                        }
                        $previousEmpFileLocation = $uploadPreviousEmpFile;
                    }
                }

                if (!strcmp($subAction, 'resumeParse'))
                {
                    // Check if the resume contents need to be parsed (user clicked parse contents button)
                    if (LicenseUtility::isParsingEnabled())
                    {
                        $pu = new ParseUtility();
                        $fileName = isset($uploadFile) ? $uploadFile : '';
                        $res = $pu->documentParse($fileName, strlen($resumeContents), '', $resumeContents);
                        if (is_array($res) && !empty($res))
                        {
                            if (isset($res[$id='first_name']) && $res[$id] != '' && $firstName == '') $firstName = $res[$id];
                            if (isset($res[$id='last_name']) && $res[$id] != '' && $lastName == '') $lastName = $res[$id];
                            if (isset($res[$id='us_address']) && $res[$id] != '' && $address == '') $address = $res[$id];
                            if (isset($res[$id='city']) && $res[$id] != '' && $city == '') $city = $res[$id];
                            if (isset($res[$id='state']) && $res[$id] != '' && $state == '') $state = $res[$id];
                            if (isset($res[$id='zip_code']) && $res[$id] != '' && $zip == '') $zip = $res[$id];
                            if (isset($res[$id='email_address']) && $res[$id] != '' && $email == '') { $email = $res[$id]; $email2 = $res[$id]; $emailconfirm = $res[$id]; }
                            if (isset($res[$id='phone_number']) && $res[$id] != '' && $phone == '') $phone = $res[$id];
                            if (isset($res[$id='skills']) && $res[$id] != '' && $keySkills == '') $keySkills = $res[$id];
                        }
                    }
                }
            }

            $template['Content'] = $template['Content - Apply for Position'];

            // Force integer
            // FIXME: Input validation, and use isRequiredIDValid() to check for / force integer.
            $jobID = intval(isset($_GET['ID']) ? $_GET['ID'] : $_POST['ID']);

            $jobOrderData = $jobOrders->get($jobID);
            if (!isset($jobOrderData['public']) || $jobOrderData['public'] == 0)
            {
                // FIXME: Generate valid XHTML error pages. Create an error/fatal method!
                echo '<html><body>This position is no longer available.  Please wait while we direct you to the job list...<script>setTimeout("document.location.href=\'?m=careers&&p=showAll\';", 1500);</script></body></html>';
                die();
            }

            /* Make JavaScript validation rules. */
            $validator = $this->_makeApplyValidator($template);

            /* Translate required fields into normal fields for replacement. */
            $template['Content'] = str_replace(' req>', '>', $template['Content']);

            if(isset($_POST[$id='file'])){
                foreach (explode(",",$_POST[$id='file']) as $key => $value) {
                    if(!empty($value)){
                        array_push($resumeFileLocation, $value);
                    }
                }
            }else{
                $resumeFileLocation = isset($_POST[$id='file']) ? $_POST[$id] : '';
            }

            /* Get the attachment (friendly) file name is there is an attachment uploaded */
            if ($resumeFileLocation != '')
            {
                if(!is_array($resumeFileLocation)){
                    $resumeFileLocation = explode(",",$resumeFileLocation);
                }
                $attachView = array();
                foreach ($resumeFileLocation as $value) {
                    $attachmentHTML = '<div style="height: 20px; background-color: #e0e0e0; margin: 5px 0 0px 0; '
                    . 'padding: 0 3px 0 5px; font-size: 11px;"> '
                    . '<img src="images/parser/attachment.gif" border="0" style="padding-top: 3px;" /> '
                    . 'Attachment: <span style="font-weight: bold;">'.$value.'</span> <span style="font-size: 11px;float: right;"><a href="javascript:void(0);" onclick="removeDocFiles(this);">(Remove)</a></span>'
                    .'<input type="hidden" value="resume" id="resumeRemove">'
                    . '</div> ';
                    array_push($attachView, $attachmentHTML);
                }
                $attachmentHTML = implode(" ",$attachView);
                $uploadFileFullPath = implode(",",$resumeFileLocation);
            }
            else
            {
                $attachmentHTML = '';
            }

            if(isset($_POST[$id='payslip_file'])){
                foreach (explode(",",$_POST[$id='payslip_file']) as $key => $value) {
                    if(!empty($value)){
                        array_push($payslipFileLocation, $value);
                    }
                }
            }else{
                $payslipFileLocation = isset($_POST[$id='payslip_file']) ? $_POST[$id] : '';
            }

            if ($payslipFileLocation != '')
            {
                if(!is_array($payslipFileLocation)){
                    $payslipFileLocation = explode(",",$payslipFileLocation);
                }
                $attachPayslipView = array();
                foreach ($payslipFileLocation as $value) {
                    $attachmentPayslipHTML = '<div style="height: 20px; background-color: #e0e0e0; margin: 5px 0 0px 0; '
                    . 'padding: 0 3px 0 5px; font-size: 11px;"> '
                    . '<img src="images/parser/attachment.gif" border="0" style="padding-top: 3px;" /> '
                    . 'Attachment: <span style="font-weight: bold;">'.$value.'</span> <span style="font-size: 11px;float: right;"><a href="javascript:void(0);" onclick="removeDocFiles(this);">(Remove)</a></span>'
                    .'<input type="hidden" value="payslip" id="payslipRemove">'
                    . '</div> ';
                    array_push($attachPayslipView, $attachmentPayslipHTML);
                }
                $attachmentPayslipHTML = implode(" ",$attachPayslipView);
                $payslipFileFullPath = implode(",",$payslipFileLocation);
            }
            else
            {
                $attachmentPayslipHTML = '';
            }


            if(isset($_POST[$id='previousEmp_file'])){
                foreach (explode(",",$_POST[$id='previousEmp_file']) as $key => $value) {
                    if(!empty($value)){
                        array_push($previousEmpFileLocation, $value);
                    }
                }
            }else{
                $previousEmpFileLocation = isset($_POST[$id='previousEmp_file']) ? $_POST[$id] : '';
            }

            if ($previousEmpFileLocation != '')
            {
                if(!is_array($previousEmpFileLocation)){
                    $previousEmpFileLocation = explode(",",$previousEmpFileLocation);
                }
                $attachPreviousEmpView = array();
                foreach ($previousEmpFileLocation as $value) {
                    $attachmentPreviousEmpHTML = '<div style="height: 20px; background-color: #e0e0e0; margin: 5px 0 0px 0; '
                    . 'padding: 0 3px 0 5px; font-size: 11px;"> '
                    . '<img src="images/parser/attachment.gif" border="0" style="padding-top: 3px;" /> '
                    . 'Attachment: <span style="font-weight: bold;">'.$value.'</span> <span style="font-size: 11px;float: right;"><a href="javascript:void(0);" onclick="removeDocFiles(this);">(Remove)</a></span>'
                    .'<input type="hidden" value="previousEmp" id="previousEmpRemove">'
                    . '</div> ';
                    array_push($attachPreviousEmpView, $attachmentPreviousEmpHTML);
                }
                $attachmentPreviousEmpHTML = implode(" ",$attachPreviousEmpView);
                $previousEmpFileFullPath = implode(",",$previousEmpFileLocation);
            }
            else
            {
                $attachmentPreviousEmpHTML = '';
            }

            
            /* Replace input fields. */
            $template['Content'] = str_replace('<jobid>', $jobID, $template['Content']);
            $template['Content'] = str_replace('<title>', $jobOrderData['title'], $template['Content']);
            $template['Content'] = str_replace('<input-firstName>', 
                '<input name="firstName" id="firstName" class="inputBoxName" value="' . $firstName . '" />'
                .'<input type="hidden" name="candidateID" id="candidateID" value="' . $candidateID . '">',
                $template['Content']);
            $template['Content'] = str_replace('<input-lastName>', '<input name="lastName" id="lastName" class="inputBoxName" value="' . $lastName . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-address>', '<textarea name="address" id="address" class="inputBoxArea">'. $address .'</textarea>', $template['Content']);
            $template['Content'] = str_replace('<input-city>', '<input name="city" id="city" class="inputBoxNormal" value="' . $city . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-state>', '<input name="state" id="state" class="inputBoxNormal" value="' . $state . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-zip>', '<input name="zip" id="zip" class="inputBoxNormal" value="' . $zip . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-phone>', '<input name="phone" id="phone" class="inputBoxNormal" value="' . $phone . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-email>', '<input name="email" id="email" class="inputBoxNormal" value="' . $email . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-phone-home>', '<input name="phoneHome" id="phoneHome" class="inputBoxNormal" value="' . $phoneHome . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-phone-cell>', '<input name="phoneCell" id="phoneCell" class="inputBoxNormal" value="' . $phoneCell . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-best-time-to-call>', '<input name="bestTimeToCall" id="bestTimeToCall" class="inputBoxNormal" value="' . $bestTimeToCall . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-email2>', '<input name="email2" id="email2" class="inputBoxNormal" value="' . $email2 . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-emailconfirm>', '<input name="emailconfirm" id="emailconfirm" class="inputBoxNormal" value="' . $emailconfirm . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-keySkills>', '<input name="keySkills" id="keySkills" class="inputBoxNormal" value="' . $keySkills . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-source>', '<input name="source" id="source" class="inputBoxNormal" value="' . $source . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-employer>', '<input name="employer" id="employer" class="inputBoxNormal" value="' . $employer . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-resumeUpload>', '<input type="file" id="resume" name="file" class="inputBoxFile" />', $template['Content']);
            $template['Content'] = str_replace('<input-resumeUploadPreview>',
                '<input type="hidden" id="applyToJobSubAction" name="applyToJobSubAction" value="" /> '
                . '<input type="hidden" id="file" name="file" value="' . $uploadFileFullPath . '" /> '
                . '<input type="hidden" id="file_path" name="file_path" value="' . $uploadFileLocationPath . '" /> '
                . '<input type="file" id="resumeFile" name="resumeFile[]" class="inputBoxFile" size="30" onchange="resumeLoadCheck();" multiple/> '
                . '<input type="button" id="resumeLoad" name="resumeLoad" value="Upload" onclick="resumeLoadFile();" disabled /><br /> '
                . $attachmentHTML,$template['Content']);
            $template['Content'] = str_replace('<input-extraNotes>', '<textarea name="extraNotes" id="extraNotes" class="inputBoxArea" maxlength="450" onkeyup="mlength=this.getAttribute ? parseInt(this.getAttribute(\'maxlength\')) : \'\'; if (this.getAttribute && this.value.length>(mlength+7)) { alert(\'Sorry, you may only enter \'+mlength+\' characters into the extra notes.\');} if (this.getAttribute && this.value.length>mlength) {this.value=this.value.substring(0,mlength); this.scrollTop = this.scrollHeight;}">'.(isset($_POST[$id='extraNotes'])?$_POST[$id]:'').'</textarea>', $template['Content']);
            $template['Content'] = str_replace('<submit', '<input type="submit" class="submitButton"', $template['Content']);

            /* EEO inputs. */
            $template['Content'] = str_replace('<input-eeo-race>', '<select name="eeorace" id="eeorace" class="inputBoxNormal" />
                                                                        <option value="">----</option>
                                                                        <option value="1">American Indian</option>
                                                                        <option value="2">Asian or Pacific Islander</option>
                                                                        <option value="3">Hispanic or Latino</option>
                                                                        <option value="4">Non-Hispanic Black</option>
                                                                        <option value="5">Non-Hispanic White</option>
                                                                    </select>', $template['Content']);

            $template['Content'] = str_replace('<input-eeo-gender>', '<select name="eeogender" id="eeogender" class="inputBoxNormal" />
                                                                        <option value="">----</option>
                                                                        <option value="m">Male</option>
                                                                        <option value="f">Female</option>
                                                                    </select>', $template['Content']);

            $template['Content'] = str_replace('<input-eeo-veteran>', '<select name="eeoveteran" id="eeoveteran" class="inputBoxNormal" />
                                                                        <option value="">----</option>
                                                                        <option value="1">Male</option>
                                                                        <option value="2">Eligible Veteran</option>
                                                                        <option value="3">Disabled Veteran</option>
                                                                        <option value="4">Eligible and Disabled</option>
                                                                    </select>', $template['Content']);

            $template['Content'] = str_replace('<input-eeo-disability>', '<select name="eeodisability" id="eeodisability" class="inputBoxNormal" />
                                                                        <option value="">----</option>
                                                                        <option value="No">No</option>
                                                                        <option value="Yes">Yes</option>
                                                                    </select>', $template['Content']);
            $template['Content'] = str_replace('<input-erName1>', '<input name="erName1" id="erName1" class="inputBoxName" value="' . $erName1 . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-erDoj1>', '<input name="erDoj1" type="text" id="erDoj1" class="inputBoxName date_picker" value="' . $erDoj1 . '" autocomplete="off" />', $template['Content']);
            $template['Content'] = str_replace('<input-erDor1>', '<input name="erDor1" type="text" id="erDor1" class="inputBoxName date_picker" value="' . $erDor1 . '" autocomplete="off" />', $template['Content']);
            $template['Content'] = str_replace('<input-erName2>', '<input name="erName2" id="erName2" class="inputBoxName" value="' . $erName2 . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-erDoj2>', '<input name="erDoj2" type="text" id="erDoj2" class="inputBoxName date_picker" value="' . $erDoj2 . '" autocomplete="off" />', $template['Content']);
            $template['Content'] = str_replace('<input-erDor2>', '<input name="erDor2" type="text" id="erDor2" class="inputBoxName date_picker" value="' . $erDor2 . '" autocomplete="off" />', $template['Content']);
            $template['Content'] = str_replace('<input-erName3>', '<input name="erName3" id="erName3" class="inputBoxName" value="' . $erName3 . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-erDoj3>', '<input name="erDoj3" type="text" id="erDoj3" class="inputBoxName date_picker" value="' . $erDoj3 . '" autocomplete="off" />', $template['Content']);
            $template['Content'] = str_replace('<input-erDor3>', '<input name="erDor3" type="text" id="erDor3" class="inputBoxName date_picker" value="' . $erDor3 . '" autocomplete="off" />', $template['Content']);
            $template['Content'] = str_replace('<input-currCTC>', '<input name="currCTC" id="currCTC" class="inputBoxName" value="' . $currCTC . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-ectcConfirm>', '<input name="ectcConfirm" id="ectcConfirm" class="inputBoxName" value="' . $ectcConfirm . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-doj>', '<input name="doj" type="text" id="doj" class="inputBoxName date_picker" value="' . $doj . '" autocomplete="off" />', $template['Content']);

            $template['Content'] = str_replace('<input-currentErName>', '<input name="currentErName" id="currentErName" class="inputBoxName" value="' . $currentErName . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-currentErDoj>', '<input name="currentErDoj" type="text" id="currentErDoj" class="inputBoxName date_picker" value="' . $currentErDoj . '" autocomplete="off"/>', $template['Content']);
            $template['Content'] = str_replace('<input-currentErDor>', '<input name="currentErDor" type="text" id="currentErDor" class="inputBoxName date_picker" value="' . $currentErDor . '" autocomplete="off"/>', $template['Content']);
            $template['Content'] = str_replace('<input-board10th>', '<input name="board10th" id="board10th" class="inputBoxName" value="' . $board10th . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-passYr10th>', '<input name="passYr10th" id="passYr10th" class="inputBoxName" value="' . $passYr10th . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-precent10th>', '<input name="precent10th" id="precent10th" class="inputBoxName" value="' . $precent10th . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-board12th>', '<input name="board12th" id="board12th" class="inputBoxName" value="' . $board12th . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-passYr12th>', '<input name="passYr12th" id="passYr12th" class="inputBoxName" value="' . $passYr12th . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-precent12th>', '<input name="precent12th" id="precent12th" class="inputBoxName" value="' . $precent12th . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-insName>', '<input name="insName" id="insName" class="inputBoxName" value="' . $insName . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-degreeCourse>', '<input name="degreeCourse" id="degreeCourse" class="inputBoxName" value="' . $degreeCourse . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-degreePassYr>', '<input name="degreePassYr" id="degreePassYr" class="inputBoxName" value="' . $degreePassYr . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-degreePrecent>', '<input name="degreePrecent" id="degreePrecent" class="inputBoxName" value="' . $degreePrecent . '" />', $template['Content']);

            $template['Content'] = str_replace('<input-payslipUploadPreview>',
                 '<input type="hidden" id="payslip_file" name="payslip_file" value="' . $payslipFileFullPath . '" /> '
                . '<input type="hidden" id="payslipFile_path" name="payslipFile_path" value="' . $uploadPayslipFileLocationPath . '" /> '
                . '<input type="file" id="payslipFile" name="payslipFile[]" class="inputBoxFile" size="30" onchange="payslipLoadCheck();" multiple/> '
                . '<input type="button" id="payslipLoad" name="payslipLoad" value="Upload" onclick="payslipLoadFile();" disabled /><br /> '
                . $attachmentPayslipHTML,$template['Content']);

            $template['Content'] = str_replace('<input-previousEmpUploadPreview>',
                 '<input type="hidden" id="previousEmp_file" name="previousEmp_file" value="' . $previousEmpFileFullPath . '" /> '
                . '<input type="hidden" id="previousEmpFile_path" name="previousEmpFile_path" value="' . $uploadpreviousEmpFileLocationPath . '" /> '
                . '<input type="file" id="previousEmpFile" name="previousEmpFile[]" class="inputBoxFile" size="30" onchange="previousEmpLoadCheck();" multiple/> '
                . '<input type="button" id="previousEmpLoad" name="previousEmpLoad" value="Upload" onclick="previousEmpLoadFile();" disabled /><br /> '
                . $attachmentPreviousEmpHTML,$template['Content']);



            /* Extra field inputs. */
            $candidates = new Candidates($siteID);
            $extraFieldsForCandidates = $candidates->extraFields->getValuesForAdd();

            foreach($extraFieldsForCandidates as $ef)
            {
                if (isset($ef['careersAddHTML']))
                {
                    $template['Content'] = str_replace('<input-extraField-' .urlencode($ef['fieldName']) . '>', $ef['careersAddHTML'], $template['Content']);
                }
                else
                {
                    $template['Content'] = str_replace('<input-extraField-' .urlencode($ef['fieldName']) . '>', $ef['addHTML'], $template['Content']);
                }
            }

            /* This is kindof a hack, but basically, we have to put the
             * validation code / form below inside the <td>, which is contained
             * in the template, as they aren't allowed in <tr>s.
             * NOTE: Continue to use ungreedy matching or this will break!
             */
            if (preg_match('/^.*?(<td.*?>)/i', $template['Content'], $matches))
            {
                $startTD = $matches[1];
                $template['Content'] = preg_replace('/^.*?(?:<td.*?>)/i', '', $template['Content']);
            }
            else
            {
                $startTD = '';
            }

            if (preg_match('/(<\/td>).*?$/i', $template['Content'], $matches))
            {
                $endTD = $matches[1];
                $template['Content'] = preg_replace('/(?:<\/td>).*?$/i', '', $template['Content']);
            }
            else
            {
                $endTD = '';
            }

            if (strpos($template['Content'], '<catsform>') === false)
            {
                $template['Content'] = $startTD . "\n" . $validator . "\n"
                    . '<form name="applyToJobForm" id="applyToJobForm" action="'
                    . CATSUtility::getIndexName()
                    . '?m=careers&amp;p=onApplyToJobOrder" '
                    . 'enctype="multipart/form-data" method="post" onsubmit="return applyValidate();">'
                    . '<input type="hidden" name="ID" value="' . $jobID . '">'
                    . '<input type="hidden" name="candidateID" value="' . $candidateID . '">'
                    . $template['Content'] . '</form>' . "\n" . $endTD;
            }
            else
            {
                $template['Content'] = $startTD . "\n" . $validator . "\n" .
                    str_replace('<catsform>', '<form name="applyToJobForm" id="applyToJobForm" action="'
                        . CATSUtility::getIndexName()
                        . '?m=careers&amp;p=onApplyToJobOrder" '
                        . 'enctype="multipart/form-data" method="post" onsubmit="return applyValidate();">'
                        . '<input type="hidden" name="ID" value="' . $jobID . '">'
                        . '<input type="hidden" name="candidateID" value="' . $candidateID . '">',
                        $template['Content'])
                    . "\n" . $endTD;
            }
        }
        else if ($p == 'onApplyToJobOrder')
        {

            if (!$this->isRequiredIDValid('ID', $_POST))
            {
                // FIXME: Generate valid XHTML error pages. Create an error/fatal method!
                echo '<html><body>This position is invalid or no longer available. Please wait while we direct you to the job list...<script>setTimeout("document.location.href=\'?m=careers&&p=showAll\';", 1500);</script></body></html>';
                die();
            }

            // Check if this is a returning candidate
            $candidateID = isset($_POST['candidateID']) ? intval($_POST['candidateID']) : -1;
            if ($candidateID == -1) $candidateID = false;

            /**
             * Applicant has completed their application, check to see if a questionnaire
             * is tied to this job order. If so, present it.
             */
            $jobID = intval($_POST['ID']);
            $jobOrderData = $jobOrders->get($jobID);
            $questionnaireLib = new Questionnaire($siteID);

            $questionnaireID = $jobOrderData['questionnaireID'];
            if ($questionnaireID)
            {
                $questionnaire = $questionnaireLib->get($questionnaireID);
                if (!is_array($questionnaire) || empty($questionnaire))
                {
                    $questionnaireID = false;
                }
            }

            // Check for postback (if the applicant has completed the questionnaire) or if no questionnaire exists
            if ((isset($_GET[$id='questionnairePostBack']) && $_GET[$id] == '1') || !$questionnaireID)
            {
                // Continue on our merry way
                $this->onApplyToJobOrder($siteID, $candidateID);

                $jobOrderData = $jobOrders->get($jobID);
                if (!isset($jobOrderData['public']) || $jobOrderData['public'] == 0)
                {
                    // FIXME: Generate valid XHTML error pages. Create an error/fatal method!
                    echo '<html><body>This position is no longer available.  Please wait while we direct you to the job list...<script>setTimeout("document.location.href=\'?m=careers&&p=showAll\';", 1500);</script></body></html>';
                    die();
                }

                $template['Content'] = $template['Content - Thanks for your Submission'];
                $template['Content'] = str_replace('<title>', $jobOrderData['title'], $template['Content']);
                $template['Content'] = str_replace('<a-jobDetails>', '<a href="' . CATSUtility::getIndexName() . '?m=careers'.(isset($_GET['templateName']) ? '&templateName='.urlencode($_GET['templateName']) : '').'&p=showJob&ID='.$_POST['ID'].'">', $template['Content']);
            }
            else
            {
                ob_start();

                // get questions/answers
                $questions = $questionnaireLib->getQuestions($questionnaireID);

                $this->_template->assign('isModal', true);
                $this->_template->assign('questionnaireID', $questionnaireID);
                $this->_template->assign('data', $questionnaire);
                $this->_template->assign('questions', $questions);
                $this->_template->display('./modules/settings/CareerPortalQuestionnaireShow.tpl');

                $buffer = ob_get_contents();
                ob_end_clean();

                $formData = '<form name="postQuestionnaire" id="postQuestionnaire" '
                    . 'enctype="multipart/form-data" method="post" action="'
                    . CATSUtility::getIndexName() . '?m=careers&p=onApplyToJobOrder'
                    . '&questionnairePostBack=1">' . "\n"
                    . $this->capturePostData($siteID);

                // Collect all of the post data and resubmit it as hidden elements
                $buffer = $formData . $buffer;

                $template['Content'] = str_replace('<questionnaire>', $buffer, $template['Content - Questionnaire']);
                $template['Content'] = str_replace('<submit', '<input type="submit" class="submitButton"', $template['Content']) . '</form>';
            }
        }
        else if ($p == 'showJob')
        {
            $template['Content'] = $template['Content - Job Details'];

            $jobID = $_GET['ID'];

            /* Filter out non numeric characters */
            for ($i = 0; $i < strlen($jobID); $i++)
            {
                if (ord(substr($jobID, $i, 1)) < ord('0') || ord(substr($jobID, $i, 1)) > ord('9') )
                {
                    $jobID = str_replace(substr($jobID, $i, 1), '*', $jobID);
                }
            }
            $jobID = str_replace('*', '', $jobID);

            /* Force integer */
            $jobID = $jobID * 1;

            $jobOrderData = $jobOrders->get($jobID);
            if (!isset($jobOrderData['public']) || $jobOrderData['public'] == 0)
            {
                echo '<html><body>This position is no longer available.  Please wait while we direct you to the job list...<script>setTimeout("document.location.href=\'?m=careers&&p=showAll\';", 1500);</script></body></html>';
                die ();
            }

            $template['Content'] = str_replace('<registeredCandidate>', $useCookie && $isRegistrationEnabled ? $this->getRegisteredCandidateBlock($siteID, $template['Content - Candidate Registration']) : '', $template['Content']);
            $template['Content'] = str_replace('<title>',        $jobOrderData['title'], $template['Content']);
            $template['Content'] = str_replace('<city>',         $jobOrderData['city'], $template['Content']);
            $template['Content'] = str_replace('<openings>',     $jobOrderData['openings'], $template['Content']);
            $template['Content'] = str_replace('<state>',        $jobOrderData['state'], $template['Content']);
            $template['Content'] = str_replace('<type>',         $jobOrders->typeCodeToString($jobOrderData['type']), $template['Content']);
            $template['Content'] = str_replace('<created>',      $jobOrderData['dateCreated'], $template['Content']);
            $template['Content'] = str_replace('<recruiter>',    $jobOrderData['recruiterFullName'], $template['Content']);
            $template['Content'] = str_replace('<companyName>',  $jobOrderData['companyName'], $template['Content']);
            $template['Content'] = str_replace('<contactName>',  $jobOrderData['contactFullName'], $template['Content']);
            $template['Content'] = str_replace('<contactPhone>', $jobOrderData['contactWorkPhone'], $template['Content']);
            $template['Content'] = str_replace('<contactEmail>', $jobOrderData['contactEmail'], $template['Content']);
            $template['Content'] = str_replace('<description>',  $jobOrderData['description'], $template['Content']);
            $template['Content'] = str_replace('<rate>',         nl2br($jobOrderData['maxRate']), $template['Content']);
            $template['Content'] = str_replace('<salary>',       nl2br($jobOrderData['salary']), $template['Content']);
            $template['Content'] = str_replace('<daysOld>',      nl2br($jobOrderData['daysOld']), $template['Content']);

            $isRegistered = $this->isCandidateRegistered($siteID, $template['Content - Candidate Registration']);

            // If candidate registration is enabled, ask them if they would like to log in first
            if ($isRegistrationEnabled && !$isRegistered)
            {
                $template['Content'] = str_replace('<a-applyToJob', '<a href="'.CATSUtility::getIndexName().'?m=careers'.(isset($_GET['templateName']) ? '&templateName='.urlencode($_GET['templateName']) : '').'&p=candidateRegistration&ID='.$jobID.'"', $template['Content']);
            }
            else
            {
                $template['Content'] = str_replace('<a-applyToJob', '<a href="'.CATSUtility::getIndexName().'?m=careers'.(isset($_GET['templateName']) ? '&templateName='.urlencode($_GET['templateName']) : '').'&p=applyToJob&ID='.$jobID.'"', $template['Content']);
            }

            $jobOrders = new JobOrders($siteID);
            $extraFieldsForJobOrders = $jobOrders->extraFields->getValuesForShow($jobID);

            foreach($extraFieldsForJobOrders as $ef)
            {
                $template['Content'] = str_replace('<extraField-' .urlencode($ef['fieldName']) . '>', $ef['display'], $template['Content']);
            }
        }
        else if ($p == 'searchResults')
        {
        }
        else
        {
            $template['Content'] = $template['Content - Main'];
            $template['Content'] = str_replace('<registeredCandidate>', $useCookie && $isRegistrationEnabled ? $this->getRegisteredCandidateBlock($siteID, $template['Content - Candidate Registration']) : '', $template['Content']);

            $isRegistered = $useCookie ? $this->isCandidateRegistered($siteID, $template['Content - Candidate Registration']) : false;

            if ($isRegistrationEnabled)
            {
                // postback
                if (isset($_GET[$id='postback']) && !strcmp($_GET[$id], 'yes'))
                {
                    $candidate = $this->ProcessCandidateRegistration($siteID, $template['Content - Candidate Registration']);

                    if ($candidate === false)
                    {
                        $isRegistered = false;
                        // Error Message
                        $template['Content'] = str_replace('<registeredLoginTitle>', '<h1 style="color: #800000;">No applicants were '
                            . 'found matching your criteria.</h1><h3>Once you apply to any of our positions, you will automatically '
                            . 'be registered.<br /><br />', $template['Content']
                        );
                    }
                    else
                    {
                        $isRegistered = true;
                    }
                }

                if (!$isRegistered)
                {
                    // If they're not logged on but registration is enabled, give them the opportunity to
                    $content = $template['Content - Candidate Registration'];
                    $js = '';

                    $content = str_replace(array('<registeredLoginTitle>', '</registeredLoginTitle>'), '', $content);
                    $content = str_replace('<applyContent>', '<div style="display: none;">', $content);
                    $content = str_replace('</applyContent>', '</div>', $content);
                    $content = str_replace('<input-submit>', '<input type="submit" id="submitButton" name="submitButton" value="Login" />', $content);
                    $content = str_replace('<input-new>', '<input type="hidden" id="isNewNo" name="isNew" value="no" />', $content);
                    $content = str_replace('<input-registered>', '', $content);
                    $content = str_replace('<input-rememberMe>', '<input type="checkbox" id="rememberMe" name="rememberMe" value="yes" checked />', $content);
                    $content = str_replace('<title>', '', $content);

                    // Process html-ish fields like <input-firstName> into the proper form
                    $content = preg_replace(
                        '/\<input\-([A-Za-z0-9]+)\>/',
                        '<input type="text" class="inputBoxNormal" style="width: 270px;" name="$1" id="$1" onfocus="onFocusFormField(this)" />',
                        $content
                    );

                    // Insert the form block
                    $content = sprintf(
                        '<form name="login" id="login" method="post" onsubmit="return validateCandidateRegistration()" '
                        . 'action="%s?postback=yes">',
                        CATSUtility::getIndexName()
                    ) . $content . '<script>enableFormFields(true);</script></form>';

                    $template['Content'] = str_replace('<registeredLogin>', $content, $template['Content']);
                }
                else
                {
                    $template['Content'] = str_replace('<registeredLoginTitle>', '<div style="display: none;">', $template['Content']);
                    $template['Content'] = str_replace('</registeredLoginTitle>', '</div>', $template['Content']);
                    $template['Content'] = str_replace(array('<registeredCandidate>', '<registeredLogin>'), '', $template['Content']);
                }
            }
            else
            {
                $template['Content'] = str_replace('<registeredLoginTitle>', '<div style="display: none;">', $template['Content']);
                $template['Content'] = str_replace('</registeredLoginTitle>', '</div>', $template['Content']);
                $template['Content'] = str_replace(array('<registeredCandidate>', '<registeredLogin>'), '', $template['Content']);
            }

        }

        $indexName = CATSUtility::getIndexName();
        foreach ($template as $index => $data)
        {
            $template[$index] = str_replace('<a-LinkMain>',   '<a href="'.$indexName.'?m=careers'.(isset($_GET['templateName']) ? '&templateName='.urlencode($_GET['templateName']) : '').'">', $template[$index]);
            $template[$index] = str_replace('<a-LinkSearch>', '<a href="'.$indexName.'?m=careers'.(isset($_GET['templateName']) ? '&templateName='.urlencode($_GET['templateName']) : '').'&amp;p=search">', $template[$index]);
            $template[$index] = str_replace('<a-ListAll>',    '<a href="'.$indexName.'?m=careers'.(isset($_GET['templateName']) ? '&templateName='.urlencode($_GET['templateName']) : '').'&amp;p=showAll">', $template[$index]);
            $template[$index] = str_replace('<siteName>', $siteName, $template[$index]);
            $template[$index] = str_replace('<numberOfOpenPositions>', count($rs), $template[$index]);

            /* Hacks for loading from a nonstandard root directory. */
            if (isset($careerPage) && $careerPage == true)
            {
                $template[$index] = str_replace('"images/', '"../images/', $template[$index]);
                $template[$index] = str_replace('\'images/', '\'../images/', $template[$index]);
                $template[$index] = str_replace('<rssURL>', '../rss/', $template[$index]);
            }
            else
            {
                $template[$index] = str_replace('<rssURL>', 'rss/', $template[$index]);
            }
        }

        $this->_template->assign('template', $template);
        $this->_template->assign('siteName', $siteName);

        if (!eval(Hooks::get('CAREERS_PAGE_BOTTOM'))) return;

        if ($careerPortalSettingsRS['useCATSTemplate'] != '')
        {
            $this->_template->display($careerPortalSettingsRS['useCATSTemplate']);
        }
        else
        {
            $this->_template->display('./modules/careers/Blank.tpl');
        }
    }


    private function _makeApplyValidator($template)
    {
        $validator = '';

        if (strpos($template['Content'], '<input-resumeUploadPreview>') !== false || strpos($template['Content'], '<input-resumeUploadPreview req>') !== false)
        {
            $validator .= '
                if(document.getElementById(\'candidateID\').value == -1){
                    if (document.getElementById(\'file\').value == \'\')
                    {
                        alert(\'Please upload your resume\');
                        document.getElementById(\'file\').focus();
                        return false;
                    }
                }';
        }

        if (strpos($template['Content'], '<input-payslipUploadPreview>') !== false || strpos($template['Content'], '<input-payslipUploadPreview req>') !== false)
        {
            $validator .= '
                if(document.getElementById(\'candidateID\').value == -1){
                    if (document.getElementById(\'payslip_file\').value == \'\')
                    {
                        alert(\'Please upload your current payslips\');
                        document.getElementById(\'payslipFile\').focus();
                        return false;
                    }
                }';
        }

        if (strpos($template['Content'], '<input-previousEmpUploadPreview>') !== false || strpos($template['Content'], '<input-previousEmpUploadPreview req>') !== false)
        {
            $validator .= '
                if(document.getElementById(\'candidateID\').value == -1){
                    if(document.getElementById(\'erName1\').value != \'\'){
                        if (document.getElementById(\'previousEmp_file\').value == \'\')
                        {
                            alert(\'Please upload your previous employer docs\');
                            document.getElementById(\'previousEmp_file\').focus();
                            return false;
                        }    
                    }
                }';
        }


        if (strpos($template['Content'], '<input-firstName>') !== false || strpos($template['Content'], '<input-firstName req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'firstName\').value == \'\')
                {
                    alert(\'Please enter a first name.\');
                    document.getElementById(\'firstName\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-lastName>') !== false || strpos($template['Content'], '<input-lastName req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'lastName\').value == \'\')
                {
                    alert(\'Please enter a last name.\');
                    document.getElementById(\'lastName\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-emailconfirm>') !== false || strpos($template['Content'], '<input-emailconfirm req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'emailconfirm\').value != document.getElementById(\'email\').value)
                {
                    alert(\'Your E-Mail address doesn\\\'t match the retyped E-Mail address.\');
                    document.getElementById(\'emailconfirm\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-email>') !== false || strpos($template['Content'], '<input-email req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'email\').value == \'\')
                {
                    alert(\'Please enter an E-Mail address.\');
                    document.getElementById(\'email\').focus();
                    return false;
                }
                if (document.getElementById(\'email\').value.indexOf(\'@\') == -1 ||
                    document.getElementById(\'email\').value.indexOf(\'.\') == -1)
                {
                    alert(\'Please enter a valid E-Mail address.\');
                    document.getElementById(\'email\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-currentErName>') !== false || strpos($template['Content'], '<input-currentErName req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'currentErName\').value == \'\')
                {
                    alert(\'Please enter the current employer name.\');
                    document.getElementById(\'currentErName\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-currentErDoj>') !== false || strpos($template['Content'], '<input-currentErDoj req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'currentErDoj\').value == \'\')
                {
                    alert(\'Please enter the current employer DOJ.\');
                    document.getElementById(\'currentErDoj\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-erName1>') !== false || strpos($template['Content'], '<input-erName1 req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'erName1\').value == \'\')
                {
                    alert(\'Please enter the employer1 name.\');
                    document.getElementById(\'erName1\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-erDoj1>') !== false || strpos($template['Content'], '<input-erDoj1 req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'erDoj1\').value == \'\')
                {
                    alert(\'Please enter the employer1 DOJ.\');
                    document.getElementById(\'erDoj1\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-erDor1>') !== false || strpos($template['Content'], '<input-erDor1 req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'erDor1\').value == \'\')
                {
                    alert(\'Please enter the employer1 DOR.\');
                    document.getElementById(\'erDor1\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-insName>') !== false || strpos($template['Content'], '<input-insName req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'insName\').value == \'\')
                {
                    alert(\'Please enter the University/Institute.\');
                    document.getElementById(\'insName\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-degreeCourse>') !== false || strpos($template['Content'], '<input-degreeCourse req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'degreeCourse\').value == \'\')
                {
                    alert(\'Please enter the course.\');
                    document.getElementById(\'degreeCourse\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-degreePassYr>') !== false || strpos($template['Content'], '<input-degreePassYr req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'degreePassYr\').value == \'\')
                {
                    alert(\'Please enter the year of passing.\');
                    document.getElementById(\'degreePassYr\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-degreePrecent>') !== false || strpos($template['Content'], '<input-degreePrecent req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'degreePrecent\').value == \'\')
                {
                    alert(\'Please enter the percentage.\');
                    document.getElementById(\'degreePrecent\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-board12th>') !== false || strpos($template['Content'], '<input-board12th req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'board12th\').value == \'\')
                {
                    alert(\'Please enter the 12th board.\');
                    document.getElementById(\'board12th\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-passYr12th>') !== false || strpos($template['Content'], '<input-passYr12th req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'passYr12th\').value == \'\')
                {
                    alert(\'Please enter the 12th year of passing.\');
                    document.getElementById(\'passYr12th\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-precent12th>') !== false || strpos($template['Content'], '<input-precent12th req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'precent12th\').value == \'\')
                {
                    alert(\'Please enter the 12th percentage.\');
                    document.getElementById(\'precent12th\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-board10th>') !== false || strpos($template['Content'], '<input-board10th req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'board10th\').value == \'\')
                {
                    alert(\'Please enter the 10th board.\');
                    document.getElementById(\'board10th\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-passYr10th>') !== false || strpos($template['Content'], '<input-passYr10th req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'passYr10th\').value == \'\')
                {
                    alert(\'Please enter the 10th year of passing.\');
                    document.getElementById(\'passYr10th\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-precent10th>') !== false || strpos($template['Content'], '<input-precent10th req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'precent10th\').value == \'\')
                {
                    alert(\'Please enter the 10th percentage.\');
                    document.getElementById(\'precent10th\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-phone-cell>') !== false || strpos($template['Content'], '<input-phone-cell req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'phoneCell\').value == \'\')
                {
                    alert(\'Please enter a phone number.\');
                    document.getElementById(\'phoneCell\').focus();
                    return false;
                }';
        }


        if (strpos($template['Content'], '<input-address>') !== false || strpos($template['Content'], '<input-address req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'address\').value == \'\')
                {
                    alert(\'Please enter an address.\');
                    document.getElementById(\'address\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-city>') !== false || strpos($template['Content'], '<input-city req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'city\').value == \'\')
                {
                    alert(\'Please enter a city.\');
                    document.getElementById(\'city\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-state>') !== false || strpos($template['Content'], '<input-state req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'state\').value == \'\')
                {
                    alert(\'Please enter a state.\');
                    document.getElementById(\'state\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-zip>') !== false || strpos($template['Content'], '<input-zip req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'zip\').value == \'\')
                {
                    alert(\'Please enter a zip code.\');
                    document.getElementById(\'zip\').focus();
                    return false;
                }';
        }        


        if (strpos($template['Content'], '<input-keySkills req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'keySkills\').value == \'\')
                {
                    alert(\'Please enter some key skills.\');
                    document.getElementById(\'keySkills\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-extraNotes req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'extraNotes\').value == \'\')
                {
                    alert(\'Please enter some extra notes.\');
                    document.getElementById(\'extraNotes\').focus();
                    return false;
                }';
        }

        if(strpos($template['Content'], '<input-erDoj1>') !==false){
            $validator .= '
                var erDor1 = new Date(document.getElementById("erDor1").value).getFullYear();
                var erDoj2 = new Date(document.getElementById("erDoj2").value).getFullYear();
                var erDor2 = new Date(document.getElementById("erDor2").value).getFullYear();
                var erDoj3 = new Date(document.getElementById("erDoj3").value).getFullYear();
                if((erDoj2-erDor1)>=2){
                    alert(\'Please contact your recruiter.\');
                    return false;
                }else{
                    if((erDoj3-erDor2)>=2){
                        alert(\'Please contact your recruiter.\');
                        return false;
                    }
                }
                ';
        }

        $validator = '<script type="text/javascript">function applyValidate() {'
            . $validator . ' return true; }' . "\n" . '</script>';

        return $validator;
    }

    /*
     * Gets HTML content for the job order response array.
     */
    // FIXME: More of this needs to be done in the template. The UI shouldn't generate HTML.
    private function getResultsTable($rs, $settings, $unformatted = false, $parameters = '')
    {
        if ($unformatted)
        {
            $html  = '<table class="sortable">' . "\n";
        }
        else
        {
            $html  = '<table class="sortable" style="width:100%;">' . "\n";
        }
        $html .= '<tr class="rowHeading" align="left">'."\n";
        if ($settings['showCompany'] == 1)
        {
            $html .= '<th nowrap="nowrap">Company</th>';
        }
        if ($settings['showDepartment'] == 1)
        {
            $html .= '<th nowrap="nowrap" align="left">Department</th>';
        }
        $html .= '<th nowrap="nowrap" align="left">Position Title</th>';
        $html .= '<th nowrap="nowrap" align="left">Location</th>';
        $html .= '</tr>'."\n";

        $rowIsEven = false;
        foreach ($rs as $index => $line)
        {
            $rowIsEven = !$rowIsEven;
            if ($rowIsEven)
            {
                $html .= '<tr class="evenTableRow">'."\n";
            }
            else
            {
                $html .= '<tr class="oddTableRow">'."\n";
            }

            if ($settings['showCompany'] == 1)
            {
                $html .= '<td>';
                $html .= htmlspecialchars($line['companyName']);
                $html .= '</td>';
            }

            if ($settings['showDepartment'] == 1)
            {
                $html .= '<td>';
                if ($line['departmentID'] == 0)
                {
                    $html .= 'General';
                }
                else
                {
                    $html .= htmlspecialchars($line['departmentName']);
                }
                $html .= '</td>';
            }

            $html .= '<td>';
            $html .= '<a href="' . CATSUtility::getIndexName() . '?m=careers' . (isset($_GET['templateName']) ? '&amp;templateName=' . urlencode($_GET['templateName']) : '').'&amp;p=showJob&amp;ID=' . $line['jobOrderID'] . '">';
            $html .= htmlspecialchars($line['title']);
            $html .= '</a>';
            $html .= '</td>';

            $html .= '<td>';
            $html .= htmlspecialchars($line['city']) . ', ' . htmlspecialchars($line['state']);
            $html .= '</td>';

            $html .= '</tr>'."\n";
        }
        $html .= '</table>';

        return $html;
    }

    /* Called by Careers Page function to handle the processing of candidate input. */
    private function onApplyToJobOrder($siteID, $candidateID = false)
    {
        echo "candidateID--".$candidateID;
        $jobOrders = new JobOrders($siteID);
        $careerPortalSettings = new CareerPortalSettings($siteID);

        if (!$this->isRequiredIDValid('ID', $_POST))
        {
            CommonErrors::fatal(COMMONERROR_BADINDEX, $this, 'Invalid job order ID.');
            return;
        }

        $jobOrderID = $_POST['ID'];

        $jobOrderData = $jobOrders->get($jobOrderID);
        if (!isset($jobOrderData['public']) || $jobOrderData['public'] == 0)
        {
            CommonErrors::fatal(COMMONERROR_BADINDEX, $this, 'The specified job order could not be found.');
            return;
        }

        $lastName       = $this->getTrimmedInput('lastName', $_POST);
        $middleName     = $this->getTrimmedInput('middleName', $_POST);
        $firstName      = $this->getTrimmedInput('firstName', $_POST);
        $email          = $this->getTrimmedInput('email', $_POST);
        $email2         = $this->getTrimmedInput('email2', $_POST);
        $address        = $this->getTrimmedInput('address', $_POST);
        $city           = $this->getTrimmedInput('city', $_POST);
        $state          = $this->getTrimmedInput('state', $_POST);
        $zip            = $this->getTrimmedInput('zip', $_POST);
        $source         = $this->getTrimmedInput('source', $_POST);
        $phone          = $this->getTrimmedInput('phone', $_POST);
        $phoneHome      = $this->getTrimmedInput('phoneHome', $_POST);
        $phoneCell      = $this->getTrimmedInput('phoneCell', $_POST);
        $bestTimeToCall = $this->getTrimmedInput('bestTimeToCall', $_POST);
        $keySkills      = $this->getTrimmedInput('keySkills', $_POST);
        $extraNotes     = $this->getTrimmedInput('extraNotes', $_POST);
        $employer       = $this->getTrimmedInput('employer', $_POST);

        $gender         = $this->getTrimmedInput('eeogender', $_POST);
        $race           = $this->getTrimmedInput('eeorace', $_POST);
        $veteran        = $this->getTrimmedInput('eeoveteran', $_POST);
        $disability     = $this->getTrimmedInput('eeodisability', $_POST);

        $erName1        = $this->getTrimmedInput('erName1', $_POST);
        $erDoj1         = $this->getTrimmedInput('erDoj1', $_POST);
        $erDor1         = $this->getTrimmedInput('erDor1', $_POST);
        $erName2        = $this->getTrimmedInput('erName2', $_POST);
        $erDoj2         = $this->getTrimmedInput('erDoj2', $_POST);
        $erDor2         = $this->getTrimmedInput('erDor2', $_POST);
        $erName3        = $this->getTrimmedInput('erName3', $_POST);
        $erDoj3         = $this->getTrimmedInput('erDoj3', $_POST);
        $erDor3         = $this->getTrimmedInput('erDor3', $_POST);
        $ectcConfirm    = $this->getTrimmedInput('ectcConfirm', $_POST);
        $doj            = $this->getTrimmedInput('doj', $_POST);

        $currentErName  = $this->getTrimmedInput('currentErName', $_POST);
        $currentErDoj   = $this->getTrimmedInput('currentErDoj', $_POST);
        $currentErDor   = $this->getTrimmedInput('currentErDor', $_POST);
        $board10th      = $this->getTrimmedInput('board10th', $_POST);
        $passYr10th     = $this->getTrimmedInput('passYr10th', $_POST);
        $precent10th    = $this->getTrimmedInput('precent10th', $_POST);
        $board12th      = $this->getTrimmedInput('board12th', $_POST);
        $passYr12th     = $this->getTrimmedInput('passYr12th', $_POST);
        $precent12th    = $this->getTrimmedInput('precent12th', $_POST);
        $insName        = $this->getTrimmedInput('insName', $_POST);
        $degreeCourse   = $this->getTrimmedInput('degreeCourse', $_POST);
        $degreePassYr   = $this->getTrimmedInput('degreePassYr', $_POST);
        $degreePrecent  = $this->getTrimmedInput('degreePrecent', $_POST);
        $currCTC        = $this->getTrimmedInput('currCTC', $_POST);

        if(!empty($erDoj1)){
            $erDoj1 = date_format(date_create($erDoj1),"Y-m-d");
        }
        if(!empty($erDor1)){
            $erDor1 = date_format(date_create($erDor1),"Y-m-d");
        }
        if(!empty($erDoj2)){
            $erDoj2 = date_format(date_create($erDoj2),"Y-m-d");
        }
        if(!empty($erDor2)){
            $erDor2 = date_format(date_create($erDor2),"Y-m-d");
        }
        if(!empty($erDoj3)){
            $erDoj3 = date_format(date_create($erDoj3),"Y-m-d");
        }
        if(!empty($erDor3)){
            $erDor3 = date_format(date_create($erDor3),"Y-m-d");
        }
        if(!empty($doj)){
            $doj = date_format(date_create($doj),"Y-m-d");
        }
        if(!empty($currentErDoj)){
            $currentErDoj = date_format(date_create($currentErDoj),"Y-m-d");
        }
        if(!empty($currentErDor)){
            $currentErDor = date_format(date_create($currentErDor),"Y-m-d");
        }


        if (empty($firstName))
        {
            CommonErrors::fatal(COMMONERROR_MISSINGFIELDS, $this, 'First Name is a required field - please have your administrator edit your templates to include the first name field.');
        }

        if (empty($lastName))
        {
            CommonErrors::fatal(COMMONERROR_MISSINGFIELDS, $this, 'Last Name is a required field - please have your administrator edit your templates to include the last name field.');
        }

        if (empty($email))
        {
            CommonErrors::fatal(COMMONERROR_MISSINGFIELDS, $this, 'E-Mail address is a required field - please have your administrator edit your templates to include the email field.');
        }

        if (empty($source))
        {
            $source = 'Online Careers Website';
        }

        $users = new Users(CATS_ADMIN_SITE);
        $automatedUser = $users->getAutomatedUser();

        /* Find if another user with same e-mail exists. If so, update the user
         * to contain the new information.
         */
        $candidates = new Candidates($siteID);

        /**
         * Save basic information in a cookie in case the site is using registration to
         * process repeated postings, etc.
         */
        $fields = array('firstName', 'lastName', 'email', 'address', 'city', 'state', 'zip', 'phone',
            'phoneHome', 'phoneCell'
        );
        $storedVal = '';
        foreach ($fields as $field)
        {
            eval('$tmp = sprintf(\'"%s"="%s"\', $field, urlencode($' . $field . '));');
            $storedVal .= $tmp;
        }
        // Store their information for an hour only (about 1 session), if they return they can log in again and
        // specify "remember me" which stores it for 2 weeks.
        @setcookie($this->getCareerPortalCookieName($siteID), $storedVal, time()+60*60);


        if ($candidateID !== false)
        {
            $candidate = $candidates->get($candidateID);

            // Candidate exists and registered. Update their profile with new values (if provided)
            $candidates->updateCareerPortal(
                $candidateID, 
                $firstName, 
                $middleName,
                $lastName, 
                $email, 
                $email2,
                $phoneCell, 
                $phone, 
                $address, 
                $city,
                $state, 
                $zip, 
                $keySkills,
                $employer,
                $currCTC,
                $automatedUser['userID'],
                $erName1,
                $erDoj1,
                $erDor1,
                $erName2,
                $erDoj2,
                $erDor2,
                $erName3,
                $erDoj3,
                $erDor3,
                $ectcConfirm,
                $doj,
                $currentErName,
                $currentErDoj,
                $currentErDor,
                $board10th,
                $passYr10th,
                $precent10th,
                $board12th,
                $passYr12th,
                $precent12th,
                $insName,
                $degreeCourse,
                $degreePassYr,
                $degreePrecent
            );

            /* Update extra feilds */
            $candidates->extraFields->setValuesOnEdit($candidateID);
        }
        else
        {
            // Lookup the candidate by e-mail, use that candidate instead if found (but don't update profile)
            $candidateID = $candidates->getIDByEmail($email);
        }
        if ($candidateID === false || $candidateID < 0)
        {
            /* New candidate. */
            $candidateID = $candidates->add(
                $firstName,
                $middleName,
                $lastName,
                $email,
                $email2,
                $phoneHome,
                $phoneCell,
                $phone,
                $address,
                $city,
                $state,
                $zip,
                $source,
                $keySkills,
                '',
                $employer,
                '',
                $currCTC,
                '',
                'Candidate submitted these notes with first application: '
                . "\n\n" . $extraNotes,
                '',
                $bestTimeToCall,
                $automatedUser['userID'],
                $automatedUser['userID'],
                $gender,
                $race,
                $veteran,
                $disability,
                false,
                $erName1,
                $erDoj1,
                $erDor1,
                $erName2,
                $erDoj2,
                $erDor2,
                $erName3,
                $erDoj3,
                $erDor3,
                $ectcConfirm,
                $doj,
                $currentErName,
                $currentErDoj,
                $currentErDor,
                $board10th,
                $passYr10th,
                $precent10th,
                $board12th,
                $passYr12th,
                $precent12th,
                $insName,
                $degreeCourse,
                $degreePassYr,
                $degreePrecent
            );

            /* Update extra fields. */
            $candidates->extraFields->setValuesOnEdit($candidateID);
        }

        // If the candidate was added and a questionnaire exists for the job order
        if ($candidateID > 0 && ($questionnaireID = $jobOrderData['questionnaireID']))
        {
            $questionnaireLib = new Questionnaire($siteID);
            // Perform any actions specified by the questionnaire
            $questionnaireLib->doActions($questionnaireID, $candidateID, $_POST);
        }

        $fileUploaded = false;

        /* Upload resume (no questionnaire) */
        if (isset($_FILES['resumeFile']) && !empty($_FILES['resumeFile']['name'][0]))
        {
            $attachmentCreator = new AttachmentCreator($siteID);
            $attachmentCreator->createFromUpload(
                DATA_ITEM_CANDIDATE, $candidateID, 'resumeFile', false, true,'file'
            );

            if ($attachmentCreator->isError())
            {
                CommonErrors::fatal(COMMONERROR_FILEERROR, $this, $attachmentCreator->getError());
                return;
            }

            $duplicatesOccurred = $attachmentCreator->duplicatesOccurred();

            $isTextExtractionError = $attachmentCreator->isTextExtractionError();
            $textExtractionErrorMessage = $attachmentCreator->getTextExtractionError();

            // FIXME: Show parse errors!

            $fileUploaded = true;
            $resumePath = $attachmentCreator->getNewFilePath();
        }
        /* Upload resume (with questionnaire) */
        else if (isset($_POST['file']) && !empty($_POST['file']))
        {
            $resumePath = '';

            $newFilePath = FileUtility::getUploadFilePath($siteID, 'careerportaladd', $_POST['file']);

            if ($newFilePath !== false)
            {
                $attachmentCreator = new AttachmentCreator($siteID);
                $attachmentCreator->createFromFile(
                    DATA_ITEM_CANDIDATE, $candidateID, $newFilePath, false, '', true, true
                );

                if ($attachmentCreator->isError())
                {
                    CommonErrors::fatal(COMMONERROR_FILEERROR, $this, $attachmentCreator->getError());
                    return;
                }

                $duplicatesOccurred = $attachmentCreator->duplicatesOccurred();

                $isTextExtractionError = $attachmentCreator->isTextExtractionError();
                $textExtractionErrorMessage = $attachmentCreator->getTextExtractionError();

                // FIXME: Show parse errors!

                $fileUploaded = true;
                $resumePath = $attachmentCreator->getNewFilePath();
            }
        }

        /* Upload resume (no questionnaire) */
        if (isset($_FILES['payslipFile']) && !empty($_FILES['resumeFile']['name'][0]))
        {
            $attachmentCreator = new AttachmentCreator($siteID);
            
            $attachmentCreator->createFromUpload(
                DATA_ITEM_CANDIDATE, $candidateID, 'payslipFile', false, true,'payslip_file'
            );

            $attachmentCreator->createFromUpload(
                DATA_ITEM_CANDIDATE, $candidateID, 'previousEmpFile', false, true,'previousEmp_file'
            );

            if ($attachmentCreator->isError())
            {
                CommonErrors::fatal(COMMONERROR_FILEERROR, $this, $attachmentCreator->getError());
                return;
            }

            $duplicatesOccurred = $attachmentCreator->duplicatesOccurred();

            $isTextExtractionError = $attachmentCreator->isTextExtractionError();
            $textExtractionErrorMessage = $attachmentCreator->getTextExtractionError();

            // FIXME: Show parse errors!

            $fileUploaded = true;
            $resumePath = $attachmentCreator->getNewFilePath();
        }

        /* Upload resume (no questionnaire) */
        if (isset($_FILES['previousEmpFile']) && !empty($_FILES['previousEmpFile']['name'][0]))
        {
            $attachmentCreator = new AttachmentCreator($siteID);

            $attachmentCreator->createFromUpload(
                DATA_ITEM_CANDIDATE, $candidateID, 'previousEmpFile', false, true,'previousEmp_file'
            );

            if ($attachmentCreator->isError())
            {
                CommonErrors::fatal(COMMONERROR_FILEERROR, $this, $attachmentCreator->getError());
                return;
            }

            $duplicatesOccurred = $attachmentCreator->duplicatesOccurred();

            $isTextExtractionError = $attachmentCreator->isTextExtractionError();
            $textExtractionErrorMessage = $attachmentCreator->getTextExtractionError();

            // FIXME: Show parse errors!

            $fileUploaded = true;
            $resumePath = $attachmentCreator->getNewFilePath();
        }

        $pipelines = new Pipelines($siteID);
        $activityEntries = new ActivityEntries($siteID);

        /* Is the candidate already in the pipeline for this job order? */
        $rs = $pipelines->get($candidateID, $jobOrderID);
        if (count($rs) == 0)
        {
            /* Attempt to add the candidate to the pipeline. */
            if (!$pipelines->add($candidateID, $jobOrderID))
            {
                CommonErrors::fatal(COMMONERROR_RECORDERROR, $this, 'Failed to add candidate to pipeline.');
            }

            // FIXME: For some reason, pipeline entries like to disappear between
            //        the above add() and this get(). WTF?
            $rs = $pipelines->get($candidateID, $jobOrderID);
            if (isset($rs['candidateJobOrderID']))
                $pipelines->updateRatingValue($rs['candidateJobOrderID'], -1);

            $newApplication = true;
        }
        else
        {
            $newApplication = false;
        }

        /* Build activity note. */
        if (!$newApplication)
        {
            $activityNote = 'User re-applied through candidate portal';
        }
        else
        {
            $activityNote = 'User applied through candidate portal';
        }

        if ($fileUploaded)
        {
            if (!$duplicatesOccurred)
            {
                $activityNote .= ' <span style="font-weight: bold;">and'
                    . ' attached a new resume (<a href="' . $resumePath
                    . '">Download</a>)</span>';
            }
            else
            {
                $activityNote .= ' and attached an existing resume (<a href="'
                    . $resumePath . '">Download</a>)';
            }
        }

		if (!empty($extraNotes))
		{
        	$activityNote .= '; added these notes: ' . $extraNotes;
		}

        /* Add the activity note. */
        $activityID = $activityEntries->add(
            $candidateID,
            DATA_ITEM_CANDIDATE,
            ACTIVITY_OTHER,
            $activityNote,
            $automatedUser['userID'],
            $jobOrderID
        );

        /* Send an E-Mail describing what happened. */
        $emailTemplates = new EmailTemplates($siteID);
        $candidatesEmailTemplateRS = $emailTemplates->getByTag(
            'EMAIL_TEMPLATE_CANDIDATEAPPLY'
        );

        if (!isset($candidatesEmailTemplateRS['textReplaced']) ||
            empty($candidatesEmailTemplateRS['textReplaced']) ||
            $candidatesEmailTemplateRS['disabled'] == 1)
        {
            $candidatesEmailTemplate = '';
        }
        else
        {
            $candidatesEmailTemplate = $candidatesEmailTemplateRS['textReplaced'];
        }

        /* Replace e-mail template variables. */
        /* E-Mail #1 - to candidate */
        $stringsToFind = array(
            '%CANDFIRSTNAME%',
            '%CANDFULLNAME%',
            '%JBODOWNER%',
            '%JBODTITLE%',
            '%JBODCLIENT%'
        );
        $replacementStrings = array(
            $firstName,
            $firstName . ' ' . $lastName,
            $jobOrderData['ownerFullName'],
            $jobOrderData['title'],
            $jobOrderData['companyName']

            //'<a href="http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?')) . '?m=candidates&amp;a=show&amp;candidateID=' . $candidateID . '">'.
              //  'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?')) . '?m=candidates&amp;a=show&amp;candidateID=' . $candidateID . '</a>'
        );
        $candidatesEmailTemplate = str_replace(
            $stringsToFind,
            $replacementStrings,
            $candidatesEmailTemplate
        );

        $emailContents = $candidatesEmailTemplate;

        if (!empty($emailContents))
        {
            $careerPortalSettings->sendEmail(
                $automatedUser['userID'],
                $email,
                CAREERS_CANDIDATEAPPLY_SUBJECT,
                $emailContents
            );
        }

        /* E-Mail #2 - to owner */

        $candidatesEmailTemplateRS = $emailTemplates->getByTag(
            'EMAIL_TEMPLATE_CANDIDATEPORTALNEW'
        );

        if (!isset($candidatesEmailTemplateRS['textReplaced']) ||
            empty($candidatesEmailTemplateRS['textReplaced']) ||
            $candidatesEmailTemplateRS['disabled'] == 1)
        {
            $candidatesEmailTemplate = '';
        }
        else
        {
            $candidatesEmailTemplate = $candidatesEmailTemplateRS['textReplaced'];
        }

        // FIXME: This will break if 'http' is elsewhere in the URL.
        $uri = str_replace('employment', '', $_SERVER['REQUEST_URI']);
        $uri = str_replace('http://', 'http', $uri);
        $uri = str_replace('//', '/', $uri);
        $uri = str_replace('http', 'http://', $uri);
        $uri = str_replace('/careers', '', $uri);

        /* Replace e-mail template variables. */
        $stringsToFind = array(
            '%CANDFIRSTNAME%',
            '%CANDFULLNAME%',
            '%JBODOWNER%',
            '%CANDOWNER%',     // Because the candidate was just added, we assume
            '%JBODTITLE%',     // the candidate owner = job order owner.
            '%JBODCLIENT%',
            '%CANDCATSURL%',
            '%JBODID%',
            '%JBODCATSURL%'
        );
        $replacementStrings = array(
            $firstName,
            $firstName . ' ' . $lastName,
            $jobOrderData['ownerFullName'],
            $jobOrderData['ownerFullName'],
            $jobOrderData['title'],
            $jobOrderData['companyName'],
            '<a href="http://' . $_SERVER['HTTP_HOST'] . substr($uri, 0, strpos($uri, '?')) . '?m=candidates&amp;a=show&amp;candidateID=' . $candidateID . '">'.
                'http://' . $_SERVER['HTTP_HOST'] . substr($uri, 0, strpos($uri, '?')) . '?m=candidates&amp;a=show&amp;candidateID=' . $candidateID . '</a>',
            $jobOrderData['jobOrderID'],
            '<a href="http://' . $_SERVER['HTTP_HOST'] . substr($uri, 0, strpos($uri, '?')) . '?m=joborders&amp;a=show&amp;jobOrderID=' . $jobOrderData['jobOrderID'] . '">'.
                'http://' . $_SERVER['HTTP_HOST'] . substr($uri, 0, strpos($uri, '?')) . '?m=joborders&amp;a=show&amp;jobOrderID=' . $jobOrderData['jobOrderID'] . '</a>',
        );
        $candidatesEmailTemplate = str_replace(
            $stringsToFind,
            $replacementStrings,
            $candidatesEmailTemplate
        );

        $emailContents = $candidatesEmailTemplate;

        if (!empty($emailContents))
        {
            $careerPortalSettings->sendEmail(
                $automatedUser['userID'],
                $jobOrderData['owner_email'],
                CAREERS_OWNERAPPLY_SUBJECT,
                $emailContents
            );


            if ($jobOrderData['owner_email'] != $jobOrderData['recruiter_email'])
            {
                $careerPortalSettings->sendEmail(
                    $automatedUser['userID'],
                    $jobOrderData['recruiter_email'],
                    CAREERS_OWNERAPPLY_SUBJECT,
                    $emailContents
                );
            }
        }
    }

    public function capturePostData($siteID, $ignore = array())
    {
        $hiddenTags = '';

        foreach ($_POST as $name => $value)
        {
            if (in_array($name, $ignore)) continue;
            $hiddenTags .= sprintf('<input type="hidden" name="%s" value="%s" />%s',
                $name,
                htmlspecialchars($value),
                "\n"
            );
        }

        if (($uploadFile = FileUtility::getUploadFileFromPost($siteID, 'careerportaladd', 'file')) !== false)
        {
            $hiddenTags .= sprintf('<input type="hidden" name="file" value="%s" />%s',
                $uploadFile, "\n"
            );
        }

        return $hiddenTags;
    }

    private function isCandidateRegistered($siteID, $template)
    {
        $fields = $this->getCookieFields($siteID);
        return $this->ProcessCandidateRegistration($siteID, $template, $fields, true) ? true : false;
    }

    private function ProcessCandidateRegistration($siteID, $template, $cookieFields = array(), $ignorePost = false)
    {
        $db = DatabaseConnection::getInstance();

        $numMatches = preg_match_all('/\<input\-([A-Za-z0-9]+)\>/', $template, $matches);
        if (!$numMatches) return false;
        $fields = array();

        foreach ($matches[1] as $tag)
        {
            // Default tags, NOT verification fields
            if (!strcasecmp('submit', $tag) || !strcasecmp('new', $tag) || !strcasecmp('registered', $tag) ||
                !strcasecmp('rememberMe', $tag))
            {
                continue;
            }

            // All verification tags MUST exist and be completed (javascript validates this)
            if (!isset($_POST[$tag]) || empty($_POST[$tag]) || $ignorePost)
            {
                // There is no post, but this call might be coming from saved cookie data
                if (!isset($cookieFields[$tag]))
                {
                    // Some fields may have different naming
                    if (!strcmp($tag, 'email') && isset($cookieFields[$id='email1'])) $fields[$tag] = $cookieFields[$id];
                    else if (!strcmp($tag, 'employer') && isset($cookieFields[$id='currentEmployer'])) $fields[$tag] = $cookieFields[$id];
                    else if (!strcmp($tag, 'phone') && isset($cookieFields[$id='phoneWork'])) $fields[$tag] = $cookieFields[$id];
                    else return false;
                }
                else
                {
                    $fields[$tag] = $cookieFields[$tag];
                }
            }
            else
            {
                $fields[$tag] = trim($_POST[$tag]);
            }
        }

        // Get a list of candidate fields to compare against
        $sql = 'SHOW COLUMNS FROM candidate';
        $columns = $db->getAllAssoc($sql);
        for ($i = 0; $i < count($columns); $i++)
        {
            // Convert out of _ notation to camel notation
            $columns[$i]['CamelField'] = str_replace('_', '', $columns[$i]['Field']);
        }

        $verificationFields = 0;
        $sql = 'SELECT candidate_id FROM candidate WHERE ';

        foreach ($fields as $tag => $tagData)
        {
            foreach ($columns as $column => $columnData)
            {
                if (!strcasecmp($columnData['CamelField'], $tag))
                {
                    $sql .= 'LCASE(' . $columnData['Field'] . ') = '
                        . $db->makeQueryString(strtolower($tagData)) . ' AND ';
                    $verificationFields++;
                }
            }
        }

        // There needs to be 1 verification field (equivilant of a "password"), otherwise anyone
        // could change anyone else's candidate information with as little as an e-mail address.
        if ($verificationFields < 1)
        {
            return false;
        }

        $sql .= sprintf('site_id = %d AND (LCASE(email1) = %s OR LCASE(email2) = %s) LIMIT 1',
            $siteID,
            $db->makeQueryString(strtolower($fields['email'])),
            $db->makeQueryString(strtolower($fields['email']))
        );

        $rs = $db->getAssoc($sql);

        if ($db->getNumRows())
        {
            $candidates = new Candidates($siteID);
            $candidate = $candidates->get($rs['candidate_id']);

            // Setup a cookie to remember the user by for the next 2 weeks
            if (isset($_POST['rememberMe']) && !strcasecmp($_POST['rememberMe'], 'yes'))
            {
                $storedVal = '';
                foreach ($fields as $tag => $tagData)
                {
                    $storedVal .= sprintf('"%s"="%s"', urlencode($tag), urlencode($tagData));
                }
                @setcookie($this->getCareerPortalCookieName($siteID), $storedVal, time()+60*60*24*7*2);
            }

            return $candidate;
        }

        return false;
    }

    private function getCareerPortalCookieName($siteID)
    {
        return sprintf('cats%dcw', $siteID);
    }

    private function getCookieFields($siteID)
    {
        $fields = array();

        // Check if there's a cookie to prefill the fields with
        if (isset($_COOKIE[$id=$this->getCareerPortalCookieName($siteID)]))
        {
            if (preg_match_all('/\\\"([^\"]+)\\\"\=\\\"([^\"]*)\\\"/', $_COOKIE[$id], $matches) > 0)
            {
                for ($i = 0; $i < count($matches[1]); $i++)
                {
                    $fields[urldecode($matches[1][$i])] = urldecode($matches[2][$i]);
                    // Some fields have multiple meanings:
                    if (!strcmp($matches[1][$i], 'email1')) $fields['email'] = urldecode($matches[2][$i]);
                    else if (!strcmp($matches[1][$i], 'currentEmployer')) $fields['employer'] = urldecode($matches[2][$i]);
                    else if (!strcmp($matches[1][$i], 'phoneWork')) $fields['phone'] = urldecode($matches[2][$i]);
                }
            }
        }

        return $fields;
    }

    private function getRegisteredCandidateBlock($siteID, $template)
    {
        $fields = $this->getCookieFields($siteID);
        $candidate = $this->ProcessCandidateRegistration($siteID, $template, $fields);

        if ($candidate !== false)
        {
            return sprintf(
                '<form style="padding:0;margin:0;border:0;" name="logout" id="logout" method="post" '
                . 'action="%s%s"><input type="hidden" id="pa" name="pa" value="" />%s<div style="margin: 20px 0 20px 0; '
                . 'line-height: 18px;"> '
                . '<h3 style="font-weight: normal;"><b>Welcome back %s.</b>&nbsp;&nbsp;Not %s? '
                . '<a href="javascript:void(0);" onclick="document.getElementById(\'pa\').value=\'logout\'; '
                . 'document.logout.submit();">Log Out</a>.'
                . '&nbsp;&nbsp;Need to update your information? <a href="javascript:void(0);" onclick="document.getElementById(\'pa\').value=\'updateProfile\'; '
                . 'document.logout.submit();">Update Profile</a>.'
                . '</h3></div>',
                CATSUtility::getIndexName(),
                $_SERVER['QUERY_STRING'] != '' ? '?' . $_SERVER['QUERY_STRING'] : '',
                $this->capturePostData($siteID, array('pa')),
                $candidate['firstName'],
                $candidate['firstName']
            );
        }

        return '';
    }

    private function checkCandidatesData($siteID, $data){
        $db = DatabaseConnection::getInstance();
        $sql = 'SELECT candidate_id FROM candidate WHERE ';
        if($data['isNew'] == 'no'){
            $sql .= sprintf('site_id = %d AND (LCASE(email1) = %s OR LCASE(email2) = %s) AND last_name = %s AND zip = %s LIMIT 1',
                $siteID,
                $db->makeQueryString(strtolower($data['email'])),
                $db->makeQueryString(strtolower($data['email'])),
                $db->makeQueryString(strtolower($data['lastName'])),
                $db->makeQueryString(strtolower($data['zip']))
            );
        }else{
            $sql .= sprintf('site_id = %d AND (LCASE(email1) = %s OR LCASE(email2) = %s) LIMIT 1',
                $siteID,
                $db->makeQueryString(strtolower($data['email'])),
                $db->makeQueryString(strtolower($data['email']))
            );
        }

        $rs = $db->getAssoc($sql);
        if ($db->getNumRows()){
            $status = 'true';
        }else{
            $status = 'false';
        }

        return $status;
    }
}

?>
