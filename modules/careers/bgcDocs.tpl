<html>
    <head>
        <title>BGC Docs</title>
        <link href="assets/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <style type="text/css">
        
            nav > .nav.nav-tabs{

              border: none;
                color:#fff;
                background:#272e38;
                border-radius:0;

            }
            nav > div a.nav-item.nav-link
            {
              border: none;
                padding: 18px 25px;
                color:#fff;
                background:#272e38;
                border-radius:0;
            }

            nav > div a.nav-item.nav-link.active
            {
                border: none;
                padding: 18px 25px;
                color:#fff;
                background:#004188;
                border-radius:0;
            }

            nav > div a.nav-item.nav-link.active:after
             {
              content: "";
              position: relative;
              bottom: -60px;
              left: -40%;
              border: 15px solid transparent;
              border-top-color: #004188 ;
            }
            .tab-content{
              background: #fdfdfd;
                line-height: 25px;
                border: 1px solid #ddd;
                border-top:5px solid #004188;
                border-bottom:5px solid #004188;
                padding:30px 25px;
            }

            nav > div a.nav-item.nav-link:hover,
            nav > div a.nav-item.nav-link:focus
            {
              border: none;
                background: #004188;
                color:#fff;
                border-radius:0;
                transition:background 0.20s linear;
            }
            .control-label:after {
              content:"*";
              color:red;
            }
            .labelNotes-wrap{
                word-wrap: break-word;
                min-width: 170px;
                max-width: 170px;
            }
            .notes_class {
                background-color: #ffdddd;
                border-left: 6px solid #f44336;
            }
            input.date_picker {
                background-image: url("../images/calendar.gif");
                background-position: right center;
                background-repeat: no-repeat;
            }
        </style>
        <link href='../js/datepicker/jquery-ui.css' rel='stylesheet'>
        <script src="../js/datepicker/jquery.min.js"></script>
        <script src="../js/datepicker/jquery-ui.min.js"></script>
        <!-- <script type="text/javascript" src="assets/jquery.min.js"></script> -->
        <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.js"></script>
        <script type="text/javascript" src="assets/bootstrap.min.js"></script>
        <script type="text/javascript" src="assets/jquery.validate.min.js"></script>
        <script type="text/javascript" src="assets/additional-methods.min.js"></script>
        
    </head>
    <body>
        <div class="container">
            <div class="py-5">
                <img class="d-block mx-auto mb-4" src="../images/temp/logo.png" alt="VHS Consulting">
                <h3>BA BGC Process</h3>
                <?php if(!$this->displayMsg){ ?>    
                <p class="lead">Please upload your required documents below.</p>
                <?php } ?>
            </div>
            <?php if($this->displayMsg){ ?>
            <div class="alert alert-success" role="alert">
                <p>Your document submitted successfully...</p>
            </div>    
            <?php } ?>
            <?php if(!$this->displayMsg){ ?>
            <div class="alert alert-info" role="alert">
                <h4><strong>Notes:</strong></h4>
                <div class="row">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>Please download the BGC Form and fill the required details and upload it</td>
                                <td>
                                    <a class="btn btn-primary" href="docs/BA BGC form Version 8.pdf" role="button" download>BGC Form</a>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>Please download the sample Affidavit for GAP mentioned in BGC Form (mention month & Year) in Rs.100 Stamp Paper</td>
                                <td>
                                    <a class="btn btn-primary" href="docs/GAP AFFIDAVIT_Sample.pdf" role="button" download>Sample Affidavit</a>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">3</th>
                                <td>Please download the sample letter banks in which you hold your salary account</td>
                                <td>
                                    <a class="btn btn-primary" href="docs/bank account vfn - authorization format.docx" role="button" download>Sample Letter</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <form method="post" id="docsInfo" name="docsInfo" enctype="multipart/form-data" >
                        <nav>
                            <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active show" id="nav-bgc-tab" data-toggle="tab" href="#nav-bgc" role="tab" aria-controls="nav-bgc" aria-selected="false">BGC Details</a>
                                <a class="nav-item nav-link" id="nav-education-tab" data-toggle="tab" href="#nav-education" role="tab" aria-controls="nav-education" aria-selected="false">Education Check</a>
                                <a class="nav-item nav-link" id="nav-previous-tab" data-toggle="tab" href="#nav-previous" role="tab" aria-controls="nav-previous" aria-selected="false">Previous Employment Check</a>
                                <a class="nav-item nav-link" id="nav-otherDocs-tab" data-toggle="tab" href="#nav-otherDocs" role="tab" aria-controls="nav-otherDocs" aria-selected="true">Other Docs</a>
                            </div>
                        </nav>
                        <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                            <div class="tab-pane fade active show" id="nav-bgc" role="tabpanel" aria-labelledby="nav-bgc-tab">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>
                                                <label class="control-label">DOJ</label>
                                            </td>
                                            <td>
                                                <input type="text" name="doj" id="doj" class="doj date_picker">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">2</th>
                                            <td>
                                                <label class="control-label">BGC Form</label>
                                            </td>
                                            <td>
                                                <input type="file" name="bgc" id="bgc" class="bgc">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">3</th>
                                            <td>
                                                <label class="control-label">Address Proof</label>
                                            </td>
                                            <td>
                                                <input type="file" name="address_proof" id="address_proof" class="address_proof">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">4</th>
                                            <td>
                                                <label class="control-label">PAN Card</label>
                                            </td>
                                            <td>
                                                <input type="file" name="panCard" id="panCard" class="panCard">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">5</th>
                                            <td>
                                                <label class="control-label">BA Vendor Deputation Letter</label>
                                            </td>
                                            <td>
                                                <input type="file" name="ba_deputation_letter" id="ba_deputation_letter" class="ba_deputation_letter">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">6</th>
                                            <td>
                                                <label class="control-label">BA Vendor Offer Letter</label>
                                            </td>
                                            <td>
                                                <input type="file" name="ba_offer_letter" id="ba_offer_letter" class="ba_offer_letter">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">7</th>
                                            <td>
                                                <label class="control-label">Aadhar proof</label>
                                            </td>
                                            <td>
                                                <input type="file" name="aadhar_proof" id="aadhar_proof" class="aadhar_proof">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">8</th>
                                            <td>
                                                <label class="control-label">Passport size photo</label>
                                            </td>
                                            <td>
                                                <input type="file" name="photo" id="photo" class="photo">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="nav-education" role="tabpanel" aria-labelledby="nav-education-tab">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>
                                                <label class="control-label">Graduation - All Sem Marksheet </label>
                                            </td>
                                            <td>
                                                <input type="file" name="gAllSem[]" id="gAllSem" class="gAllSem" multiple>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">2</th>
                                            <td>
                                                <label class="control-label">Graduation - Provisional Certificate </label>
                                            </td>
                                            <td>
                                                <input type="file" name="gPC" id="gPC" class="gPC">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">3</th>
                                            <td>
                                                <label>Post Graduation - All Sem Marksheet </label>
                                            </td>
                                            <td>
                                                <input type="file" name="pgAllSem[]" id="pgAllSem" class="pgAllSem" multiple>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">4</th>
                                            <td>
                                                <label>Post Graduation - Provisional Certificate </label>
                                            </td>
                                            <td>
                                                <input type="file" name="pgPC" id="pgPC" class="pgPC" >
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">5</th>
                                            <td>
                                                <label>Post Graduation - Certificate </label>
                                            </td>
                                            <td>
                                                <input type="file" name="pgCert" id="pgCert" class="pgCert">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="nav-previous" role="tabpanel" aria-labelledby="nav-previous-tab">
                                <table class="table table-striped">
                                    <tbody class="preEmpView">
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>
                                                <label class="control-label">Previous Employeer payslip </label>
                                            </td>
                                            <td>
                                                <input type="file" name="preEmpPayslip[]" id="preEmpPayslip" class="preEmpPayslip" multiple>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">2</th>
                                            <td>
                                                <label class="control-label">Previous Employeer Offer Letter </label>
                                            </td>
                                            <td>
                                                <input type="file" name="preEmpOL[]" id="preEmpOL" class="preEmpOL">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">3</th>
                                            <td>
                                                <label class="control-label">Previous Employeer Experience Letter </label>
                                            </td>
                                            <td>
                                                <input type="file" name="preEmpEL[]" id="preEmpEL" class="preEmpEL">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">4</th>
                                            <td>
                                                <label class="control-label">Previous Employeer Relieving Letter </label>
                                            </td>
                                            <td>
                                                <input type="file" name="preEmpRL[]" id="preEmpRL" class="preEmpRL">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">5</th>
                                            <td>
                                                <label class="control-label">Previous Employeer Bank Statement </label>
                                            </td>
                                            <td>
                                                <input type="file" name="preEmpBankState[]" id="preEmpBankState" class="preEmpBankState">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-right">
                                                <input class="addBtn btn btn-primary" type="button" name="add" value="Add" data-add-class='preEmpViewAdd'>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody class="preEmpView preEmpViewAdd" style="display: none">
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>
                                                <label class="control-label">Previous Employeer payslip </label>
                                            </td>
                                            <td>
                                                <input type="file" name="preEmpPayslip[]" id="preEmpPayslip" class="preEmpPayslip" multiple disabled="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">2</th>
                                            <td>
                                                <label class="control-label">Previous Employeer Offer Letter </label>
                                            </td>
                                            <td>
                                                <input type="file" name="preEmpOL[]" id="preEmpOL" class="preEmpOL" disabled="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">3</th>
                                            <td>
                                                <label class="control-label">Previous Employeer Experience Letter </label>
                                            </td>
                                            <td>
                                                <input type="file" name="preEmpEL[]" id="preEmpEL" class="preEmpEL" disabled="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">4</th>
                                            <td>
                                                <label class="control-label">Previous Employeer Relieving Letter </label>
                                            </td>
                                            <td>
                                                <input type="file" name="preEmpRL[]" id="preEmpRL" class="preEmpRL" disabled="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">5</th>
                                            <td>
                                                <label class="control-label">Previous Employeer Bank Statement </label>
                                            </td>
                                            <td>
                                                <input type="file" name="preEmpBankState[]" id="preEmpBankState" class="preEmpBankState">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-right">
                                                <input type="button" value="Remove" name="remove" class="btn btn-danger remove">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="nav-otherDocs" role="tabpanel" aria-labelledby="nav-otherDocs-tab">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <th scope="row">1</th>
                                            <td class="labelNotes-wrap">
                                                <label>Gap Affidavit</label>
                                                <!-- <label><i><b>Note:</b>Affidavit for GAP mentioned in BGC Form (mention month & Year) in Rs.100 Stamp Paper</i></label> -->
                                            </td>
                                            <td>
                                                <input type="file" name="gap_affidavit" id="gap_affidavit" class="gap_affidavit">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">2</th>
                                            <td>
                                                <label>Bank Account Verfication Letter</label>
                                            </td>
                                            <td>
                                                <input type="file" name="acc_verify" id="acc_verify" class="acc_verify">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <br><br>
                        <div class="text-center">
                            <input type="submit" class="btn btn-primary" name="bgcSubmit" value="Submit">
                        </div>
                    </form>
                </div>
            </div>
            <?php } ?>
        </div>
        <script type="text/javascript">

            $(document).on('click','[name="add"]',function(){
                if($('.preEmpView').length < 4){
                    var copyClass = $(this).attr('data-add-class');
                    $clone = $('.'+copyClass)[0].outerHTML;
                    $('.'+copyClass).after($clone).removeClass($(this).attr('data-add-class')).show().find('input, select').prop('disabled', false);
                }else{
                    $('[name="add"]').prop('disabled',true);
                }
            });

            $(document).on('click','.remove',function(){
                $(this).parents('.preEmpView').remove();
                $('[name="add"]').prop('disabled',false);   
            });

            $(document).ready(function(){

                $( ".date_picker" ).datepicker({
                    dateFormat: 'dd-M-yy',
                });

                $("#docsInfo").validate({
                    ignore: [':disabled,:hidden'],
                    rules: {
                        doj: {
                            required:true,
                        },
                        bgc: {
                            required:true,
                        },
                        address_proof: {
                            required:true,
                        },
                        panCard: {
                            required:true,
                        },
                        ba_deputation_letter: {
                            required:true,
                        },
                        ba_offer_letter: {
                            required:true,
                        },
                        aadhar_proof: {
                            required:true,
                        },
                        photo: {
                            required:true,
                        },
                        'gAllSem[]': {
                            required:true,
                        },
                        gPC: {
                            required:true,
                        },
                        'preEmpPayslip[]': {
                            required: {
                                depends: function(element) {
                                    // console.log($(element).parents('table').find('.preEmpView').length,'element');
                                    // console.log($(element).is(':visible'),'view');
                                    if($(element).is(':visible')){
                                        return $(element).is(':visible');
                                    }
                                }
                            }
                        },
                        'preEmpOL[]': {
                            required:true
                        },
                        'preEmpEL[]': {
                            required:true
                        },
                        'preEmpRL[]': {
                            required:true
                        },
                        'preEmpBankState[]': {
                            required:true
                        }
                    },
                    messages: {
                        doj: {
                            required: "Please enter the DOJ"
                        },
                        bgc: {
                            required: "Please upload your filled BGC Form"
                        },
                        address_proof: {
                            required: "Please upload your address proof"
                        },
                        panCard: {
                            required: "Please upload your pan card"
                        },
                        ba_deputation_letter: {
                            required: "Please upload your BA Vendor Deputation Letter "
                        },
                        ba_offer_letter: {
                            required: "Please upload your BA Vendor Offer Letter"
                        },
                        aadhar_proof: {
                            required: "Please upload your Aadhar proof Enrollment"
                        },
                        photo: {
                            required: "Please upload your recent passport size photo",
                        },
                        'gAllSem[]': {
                            required: "Please upload your all sem marksheet",
                        },
                        gPC: {
                            required: "Please upload your provisional certificate",
                        },
                        'preEmpPayslip[]': {
                            required: "Please upload your previous employeer (last 3 to 6 months) payslip"
                        },
                        'preEmpOL[]': {
                            required: "Please upload your previous employeer offer letter"
                        },
                        'preEmpEL[]': {
                            required: "Please upload your previous employeer experience letter"
                        },
                        'preEmpRL[]': {
                            required: "Please upload your previous employeer relieving letter"
                        },
                        'preEmpBankState[]': {
                            required: "Please upload your previous employeer bank statement"
                        }

                    },
                    onfocusout: false,
                    submitHandler: function(form, event) {
                        form.submit();
                    },
                    invalidHandler: function(event, validator) {
                        if(validator.numberOfInvalids()){
                        
                            var errors = validator.errorList;
                            console.log(errors,'errors');
                            $.each(errors, function(index, value){ 
                                console.log(value['element']);
                                $(value['element']).trigger( "focus" );
                                alert(value.message); 
                                return false;
                            });
                        }
                    },
                    errorPlacement: function(error, element) {
                    }
                });
            });
        </script>
    </body>
</html>