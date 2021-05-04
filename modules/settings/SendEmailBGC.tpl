<?php /* $Id: SendEmail.tpl 3078 2007-09-21 20:25:28Z will $ */ ?>
<?php TemplateUtility::printHeader('Settings', array('ckeditor/ckeditor.js', 'modules/candidates/validator.js', 'js/searchSaved.js', 'js/sweetTitles.js', 'js/searchAdvanced.js', 'js/highlightrows.js', 'js/export.js')); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active, $this->subActive); ?>
    <div id="main">
        <?php TemplateUtility::printQuickSearch(); ?>

        <div id="contents">
            <table>
                <tr>
                    <td width="3%">
                        <img src="images/candidate.gif" width="24" height="24" border="0" alt="Settings" style="margin-top: 3px;" />&nbsp;
                    </td>
                    <td><h2>Settings: Send E-mail BGC</h2></td>
                </tr>
            </table>

            <p class="note">Send E-mail To Candidates </p>

            <?php
            if($this->success == true)
            {
                ?>

                <br />
                <span style="font-size: 12pt; font-weight: 900;">
                Your e-mail has been successfully sent to the following recipients:
                <blockquote>
                <?php
                echo $this->success_to;
                ?>
                </blockquote>


                <?php
            }
            else
            {
                $emailTo = '';
                foreach($this->recipients as $recipient)
                {
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

            <table class="editTable" width="100%">
                <tr>
                    <td>
                        <form name="candidateForm" id="candidateForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=settings&amp;a=sendMailBGC" method="post" autocomplete="off" enctype="multipart/form-data">
                            <input type="hidden" name="postback" id="postback" value="candidateID" />
                            <table>
                                <tr>
                                    <td class="tdVertical" style="text-align: right;">
                                        <label id="candidateLabel" for="type">Candidate:</label>
                                    </td>
                                    <td class="tdData">
                                        <select tabindex="7" id="candidateID" name="candidateID" class="inputbox" onchange="getCandidatesData()" style="width: 600px;">
                                            <option value="" disabled="" selected="">Selected</option>
                                        <?php foreach($this->candidatesData as $Data): ?>
                                            <option value="<?php echo $Data['candidateID'] ?>" 
                                                    <?php if(isset($this->selectedData) && $this->selectedData == $Data['candidateID']) echo('selected'); ?> >
                                                    <?php echo $Data['email'];?>
                                            </option>
                                        <?php endforeach; ?>
                                        </select>&nbsp;
                                    </td>
                                </tr>
                            </table>
                        </form>
                        <form name="emailForm" id="emailForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=settings&amp;a=sendMailBGC" method="post" onsubmit="return checkCandidate(document.emailForm);" autocomplete="off" enctype="multipart/form-data">
                        <input type="hidden" name="postback" id="postback" value="postback" />
                        <input type="hidden" name="emailTo" id="emailTo" value="<?php $this->_($this->selectedDataEmail); ?>" />
                        <table>
                            <tr>
                                <td class="tdVertical" style="text-align: right;">
                                    <label id="emailSubjectLabel" for="emailSubject">Subject</label>
                                </td>
                                <td class="tdData">
                                    <input id="emailSubject" tabindex="<?php echo($tabIndex++); ?>" type="text" name="emailSubject" class="inputbox" style="width: 600px;" readonly value="<?php $this->_($this->SubjectMsg); ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="tdVertical" style="text-align: right;">
                                    <label id="emailBodyLabel" for="emailBody">Body</label>
                                </td>
                                <td class="tdData">
                                    <textarea id="emailBody" tabindex="<?php echo($tabIndex++); ?>" name="emailBody" rows="10" cols="90" style="width: 600px;" class="inputbox"><?php $this->_($this->bodyMsg); ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td align="right" valign="top" colspan="2">
                                    <input type="submit" tabindex="<?php echo($tabIndex++); ?>" class="button" value="Send E-Mail" />&nbsp;
                                    <input type="reset"  tabindex="<?php echo($tabIndex++); ?>" class="button" value="Reset" onclick="emailFormReset()" />&nbsp;
                                </td>
                            </tr>
                        </table>

                        </form>

			<script type="text/javascript">
                        
                        //added the code below for the ckeditor html box - Jamin 2-19-2010
                        //adjusted code to remove or prevent extra breaks in email - Jamin 2-23-2010
			CKEDITOR.replace( 'emailBody' );
    			CKEDITOR.on('instanceReady', function(ev)
        		{
            		var tags = ['p', 'ol', 'ul', 'li']; // etc.

            		for (var key in tags) {
                	ev.editor.dataProcessor.writer.setRules(tags[key],
                    	{
                        indent : false,
                        breakBeforeOpen : false,
                        breakAfterOpen : false,
                        breakBeforeClose : false,
                        breakAfterClose : false, 
                    	});
            		}
        		});
                $(document).ready(function() {
        CKEDITOR.config.readOnly = true;
    });
              		</script>

                    </td>
                </tr>
            </table>
            <?php
            }
            ?>
        </div>
    </div>
<?php TemplateUtility::printFooter(); ?>
