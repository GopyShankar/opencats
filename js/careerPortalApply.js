/* Set the action of the DOM container */
function setSubAction(action)
{
    var obj = document.getElementById('applyToJobSubAction');
    if (obj)
    {
        obj.value = action;
    }
}

/* Check if there's a resume to upload */
function resumeLoadCheck()
{
    var fileInput = document.getElementById('resumeFile');
    var parseButton = document.getElementById('resumePopulate');
    var resumeUpload = document.getElementById('resumeLoad');

    resumeUpload.disabled = (fileInput.value).length ? false : true;
    if (parseButton)
    {
        parseButton.disabled = (fileInput.value).length ? false : true;
    }
}

function payslipLoadCheck()
{
    var fileInput = document.getElementById('payslipFile');
    var parseButton = document.getElementById('payslipPopulate');
    var resumeUpload = document.getElementById('payslipLoad');

    resumeUpload.disabled = (fileInput.value).length ? false : true;
    if (parseButton)
    {
        parseButton.disabled = (fileInput.value).length ? false : true;
    }
}

function previousEmpLoadCheck()
{
    var fileInput = document.getElementById('previousEmpFile');
    var parseButton = document.getElementById('previousEmpPopulate');
    var resumeUpload = document.getElementById('previousEmpLoad');

    resumeUpload.disabled = (fileInput.value).length ? false : true;
    if (parseButton)
    {
        parseButton.disabled = (fileInput.value).length ? false : true;
    }
}

function previousEmpOLLoadCheck()
{
    var fileInput = document.getElementById('previousEmpOLFile');
    var parseButton = document.getElementById('previousEmpOLPopulate');
    var resumeUpload = document.getElementById('previousEmpOLLoad');

    resumeUpload.disabled = (fileInput.value).length ? false : true;
    if (parseButton)
    {
        parseButton.disabled = (fileInput.value).length ? false : true;
    }
}

function previousEmpELLoadCheck()
{
    var fileInput = document.getElementById('previousEmpELFile');
    var parseButton = document.getElementById('previousEmpELPopulate');
    var resumeUpload = document.getElementById('previousEmpELLoad');

    resumeUpload.disabled = (fileInput.value).length ? false : true;
    if (parseButton)
    {
        parseButton.disabled = (fileInput.value).length ? false : true;
    }
}

function previousEmpRLLoadCheck()
{
    var fileInput = document.getElementById('previousEmpRLFile');
    var parseButton = document.getElementById('previousEmpRLPopulate');
    var resumeUpload = document.getElementById('previousEmpRLLoad');

    resumeUpload.disabled = (fileInput.value).length ? false : true;
    if (parseButton)
    {
        parseButton.disabled = (fileInput.value).length ? false : true;
    }
}

function previousEmp2LoadCheck()
{
    var fileInput = document.getElementById('previousEmp2File');
    var parseButton = document.getElementById('previousEmp2Populate');
    var resumeUpload = document.getElementById('previousEmp2Load');

    resumeUpload.disabled = (fileInput.value).length ? false : true;
    if (parseButton)
    {
        parseButton.disabled = (fileInput.value).length ? false : true;
    }
}

function previousEmp2OLLoadCheck()
{
    var fileInput = document.getElementById('previousEmp2OLFile');
    var parseButton = document.getElementById('previousEmp2OLPopulate');
    var resumeUpload = document.getElementById('previousEmp2OLLoad');

    resumeUpload.disabled = (fileInput.value).length ? false : true;
    if (parseButton)
    {
        parseButton.disabled = (fileInput.value).length ? false : true;
    }
}

function previousEmp2ELLoadCheck()
{
    var fileInput = document.getElementById('previousEmp2ELFile');
    var parseButton = document.getElementById('previousEmp2ELPopulate');
    var resumeUpload = document.getElementById('previousEmp2ELLoad');

    resumeUpload.disabled = (fileInput.value).length ? false : true;
    if (parseButton)
    {
        parseButton.disabled = (fileInput.value).length ? false : true;
    }
}

function previousEmp2RLLoadCheck()
{
    var fileInput = document.getElementById('previousEmp2RLFile');
    var parseButton = document.getElementById('previousEmp2RLPopulate');
    var resumeUpload = document.getElementById('previousEmp2RLLoad');

    resumeUpload.disabled = (fileInput.value).length ? false : true;
    if (parseButton)
    {
        parseButton.disabled = (fileInput.value).length ? false : true;
    }
}

function previousEmp3LoadCheck()
{
    var fileInput = document.getElementById('previousEmp3File');
    var parseButton = document.getElementById('previousEmp3Populate');
    var resumeUpload = document.getElementById('previousEmp3Load');

    resumeUpload.disabled = (fileInput.value).length ? false : true;
    if (parseButton)
    {
        parseButton.disabled = (fileInput.value).length ? false : true;
    }
}

function previousEmp3OLLoadCheck()
{
    var fileInput = document.getElementById('previousEmp3OLFile');
    var parseButton = document.getElementById('previousEmp3OLPopulate');
    var resumeUpload = document.getElementById('previousEmp3OLLoad');

    resumeUpload.disabled = (fileInput.value).length ? false : true;
    if (parseButton)
    {
        parseButton.disabled = (fileInput.value).length ? false : true;
    }
}

function previousEmp3ELLoadCheck()
{
    var fileInput = document.getElementById('previousEmp3ELFile');
    var parseButton = document.getElementById('previousEmp3ELPopulate');
    var resumeUpload = document.getElementById('previousEmp3ELLoad');

    resumeUpload.disabled = (fileInput.value).length ? false : true;
    if (parseButton)
    {
        parseButton.disabled = (fileInput.value).length ? false : true;
    }
}

function previousEmp3RLLoadCheck()
{
    var fileInput = document.getElementById('previousEmp3RLFile');
    var parseButton = document.getElementById('previousEmp3RLPopulate');
    var resumeUpload = document.getElementById('previousEmp3RLLoad');

    resumeUpload.disabled = (fileInput.value).length ? false : true;
    if (parseButton)
    {
        parseButton.disabled = (fileInput.value).length ? false : true;
    }
}

function eduDocLoadCheck()
{
    var fileInput = document.getElementById('eduDocFile');
    var parseButton = document.getElementById('eduDocPopulate');
    var resumeUpload = document.getElementById('eduDocLoad');

    resumeUpload.disabled = (fileInput.value).length ? false : true;
    if (parseButton)
    {
        parseButton.disabled = (fileInput.value).length ? false : true;
    }
}

function eduDocCMLoadCheck()
{
    var fileInput = document.getElementById('eduDocCMFile');
    var parseButton = document.getElementById('eduDocCMPopulate');
    var resumeUpload = document.getElementById('eduDocCMLoad');

    resumeUpload.disabled = (fileInput.value).length ? false : true;
    if (parseButton)
    {
        parseButton.disabled = (fileInput.value).length ? false : true;
    }
}

function eduDocPCLoadCheck()
{
    var fileInput = document.getElementById('eduDocPCFile');
    var parseButton = document.getElementById('eduDocPCPopulate');
    var resumeUpload = document.getElementById('eduDocPCLoad');

    resumeUpload.disabled = (fileInput.value).length ? false : true;
    if (parseButton)
    {
        parseButton.disabled = (fileInput.value).length ? false : true;
    }
}

function eduDocCCLoadCheck()
{
    var fileInput = document.getElementById('eduDocCCFile');
    var parseButton = document.getElementById('eduDocCCPopulate');
    var resumeUpload = document.getElementById('eduDocCCLoad');

    resumeUpload.disabled = (fileInput.value).length ? false : true;
    if (parseButton)
    {
        parseButton.disabled = (fileInput.value).length ? false : true;
    }
}

function eduDoc12LoadCheck()
{
    var fileInput = document.getElementById('eduDoc12File');
    var parseButton = document.getElementById('eduDoc12Populate');
    var resumeUpload = document.getElementById('eduDoc12Load');

    resumeUpload.disabled = (fileInput.value).length ? false : true;
    if (parseButton)
    {
        parseButton.disabled = (fileInput.value).length ? false : true;
    }
}

function eduDoc10LoadCheck()
{
    var fileInput = document.getElementById('eduDoc10File');
    var parseButton = document.getElementById('eduDoc10Populate');
    var resumeUpload = document.getElementById('eduDoc10Load');

    resumeUpload.disabled = (fileInput.value).length ? false : true;
    if (parseButton)
    {
        parseButton.disabled = (fileInput.value).length ? false : true;
    }
}

function addressProofLoadCheck()
{
    var fileInput = document.getElementById('addressProofFile');
    var parseButton = document.getElementById('addressProofPopulate');
    var resumeUpload = document.getElementById('addressProofLoad');

    resumeUpload.disabled = (fileInput.value).length ? false : true;
    if (parseButton)
    {
        parseButton.disabled = (fileInput.value).length ? false : true;
    }
}

function relievingProofLoadCheck()
{
    var fileInput = document.getElementById('relievingProofFile');
    var parseButton = document.getElementById('relievingProofPopulate');
    var resumeUpload = document.getElementById('relievingProofLoad');

    resumeUpload.disabled = (fileInput.value).length ? false : true;
    if (parseButton)
    {
        parseButton.disabled = (fileInput.value).length ? false : true;
    }
}

/* Load the contents of the uploaded file into the textarea box */
function resumeLoadFile()
{
    setSubAction('resumeLoad');
    document.applyToJobForm.submit();
}

function payslipLoadFile()
{
    setSubAction('payslipLoad');
    document.applyToJobForm.submit();
}

function previousEmpLoadFile()
{
    setSubAction('previousEmpLoad');
    document.applyToJobForm.submit();
}

function previousEmpOLLoadFile()
{
    setSubAction('previousEmpOLLoad');
    document.applyToJobForm.submit();
}

function previousEmpELLoadFile()
{
    setSubAction('previousEmpELLoad');
    document.applyToJobForm.submit();
}

function previousEmpRLLoadFile()
{
    setSubAction('previousEmpRLLoad');
    document.applyToJobForm.submit();
}

function previousEmp2LoadFile()
{
    setSubAction('previousEmp2Load');
    document.applyToJobForm.submit();
}

function previousEmp2OLLoadFile()
{
    setSubAction('previousEmp2OLLoad');
    document.applyToJobForm.submit();
}

function previousEmp2ELLoadFile()
{
    setSubAction('previousEmp2ELLoad');
    document.applyToJobForm.submit();
}

function previousEmp2RLLoadFile()
{
    setSubAction('previousEmp2RLLoad');
    document.applyToJobForm.submit();
}

function previousEmp3LoadFile()
{
    setSubAction('previousEmp3Load');
    document.applyToJobForm.submit();
}

function previousEmp3OLLoadFile()
{
    setSubAction('previousEmp3OLLoad');
    document.applyToJobForm.submit();
}

function previousEmp3ELLoadFile()
{
    setSubAction('previousEmp3ELLoad');
    document.applyToJobForm.submit();
}

function previousEmp3RLLoadFile()
{
    setSubAction('previousEmp3RLLoad');
    document.applyToJobForm.submit();
}

function eduDocLoadFile()
{
    setSubAction('eduDocLoad');
    document.applyToJobForm.submit();
}

function eduDocCMLoadFile()
{
    setSubAction('eduDocCMLoad');
    document.applyToJobForm.submit();
}

function eduDocPCLoadFile()
{
    setSubAction('eduDocPCLoad');
    document.applyToJobForm.submit();
}

function eduDocCCLoadFile()
{
    setSubAction('eduDocCCLoad');
    document.applyToJobForm.submit();
}

function eduDoc12LoadFile()
{
    setSubAction('eduDoc12Load');
    document.applyToJobForm.submit();
}

function eduDoc10LoadFile()
{
    setSubAction('eduDoc10Load');
    document.applyToJobForm.submit();
}

function addressProofLoadFile()
{
    setSubAction('addressProofLoad');
    document.applyToJobForm.submit();
}

function relievingProofLoadFile()
{
    setSubAction('relievingProofLoad');
    document.applyToJobForm.submit();
}

function resumeParse()
{
    var fileInput = document.getElementById('resumeFile');
    var resumeContents = document.getElementById('resumeContents');
    if ((resumeContents.value).length || (fileInput.value).length)
    {
        setSubAction('resumeParse');
        document.applyToJobForm.submit();
    }
}

function resumeContentsChange(e)
{
    var parseButton = document.getElementById('resumePopulate');
    var fileInput = document.getElementById('resumeFile');
    if (parseButton)
    {
        parseButton.disabled = !(e.value).length && !(fileInput.value).length ? true : false;
    }
}

/* Preload default career portal images (should move to template) */
var returnToMainOff = new Image(130, 25);
returnToMainOff.src = '../images/careers_return.gif';
var returnToMainOn = new Image(130, 25);
returnToMainOn.src = '../images/careers_return-o.gif';

var rssFeedOff = new Image(130, 25);
rssFeedOff.src = '../images/careers_rss.gif';
var rssFeedOn = new Image(130, 25);
rssFeedOn.src = '../images/careers_rss-o.gif';

var showAllJobsOff = new Image(130, 25);
showAllJobsOff.src = '../images/careers_show.gif';
var showAllJobsOn = new Image(130, 25);
showAllJobsOn.src = '../images/careers_show-o.gif';

var applyToPositionOff = new Image(130, 25);
applyToPositionOff.src = '../images/careers_apply.gif';
var applyToPositionOn = new Image(130, 25);
applyToPositionOn.src = '../images/careers_apply-o.gif';

// var submitApplicationNowOff = new Image(130, 25);
// submitApplicationNowOff.src = '../images/careers_submit.gif';
// var submitApplicationNowOn = new Image(130, 25);
// submitApplicationNowOn.src = '../images/careers_submit-o.gif';

var submitApplicationNowOff = new Image(130, 25);
submitApplicationNowOff.src = '../images/careers_submitviolet.png';
var submitApplicationNowOn = new Image(130, 25);
submitApplicationNowOn.src = '../images/careers_submitviolet-o.png';

function buttonMouseOver(ename, tf)
{
    var e = document.getElementById(ename);
    var tag;
    if (tf)
    {
        tag = 'On';
    }
    else
    {
        tag = 'Off';
    }
    eval('e.src = ' + ename + tag + '.src');
}

function onFocusFormField(e)
{
    var isNewNo = document.getElementById('isNewNo');

    if (e.id != 'email')
    {
        if (!isNewNo.checked)
        {
            isNewNo.checked = true;
        }
    }
}

function focusFirstField()
{
    var inputs = document.getElementsByTagName('input');
    var emailTabIndex = -1;
    var nextObjDist = -1;
    var nextObj = 0;
    var dist;

    // Get the tabIndex for the required e-mail field
    for (var i = 0; i < inputs.length; i++)
    {
        if (inputs[i].id == 'email')
        {
            emailTabIndex = inputs[i].tabIndex;
        }
    }

    // If there is no e-mail field, we can't do anything
    if (emailTabIndex == -1) return;

    // Get the next closest
    for (var i = 0; i < inputs.length; i++)
    {
        if (inputs[i].id != 'email' && inputs[i].type == 'text')
        {
            dist = Math.abs(emailTabIndex - inputs[i].tabIndex);
            if (nextObjDist == -1 || dist  < nextObjDist)
            {
                nextObjDist = dist;
                nextObj = inputs[i];
            }
        }
    }

    if (nextObj)
    {
        nextObj.focus();
        nextObj.select();
    }
}

function enableFormFields(tf)
{
    var inputs = document.getElementsByTagName('input');
    var rememberMe = document.getElementById('rememberMe');

    if (rememberMe)
    {
        rememberMe.disabled = !tf;
    }

    for (var i = 0; i < inputs.length; i++)
    {
        if (inputs[i].id != 'email' && inputs[i].type == 'text')
        {
            inputs[i].disabled = !tf;
        }
    }
}

function isCandidateRegisteredChange()
{
    var isNewYes = document.getElementById('isNewYes');
    var isNewNo = document.getElementById('isNewNo');

    if (isNewYes.checked)
    {
        enableFormFields(false);
    }
    else
    {
        enableFormFields(true);
        focusFirstField();
    }
}

function validateCandidateRegistration()
{
    var obj;
    var isNewObj = document.getElementById('isNewYes');
    var isNew = isNewObj ? isNewObj.checked : false;

    var formFields = [
        'firstName', 'lastName', 'zipCode', 'zip','address', 'city', 'state', 'homePhone',
        'mobilePhone', 'workPhone'
    ];

    // E-mail address is the only required field regardless of registered/unregistered
    if (obj = document.getElementById('email'))
    {
        if (!(obj.value).match(/^[A-Za-z0-9\.\-\_]+\@[A-Za-z0-9\.\-\_]+\.[A-Za-z0-9]{2,6}$/))
        {
            obj.style.backgroundColor = '#FDF0F0';
            alert('Please enter a valid e-mail address.');
            return false;
        }
    }

    if (!isNew)
    {
        var error = false;
        for (var fieldIndex = 0; fieldIndex < formFields.length; fieldIndex++)
        {
            if (obj = document.getElementById(formFields[fieldIndex]))
            {
                if (!(obj.value).length)
                {
                    obj.style.backgroundColor = '#FDF0F0';
                    error = true;
                }
            }
        }
        if (error)
        {
            alert("Because you have registered before, please complete all the fields to login.\n\nIf you haven\'t registered before, please select \"I have not registered on this website\".");
            return false;
        }
    }

    return true;
}


function removeDocFiles(val){
    var source = $(val).closest("span").next('input').val();
    console.log(source,'source');
    if(source == 'resume'){
        var removeFile = $(val).closest("div").find('span').html();
        var fileList = $('#file').val();
        var res = fileList.split(",");
        res.splice(res.indexOf(removeFile), 1);
        var files = res.toString();
        $(val).closest("div").remove();
        $('input#file').replaceWith('<input type="hidden" id="file" name="file" value="' +files+ '" />');
    }else if(source == 'payslip'){
        var removeFile = $(val).closest("div").find('span').html();
        var fileList = $('#payslip_file').val();
        var res = fileList.split(",");
        res.splice(res.indexOf(removeFile), 1);
        var files = res.toString();
        $(val).closest("div").remove();
        $('input#payslip_file').replaceWith('<input type="hidden" id="payslip_file" name="payslip_file" value="' +files+ '" />');
    }else if(source == "previousEmp"){
        var removeFile = $(val).closest("div").find('span').html();
        var fileList = $('#previousEmp_file').val();
        var res = fileList.split(",");
        res.splice(res.indexOf(removeFile), 1);
        var files = res.toString();
        $(val).closest("div").remove();
        $('input#previousEmp_file').replaceWith('<input type="hidden" id="previousEmp_file" name="previousEmp_file" value="' +files+ '" />');
    }else if(source == "previousEmpOL"){
        var removeFile = $(val).closest("div").find('span').html();
        var fileList = $('#previousEmpOL_file').val();
        var res = fileList.split(",");
        res.splice(res.indexOf(removeFile), 1);
        var files = res.toString();
        $(val).closest("div").remove();
        $('input#previousEmpOL_file').replaceWith('<input type="hidden" id="previousEmpOL_file" name="previousEmpOL_file" value="' +files+ '" />');
    }else if(source == "previousEmpEL"){
        var removeFile = $(val).closest("div").find('span').html();
        var fileList = $('#previousEmpEL_file').val();
        var res = fileList.split(",");
        res.splice(res.indexOf(removeFile), 1);
        var files = res.toString();
        $(val).closest("div").remove();
        $('input#previousEmpEL_file').replaceWith('<input type="hidden" id="previousEmpEL_file" name="previousEmpEL_file" value="' +files+ '" />');
    }else if(source == "previousEmpRL"){
        var removeFile = $(val).closest("div").find('span').html();
        var fileList = $('#previousEmpRL_file').val();
        var res = fileList.split(",");
        res.splice(res.indexOf(removeFile), 1);
        var files = res.toString();
        $(val).closest("div").remove();
        $('input#previousEmpRL_file').replaceWith('<input type="hidden" id="previousEmpRL_file" name="previousEmpRL_file" value="' +files+ '" />');
    }else if(source == "previousEmp2"){
        var removeFile = $(val).closest("div").find('span').html();
        var fileList = $('#previousEmp2_file').val();
        var res = fileList.split(",");
        res.splice(res.indexOf(removeFile), 1);
        var files = res.toString();
        $(val).closest("div").remove();
        $('input#previousEmp2_file').replaceWith('<input type="hidden" id="previousEmp2_file" name="previousEmp2_file" value="' +files+ '" />');
    }else if(source == "previousEmp2OL"){
        var removeFile = $(val).closest("div").find('span').html();
        var fileList = $('#previousEmp2OL_file').val();
        var res = fileList.split(",");
        res.splice(res.indexOf(removeFile), 1);
        var files = res.toString();
        $(val).closest("div").remove();
        $('input#previousEmp2OL_file').replaceWith('<input type="hidden" id="previousEmp2OL_file" name="previousEmp2OL_file" value="' +files+ '" />');
    }else if(source == "previousEmp2EL"){
        var removeFile = $(val).closest("div").find('span').html();
        var fileList = $('#previousEmp2EL_file').val();
        var res = fileList.split(",");
        res.splice(res.indexOf(removeFile), 1);
        var files = res.toString();
        $(val).closest("div").remove();
        $('input#previousEmp2EL_file').replaceWith('<input type="hidden" id="previousEmp2EL_file" name="previousEmp2EL_file" value="' +files+ '" />');
    }else if(source == "previousEmp2RL"){
        var removeFile = $(val).closest("div").find('span').html();
        var fileList = $('#previousEmp2RL_file').val();
        var res = fileList.split(",");
        res.splice(res.indexOf(removeFile), 1);
        var files = res.toString();
        $(val).closest("div").remove();
        $('input#previousEmp2RL_file').replaceWith('<input type="hidden" id="previousEmp2RL_file" name="previousEmp2RL_file" value="' +files+ '" />');
    }else if(source == "previousEmp3"){
        var removeFile = $(val).closest("div").find('span').html();
        var fileList = $('#previousEmp3_file').val();
        var res = fileList.split(",");
        res.splice(res.indexOf(removeFile), 1);
        var files = res.toString();
        $(val).closest("div").remove();
        $('input#previousEmp3_file').replaceWith('<input type="hidden" id="previousEmp3_file" name="previousEmp3_file" value="' +files+ '" />');
    }else if(source == "previousEmp3OL"){
        var removeFile = $(val).closest("div").find('span').html();
        var fileList = $('#previousEmp3OL_file').val();
        var res = fileList.split(",");
        res.splice(res.indexOf(removeFile), 1);
        var files = res.toString();
        $(val).closest("div").remove();
        $('input#previousEmp3OL_file').replaceWith('<input type="hidden" id="previousEmp3OL_file" name="previousEmp3OL_file" value="' +files+ '" />');
    }else if(source == "previousEmp3EL"){
        var removeFile = $(val).closest("div").find('span').html();
        var fileList = $('#previousEmp3EL_file').val();
        var res = fileList.split(",");
        res.splice(res.indexOf(removeFile), 1);
        var files = res.toString();
        $(val).closest("div").remove();
        $('input#previousEmp3EL_file').replaceWith('<input type="hidden" id="previousEmp3EL_file" name="previousEmp3EL_file" value="' +files+ '" />');
    }else if(source == "previousEmp3RL"){
        var removeFile = $(val).closest("div").find('span').html();
        var fileList = $('#previousEmp3RL_file').val();
        var res = fileList.split(",");
        res.splice(res.indexOf(removeFile), 1);
        var files = res.toString();
        $(val).closest("div").remove();
        $('input#previousEmp3RL_file').replaceWith('<input type="hidden" id="previousEmp3RL_file" name="previousEmp3RL_file" value="' +files+ '" />');
    }else if(source == "eduDoc"){
        var removeFile = $(val).closest("div").find('span').html();
        var fileList = $('#eduDoc_file').val();
        var res = fileList.split(",");
        res.splice(res.indexOf(removeFile), 1);
        var files = res.toString();
        $(val).closest("div").remove();
        $('input#eduDoc_file').replaceWith('<input type="hidden" id="eduDoc_file" name="eduDoc_file" value="' +files+ '" />');
    }else if(source == "eduDocCM"){
        var removeFile = $(val).closest("div").find('span').html();
        var fileList = $('#eduDocCM_file').val();
        var res = fileList.split(",");
        res.splice(res.indexOf(removeFile), 1);
        var files = res.toString();
        $(val).closest("div").remove();
        $('input#eduDocCM_file').replaceWith('<input type="hidden" id="eduDocCM_file" name="eduDocCM_file" value="' +files+ '" />');
    }else if(source == "eduDocPC"){
        var removeFile = $(val).closest("div").find('span').html();
        var fileList = $('#eduDocPC_file').val();
        var res = fileList.split(",");
        res.splice(res.indexOf(removeFile), 1);
        var files = res.toString();
        $(val).closest("div").remove();
        $('input#eduDocPC_file').replaceWith('<input type="hidden" id="eduDocPC_file" name="eduDocPC_file" value="' +files+ '" />');
    }else if(source == "eduDocCC"){
        var removeFile = $(val).closest("div").find('span').html();
        var fileList = $('#eduDocCC_file').val();
        var res = fileList.split(",");
        res.splice(res.indexOf(removeFile), 1);
        var files = res.toString();
        $(val).closest("div").remove();
        $('input#eduDocCC_file').replaceWith('<input type="hidden" id="eduDocCC_file" name="eduDocCC_file" value="' +files+ '" />');
    }else if(source == "eduDoc12"){
        var removeFile = $(val).closest("div").find('span').html();
        var fileList = $('#eduDoc12_file').val();
        var res = fileList.split(",");
        res.splice(res.indexOf(removeFile), 1);
        var files = res.toString();
        $(val).closest("div").remove();
        $('input#eduDoc12_file').replaceWith('<input type="hidden" id="eduDoc12file" name="eduDoc12_file" value="' +files+ '" />');
    }else if(source == "eduDoc10"){
        var removeFile = $(val).closest("div").find('span').html();
        var fileList = $('#eduDoc10_file').val();
        var res = fileList.split(",");
        res.splice(res.indexOf(removeFile), 1);
        var files = res.toString();
        $(val).closest("div").remove();
        $('input#eduDoc10_file').replaceWith('<input type="hidden" id="eduDoc10_file" name="eduDoc10_file" value="' +files+ '" />');
    }else if(source == "addressProof"){
        var removeFile = $(val).closest("div").find('span').html();
        var fileList = $('#addressProof_file').val();
        var res = fileList.split(",");
        res.splice(res.indexOf(removeFile), 1);
        var files = res.toString();
        $(val).closest("div").remove();
        $('input#addressProof_file').replaceWith('<input type="hidden" id="addressProof_file" name="addressProof_file" value="' +files+ '" />');
    }else if(source == "relievingProof"){
        var removeFile = $(val).closest("div").find('span').html();
        var fileList = $('#relievingProof_file').val();
        var res = fileList.split(",");
        res.splice(res.indexOf(removeFile), 1);
        var files = res.toString();
        $(val).closest("div").remove();
        $('input#relievingProof_file').replaceWith('<input type="hidden" id="relievingProof_file" name="relievingProof_file" value="' +files+ '" />');
    }
}
