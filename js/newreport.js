function checkReportForm(){
    var errorMessage = '';

    errorMessage += checkReportName();

    if (errorMessage != '')
    {
        alert("Form Error:\n" + errorMessage);
        return false;
    }

    return true;
}

function checkReportName(){
    var errorMessage = '';

    fieldValue = document.getElementById('report_type').value;
    fieldLabel = document.getElementById('reportLabel');
    if (fieldValue == '')
    {
        errorMessage = "    - You must select the report type.\n";

        fieldLabel.style.color = '#ff0000';
    }
    else
    {
        fieldLabel.style.color = '#000';
    }

    return errorMessage;
}