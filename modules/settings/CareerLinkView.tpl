<?php TemplateUtility::printHeader('Settings', array('modules/settings/validator.js', 'modules/settings/Settings.js', 'js/careerportal.js')); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active, $this->subActive); ?>

    <div id="main">
        <?php TemplateUtility::printQuickSearch(); ?>

        <div id="contents">
            <table>
                <tr>
                    <td width="3%">
                        <img src="images/settings.gif" width="24" height="24" border="0" alt="Settings" style="margin-top: 3px;" />&nbsp;
                    </td>
                    <td><h2>Settings: CareerLink</h2></td>
                </tr>
            </table>

            <p class="note">Career Portal Link</p>

            <table width="100%">
                <tr>
                    <td>
                        <table class="editTable" width="100%">
                            <tr id="careerPortalEnabled">
                                <td class="tdVertical">
                                    Career Portal URL:
                                </td>
                                <td class="tdData">
                                    <a href="<?php $this->_($this->careerPortalURL); ?>"><?php $this->_($this->careerPortalURL); ?></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

           


            
        </div>
    </div>
<?php TemplateUtility::printFooter(); ?>
