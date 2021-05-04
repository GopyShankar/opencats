/*
 * CATS
 * Candidates Form Validation
 *
 * Copyright (C) 2005 - 2007 Cognizo Technologies, Inc.
 * All rights reserved.
 *
 * $Id: validator.js 2646 2007-07-09 16:40:31Z Andrew $
 */

function checkAddForm(form)
{
    var errorMessage = '';

    errorMessage += checkFirstName();
    errorMessage += checkLastName();

    if (errorMessage != '')
    {
        alert("Form Error:\n" + errorMessage);
        return false;
    }

    return true;
}

function checkEditForm(form)
{
    var errorMessage = '';

    errorMessage += checkFirstName();
    errorMessage += checkLastName();

    if (errorMessage != '')
    {
        alert("Form Error:\n" + errorMessage);
        return false;
    }

    return true;
}

function checkCreateAttachmentForm(form)
{
    var errorMessage = '';

    errorMessage += checkAttachmentFile();

    if (errorMessage != '')
    {
        alert("Form Error:\n" + errorMessage);
        return false;
    }

    return true;
}

function checkSearchByFullNameForm(form)
{
    var errorMessage = '';

    errorMessage += checkSearchFullName();

    if (errorMessage != '')
    {
        alert("Form Error:\n" + errorMessage);
        return false;
    }

    return true;
}

function checkSearchPhoneNumberForm(form)
{
    var errorMessage = '';

    errorMessage += checkPhoneNumber();

    if (errorMessage != '')
    {
        alert("Form Error:\n" + errorMessage);
        return false;
    }

    return true;
}

function checkSearchByKeySkillsForm(form)
{
    var errorMessage = '';

    errorMessage += checkSearchKeySkills();

    if (errorMessage != '')
    {
        alert("Form Error:\n" + errorMessage);
        return false;
    }

    return true;
}

function checkSearchResumeForm(form)
{
    var errorMessage = '';

    errorMessage += checkSearchResume();

    if (errorMessage != '')
    {
        alert("Form Error:\n" + errorMessage);
        return false;
    }

    return true;
}

function checkEmailForm(form)
{
    var errorMessage = '';

    errorMessage += checkEmailSubject();
    errorMessage += checkEmailBody();

    if (errorMessage != '')
    {
        alert("Form Error:\n" + errorMessage);
        return false;
    }

    return true;
}

function checkEmailTo(form)
{
    var errorMessage = '';

    errorMessage += checkJobOrderValid();
    errorMessage += checkEmailToAddress();

    if (errorMessage != '')
    {
        alert("Form Error:\n" + errorMessage);
        return false;
    }

    return true;
}

function checkOfferLetterForm(){
    var errorMessage = '';

    errorMessage += checkCandidateValid();
    errorMessage += checkDOJValid();
    errorMessage += checkDesignationValid();
    errorMessage += checkAnnualValid();
    errorMessage += checkvalidDateValid();

    if (errorMessage != '')
    {
        alert("Form Error:\n" + errorMessage);
        return false;
    }

    return true;
}

function checkDOJValid()
{
    var errorMessage = '';

    fieldValue = document.getElementById('doj').value;
    fieldLabel = document.getElementById('dojLabel');
    if (fieldValue == '')
    {
        errorMessage = "    - You must enter the DOJ.\n";

        fieldLabel.style.color = '#ff0000';
    }
    else
    {
        fieldLabel.style.color = '#000';
    }

    return errorMessage;
}

function checkDesignationValid()
{
    var errorMessage = '';

    fieldValue = document.getElementById('designation').value;
    fieldLabel = document.getElementById('designationLabel');
    if (fieldValue == '')
    {
        errorMessage = "    - You must enter the designation.\n";

        fieldLabel.style.color = '#ff0000';
    }
    else
    {
        fieldLabel.style.color = '#000';
    }

    return errorMessage;
}

function checkAnnualValid()
{
    var errorMessage = '';

    fieldValue = document.getElementById('annual').value;
    fieldLabel = document.getElementById('annualLabel');
    if (fieldValue == '')
    {
        errorMessage = "    - You must enter the annual.\n";

        fieldLabel.style.color = '#ff0000';
    }
    else
    {
        fieldLabel.style.color = '#000';
    }

    return errorMessage;
}

function checkvalidDateValid()
{
    var errorMessage = '';

    fieldValue = document.getElementById('validDate').value;
    fieldLabel = document.getElementById('validDateLabel');
    if (fieldValue == '')
    {
        errorMessage = "    - You must enter the validDate.\n";

        fieldLabel.style.color = '#ff0000';
    }
    else
    {
        fieldLabel.style.color = '#000';
    }

    return errorMessage;
}

function checkCandidate(form)
{
    var errorMessage = '';

    errorMessage += checkCandidateValid();

    if (errorMessage != '')
    {
        alert("Form Error:\n" + errorMessage);
        return false;
    }

    return true;
}

function emailFormReset(){
    document.getElementById('jobID').value = '';
}

function checkCandidateValid()
{
    var errorMessage = '';

    fieldValue = document.getElementById('candidateID').value;
    fieldLabel = document.getElementById('candidateLabel');
    if (fieldValue == '')
    {
        errorMessage = "    - You must select the candidate.\n";

        fieldLabel.style.color = '#ff0000';
    }
    else
    {
        fieldLabel.style.color = '#000';
    }

    return errorMessage;
}

function checkJobOrderValid()
{
    var errorMessage = '';

    fieldValue = document.getElementById('jobID').value;
    fieldLabel = document.getElementById('jobOrderLabel');
    if (fieldValue == '')
    {
        errorMessage = "    - You must select the JD.\n";

        fieldLabel.style.color = '#ff0000';
    }
    else
    {
        fieldLabel.style.color = '#000';
    }

    return errorMessage;
}

function checkFirstName()
{
    var errorMessage = '';

    fieldValue = document.getElementById('firstName').value;
    fieldLabel = document.getElementById('firstNameLabel');
    if (fieldValue == '')
    {
        errorMessage = "    - You must enter a first name.\n";

        fieldLabel.style.color = '#ff0000';
    }
    else
    {
        fieldLabel.style.color = '#000';
    }

    return errorMessage;
}

function checkLastName()
{
    var errorMessage = '';

    fieldValue = document.getElementById('lastName').value;
    fieldLabel = document.getElementById('lastNameLabel');
    if (fieldValue == '')
    {
        errorMessage = "    - You must enter a last name.\n";

        fieldLabel.style.color = '#ff0000';
    }
    else
    {
        fieldLabel.style.color = '#000';
    }

    return errorMessage;
}

function checkSearchFullName()
{
    var errorMessage = '';

    fieldValue = document.getElementById('wildCardString_fullName').value;
    fieldLabel = document.getElementById('wildCardStringLabel_fullName');
    if (fieldValue == '')
    {
        errorMessage = "    - You must enter some search text.\n";

        fieldLabel.style.color = '#ff0000';
    }
    else
    {
        fieldLabel.style.color = '#000';
    }

    return errorMessage;
}

function checkSearchKeySkills()
{
    var errorMessage = '';

    fieldValue = document.getElementById('wildCardString_keySkills').value;
    fieldLabel = document.getElementById('wildCardStringLabel_keySkills');
    if (fieldValue == '')
    {
        errorMessage = "    - You must enter some search text.\n";

        fieldLabel.style.color = '#ff0000';
    }
    else
    {
        fieldLabel.style.color = '#000';
    }

    return errorMessage;
}

function checkSearchResume()
{
    var errorMessage = '';

    fieldValue = document.getElementById('wildCardString_resume').value;
    fieldLabel = document.getElementById('wildCardStringLabel_resume');
    if (fieldValue == '')
    {
        errorMessage = "    - You must enter some search text.\n";

        fieldLabel.style.color = '#ff0000';
    }
    else
    {
        fieldLabel.style.color = '#000';
    }

    return errorMessage;
}

function checkAttachmentFile()
{
    var errorMessage = '';

    fieldValue = document.getElementById('file').value;
    fieldLabel = document.getElementById('file');
    if (fieldValue == '')
    {
        errorMessage = "    - You must enter a file to upload.\n";

        fieldLabel.style.color = '#ff0000';
    }
    else
    {
        fieldLabel.style.color = '#000';
    }

    return errorMessage;
}

function checkPhoneNumber()
{
    var errorMessage = '';

    fieldValue = document.getElementById('wildCardString_phoneNumber').value;
    fieldLabel = document.getElementById('wildCardStringLabel_phoneNumber');

    if (fieldValue == '')
    {
        errorMessage = "    - You must enter numbers to search.\n";

        fieldLabel.style.color = '#ff0000';
    }
    else
    {
        fieldLabel.style.color = '#000';
    }

    return errorMessage;
}

function checkEmailSubject()
{
    var errorMessage = '';

    fieldValue = document.getElementById('emailSubject').value;
    fieldLabel = document.getElementById('emailSubjectLabel');

    if (fieldValue == '')
    {
        errorMessage = "    - You must enter a subject for your e-mail.\n";

        fieldLabel.style.color = '#ff0000';
    }
    else
    {
        fieldLabel.style.color = '#000';
    }

    return errorMessage;
}

function checkEmailBody()
{
    var errorMessage = '';

    fieldValue = document.getElementById('emailBody').value;
    fieldLabel = document.getElementById('emailBodyLabel');

    if (fieldValue == '')
    {
        errorMessage = "    - You must enter a body for your e-mail.\n";

        fieldLabel.style.color = '#ff0000';
    }
    else
    {
        fieldLabel.style.color = '#000';
    }

    return errorMessage;
}

function validateEmail(email) 
{
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
}

var errorMessage = '';
function toMailVaidateCheck(fieldValue){
    errorMessage = '';
    if(validateEmail(fieldValue)){
        fieldLabel.style.color = '#000';
    }else{
        errorMessage = "    - Please enter the valid email address.\n";
        fieldLabel.style.color = '#ff0000';
    }
    return errorMessage;
}

function checkEmailToAddress()
{
    var errorMessage = '';

    fieldValue = document.getElementById('emailTo').value;
    fieldLabel = document.getElementById('emailToLabel');

    if (fieldValue == '')
    {
        errorMessage = "    - You must enter to-email address in your e-mail.\n";

        fieldLabel.style.color = '#ff0000';
    }
    else
    {
        
        if(fieldValue.endsWith(';')){
            errorMessage = "    - You have to use comma symbol for between email address1.\n";

            fieldLabel.style.color = '#ff0000';
        }else{
            if(fieldValue.search(',') != -1){
                var res = fieldValue.split(",");
                for(var i = 0; i <= res.length; i++){
                    if(res[i]){
                        errorMessage = toMailVaidateCheck(res[i]);
                    }
                }
            }
        }
    }

    return errorMessage;
}

function getJobData(){
    document.jobIDForm.submit();
}

function getCandidatesData(){
    document.candidateForm.submit();
}
