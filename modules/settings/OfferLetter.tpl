<?php /* $Id: SendEmail.tpl 3078 2007-09-21 20:25:28Z will $ */ ?>
<?php TemplateUtility::printHeader('Settings', array('ckeditor/ckeditor.js', 'modules/candidates/validator.js', 'js/searchSaved.js', 'js/sweetTitles.js', 'js/searchAdvanced.js', 'js/highlightrows.js', 'js/export.js')); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active, $this->subActive); ?>
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
            $emailTo = '';
            foreach($this->recipients as $recipient){
                if(strlen($recipient['email1']) > 0)
                {
                    $eml = $recipient['email1'];
                }
                else if(strlen($recipient['email2']) > 0)
                {
                    $eml = $recipient['email2'];
                }
                else
                {
                    $eml = '';
                }
                if($eml != '')
                {
                    if($emailTo != '')
                    {
                        $emailTo .= ', ';
                    }
                    $emailTo .= $eml;
                }
            }
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
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="tdVertical" style="text-align: right;">
                                    <label id="candidateLabel" for="type">Candidate:</label>
                                </td>
                                <td class="tdData">
                                    <select tabindex="7" id="candidateID" name="candidateID" class="inputbox" onchange="getCandidatesOfferData()" style="width: 400px;">
                                        <option value="" disabled="" selected="">Selected</option>
                                    <?php foreach($this->candidatesData as $Data): ?>
                                        <option value="<?php echo $Data['candidateID'] ?>" 
                                                <?php if(isset($this->selectedData) && $this->selectedData == $Data['candidateID']) echo('selected'); ?> >
                                                <?php echo $Data['firstName'] .' '.$Data['lastName'];?>
                                        </option>
                                    <?php endforeach; ?>
                                    </select>&nbsp;
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
                                    <input type="text" name="doj" id="doj" class="inputbox date_picker" style="width: 400px;" value="<?php $this->_($this->offerLetterData['doj']); ?>" >
                                </td>
                            </tr>
                            <tr>
                                <td class="tdVertical" style="text-align: right;">
                                    <label id="designationLabel" for="designation">Designations</label>
                                </td>
                                <td class="tdData">
                                    <input type="text" name="designation" id="designation" class="inputbox" style="width: 400px;" value="<?php $this->_($this->offerLetterData['designation']); ?>" >
                                </td>
                            </tr>
                            <tr>
                                <td class="tdVertical" style="text-align: right;">
                                    <label id="annualLabel" for="annual">Annual</label>
                                </td>
                                <td class="tdData">
                                    <input type="text" name="annual" id="annual" class="inputbox" style="width: 400px;" value="<?php $this->_($this->offerLetterData['annual']); ?>" >
                                </td>
                            </tr>
                            <tr>
                                <td class="tdVertical" style="text-align: right;">
                                    <label id="validDateLabel" for="validDate">Valid Date</label>
                                </td>
                                <td class="tdData">
                                    <input type="text" name="validDate" id="validDate" class="inputbox date_picker" style="width: 400px;" value="<?php $this->_($this->offerLetterData['validDate']); ?>" >
                                </td>
                            </tr>
                            <tr>
                                <td class="tdVertical" style="text-align: right;">
                                    <label id="insuranceYNLabel" for="insuranceYN">Insurance</label>
                                </td>
                                <td class="tdData">
                                    <?php if($this->offerLetterData['insuranceYN'] == 'Y'){ ?>
                                    <input type="checkbox" name="insuranceYN" id="insuranceYN" class="inputbox" value="Y" checked >
                                    <?php }else{ ?>
                                    <input type="checkbox" name="insuranceYN" id="insuranceYN" class="inputbox" value="Y" >
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
                        <iframe src="<?php $this->_($this->pdfPath); ?>" width="100%" height="500"></iframe>
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
                            <?php if($this->sendMailFlag == 'Y'){ ?>
                            CKEDITOR.config.readOnly = true;
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
