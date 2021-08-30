<?php /* $Id: Add.tpl 3746 2007-11-28 20:28:21Z andrew $ */ 

// echo "<pre>";
// print_r($_SERVER);
// echo "</pre>";

?>
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
    <?php TemplateUtility::printHeader('Candidates', array('modules/candidates/validator.js', 'js/addressParser.js', 'js/listEditor.js',  'js/candidate.js', 'js/newreport.js','js/datepicker/jquery.min.js','js/datepicker/jquery-ui.min.js')); ?>
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
                    <td><h2>Reports: Get Report </h2></td>
                </tr>
            </table>

            <p class="note">Basic Information</p>

            <?php $URI = CATSUtility::getIndexName() . '?m=newreports&amp;a=writeExcel'; ?>
            <?php //$URI = CATSUtility::getIndexName() . '?m=newreports'; ?>

            <form name="reportForm" id="reportForm" enctype="multipart/form-data" action="<?php echo($URI); ?>" method="post" autocomplete="off" enctype="multipart/form-data" onsubmit="return checkReportForm(document.reportForm);">
                
                <input type="hidden" name="postback" id="postback" value="postback" />
                <table class="editTable">
                    <tr>
                        <td class="tdVertical">
                            <label id="reportLabel" for="report">Report Type:</label>
                        </td>
                        <td class="tdData">
                            <select id="report_type" class="inputbox" name="reportName" style="width: 150px;">
                                <option value="">Select Report</option>
                                <option value="Attendance">Attendance</option>
                                <option value="total_profile_sent">Total Profiles sent</option>
                                <option value="new_profile">New Profiles</option>
                                <option value="recycled_profile">Recycled Profiles</option>
                                <option value="L1">Level1</option>
                                <option value="L2">Level2</option>
                                <option value="CE">CE</option>
                                <option value="CE_not_available">CE Not Available</option>
                                <option value="CE_pending">CE Pending</option>
                                <option value="total_selects">Total Selects</option>
                                <option value="not_available">Not Available</option>
                                <option value="pending">Pending</option>
                                <option value="responded">Responded or Pipeline</option>
                                <option value="no_show">No Show</option>
                                <option value="confirm">Confirmed</option>
                                <option value="bgc_sent_customer">BGV Docs sent to customer</option>
                                <option value="bgc_pending">BGV Docs pending</option>
                                <option value="bgc_cleared">BGV Cleared</option>
                                <option value="on_board">On-board</option>
                                <option value="released">Released</option>
                            </select>
                        </td>
                    </tr>
                </table>


                <input type="submit" class="button" value="Get Report" />&nbsp;
                <input type="reset"  class="button" value="Reset" />&nbsp;
            </form>


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
