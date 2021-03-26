<?php /* $Id: Add.tpl 3746 2007-11-28 20:28:21Z andrew $ */ ?>
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
<?php if ($this->isModal): ?>
    <?php TemplateUtility::printModalHeader('Candidates', array('modules/candidates/validator.js', 'js/addressParser.js', 'js/listEditor.js',  'js/candidate.js', 'js/candidateParser.js','js/datepicker/jquery.min.js','js/datepicker/jquery-ui.min.js'), 'Add New Candidate to This Job Order Pipeline'); ?>
<?php else: ?>
    <?php TemplateUtility::printHeader('Candidates', array('modules/candidates/validator.js', 'js/addressParser.js', 'js/listEditor.js',  'js/candidate.js', 'js/candidateParser.js','js/datepicker/jquery.min.js','js/datepicker/jquery-ui.min.js')); ?>
    <?php TemplateUtility::printHeaderBlock(); ?>
    <?php TemplateUtility::printTabs($this->active, $this->subActive); ?>

    <div id="main">
        <?php TemplateUtility::printQuickSearch(); ?>

        <div id="contents">

            <table>
                <tr>
                    <td width="3%">
                        <img src="images/candidate.gif" width="24" height="24" alt="Candidates" style="border: none; margin-top: 3px;" />&nbsp;
                    </td>
                    <td><h2>Candidates: Add Candidate</h2></td>
                </tr>
            </table>

<?php endif; ?>

            <p class="note<?php if ($this->isModal): ?>Unsized<?php endif; ?>">Basic Information</p>

            <table style="font-weight:bold; border: 1px solid #000; background-color: #ffed1a; padding:5px; display:none; margin-bottom:7px;" width="100%" id="candidateAlreadyInSystemTable">
                <tr>
                    <td class="tdVertical">
                        This profile may already be in the system.&nbsp;&nbsp;Possible duplicate candidate profile:&nbsp;&nbsp;
                        <a href="javascript:void(0);" onclick="window.open('<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=show&amp;candidateID='+candidateIsAlreadyInSystemID);">
                            <img src="images/new_window.gif" border="0" />
                            <img src="images/candidate_small.gif" border="0" />
                            <span id="candidateAlreadyInSystemName"></span>
                        </a>
                    </td>
                </tr>
            </table>

            <?php if ($this->isModal): ?>
                <?php $URI = CATSUtility::getIndexName() . '?m=joborders&amp;a=addCandidateModal&jobOrderID=' . $this->jobOrderID; ?>
            <?php else: ?>
                <?php $URI = CATSUtility::getIndexName() . '?m=candidates&amp;a=add'; ?>
            <?php endif; ?>

            <form name="addCandidateForm" id="addCandidateForm" enctype="multipart/form-data" action="<?php echo($URI); ?>" method="post" onsubmit="return (checkAddForm(document.addCandidateForm) && onSubmitEmailInSystem() && onSubmitPhoneInSystem());" autocomplete="off" enctype="multipart/form-data">
                <?php if ($this->isModal): ?>
                    <input type="hidden" name="jobOrderID" id="jobOrderID" value="<?php echo($this->jobOrderID); ?>" />
                <?php endif; ?>
                <input type="hidden" name="postback" id="postback" value="postback" />
                <input type="hidden" name="documentTempFilePath" id="documentTempFilePath" value="<?php $this->_($this->preassignedFields['documentTempFilePath']) ?>" />

                <table class="editTable">
                    <?php if ($this->isParsingEnabled): ?>
                    <tr>
                        <td class="tdVertical" colspan="2">
                            <img src="images/parser/manual.gif" border="0" />
                        </td>
                        <td class="tdVertical">
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td align="left"><h2>Upload your documents</h2></td>
                                    <td align="right">
                                        &nbsp;
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="tdVertical">
                            <label id="panCardLabel" for="panCard">Pan Card:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" tabindex="2" name="panCard" id="panCard" class="inputbox" style="width: 150px" value="<?php if(isset($this->preassignedFields['panCard'])) $this->_($this->preassignedFields['panCard']); ?>" />&nbsp;*
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical">
                            <label id="firstNameLabel" for="firstName">First Name:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" tabindex="1" name="firstName" id="firstName" class="inputbox" style="width: 150px" value="<?php if(isset($this->preassignedFields['firstName'])) $this->_($this->preassignedFields['firstName']); ?>" />&nbsp;*
                        </td>

                        <td rowspan="12" align="center" valign="top">
                            <?php if ($this->isParsingEnabled): ?>
                                <input type="hidden" name="loadDocument" id="loadDocument" value="" />
                                <input type="hidden" name="parseDocument" id="parseDocument" value="" />
                                <input type="hidden" name="documentTempFile" id="documentTempFile" value="<?php echo (isset($this->preassignedFields['documentTempFile']) ? $this->preassignedFields['documentTempFile'] : ''); ?>" />
                                <table cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td valign="middle" align="right" colspan="2">
                                            <img src="images/parser/arrow.gif" border="0" />
                                            <input type="hidden" name="MAX_FILE_SIZE" VALUE="10000000" />
                                            <input type="file" id="documentFile" name="documentFile[]" onchange="documentFileChange();" size="<?php if ($this->isModal): ?>20<?php else: ?>40<?php endif; ?>" multiple/>
                                            <input type="button" id="documentLoad" value="Upload" onclick="loadDocumentFileContents();" disabled />
                                            &nbsp;
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top" align="left" colspan="2">
                                            <?php if (isset($this->preassignedFields['documentTempFile']) && ($tempFile = $this->preassignedFields['documentTempFile']) != ''): $tempFile = explode(",",$this->preassignedFields['documentTempFile'])?>
                                            <div id="showAttachmentDetails" style="height: 20px; background-color: #e0e0e0; width: 500px; margin: 1px 0 5px 0; padding: 0 3px 0 5px;">
                                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                                    <?php foreach ($tempFile as $key => $value) { ?>
                                                    <tr>
                                                        <td align="left" valign="top" nowrap="nowrap" style="font-size: 11px;">
                                                            <img src="images/parser/attachment.gif" border="0" style="padding-top: 3px;" />
                                                            Attachment: <span style="font-weight: bold;"><?php echo $value; ?></span>
                                                        </td>
                                                        <td align="right" valign="top" nowrap="nowrap" style="font-size: 11px;">
                                                            <a href="javascript:void(0);" onclick="removeDocumentFile();">(remove)</a>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                </table>
                                            </div>
                                            <?php endif; ?>
                                            <!-- <textarea class="inputbox" tabindex="90" name="documentText" id="documentText" rows="5" cols="40" onmousemove="documentCheck();" onchange="documentCheck();" onmousedown="documentCheck();" onkeypress="documentCheck();" style="width: <?php //if ($this->isModal): ?>320<?php //else: ?>500<?php //endif; ?>px; height: 210px; padding: 3px;"><?php //echo $this->contents; ?></textarea>
                                            <br/>
                                            <div style="color: #666666; text-align: center;">
                                            (<b>hint:</b> you may also paste the resume contents)
                                            <br /><br />
                                            Need to upload multiple resumes? <a href="<?php //echo CATSUtility::getIndexName(); ?>?m=import&a=massImport">Click here!</a>
                                            </div> -->
                                        </td>
                                    </tr>
                                </table>
                            <?php else: ?>
                                <?php if (PARSING_ENABLED &&
                                    count($this->parsingStatus) &&
                                    $this->parsingStatus['parseUsed'] >= $this->parsingStatus['parseLimit'] &&
                                    $this->parsingStatus['parseLimit'] >= 0): ?>
                                <a href="http://www.catsone.com/professional" target="_blank">All daily resume imports used. For more, upgrade to CATS professional</a>.
                                <?php endif; ?>
                                <?php $freeformTop = '<p class="freeformtop">Cut and paste freeform address here.</p>'; ?>
                                <?php eval(Hooks::get('CANDIDATE_TEMPLATE_ABOVE_FREEFORM')); ?>
                                <?php echo($freeformTop); ?>

                                <textarea class="inputbox" tabindex="90" name="addressBlock" id="addressBlock" rows="5" cols="40" style="width: 500px; height: 250px;"></textarea>

                                <?php $freeformBottom = '<p class="freeformbottom">Cut and paste freeform address here.</p>'; ?>
                                <?php eval(Hooks::get('CANDIDATE_TEMPLATE_BELOW_FREEFORM')); ?>
                                <?php echo($freeformBottom); ?>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="middleNameLabel" for="middleName">Middle Name:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" tabindex="2" name="middleName" id="middleName" class="inputbox" style="width: 150px" value="<?php if(isset($this->preassignedFields['middleName'])) $this->_($this->preassignedFields['middleName']); ?>" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="lastNameLabel" for="lastName">Last Name:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" tabindex="2" name="lastName" id="lastName" class="inputbox" style="width: 150px" value="<?php if(isset($this->preassignedFields['lastName'])) $this->_($this->preassignedFields['lastName']); ?>" />&nbsp;*
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="emailLabel" for="email1">E-Mail:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" tabindex="3" name="email1" id="email1" class="inputbox" style="width: 150px" value="<?php if(isset($this->preassignedFields['email'])) $this->_($this->preassignedFields['email']); elseif (isset($this->preassignedFields['email1'])) $this->_($this->preassignedFields['email1']); ?>" onchange="checkEmailAlreadyInSystem(this.value);" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="email2Label" for="email2">2nd E-Mail:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" tabindex="4" name="email2" id="email2" class="inputbox" style="width: 150px" value="<?php if (isset($this->preassignedFields['email2'])) $this->_($this->preassignedFields['email2']); ?>" onchange="checkEmailAlreadyInSystem(this.value);" />
                        </td>
                    </tr>

                    <!-- <tr>
                        <td class="tdVertical">
                            <label id="webSiteLabel" for="webSite">Web Site:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" tabindex="5" name="webSite" id="webSite" class="inputbox" style="width: 150px" value="<?php //if (isset($this->preassignedFields['webSite'])) $this->_($this->preassignedFields['webSite']); ?>" />
                        </td>
                    </tr> -->

                    <tr>
                        <td class="tdVertical">
                            <label id="phoneHomeLabel" for="phoneHome">Phone:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" tabindex="6" name="phoneHome" id="phoneHome" class="inputbox" style="width: 150px;" value="<?php if (isset($this->preassignedFields['phoneHome'])) $this->_($this->preassignedFields['phoneHome']); ?>" onchange="checkPhoneAlreadyInSystem(this.value);"  />
                            <?php if ($this->isParsingEnabled): ?>
                                <?php if ($this->parsingStatus['parseLimit'] >= 0 && $this->parsingStatus['parseUsed'] >= $this->parsingStatus['parseLimit']): ?>
                                    &nbsp;
                                <?php else: ?>
                                    <?php if ($this->isModal): ?>&nbsp;&nbsp;<?php else: ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php endif; ?>
                                    <img id="transfer" src="images/parser/transfer<?php echo ($this->contents != '' ? '' : '_grey'); ?>.gif" <?php echo ($this->contents != '' ? 'style="cursor: pointer;"' : ''); ?> border="0" alt="Import Resume" onclick="parseDocumentFileContents();" />
                                <?php endif; ?>
                            <?php else: ?>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="arrowButton" tabindex="91" align="middle" type="button" value="&lt;--" class="arrowbutton" onclick="AddressParser_parse('addressBlock', 'person', 'addressParserIndicator', 'arrowButton'); document.addCandidateForm.panCard.focus();" />
                            <?php endif; ?>
                        </td>
                    </tr>

                    <!-- <tr>
                        <td class="tdVertical">
                            <label id="phoneCellLabel" for="phoneCell">Cell Phone:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" tabindex="7" name="phoneCell" id="phoneCell" class="inputbox" style="width: 150px;" value="<?php //if (isset($this->preassignedFields['phoneCell'])) $this->_($this->preassignedFields['phoneCell']); ?>" onchange="checkPhoneAlreadyInSystem(this.value);" />
                        </td>
                    </tr> -->

                    <tr>
                        <td class="tdVertical">
                            <label id="phoneWorkLabel" for="phoneWork">Alternate Phone:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" tabindex="8" name="phoneWork" id="phoneWork" class="inputbox" style="width: 150px" value="<?php if (isset($this->preassignedFields['phoneWork'])) $this->_($this->preassignedFields['phoneWork']); ?>" onchange="checkPhoneAlreadyInSystem(this.value);" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="addressLabel" for="address">Address:</label>
                        </td>
                        <td class="tdData">
                            <textarea tabindex="9" name="address" id="address" rows="2" cols="40" class="inputbox" style="width: 150px"><?php if(isset($this->preassignedFields['address'])) $this->_($this->preassignedFields['address']); if(isset($this->preassignedFields['address2'])) $this->_("\n" . $this->preassignedFields['address2']); ?></textarea>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/indicator2.gif" id="addressParserIndicator" alt="" style="visibility: hidden; margin-left: 10px;" height="16" width="16" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="cityLabel" for="city">City:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" tabindex="11" name="city" id="city" class="inputbox" style="width: 150px" value="<?php if(isset($this->preassignedFields['city'])) $this->_($this->preassignedFields['city']); ?>" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="stateLabel" for="state">State:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" tabindex="12" name="state" id="state" class="inputbox" style="width: 150px" value="<?php if(isset($this->preassignedFields['state'])) $this->_($this->preassignedFields['state']); ?>" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="zipLabel" for="zip">Postal Code:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" tabindex="13" name="zip" id="zip" class="inputbox" style="width: 150px" value="<?php if(isset($this->preassignedFields['zip'])) $this->_($this->preassignedFields['zip']); ?>" />&nbsp;
                            <input type="button" tabindex="92" onclick="CityState_populate('zip', 'ajaxIndicator');" value="Lookup" />
                            <img src="images/indicator2.gif" alt="AJAX" id="ajaxIndicator" style="vertical-align: middle; visibility: hidden; margin-left: 5px;" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="stateLabel" for="state">Best Time to Call:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" tabindex="13" name="bestTimeToCall" id="bestTimeToCall" class="inputbox" style="width: 150px" value="<?php if(isset($this->preassignedFields['bestTimeToCall'])) $this->_($this->preassignedFields['bestTimeToCall']); ?>" />
                        </td>
                    </tr>

                    <?php $tabIndex = 15; ?>
                </table>

                <p class="note<?php if ($this->isModal): ?>Unsized<?php endif; ?>" style="margin-top: 5px;">Current Employer Details</p>
                <table class="editTable">
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="current_employer_name" for="currentEmployer">Current Employer Name:</label></td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="currentEmployer" id="currentEmployer" class="inputbox" style="width: 150px" value="<?php if(isset($this->preassignedFields['currentEmployer'])) $this->_($this->preassignedFields['currentEmployer']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="current_employer_doj" for="currentErDoj">Current Employer DOJ:</label></td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="currentErDoj" id="currentErDoj" class="inputbox date_picker" style="width: 150px" value="<?php if(isset($this->preassignedFields['currentErDoj'])) $this->_($this->preassignedFields['currentErDoj']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="current_employer_dor" for="currentErDor">Current Employer DOR/LWD:</label></td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="currentErDor" id="currentErDor" class="inputbox date_picker" style="width: 150px" value="<?php if(isset($this->preassignedFields['currentErDor'])) $this->_($this->preassignedFields['currentErDor']); ?>" />
                        </td>
                    </tr>
                </table>

                <p class="note<?php if ($this->isModal): ?>Unsized<?php endif; ?>" style="margin-top: 5px;">Previous Employer Details</p>
                <table class="editTable">
                    <p>Previous Employer 1</p>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="previous_employer1_name" for="erName1">Employer1 Name:</label></td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="erName1" id="erName1" class="inputbox" style="width: 150px" value="<?php if(isset($this->preassignedFields['erName1'])) $this->_($this->preassignedFields['erName1']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="previous_employer1_doj" for="erDoj1">Employer1 DOJ:</label></td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="erDoj1" id="erDoj1" class="inputbox date_picker" style="width: 150px" value="<?php if(isset($this->preassignedFields['erDoj1'])) $this->_($this->preassignedFields['erDoj1']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="previous_employer1_dor" for="erDor1">Employer1 DOR/LWD:</label></td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="erDor1" id="erDor1" class="inputbox date_picker" style="width: 150px" value="<?php if(isset($this->preassignedFields['erDor1'])) $this->_($this->preassignedFields['erDor1']); ?>" />
                        </td>
                    </tr>
                </table>
                <table class="editTable">
                    <p>Previous Employer 2</p>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="previous_employer2_name" for="erName2">Employer2 Name:</label></td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="erName2" id="erName2" class="inputbox" style="width: 150px" value="<?php if(isset($this->preassignedFields['erName2'])) $this->_($this->preassignedFields['erName2']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="previous_employer1_doj" for="erDoj2">Employer2 DOJ:</label></td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="erDoj2" id="erDoj2" class="inputbox date_picker" style="width: 150px" value="<?php if(isset($this->preassignedFields['erDoj2'])) $this->_($this->preassignedFields['erDoj2']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="previous_employer1_dor" for="erDor2">Employer2 DOR/LWD:</label></td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="erDor2" id="erDor2" class="inputbox date_picker" style="width: 150px" value="<?php if(isset($this->preassignedFields['erDor2'])) $this->_($this->preassignedFields['erDor2']); ?>" />
                        </td>
                    </tr>
                </table>
                <table class="editTable">
                    <p>Previous Employer 3</p>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="previous_employer1_name" for="erName3">Employer3 Name:</label></td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="erName3" id="erName3" class="inputbox" style="width: 150px" value="<?php if(isset($this->preassignedFields['erName3'])) $this->_($this->preassignedFields['erName3']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="previous_employer1_doj" for="erDoj3">Employer3 DOJ:</label></td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="erDoj3" id="erDoj3" class="inputbox date_picker" style="width: 150px" value="<?php if(isset($this->preassignedFields['erDoj3'])) $this->_($this->preassignedFields['erDoj3']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="previous_employer1_dor" for="erDor3">Employer3 DOR/LWD:</label></td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="erDor3" id="erDor3" class="inputbox date_picker" style="width: 150px" value="<?php if(isset($this->preassignedFields['erDor3'])) $this->_($this->preassignedFields['erDor3']); ?>" />
                        </td>
                    </tr>
                </table>

                <p class="note<?php if ($this->isModal): ?>Unsized<?php endif; ?>" style="margin-top: 5px;">Education Details</p>
                <table class="editTable">
                    <p>Degree Deatils</p>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="insNameLabel" for="insName">University/Institute:</label></td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="insName" id="insName" class="inputbox" style="width: 150px" value="<?php if(isset($this->preassignedFields['insName'])) $this->_($this->preassignedFields['insName']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="degreeCourseLabel" for="degreeCourse">Course:</label></td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="degreeCourse" id="degreeCourse" class="inputbox" style="width: 150px" value="<?php if(isset($this->preassignedFields['degreeCourse'])) $this->_($this->preassignedFields['degreeCourse']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="degreePassYrLabel" for="degreePassYr">Year Of Passing:</label></td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="degreePassYr" id="degreePassYr" class="inputbox" style="width: 150px" value="<?php if(isset($this->preassignedFields['degreePassYr'])) $this->_($this->preassignedFields['degreePassYr']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="degreePrecentLabel" for="degreePrecent">Percentage:</label></td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="degreePrecent" id="degreePrecent" class="inputbox" style="width: 150px" value="<?php if(isset($this->preassignedFields['degreePrecent'])) $this->_($this->preassignedFields['degreePrecent']); ?>" />
                        </td>
                    </tr>
                </table>
                <table class="editTable">
                    <p>12th Deatils</p>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="board12thLabel" for="board12th">Board:</label></td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="board12th" id="board12th" class="inputbox" style="width: 150px" value="<?php if(isset($this->preassignedFields['board12th'])) $this->_($this->preassignedFields['board12th']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="passYr12thLabel" for="passYr12th">Year Of Passing:</label></td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="passYr12th" id="passYr12th" class="inputbox" style="width: 150px" value="<?php if(isset($this->preassignedFields['passYr12th'])) $this->_($this->preassignedFields['passYr12th']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="precent12thLabel" for="precent12th">Percentage:</label></td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="precent12th" id="precent12th" class="inputbox" style="width: 150px" value="<?php if(isset($this->preassignedFields['precent12th'])) $this->_($this->preassignedFields['precent12th']); ?>" />
                        </td>
                    </tr>
                </table>
                <table class="editTable">
                    <p>10th Deatils</p>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="board10thLabel" for="board10th">Board:</label></td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="board10th" id="board10th" class="inputbox" style="width: 150px" value="<?php if(isset($this->preassignedFields['board10th'])) $this->_($this->preassignedFields['board10th']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="passYr10thLabel" for="passYr10th">Year Of Passing:</label></td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="passYr10th" id="passYr10th" class="inputbox" style="width: 150px" value="<?php if(isset($this->preassignedFields['passYr10th'])) $this->_($this->preassignedFields['passYr10th']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="tdVertical tdVerticalNew"><label id="precent10thLabel" for="precent10th">Percentage:</label></td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="precent10th" id="precent10th" class="inputbox" style="width: 150px" value="<?php if(isset($this->preassignedFields['precent10th'])) $this->_($this->preassignedFields['precent10th']); ?>" />
                        </td>
                    </tr>
                </table>




                <?php if (!$this->isParsingEnabled || $this->associatedAttachment != 0): ?>
                <p class="note<?php if ($this->isModal): ?>Unsized<?php endif; ?>" style="margin-top: 5px;">Resume</p>

                <table class="editTable">
                    <tr>
                        <td class="tdVertical">Resume:</td>
                        <td class="tdData" style="width:320px;">
                            <?php if ($this->associatedAttachment == 0): ?>
                                <nobr> <?php /* FIXME:  remove nobr stuff */ ?>
                                    <?php if (isset($this->overAttachmentQuota)): ?>
                                        <span style="font-size:10px;">(You have already reached your limit of <?php echo(FREE_ACCOUNT_SIZE/1024); ?> MB of attachments, and cannot add additional file attachments without upgrading to CATS Professional Hosted.)<br /></font>Copy and Paste Resume:&nbsp;
                                    <?php else: ?>
                                        <input type="file" id="file" name="file" size="21" tabindex="<?php echo($tabIndex++); ?>" <?php if($this->associatedTextResume !== false): ?>disabled<?php endif; ?> /> &nbsp;
                                    <?php endif; ?>
                                    <a href="javascript:void(0);" onclick="if (document.getElementById('textResumeTD').style.display != '') { document.getElementById('textResumeTD').style.display = ''; document.getElementById('file').disabled=true; } else { document.getElementById('textResumeTD').style.display='none'; document.getElementById('file').disabled = false; }">
                                        <img src="images/package_editors.gif" style="margin:0px; padding:0px;"  class="absmiddle" alt="" border="0" title="Copy / Paste Resume" />
                                    </a>
                                </nobr>
                             <?php else: ?>
                                <a href="<?php echo $this->associatedAttachmentRS['retrievalURL']; ?>">
                                    <img src="<?php $this->_($this->associatedAttachmentRS['attachmentIcon']) ?>" alt="" width="16" height="16" style="border: none;" />
                                </a>
                                <a href="<?php echo $this->associatedAttachmentRS['retrievalURL']; ?>">
                                    <?php $this->_($this->associatedAttachmentRS['originalFilename']) ?>
                                </a>
                                <?php echo($this->associatedAttachmentRS['previewLink']); ?>
                                <input type="hidden" name="associatedAttachment" value="<?php echo($this->associatedAttachment); ?>" />
                            <?php endif; ?>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="3" align="left" valign="top">
                            <input type="hidden" name="textResumeFilename" value="<?php if(isset($this->preassignedFields['textResumeFilename'])) $this->_($this->preassignedFields['textResumeFilename']); else echo('resume.txt'); ?>" />
                            <div id="textResumeTD" <?php if($this->associatedTextResume === false): ?>style="display:none;"<?php endif; ?>>
                                <p class="freeformtop" style="width: 700px;">Cut and paste resume text here.</p>

                                &nbsp;<textarea class="inputbox" tabindex="90" name="textResumeBlock" id="textResumeBlock" rows="5" cols="60" style="width: 700px; height: 300px;"><?php if ($this->associatedTextResume !== false) $this->_($this->associatedTextResume); ?></textarea>

                                <p class="freeformtop" style="width: 700px;">Cut and paste resume text here.</p>
                            </div>
                        </td>
                    </tr>
                </table>
                <?php else: ?>
                <br />
                <?php endif; ?>

                <?php if($this->EEOSettingsRS['enabled'] == 1): ?>
                    <p class="note<?php if ($this->isModal): ?>Unsized<?php endif; ?>" style="margin-top: 5px;">EEO Information</p>
                    <table class="editTable">
                         <?php if ($this->EEOSettingsRS['genderTracking'] == 1): ?>
                             <tr>
                                <td class="tdVertical">
                                    <label id="canRelocateLabel" for="canRelocate">Gender:</label>
                                </td>
                                <td class="tdData">
                                    <select id="gender" name="gender" class="inputbox" style="width:200px;" tabindex="<?php echo($tabIndex++); ?>">
                                        <option selected="selected" value="">----</option>
                                        <option value="M"<?php if (isset($this->preassignedFields['gender']) && $this->preassignedFields['gender'] == 'M') echo ' selected'; ?>>Male</option>
                                        <option value="F"<?php if (isset($this->preassignedFields['gender']) && $this->preassignedFields['gender'] == 'F') echo ' selected'; ?>>Female</option>
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
                                    <select id="race" name="race" class="inputbox" style="width:200px;" tabindex="<?php echo($tabIndex++); ?>">
                                        <option selected="selected" value="">----</option>
                                        <option value="1"<?php if (isset($this->preassignedFields['race']) && $this->preassignedFields['race'] == '1') echo ' selected'; ?>>American Indian</option>
                                        <option value="2"<?php if (isset($this->preassignedFields['race']) && $this->preassignedFields['race'] == '2') echo ' selected'; ?>>Asian or Pacific Islander</option>
                                        <option value="3"<?php if (isset($this->preassignedFields['race']) && $this->preassignedFields['race'] == '3') echo ' selected'; ?>>Hispanic or Latino</option>
                                        <option value="4"<?php if (isset($this->preassignedFields['race']) && $this->preassignedFields['race'] == '4') echo ' selected'; ?>>Non-Hispanic Black</option>
                                        <option value="5"<?php if (isset($this->preassignedFields['race']) && $this->preassignedFields['race'] == '5') echo ' selected'; ?>>Non-Hispanic White</option>
                                    </select>
                                </td>
                             </tr>
                         <?php endif; ?>
                         <?php if ($this->EEOSettingsRS['veteranTracking'] == 1): ?>
                             <tr>
                                <td class="tdVertical">
                                    <label id="canRelocateLabel" for="canRelocate">Veteran Status:</label>
                                </td>
                                <td class="tdData">
                                    <select id="veteran" name="veteran" class="inputbox" style="width:200px;" tabindex="<?php echo($tabIndex++); ?>">
                                        <option selected="selected" value="">----</option>
                                        <option value="1"<?php if (isset($this->preassignedFields['veteran']) && $this->preassignedFields['veteran'] == '1') echo ' selected'; ?>>No</option>
                                        <option value="2"<?php if (isset($this->preassignedFields['veteran']) && $this->preassignedFields['veteran'] == '2') echo ' selected'; ?>>Eligible Veteran</option>
                                        <option valie="3"<?php if (isset($this->preassignedFields['veteran']) && $this->preassignedFields['veteran'] == '3') echo ' selected'; ?>>Disabled Veteran</option>
                                        <option value="4"<?php if (isset($this->preassignedFields['veteran']) && $this->preassignedFields['veteran'] == '4') echo ' selected'; ?>>Eligible and Disabled</option>
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
                                    <select id="disability" name="disability" class="inputbox" style="width:200px;" tabindex="<?php echo($tabIndex++); ?>">
                                        <option selected="selected" value="">----</option>
                                        <option value="No"<?php if (isset($this->preassignedFields['disability']) && $this->preassignedFields['disability'] == 'No') echo ' selected'; ?>>No</option>
                                        <option value="Yes"<?php if (isset($this->preassignedFields['disability']) && $this->preassignedFields['disability'] == 'Yes') echo ' selected'; ?>>Yes</option>
                                    </select>
                                </td>
                             </tr>
                         <?php endif; ?>
                    </table>
                    <br />
                <?php endif; ?>

                <p class="note<?php if ($this->isModal): ?>Unsized<?php endif; ?>" style="margin-top: 5px;">Other</p>
                <table class="editTable">

                    <?php for ($i = 0; $i < count($this->extraFieldRS); $i++): ?>
                        <tr>
                            <td class="tdVertical" id="extraFieldTd<?php echo($i); ?>">
                                <label id="extraFieldLbl<?php echo($i); ?>">
                                    <?php $this->_($this->extraFieldRS[$i]['fieldName']); ?>:
                                </label>
                            </td>
                            <td class="tdData" id="extraFieldData<?php echo($i); ?>">
                                <?php echo($this->extraFieldRS[$i]['addHTML']); ?>
                            </td>
                        </tr>
                    <?php endfor; ?>

                    <tr>
                        <td class="tdVertical">
                            <label id="canRelocateLabel" for="canRelocate">Can Relocate:</label>
                        </td>
                        <td class="tdData">
                            <input type="checkbox" tabindex="<?php echo($tabIndex++); ?>" id="canRelocate" name="canRelocate" value="1"<?php if (isset($this->preassignedFields['canRelocate']) && $this->preassignedFields['canRelocate'] == '1') echo ' checked'; ?> />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="dateAvailableLabel" for="dateAvailable">Date Available:</label>
                        </td>
                        <td class="tdData">
                            <script type="text/javascript">DateInput('dateAvailable', false, 'MM-DD-YY', '', <?php echo($tabIndex++); ?>);</script>

                            <?php /* DateInput()s take up 3 tabindexes. */ ?>
                            <?php $tabIndex += 2; ?>
                        </td>
                    </tr>

                    <!-- <tr>
                        <td class="tdVertical">
                            <label id="currentEmployerLabel" for="currentEmployer">Current Employer:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php //echo($tabIndex++); ?>" name="currentEmployer" id="currentEmployer" class="inputbox" style="width: 150px" value="<?php //if (isset($this->preassignedFields['currentEmployer'])) $this->_($this->preassignedFields['currentEmployer']); ?>" />
                        </td>
                    </tr> -->

                    <tr>
                        <td class="tdVertical">
                            <label id="currentPayLabel" for="currentEmployer">Current Pay:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="currentPay" id="currentPay" class="inputbox" style="width: 150px" value="<?php if (isset($this->preassignedFields['currentPay'])) $this->_($this->preassignedFields['currentPay']); ?>" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="ectcConfirmLabel" for="ectcConfirm">Expected CTC:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="ectcConfirm" id="ectcConfirm" class="inputbox" style="width: 150px" value="<?php if (isset($this->preassignedFields['ectcConfirm'])) $this->_($this->preassignedFields['ectcConfirm']); ?>" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="desiredPayLabel" for="currentEmployer">Desired Pay:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="desiredPay" id="desiredPay" class="inputbox" style="width: 150px" value="<?php if (isset($this->preassignedFields['desiredPay'])) $this->_($this->preassignedFields['desiredPay']); ?>" />
                        </td>
                    </tr>


                    <tr>
                        <td class="tdVertical">
                            <label id="dojLabel" for="doj">Expected DOJ:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="doj" id="doj" class="inputbox date_picker" style="width: 150px" value="<?php if (isset($this->preassignedFields['doj'])) $this->_($this->preassignedFields['doj']); ?>" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="totalExpLabel" for="totalExp">Total Experience:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="totalExp" id="totalExp" class="inputbox" style="width: 150px" value="<?php if (isset($this->preassignedFields['totalExp'])) $this->_($this->preassignedFields['totalExp']); ?>" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="relevantExpLabel" for="relevantExp">Relevant Experience:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="relevantExp" id="relevantExp" class="inputbox" style="width: 150px" value="<?php if (isset($this->preassignedFields['relevantExp'])) $this->_($this->preassignedFields['relevantExp']); ?>" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="currentCityLabel" for="currentCity">Current City:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="currentCity" id="currentCity" class="inputbox" style="width: 150px" value="<?php if (isset($this->preassignedFields['currentCity'])) $this->_($this->preassignedFields['currentCity']); ?>" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="preferredCityLabel" for="preferredCity">Preferred City:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" tabindex="<?php echo($tabIndex++); ?>" name="preferredCity" id="preferredCity" class="inputbox" style="width: 150px" value="<?php if (isset($this->preassignedFields['preferredCity'])) $this->_($this->preassignedFields['preferredCity']); ?>" />
                        </td>
                    </tr>



                    <tr>
                        <td class="tdVertical">
                            <label id="sourceLabel" for="sourceSelect">Source:</label>
                        </td>
                        <td class="tdData">
<?php if ($this->isModal): ?>
                            <select id="sourceSelect" tabindex="<?php echo($tabIndex++); ?>" name="source" class="inputbox" style="width: 150px;">
<?php else: ?>
                            <select id="sourceSelect" tabindex="<?php echo($tabIndex++); ?>" name="source" class="inputbox" style="width: 150px;" onchange="if (this.value == 'edit') { listEditor('Sources', 'sourceSelect', 'sourceCSV', false); this.value = '(none)'; } if (this.value == 'nullline') { this.value = '(none)'; }">
                                <option value="edit">(Edit Sources)</option>
                                <option value="nullline">-------------------------------</option>
<?php endif; ?>
                                    <option value="(none)" <?php if (!isset($this->preassignedFields['source'])): ?>selected="selected"<?php endif; ?>>(None)</option>
                                    <?php if (isset($this->preassignedFields['source'])): ?>
                                        <option value="<?php $this->_($this->_($this->preassignedFields['source'])); ?>" selected="selected"><?php $this->_($this->_($this->preassignedFields['source'])); ?></option>
                                    <?php endif; ?>
                                <?php foreach ($this->sourcesRS AS $index => $source): ?>
                                    <option value="<?php $this->_($source['name']); ?>"><?php $this->_($source['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="sourceCSV" name="sourceCSV" value="<?php $this->_($this->sourcesString); ?>" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="keySkillsLabel" for="keySkills">Key Skills:</label>
                        </td>
                        <td class="tdData">
                            <input type="text" class="inputbox" tabindex="<?php echo($tabIndex++); ?>" name="keySkills" id="keySkills" style="width: 400px;" value="<?php if (isset($this->preassignedFields['keySkills'])) $this->_($this->preassignedFields['keySkills']); ?>" />
                        </td>
                    </tr>

                    <tr>
                        <td class="tdVertical">
                            <label id="notesLabel" for="notes">Misc. Notes:</label>
                        </td>
                        <td class="tdData">
                            <textarea class="inputbox" tabindex="<?php echo($tabIndex++); ?>" name="notes" id="notes" rows="5" cols="40" style="width: 400px;"><?php if (isset($this->preassignedFields['notes'])) $this->_($this->preassignedFields['notes']); ?></textarea>
                        </td>
                    </tr>
                </table>

                <input type="submit" tabindex="<?php echo($tabIndex++); ?>" class="button" value="Add Candidate" />&nbsp;
                <input type="reset"  tabindex="<?php echo($tabIndex++); ?>" class="button" value="Reset" />&nbsp;
                <?php if ($this->isModal): ?>
                    <input type="button" tabindex="<?php echo($tabIndex++); ?>" class="button" value="Back to Search" onclick="javascript:goToURL('<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=considerCandidateSearch&amp;jobOrderID=<?php echo($this->jobOrderID); ?>');" />
                <?php else: ?>
                    <input type="button" tabindex="<?php echo($tabIndex++); ?>" class="button" value="Back to Candidates" onclick="javascript:goToURL('<?php echo(CATSUtility::getIndexName()); ?>?m=candidates');" />
                <?php endif; ?>
            </form>

<script type="text/javascript">
    document.addCandidateForm.panCard.focus();
    <?php if(isset($this->preassignedFields['email']) || isset($this->preassignedFields['email1'])): ?>
        checkEmailAlreadyInSystem(urlDecode("<?php if(isset($this->preassignedFields['email'])) echo(urlencode($this->preassignedFields['email'])); else if(isset($this->preassignedFields['email1'])) echo(urlencode($this->preassignedFields['email1'])); ?>"));
    <?php endif; ?>
    <?php if(isset($this->preassignedFields['email2']) || isset($this->preassignedFields['email2'])): ?>
        checkEmailAlreadyInSystem(urlDecode("<?php if(isset($this->preassignedFields['email2'])) echo(urlencode($this->preassignedFields['email2'])); else if(isset($this->preassignedFields['email2'])) echo(urlencode($this->preassignedFields['email2'])); ?>"));
    <?php endif; ?>
    <?php if(isset($this->preassignedFields['phoneCell']) || isset($this->preassignedFields['phoneCell'])): ?>
        checkEmailAlreadyInSystem(urlDecode("<?php if(isset($this->preassignedFields['phoneCell'])) echo(urlencode($this->preassignedFields['phoneCell'])); else if(isset($this->preassignedFields['phoneCell'])) echo(urlencode($this->preassignedFields['phoneCell'])); ?>"));
    <?php endif; ?>
    <?php if(isset($this->preassignedFields['phoneWork']) || isset($this->preassignedFields['phoneWork'])): ?>
        checkEmailAlreadyInSystem(urlDecode("<?php if(isset($this->preassignedFields['phoneWork'])) echo(urlencode($this->preassignedFields['phoneWork'])); else if(isset($this->preassignedFields['phoneWork'])) echo(urlencode($this->preassignedFields['phoneWork'])); ?>"));
    <?php endif; ?>
    <?php if(isset($this->preassignedFields['phoneHome']) || isset($this->preassignedFields['phoneHome'])): ?>
        checkEmailAlreadyInSystem(urlDecode("<?php if(isset($this->preassignedFields['phoneHome'])) echo(urlencode($this->preassignedFields['phoneHome'])); else if(isset($this->preassignedFields['phoneHome'])) echo(urlencode($this->preassignedFields['phoneHome'])); ?>"));
    <?php endif; ?>
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

<?php if ($this->isModal): ?>
    </body>
</html>
<?php else: ?>
        </div>
    </div>
<?php TemplateUtility::printFooter(); ?>
<?php endif; ?>
