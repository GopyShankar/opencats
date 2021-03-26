<?php /* $Id: Edit.tpl 3695 2007-11-26 22:01:04Z brian $ */ ?>
<link href='js/datepicker/jquery-ui.css' rel='stylesheet'>
<style type="text/css">
    input.date_picker {
        background-image: url("images/calendar.gif");
        background-position: right center;
        background-repeat: no-repeat;
    }
    td.tdVerticalNew {
        width: 160px !important 
    }
</style>
<?php TemplateUtility::printHeader('Candidates', array('modules/candidates/validator.js', 'js/sweetTitles.js', 'js/listEditor.js', 'js/doubleListEditor.js','js/datepicker/jquery.min.js','js/datepicker/jquery-ui.min.js')); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active); ?>
    <div id="main">
        <?php TemplateUtility::printQuickSearch(); ?>

        <div id="contents">
            <table>
                <tr>
                    <td width="3%">
                        <img src="images/candidate.gif" width="24" height="24" border="0" alt="Candidates" style="margin-top: 3px;" />&nbsp;
                    </td>
                    <td><h2>Candidates: Edit</h2></td>
               </tr>
            </table>

            <p class="note">Edit Candidate</p>

            <form name="editCandidateForm" id="editCandidateForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=edit" method="post" onsubmit="return checkEditForm(document.editCandidateForm);" autocomplete="off">
                <input type="hidden" name="postback" id="postback" value="postback" />
                <input type="hidden" id="candidateID" name="candidateID" value="<?php $this->_($this->data['candidateID']); ?>" />

                <table class="editTable" width="700">
                    <tr>
                        <td class="tdVertical" valign="top" style="height: 28px;">
                            <label id="isHotLabel" for="isHot">Active:</label>
                        </td>
                        <td class="tdData" >
                            <input type="checkbox" id="isActive" name="isActive"<?php if ($this->data['isActive'] == 1): ?> checked<?php endif; ?> />
                            <img title="Unchecking this box indicates the candidate is inactive, and will no longer display on the resume search results." src="images/information.gif" alt="" width="16" height="16" />
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="tdVertical">
                            <label id="panCardLabel" for="panCard">Pan Card:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" tabindex="2" name="panCard" id="panCard" class="inputbox" style="width: 150px" value="<?php $this->_($this->data['panCard']); ?>" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="firstNameLabel" for="firstName">First Name:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" class="inputbox" id="firstName" name="firstName" value="<?php $this->_($this->data['firstName']); ?>" style="width: 150px;" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="middleNameLabel" for="middleName">Middle Name:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" class="inputbox" id="middleName" name="middleName" value="<?php $this->_($this->data['middleName']); ?>" style="width: 150px;" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="lastNameLabel" for="lastName">Last Name:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" class="inputbox" id="lastName" name="lastName" value="<?php $this->_($this->data['lastName']); ?>" style="width: 150px;" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="email1Label" for="email1">E-Mail:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" class="inputbox" id="email1" name="email1" value="<?php $this->_($this->data['email1']); ?>" style="width: 150px;" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical">
                            <label id="email2Label" for="email2">2nd E-Mail:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" class="inputbox" id="email2" name="email2" value="<?php $this->_($this->data['email2']); ?>" style="width: 150px;" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="phoneWorkLabel" for="phoneWork">Phone:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" class="inputbox" id="phoneWork" name="phoneWork" value="<?php $this->_($this->data['phoneWork']); ?>" style="width: 150px;" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="phoneHomeLabel" for="phoneHome">Alternate Phone:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" class="inputbox" id="phoneHome" name="phoneHome" value="<?php $this->_($this->data['phoneHome']); ?>" style="width: 150px;" />
                        </td>
                    </tr>

                    <!-- <tr>
                        <td class="tdVertical">
                            <label id="webSiteLabel" for="webSite">Web Site:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" class="inputbox" id="webSite" name="webSite" value="<?php //$this->_($this->data['webSite']); ?>" style="width: 150px" />
                        </td>
                    </tr> -->

                    <tr>
                        <td class="tdVertical">
                            <label id="addressLabel" for="address1">Address:</label>
                        </td>
                        <td class="tdData">
                            <textarea class="inputbox" id="address" name="address" style="width: 150px;"><?php $this->_($this->data['address']); ?></textarea>
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="cityLabel" for="city">City:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" class="inputbox" id="city" name="city" value="<?php $this->_($this->data['city']); ?>" style="width: 150px;" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="stateLabel" for="state">State:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" class="inputbox" id="state" name="state" value="<?php $this->_($this->data['state']); ?>" style="width: 150px;" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="zipLabel" for="zip">Postal Code:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" class="inputbox" id="zip" name="zip" value="<?php $this->_($this->data['zip']); ?>" style="width: 150px;" />
                            <input type="button" class="button" onclick="CityState_populate('zip', 'ajaxIndicator');" value="Lookup" />
                            <img src="images/indicator2.gif" alt="AJAX" id="ajaxIndicator" style="vertical-align: middle; visibility: hidden; margin-left: 5px;" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="canRelocateLabel" for="canRelocate">Best Time To Call:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" class="inputbox" id="bestTimeToCall" name="bestTimeToCall" value="<?php $this->_($this->data['bestTimeToCall']); ?>" style="width: 150px;" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical" valign="top" style="height: 28px;">
                            <label id="isHotLabel" for="isHot">Hot Candidate:</label>
                        </td>
                        <td class="tdData" >
                            <input type="checkbox" id="isHot" name="isHot"<?php if ($this->data['isHot'] == 1): ?> checked<?php endif; ?> />

                        </td>
                    </tr>
                            
                    <tr>
                        <td class="tdVertical">
                            <label id="sourceLabel" for="source">Source:</label>
                        </td>
                        <td class="tdData">
                            <select id="sourceSelect" name="source" class="inputbox" style="width: 150px;" onchange="if (this.value == 'edit') { listEditor('Sources', 'sourceSelect', 'sourceCSV', false, ''); this.value = '(none)'; } if (this.value == 'nullline') { this.value = '(none)'; }">
                                <option value="edit">(Edit Sources)</option>
                                <option value="nullline">-------------------------------</option>
                                <?php if ($this->sourceInRS == false): ?>
                                    <?php if ($this->data['source'] != '(none)'): ?>
                                        <option value="(none)">(None)</option>
                                    <?php endif; ?>
                                    <option value="<?php $this->_($this->data['source']); ?>" selected="selected"><?php $this->_($this->data['source']); ?></option>
                                <?php else: ?>
                                    <option value="(none)">(None)</option>
                                <?php endif; ?>
                                <?php foreach ($this->sourcesRS AS $index => $source): ?>
                                    <option value="<?php $this->_($source['name']); ?>" <?php if ($source['name'] == $this->data['source']): ?>selected<?php endif; ?>><?php $this->_($source['name']); ?></option>
                                <?php endforeach; ?>
                            </select>

                            <input type="hidden" id="sourceCSV" name="sourceCSV" value="<?php $this->_($this->sourcesString); ?>" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="ownerLabel" for="owner">Owner:</label>
                        </td>
                        <td class="tdData">
                            <select id="owner" name="owner" class="inputbox" style="width: 150px;" <?php if (!$this->emailTemplateDisabled): ?>onchange="document.getElementById('divOwnershipChange').style.display=''; <?php if ($this->canEmail): ?>document.getElementById('checkboxOwnershipChange').checked=true;<?php endif; ?>"<?php endif; ?>>
                                <option value="-1">None</option>

                                <?php foreach ($this->usersRS as $rowNumber => $usersData): ?>
                                    <?php if ($this->data['owner'] == $usersData['userID']): ?>
                                        <option selected="selected" value="<?php $this->_($usersData['userID']) ?>"><?php $this->_($usersData['lastName']) ?>, <?php $this->_($usersData['firstName']) ?></option>
                                    <?php else: ?>
                                        <option value="<?php $this->_($usersData['userID']) ?>"><?php $this->_($usersData['lastName']) ?>, <?php $this->_($usersData['firstName']) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>&nbsp;*
                            <div style="display:none;" id="divOwnershipChange">
                                <input type="checkbox" name="ownershipChange" id="checkboxOwnershipChange" <?php if (!$this->canEmail): ?>disabled<?php endif; ?>> E-Mail new owner of change
                            </div>
                        </td>
                    </tr>

                     <tr>
                        <td class="tdVertical">
                            <label id="sourceLabel" for="image">Picture:</label>
                        </td>
                        <td class="tdData">
                            <input type="button" class="button" id="addImage" name="addImage" value="Edit Profile Picture" style="width:150px;" onclick="showPopWin('<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=addEditImage&amp;candidateID=<?php echo($this->candidateID); ?>', 400, 370, null); return false;" />&nbsp;
                        </td>
                    </tr>
                </table>

                <p class="note<?php if ($this->isModal): ?>Unsized<?php endif; ?>" style="margin-top: 5px;">Current Employer Details</p>
                <table class="editTable">
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="current_employer_name" for="currentEmployer">Current Employer Name:</label></td>
                        <td class="tdData">
                            <input type="text" name="currentEmployer" id="currentEmployer" class="inputbox" style="width: 150px" value="<?php $this->_($this->data['currentEmployer']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="current_employer_doj" for="currentErDoj">Current Employer DOJ:</label></td>
                        <td class="tdData">
                            <input type="text" name="currentErDoj" id="currentErDoj" class="inputbox date_picker" style="width: 150px" value="<?php $this->_($this->data['current_er_doj']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="current_employer_dor" for="currentErDor">Current Employer DOR/LWD:</label></td>
                        <td class="tdData">
                            <input type="text" name="currentErDor" id="currentErDor" class="inputbox date_picker" style="width: 150px" value="<?php $this->_($this->data['current_er_dor']); ?>" />
                        </td>
                    </tr>
                </table>

                <p class="note<?php if ($this->isModal): ?>Unsized<?php endif; ?>" style="margin-top: 5px;">Previous Employer Details</p>
                <table class="editTable">
                    <p>Previous Employer 1</p>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="previous_employer1_name" for="erName1">Employer1 Name:</label></td>
                        <td class="tdData">
                            <input type="text" name="erName1" id="erName1" class="inputbox" style="width: 150px" value="<?php $this->_($this->data['employer1_name']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="previous_employer1_doj" for="erDoj1">Employer1 DOJ:</label></td>
                        <td class="tdData">
                            <input type="text" name="erDoj1" id="erDoj1" class="inputbox date_picker" style="width: 150px" value="<?php $this->_($this->data['employer1_doj']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="previous_employer1_dor" for="erDor1">Employer1 DOR/LWD:</label></td>
                        <td class="tdData">
                            <input type="text" name="erDor1" id="erDor1" class="inputbox date_picker" style="width: 150px" value="<?php $this->_($this->data['employer1_dor']); ?>" />
                        </td>
                    </tr>
                </table>
                <table class="editTable">
                    <p>Previous Employer 2</p>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="previous_employer2_name" for="erName2">Employer2 Name:</label></td>
                        <td class="tdData">
                            <input type="text" name="erName2" id="erName2" class="inputbox" style="width: 150px" value="<?php $this->_($this->data['employer2_name']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="previous_employer1_doj" for="erDoj2">Employer2 DOJ:</label></td>
                        <td class="tdData">
                            <input type="text" name="erDoj2" id="erDoj2" class="inputbox date_picker" style="width: 150px" value="<?php $this->_($this->data['employer2_doj']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="previous_employer1_dor" for="erDor2">Employer2 DOR/LWD:</label></td>
                        <td class="tdData">
                            <input type="text" name="erDor2" id="erDor2" class="inputbox date_picker" style="width: 150px" value="<?php $this->_($this->data['employer2_dor']); ?>" />
                        </td>
                    </tr>
                </table>
                <table class="editTable">
                    <p>Previous Employer 3</p>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="previous_employer1_name" for="erName3">Employer3 Name:</label></td>
                        <td class="tdData">
                            <input type="text" name="erName3" id="erName3" class="inputbox" style="width: 150px" value="<?php $this->_($this->data['employer3_name']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="previous_employer1_doj" for="erDoj3">Employer3 DOJ:</label></td>
                        <td class="tdData">
                            <input type="text" name="erDoj3" id="erDoj3" class="inputbox date_picker" style="width: 150px" value="<?php $this->_($this->data['employer3_doj']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="previous_employer1_dor" for="erDor3">Employer3 DOR/LWD:</label></td>
                        <td class="tdData">
                            <input type="text" name="erDor3" id="erDor3" class="inputbox date_picker" style="width: 150px" value="<?php $this->_($this->data['employer3_dor']); ?>" />
                        </td>
                    </tr>
                </table>

                <p class="note<?php if ($this->isModal): ?>Unsized<?php endif; ?>" style="margin-top: 5px;">Education Details</p>
                <table class="editTable">
                    <p>Degree Deatils</p>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="insNameLabel" for="insName">University/Institute:</label></td>
                        <td class="tdData">
                            <input type="text" name="insName" id="insName" class="inputbox" style="width: 150px" value="<?php $this->_($this->data['insName']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="degreeCourseLabel" for="degreeCourse">Course:</label></td>
                        <td class="tdData">
                            <input type="text" name="degreeCourse" id="degreeCourse" class="inputbox" style="width: 150px" value="<?php $this->_($this->data['degreeCourse']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="degreePassYrLabel" for="degreePassYr">Year Of Passing:</label></td>
                        <td class="tdData">
                            <input type="text" name="degreePassYr" id="degreePassYr" class="inputbox" style="width: 150px" value="<?php $this->_($this->data['degreePassYr']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="degreePrecentLabel" for="degreePrecent">Percentage:</label></td>
                        <td class="tdData">
                            <input type="text" name="degreePrecent" id="degreePrecent" class="inputbox" style="width: 150px" value="<?php $this->_($this->data['degreePrecent']); ?>" />
                        </td>
                    </tr>
                </table>
                <table class="editTable">
                    <p>12th Deatils</p>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="board12thLabel" for="board12th">Board:</label></td>
                        <td class="tdData">
                            <input type="text" name="board12th" id="board12th" class="inputbox" style="width: 150px" value="<?php $this->_($this->data['board12th']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="passYr12thLabel" for="passYr12th">Year Of Passing:</label></td>
                        <td class="tdData">
                            <input type="text" name="passYr12th" id="passYr12th" class="inputbox" style="width: 150px" value="<?php $this->_($this->data['passYr12th']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="precent12thLabel" for="precent12th">Percentage:</label></td>
                        <td class="tdData">
                            <input type="text" name="precent12th" id="precent12th" class="inputbox" style="width: 150px" value="<?php $this->_($this->data['precent12th']); ?>" />
                        </td>
                    </tr>
                </table>
                <table class="editTable">
                    <p>10th Deatils</p>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="board10thLabel" for="board10th">Board:</label></td>
                        <td class="tdData">
                            <input type="text" name="board10th" id="board10th" class="inputbox" style="width: 150px" value="<?php $this->_($this->data['board10th']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="passYr10thLabel" for="passYr10th">Year Of Passing:</label></td>
                        <td class="tdData">
                            <input type="text" name="passYr10th" id="passYr10th" class="inputbox" style="width: 150px" value="<?php $this->_($this->data['passYr10th']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="precent10thLabel" for="precent10th">Percentage:</label></td>
                        <td class="tdData">
                            <input type="text" name="precent10th" id="precent10th" class="inputbox" style="width: 150px" value="<?php $this->_($this->data['precent10th']); ?>" />
                        </td>
                    </tr>
                </table>




               
                <?php if($this->EEOSettingsRS['enabled'] == 1): ?>
                    <?php if(!$this->EEOSettingsRS['canSeeEEOInfo']): ?>
                        <table class="editTable" width="700">
                            <tr>
                                <td>
                                    Editing EEO data is disabled.
                                </td>
                            </tr>
                        </tr>
                        <table class="editTable" width="700" style="display:none;">
                    <?php else: ?>
                        <table class="editTable" width="700">
                    <?php endif; ?>               

                         <?php if ($this->EEOSettingsRS['genderTracking'] == 1): ?>
                             <tr>
                                <td class="tdVertical">
                                    <label id="canRelocateLabel" for="canRelocate">Gender:</label>
                                </td>
                                <td class="tdData">
                                    <select id="gender" name="gender" class="inputbox" style="width:200px;">
                                        <option value="">----</option>
                                        <option value="m" <?php if (strtolower($this->data['eeoGender']) == 'm') echo('selected'); ?>>Male</option>
                                        <option value="f" <?php if (strtolower($this->data['eeoGender']) == 'f') echo('selected'); ?>>Female</option>
                                    </select>
                                </td>
                             </tr>
                         <?php endif; ?>
                         <?php if ($this->EEOSettingsRS['ethnicTracking'] == 1): ?>
                             <tr>
                                <td class="tdVertical">
                                    <label id="canRelocateLabel" for="canRelocate">Ethnic Background:</label>
                                </td>
                                <td class="tdData">
                                    <select id="race" name="race" class="inputbox" style="width:200px;">
                                        <option value="">----</option>
                                        <option value="1" <?php if ($this->data['eeoEthnicTypeID'] == 1) echo('selected'); ?>>American Indian</option>
                                        <option value="2" <?php if ($this->data['eeoEthnicTypeID'] == 2) echo('selected'); ?>>Asian or Pacific Islander</option>
                                        <option value="3" <?php if ($this->data['eeoEthnicTypeID'] == 3) echo('selected'); ?>>Hispanic or Latino</option>
                                        <option value="4" <?php if ($this->data['eeoEthnicTypeID'] == 4) echo('selected'); ?>>Non-Hispanic Black</option>
                                        <option value="5" <?php if ($this->data['eeoEthnicTypeID'] == 5) echo('selected'); ?>>Non-Hispanic White</option>
                                    </select>
                                </td>
                             </tr>
                         <?php endif; ?>
                         <?php if ($this->EEOSettingsRS['veteranTracking'] == 1): ?>
                             <tr>
                                <td class="tdVertical">
                                    <label id="canRelocateLabel" for="canRelocate">Vetran Status:</label>
                                </td>
                                <td class="tdData">
                                    <select id="veteran" name="veteran" class="inputbox" style="width:200px;">
                                        <option value="">----</option>
                                        <option value="1" <?php if ($this->data['eeoVeteranTypeID'] == 1) echo('selected'); ?>>No</option>
                                        <option value="2" <?php if ($this->data['eeoVeteranTypeID'] == 2) echo('selected'); ?>>Eligible Veteran</option>
                                        <option value="3" <?php if ($this->data['eeoVeteranTypeID'] == 3) echo('selected'); ?>>Disabled Veteran</option>
                                        <option value="4" <?php if ($this->data['eeoVeteranTypeID'] == 4) echo('selected'); ?>>Eligible and Disabled</option>
                                    </select>
                                </td>
                             </tr>
                         <?php endif; ?>
                         <?php if ($this->EEOSettingsRS['disabilityTracking'] == 1): ?>
                             <tr>
                                <td class="tdVertical">
                                    <label id="canRelocateLabel" for="canRelocate">Disability Status:</label>
                                </td>
                                <td class="tdData">
                                    <select id="disability" name="disability" class="inputbox" style="width:200px;">
                                        <option value="">----</option>
                                        <option value="No" <?php if ($this->data['eeoDisabilityStatus'] == 'No') echo('selected'); ?>>No</option>
                                        <option value="Yes" <?php if ($this->data['eeoDisabilityStatus'] == 'Yes') echo('selected'); ?>>Yes</option>
                                    </select>
                                </td>
                             </tr>
                         <?php endif; ?>
                    </table>
                <?php endif; ?>

                <table class="editTable" width="700">
                    
                    <?php for ($i = 0; $i < count($this->extraFieldRS); $i++): ?>
                        <tr>
                            <td class="tdVertical" id="extraFieldTd<?php echo($i); ?>">
                                <label id="extraFieldLbl<?php echo($i); ?>">
                                    <?php $this->_($this->extraFieldRS[$i]['fieldName']); ?>:
                                </label>
                            </td>
                            <td class="tdData" id="extraFieldData<?php echo($i); ?>">
                                <?php echo($this->extraFieldRS[$i]['editHTML']); ?>
                            </td>
                        </tr>
                    <?php endfor; ?>

                    <tr>
                        <td class="tdVertical">
                            <label id="canRelocateLabel" for="canRelocate">Can Relocate:</label>
                        </td>
                        <td class="tdData">
                            <input type="checkbox" id="canRelocate" name="canRelocate"<?php if ($this->data['canRelocate'] == 1): ?> checked<?php endif; ?> />
                        </td>
                    </tr>


                    <tr>
                        <td class="tdVertical">
                            <label id="dateAvailableLabel" for="dateAvailable">Date Available:</label>
                        </td>
                        <td class="tdData">
                            <?php if (!empty($this->data['dateAvailable'])): ?>
                                <script type="text/javascript">DateInput('dateAvailable', false, 'MM-DD-YY', '<?php echo($this->data['dateAvailableMDY']); ?>', -1);</script>
                            <?php else: ?>
                                <script type="text/javascript">DateInput('dateAvailable', false, 'MM-DD-YY', '', -1);</script>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <!-- <tr>
                        <td class="tdVertical">
                            <label id="currentEmployerLabel" for="currentEmployer">Current Employer:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" class="inputbox" id="currentEmployer" name="currentEmployer" value="<?php //$this->_($this->data['currentEmployer']); ?>" style="width: 150px;" />
                        </td>
                    </tr> -->

                    <tr>
                        <td class="tdVertical">
                            <label id="currentPayLabel" for="currentEmployer">Current Pay:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" name="currentPay" id="currentPay" value="<?php $this->_($this->data['currentPay']); ?>" class="inputbox" style="width: 150px" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="ectcConfirmLabel" for="ectcConfirm">Expected CTC:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="ectcConfirm" id="ectcConfirm" class="inputbox" style="width: 150px" value="<?php $this->_($this->preassignedFields['ectcConfirm']); ?>" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="desiredPayLabel" for="currentEmployer">Desired Pay:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" name="desiredPay" id="desiredPay" value="<?php $this->_($this->data['desiredPay']); ?>" class="inputbox" style="width: 150px" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="dojLabel" for="doj">Expected DOJ:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="doj" id="doj" class="inputbox date_picker" style="width: 150px" value="<?php $this->_($this->data['doj']); ?>" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="totalExpLabel" for="totalExp">Total Experience:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="totalExp" id="totalExp" class="inputbox" style="width: 150px" value="<?php $this->_($this->data['totalExp']); ?>" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="relevantExpLabel" for="relevantExp">Relevant Experience:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="relevantExp" id="relevantExp" class="inputbox" style="width: 150px" value="<?php $this->_($this->data['relevantExp']); ?>" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="currentCityLabel" for="currentCity">Current City:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="currentCity" id="currentCity" class="inputbox" style="width: 150px" value="<?php $this->_($this->data['currentCity']); ?>" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="preferredCityLabel" for="preferredCity">Preferred City:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="preferredCity" id="preferredCity" class="inputbox" style="width: 150px" value="<?php $this->_($this->data['preferredCity']); ?>" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="keySkillsLabel" for="keySkills">Key Skills:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" class="inputbox" id="keySkills" name="keySkills" value="<?php $this->_($this->data['keySkills']); ?>" style="width: 400px;" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="notesLabel" for="notes">Misc. Notes:</label>
                        </td>
                        <td class="tdData">
                            <textarea class="inputbox" id="notes" name="notes" rows="5" style="width: 400px;"><?php $this->_($this->data['notes']); ?></textarea>
                        </td>
                    </tr>
                </table>
                <input type="submit" class="button" name="submit" id="submit" value="Save" />&nbsp;
                <input type="reset"  class="button" name="reset"  id="reset"  value="Reset" onclick="resetFormForeign();" />&nbsp;
                <input type="button" class="button" name="back"   id="back"   value="Back to Details" onclick="javascript:goToURL('<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=show&amp;candidateID=<?php echo($this->candidateID); ?>');" />
            </form>

            <script type="text/javascript">
                document.editCandidateForm.panCard.focus();
            </script>
            <script> 
            $(document).ready(function() { 

                $(function() { 
                    $( ".date_picker" ).datepicker({
                        dateFormat: 'dd-M-yy',
                    }); 
                }); 
            }) 
            </script>
        </div>
    </div>
<?php TemplateUtility::printFooter(); ?>
