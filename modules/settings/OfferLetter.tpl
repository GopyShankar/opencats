<?php /* $Id: SendEmail.tpl 3078 2007-09-21 20:25:28Z will $ */ ?>
<?php TemplateUtility::printHeader('Settings', array('ckeditor/ckeditor.js', 'modules/candidates/validator.js', 'js/searchSaved.js', 'js/sweetTitles.js', 'js/searchAdvanced.js', 'js/highlightrows.js', 'js/export.js', 'js/suggest.js')); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active, $this->subActive); ?>
<?php 
$refNo = '';
if(isset($this->offerLetterData['refNo'])){
    $refNo = $this->offerLetterData['refNo'];
}else{
    $refNo = ($this->selectedOfferType == 'conditional') ? ATS_REF_NO_PRE_COL : ATS_REF_NO_PRE_OL;
}

?>
<link href='js/datepicker/jquery-ui.css' rel='stylesheet'>
<script src="js/datepicker/jquery.min.js"></script>
<script src="js/datepicker/jquery-ui.min.js"></script>
<style type="text/css">
    input.date_picker {
        background-image: url("images/calendar.gif");
        background-position: right center;
        background-repeat: no-repeat;
    }
</style>
<div id="main">
    <?php TemplateUtility::printQuickSearch(); ?>
    <div id="contents">
        <table>
            <tr>
                <td width="3%">
                    <img src="images/candidate.gif" width="24" height="24" border="0" alt="Settings" style="margin-top: 3px;" />&nbsp;
                </td>
                <td><h2>Settings: Send Offer Letter</h2></td>
            </tr>
        </table>
        <p class="note">Send Offer Letter To Candidates </p>
        <?php if($this->success == true){ ?>
        <br/>
        <span style="font-size: 12pt; font-weight: 900;">
            Your e-mail has been successfully sent to the following recipients:
            <blockquote>
            <?php echo $this->success_to; ?>
            </blockquote>
        <?php } else {
            $tabIndex = 1;
        ?>
        </span>

        <table class="editTable" width="100%">
            <tr>
                <td>
                    <div style="float: left;width: 40%;">
                    <form name="candidateForm" id="candidateForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=settings&amp;a=offerLetter" method="post" autocomplete="off" enctype="multipart/form-data">
                        <input type="hidden" name="postback" id="postback" value="candidateID" />
                        <table>
                            <tr>
                                <td class="tdVertical" style="text-align: right;">
                                    <label id="offerletter_typeLabel" for="offerletter_type">OfferLetter Type</label>
                                </td>
                                <td class="tdData">
                                    <select tabindex="7" id="offerletter_type" name="offerletter_type" style="width: 400px;" onchange="getOfferTypeData()">
                                        <option value="">Select</option>
                                        <option value="interim" <?php echo ($this->selectedOfferType == 'interim')? 'selected': ''; ?> >Interim Offer</option>
                                        <option value="conditional" <?php echo ($this->selectedOfferType == 'conditional')? 'selected': ''; ?> >Conditional Offer</option>
                                        <option value="final" <?php echo ($this->selectedOfferType == 'final')? 'selected': ''; ?> >Final Offer</option>
                                        <option value="fixed_term" <?php echo ($this->selectedOfferType == 'fixed_term')? 'selected': ''; ?> >fixed_term Offer</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="tdVertical" style="text-align: right;">
                                    <label id="candidateLabel" for="type">Candidate:</label>
                                </td>
                                <!-- <td class="tdData">
                                    <select tabindex="7" id="candidateID1" name="candidateID1" class="inputbox" onchange="getCandidatesData()" style="width: 400px;">
                                        <option value="" disabled="" selected="">Selected</option>
                                    <?php //foreach($this->candidatesData as $Data): ?>
                                        <option value="<?php //echo $Data['candidateID'] ?>" 
                                                <?php //if(isset($this->selectedData) && $this->selectedData == $Data['candidateID']) echo('selected'); ?> >
                                                <?php //echo $Data['firstName'] .' '.$Data['lastName'];?>
                                        </option>
                                    <?php //endforeach; ?>
                                    </select>&nbsp;
                                </td> -->
                                <td>
                                    <input type="hidden" name="candidateID" id="candidateID" value="<?php (isset($this->offerLetterData['candidateID']))? $this->_($this->offerLetterData['candidateID']) : ''; ?>" />
                                    <input type="text" name="candidateName" id="candidateName" tabindex="7" class="inputbox" style="width: 400px" onFocus="suggestListActivate('getCandidateNames', 'candidateName', 'CompanyResults', 'candidateID', 'ajaxTextEntryHover', 0, '<?php echo($this->sessionCookie); ?>', 'helpShim');" onchange="setTimeout(function(){ getCandidatesData(); }, 1000);" value="<?php (isset($this->offerLetterData['name']))?$this->_($this->offerLetterData['name']) : ''; ?>" />
                                    <br />
                                    <iframe id="helpShim" src="javascript:void(0);" scrolling="no" frameborder="0" style="position:absolute; display:none;"></iframe>
                                    <div id="CompanyResults" class="ajaxSearchResults" onclick="getCandidatesData()" ></div>
                                </td>
                            </tr>
                        </table>
                    </form>
                    <form name="emailForm" id="emailForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=settings&amp;a=offerLetter" method="post" onsubmit="return checkOfferLetterForm(document.emailForm);" autocomplete="off" enctype="multipart/form-data">
                        <input type="hidden" name="postback" id="postback" value="postback" />
                        <input type="hidden" name="candidateID" value="<?php $this->_($this->selectedData); ?>" />
                        <input type="hidden" name="offerletter_type" value="<?php $this->_($this->selectedOfferType); ?>" />
                        <table>
                            
                            <tr>
                                <td class="tdVertical" style="text-align: right;">
                                    <label id="dojLabel" for="doj">DOJ</label>
                                </td>
                                <td class="tdData">
                                    <input type="text" name="doj" id="doj" class="inputbox date_picker" style="width: 400px;" value="<?php (isset($this->offerLetterData['doj']))?$this->_($this->offerLetterData['doj']):''; ?>" >
                                </td>
                            </tr>
                            <tr>
                                <td class="tdVertical" style="text-align: right;">
                                    <label id="designationLabel" for="designation">Designations</label>
                                </td>
                                <td class="tdData">
                                    <input type="text" name="designation" id="designation" class="inputbox" style="width: 400px;" value="<?php (isset($this->offerLetterData['designation']))?$this->_($this->offerLetterData['designation']):''; ?>" >
                                </td>
                            </tr>
                            <tr>
                                <td class="tdVertical" style="text-align: right;">
                                    <label id="annualLabel" for="annual">Annual</label>
                                </td>
                                <td class="tdData">
                                    <input type="text" name="annual" id="annual" class="inputbox" style="width: 400px;" value="<?php isset($this->offerLetterData['annual'])?$this->_($this->offerLetterData['annual']):''; ?>" >
                                </td>
                            </tr>
                            <tr>
                                <td class="tdVertical" style="text-align: right;">
                                    <label id="validDateLabel" for="validDate">Valid Date</label>
                                </td>
                                <td class="tdData">
                                    <input type="text" name="validDate" id="validDate" class="inputbox date_picker" style="width: 400px;" value="<?php (isset($this->offerLetterData['validDate']))?$this->_($this->offerLetterData['validDate']):''; ?>" >
                                </td>
                            </tr>
                            <tr>
                                <td class="tdVertical" style="text-align: right;">
                                    <label id="referenceLabel" for="refNo">Letter Reference No</label>
                                </td>
                                <td class="tdData">
                                    <input type="text" name="refNo" id="refNo" class="inputbox" style="width: 400px;" value="<?php $this->_($refNo); ?>" <?php echo (isset($this->selectedOfferType) && $this->selectedOfferType == 'conditional') ? 'maxlength=22' : 'maxlength=21'; ?>  >
                                </td>
                            </tr>
                            <tr>
                                <td class="tdVertical" style="text-align: right;">
                                    <label id="offerDateLabel" for="offerDate">Offer Date</label>
                                </td>
                                <td class="tdData">
                                    <input type="text" name="offerDate" id="offerDate" class="inputbox date_picker" style="width: 400px;" value='<?php (isset($this->offerLetterData['offer_date']))?$this->_($this->offerLetterData['offer_date']): $this->_(date("d-M-y")); ?>' >
                                </td>
                            </tr>
                            <tr>
                                <td class="tdVertical" style="text-align: right;">
                                    <label id="validNameLabel" for="cname"> Candidate Name</label>
                                </td>
                                <td class="tdData">
                                    <input type="text" name="cname" id="cname" class="inputbox" style="width: 400px;" value="<?php (isset($this->offerLetterData['name']))?$this->_($this->offerLetterData['name']):''; ?>" >
                                </td>
                            </tr>

                            <tr>
                                <td class="tdVertical" style="text-align: right;">
                                    <label id="salutationLabel" for="salutation">Salutation</label>
                                </td>
                                <td class="tdData">
                                    <input type="radio" name="salutation" id="salutationMr" class="inputbox" value="Mr" <?php echo (isset($this->offerLetterData['salutation']) && $this->offerLetterData['salutation'] == 'Mr')? 'checked':''; ?> > Mr
                                    <input type="radio" name="salutation" id="salutationMs" class="inputbox" value="Ms" <?php echo (isset($this->offerLetterData['salutation']) && $this->offerLetterData['salutation'] == 'Ms')? 'checked':''; ?> > Ms
                                </td>
                            </tr>

                            <tr>
                                <td class="tdVertical" style="text-align: right;">
                                    <label id="fatherNameLabel" for="fatherName">Father Name</label>
                                </td>
                                <td class="tdData">
                                    <input type="text" name="fatherName" id="fatherName" class="inputbox" style="width: 400px;" value="<?php (isset($this->offerLetterData['fatherName']))?$this->_($this->offerLetterData['fatherName']):''; ?>">
                                </td>
                            </tr>

                            <tr>
                                <td class="tdVertical" style="text-align: right;">
                                    <label id="genderLabel" for="gender">Gender</label>
                                </td>
                                <td class="tdData">
                                    <input type="radio" name="gender" id="genderM" class="inputbox" value="M" <?php echo (isset($this->offerLetterData['gender']) && $this->offerLetterData['gender'] == 'M')? 'checked':''; ?> > Male
                                    <input type="radio" name="gender" id="genderF" class="inputbox" value="F" <?php echo (isset($this->offerLetterData['gender']) && $this->offerLetterData['gender'] == 'F')? 'checked':''; ?> > Female
                                </td>
                            </tr>

                            <tr>
                                <td class="tdVertical" style="text-align: right;">
                                    <label id="maritalStatusLabel" for="maritalStatus">Marital Status</label>
                                </td>
                                <td class="tdData">
                                    <input type="radio" name="maritalStatus" id="maritalStatusS" class="inputbox" value="S" <?php echo (isset($this->offerLetterData['maritalStatus']) && $this->offerLetterData['maritalStatus'] == 'S')? 'checked':''; ?> > Single
                                    <input type="radio" name="maritalStatus" id="maritalStatusM" class="inputbox" value="M" <?php echo (isset($this->offerLetterData['maritalStatus']) && $this->offerLetterData['maritalStatus'] == 'M')? 'checked':''; ?> > Married
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="tdVertical" style="text-align: right;">
                                    <label id="validEmailLabel" for="email">Email</label>
                                </td>
                                <td class="tdData">
                                    <input type="text" name="email" id="email" class="inputbox" style="width: 400px;" value="<?php (isset($this->offerLetterData['email']))?$this->_($this->offerLetterData['email']):''; ?>" >
                                </td>
                            </tr>
                            <tr>
                                <td class="tdVertical" style="text-align: right;">
                                    <label id="validNameLabel" for="address"> Address</label>
                                </td>
                                <td class="tdData">
                                    <input type="text" name="address" id="address" class="inputbox" style="width: 400px;" value="<?php (isset($this->offerLetterData['address']))?$this->_($this->offerLetterData['address']):''; ?>" >
                                </td>
                            </tr>
                            <tr>
                                <td class="tdVertical" style="text-align: right;">
                                    <label id="validNameLabel" for="city"> City</label>
                                </td>
                                <td class="tdData">
                                    <input type="text" name="city" id="city" class="inputbox" style="width: 400px;" value="<?php (isset($this->offerLetterData['city']))?$this->_($this->offerLetterData['city']):''; ?>" >
                                </td>
                            </tr>
                            <tr>
                                <td class="tdVertical" style="text-align: right;">
                                    <label id="validNameLabel" for="state"> State</label>
                                </td>
                                <td class="tdData">
                                    <input type="text" name="state" id="state" class="inputbox" style="width: 400px;" value="<?php (isset($this->offerLetterData['state']))?$this->_($this->offerLetterData['state']):''; ?>" >
                                </td>
                            </tr>
                            <tr>
                                <td class="tdVertical" style="text-align: right;">
                                    <label id="validNameLabel" for="zip"> Zip</label>
                                </td>
                                <td class="tdData">
                                    <input type="text" name="zip" id="zip" class="inputbox" style="width: 400px;" value="<?php (isset($this->offerLetterData['zip']))?$this->_($this->offerLetterData['zip']):''; ?>" maxlength=6 >
                                </td>
                            </tr>
                            <tr>
                                <td class="tdVertical" style="text-align: right;">
                                    <label id="insuranceYNLabel" for="insuranceYN">Insurance</label>
                                </td>
                                <td class="tdData">
                                    <?php if(isset($this->offerLetterData['insuranceYN']) && $this->offerLetterData['insuranceYN'] == 'Y'){ ?>
                                    <input type="checkbox" name="insuranceYN" id="insuranceYN" class="inputbox" value="Y" checked >
                                    <?php }else{ ?>
                                    <input type="checkbox" name="insuranceYN" id="insuranceYN" class="inputbox" value="Y" >
                                    <?php } ?>
                                </td>
                            </tr>
                             <tr>
                                <td class="tdVertical" style="text-align: right;">
                                    <label id="gratuityYNLabel" for="gratuityYN">Gratuity</label>
                                </td>
                                <td class="tdData">
                                    <?php if(isset($this->offerLetterData['gratuityYN']) && $this->offerLetterData['gratuityYN'] == 'Y'){ ?>
                                    <input type="checkbox" name="gratuityYN" id="gratuityYN" class="inputbox" value="Y" checked >
                                    <?php }else{ ?>
                                    <input type="checkbox" name="gratuityYN" id="gratuityYN" class="inputbox" value="Y" >
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php if($this->sendMailFlag == 'Y'){ ?>
                            <tr>
                                <td class="tdVertical" style="text-align: right;">
                                    <label id="emailSubjectLabel" for="emailSubject">Subject</label>
                                </td>
                                <td class="tdData">
                                    <input id="emailSubject" tabindex="<?php echo($tabIndex++); ?>" type="text" name="emailSubject" class="inputbox" style="width: 400px;" readonly value="<?php $this->_($this->SubjectMsg); ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="tdVertical" style="text-align: right;">
                                    <label id="emailBodyLabel" for="emailBody">Body</label>
                                </td>
                                <td class="tdData">
                                    <textarea id="emailBody" tabindex="<?php echo($tabIndex++); ?>" name="emailBody" rows="10" cols="90" style="width: 400px;" class="inputbox"><?php $this->_($this->bodyMsg); ?></textarea>
                                </td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td align="center" valign="top" colspan="2">
                                    <input type="submit" tabindex="<?php echo($tabIndex++); ?>" class="button" value="Generate Offer Letter" style="cursor: pointer;" />&nbsp;
                                    <?php if($this->sendMailFlag == 'Y'){ ?>
                                    <input type="reset"  tabindex="<?php echo($tabIndex++); ?>" class="button" value="Send Mail" onclick="sendMail()" style="cursor: pointer;" />&nbsp;
                                    <?php } ?>
                                </td>
                            </tr>
                        </table>
                    </form>
                    </div>
                    <div style="float: right;width: 55%;">
                        <iframe id="pdfView" src="<?php $this->_($this->pdfPath); ?>" width="100%" height="700"></iframe>
                    </div>

			        <script type="text/javascript">
                        <?php if($this->sendMailFlag == 'Y'){ ?>
                        CKEDITOR.replace( 'emailBody' );
                        CKEDITOR.on('instanceReady', function(ev)
                        {
                            var tags = ['p', 'ol', 'ul', 'li']; // etc.

                            for (var key in tags) {
                                ev.editor.dataProcessor.writer.setRules(tags[key],{
                                    indent : false,
                                    breakBeforeOpen : false,
                                    breakAfterOpen : false,
                                    breakBeforeClose : false,
                                    breakAfterClose : false, 
                                });
                            }
                        });
                        <?php } ?>
                        $(document).ready(function() {
                            $( ".date_picker" ).datepicker({
                                dateFormat: 'dd-M-yy',
                            });
                            
                            <?php if($this->messageAlert != ''){ ?>
                            alert('<?php echo $this->messageAlert; ?>');
                            <?php } ?>


                            <?php if($this->sendMailFlag == 'Y'){ ?>
                            CKEDITOR.config.readOnly = true;
                            <?php } ?>
                            <?php if(isset($this->pdfPath) && $this->pdfPath != ''){ ?>
                                setTimeout(function(){
                                    var $frame = document.getElementById('pdfView');
                                    $frame.contentWindow.location.href = $frame.src;    
                                }, 2000)
                                
                            <?php } ?>
                        });
                        function sendMail(){
                            document.emailForm.postback.value = 'sendMail';
                            document.emailForm.submit();
                        }
              		</script>

                </td>
            </tr>
        </table>
        <?php } ?>
    </div>
</div>
<?php TemplateUtility::printFooter(); ?>
