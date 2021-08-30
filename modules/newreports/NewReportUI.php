<?php
/*
 * CATS
 * Home Module
 *
 * Copyright (C) 2005 - 2007 Cognizo Technologies, Inc.
 *
 *
 * The contents of this file are subject to the CATS Public License
 * Version 1.1a (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.catsone.com/.
 *
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 *
 * The Original Code is "CATS Standard Edition".
 *
 * The Initial Developer of the Original Code is Cognizo Technologies, Inc.
 * Portions created by the Initial Developer are Copyright (C) 2005 - 2007
 * (or from the year in which this file was created to the year 2007) by
 * Cognizo Technologies, Inc. All Rights Reserved.
 *
 *
 * $Id: HomeUI.php 3810 2007-12-05 19:13:25Z brian $
 */

include_once('./lib/NewVersionCheck.php');
include_once('./lib/CommonErrors.php');
include_once('./lib/Dashboard.php');
include_once('./lib/NewReports.php');

include_once('./vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// require './vendor/autoload.php';

class NewReportUI extends UserInterface
{
    public function __construct()
    {
        parent::__construct();

        $this->_authenticationRequired = true;
        $this->_moduleDirectory = 'newreports';
        $this->_moduleName = 'newreports';
        $this->_moduleTabText = 'New Report';
        $this->_subTabs = array();
    }


    public function handleRequest()
    {
        $action = $this->getAction();

        switch ($action)
        {
            case 'writeExcel':
                // include_once('./lib/Search.php');
                // include_once('./lib/StringUtility.php');

                $this->writeExcel();
                break;  

            case 'report':
            default:
                $this->report();
                break;
        }
    }


    private function report()
    {        
        
        // echo "<pre>";
        // print_r($_POST);
        // echo "</pre>";
        
        $this->_template->assign('active', $this);
        $this->_template->assign('subActive', $this);
        $this->_template->display('./modules/newreports/getReport.tpl');
    }

    private function writeExcel(){
        
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";

        if(isset($_POST['reportName']) && !empty($_POST['reportName'])){
            
            if($_POST['reportName'] == 'Attendance'){
                $this->getAttendance();    
            }

            if($_POST['reportName'] == 'total_profile_sent'){
                $this->totalProfileSent();    
            }

            if($_POST['reportName'] == 'new_profile'){
                $this->newProfile();    
            }

            if($_POST['reportName'] == 'recycled_profile'){
                $this->recycledProfile();    
            }

            if($_POST['reportName'] == 'L1'){
                $this->L1();    
            }

            if($_POST['reportName'] == 'L2'){
                $this->L2();    
            }

            if($_POST['reportName'] == 'CE'){
                $this->CE();    
            }

            if($_POST['reportName'] == 'CE_not_available'){
                $this->CENotAvailable();    
            }

            if($_POST['reportName'] == 'CE_pending'){
                $this->CEPending();    
            }

            if($_POST['reportName'] == 'total_selects'){
                $this->totalSelects();    
            }

            if($_POST['reportName'] == 'not_available'){
                $this->notAvailable();    
            }

            if($_POST['reportName'] == 'pending'){
                $this->pending();    
            }

            if($_POST['reportName'] == 'responded'){
                $this->responded();    
            }

            if($_POST['reportName'] == 'no_show'){
                $this->NoShow();    
            }

            if($_POST['reportName'] == 'confirm'){
                $this->confirm();    
            }

            if($_POST['reportName'] == 'bgc_sent_customer'){
                $this->bgc_sent_customer();    
            }

            if($_POST['reportName'] == 'bgc_pending'){
                $this->bgc_pending();    
            }

            if($_POST['reportName'] == 'bgc_cleared'){
                $this->bgc_cleared();    
            }

            if($_POST['reportName'] == 'on_board'){
                $this->on_board();    
            }

            if($_POST['reportName'] == 'released'){
                $this->released();    
            }
            
        }else{
            // $this->report();
        }

    }

    private function getAttendance(){
        // $fileName = date('m-d-Y_hi').".xlsx";
        $fileName = "Attendance.xlsx";
        $spreadsheet = new Spreadsheet();
        // $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        // $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        // $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        // $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        // $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'S No')
            ->setCellValue('B1', 'Number of profiles Shortlisted')
            ->setCellValue('C1', 'Rectruiter Name')
            ->setCellValue('D1', 'Email ID')
            ->setCellValue('E1', 'First break Login time')
            ->setCellValue('F1', 'First break Logout time')
            ->setCellValue('G1', 'Lunch break Login time')
            ->setCellValue('H1', 'Lunch break Logout time')
            ->setCellValue('I1', 'Second break Login time')
            ->setCellValue('J1', 'EOD Logout time')
            ->setCellValue('K1', 'Effective Hours');


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_end_clean();
        $writer->save('php://output');
        exit;
    }

    private function totalProfileSent(){

        $fileName = "TotalProfileSent.xlsx";
        $spreadsheet = new Spreadsheet();

        $headingStyleArray = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '4a86e8'
                ]
            ]
        ];

        $spreadsheet->getActiveSheet()->getStyle('A1:M1')->applyFromArray($headingStyleArray)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        // $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        // $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(40);
        // $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        // $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        // $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(50);
        // $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(50);
        // $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        // $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(50);
        // $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(40);
        // $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        // $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(40);
        // $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(40);

        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'S.No')
            ->setCellValue('B1', 'RGS ID')
            ->setCellValue('C1', 'Candidate Name')
            ->setCellValue('D1', 'Shift')
            ->setCellValue('E1', 'Contact Number')
            ->setCellValue('F1', 'Primary Skill')
            ->setCellValue('G1', 'Secondary Skill')
            ->setCellValue('H1', 'Total Exp')
            ->setCellValue('I1', 'Current Organization')
            ->setCellValue('J1', 'Notice Period')
            ->setCellValue('K1', 'Gap')
            ->setCellValue('L1', 'Current Location')
            ->setCellValue('M1', 'Preferred Location');

        $data = new NewReports($this->_siteID);
        $getProfile = $data->getCandidateDetails();
        $sno=1;
        $rowNumber = 2;
        foreach ($getProfile as $key => $value) {
            $spreadsheet->setActiveSheetIndex(0)        
                ->setCellValue('A'.$rowNumber, $sno)
                ->setCellValue('B'.$rowNumber, '')
                ->setCellValue('C'.$rowNumber, $value['candidate_name'])
                ->setCellValue('D'.$rowNumber, '')
                ->setCellValue('E'.$rowNumber, $value['phone_home'])
                ->setCellValue('F'.$rowNumber, $value['key_skills'])
                ->setCellValue('G'.$rowNumber, '')
                ->setCellValue('H'.$rowNumber, $value['totalExp'])
                ->setCellValue('I'.$rowNumber, $value['current_employer'])
                ->setCellValue('J'.$rowNumber, '')
                ->setCellValue('K'.$rowNumber, '')
                ->setCellValue('L'.$rowNumber, $value['currentCity'])
                ->setCellValue('M'.$rowNumber, $value['preferredCity']);
            $sno++;
            $rowNumber++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_end_clean();
        $writer->save('php://output');
        exit;
        
    }

    private function newProfile(){
        $fileName = "NewProfile.xlsx";
        $spreadsheet = new Spreadsheet();

        $headingStyleArray = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '4a86e8'
                ]
            ]
        ];
        
        $spreadsheet->getActiveSheet()->getStyle('A1:M1')->applyFromArray($headingStyleArray)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'S.No')
            ->setCellValue('B1', 'RGS ID')
            ->setCellValue('C1', 'Candidate Name')
            ->setCellValue('D1', 'Shift')
            ->setCellValue('E1', 'Contact Number')
            ->setCellValue('F1', 'Primary Skill')
            ->setCellValue('G1', 'Secondary Skill')
            ->setCellValue('H1', 'Total Exp')
            ->setCellValue('I1', 'Current Organization')
            ->setCellValue('J1', 'Notice Period')
            ->setCellValue('K1', 'Gap')
            ->setCellValue('L1', 'Current Location')
            ->setCellValue('M1', 'Preferred Location');

        $data = new NewReports($this->_siteID);
        $getProfile = $data->getCandidateDetails();
        $sno=1;
        $rowNumber = 2;
        foreach ($getProfile as $key => $value) {
            $spreadsheet->setActiveSheetIndex(0)        
                ->setCellValue('A'.$rowNumber, $sno)
                ->setCellValue('B'.$rowNumber, '')
                ->setCellValue('C'.$rowNumber, $value['candidate_name'])
                ->setCellValue('D'.$rowNumber, '')
                ->setCellValue('E'.$rowNumber, $value['phone_home'])
                ->setCellValue('F'.$rowNumber, $value['key_skills'])
                ->setCellValue('G'.$rowNumber, '')
                ->setCellValue('H'.$rowNumber, $value['totalExp'])
                ->setCellValue('I'.$rowNumber, $value['current_employer'])
                ->setCellValue('J'.$rowNumber, '')
                ->setCellValue('K'.$rowNumber, '')
                ->setCellValue('L'.$rowNumber, $value['currentCity'])
                ->setCellValue('M'.$rowNumber, $value['preferredCity']);
            $sno++;
            $rowNumber++;
        }


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_end_clean();
        $writer->save('php://output');
        exit;
    }

    private function recycledProfile(){
        $fileName = "RecycledProfile.xlsx";
        $spreadsheet = new Spreadsheet();

        $headingStyleArray = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '4a86e8'
                ]
            ]
        ];
        
        $spreadsheet->getActiveSheet()->getStyle('A1:M1')->applyFromArray($headingStyleArray)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'S.No')
            ->setCellValue('B1', 'RGS ID')
            ->setCellValue('C1', 'Candidate Name')
            ->setCellValue('D1', 'Shift')
            ->setCellValue('E1', 'Contact Number')
            ->setCellValue('F1', 'Primary Skill')
            ->setCellValue('G1', 'Secondary Skill')
            ->setCellValue('H1', 'Total Exp')
            ->setCellValue('I1', 'Current Organization')
            ->setCellValue('J1', 'Notice Period')
            ->setCellValue('K1', 'Gap')
            ->setCellValue('L1', 'Current Location')
            ->setCellValue('M1', 'Preferred Location');

        $data = new NewReports($this->_siteID);
        $getProfile = $data->getCandidateDetails();
        $sno=1;
        $rowNumber = 2;
        foreach ($getProfile as $key => $value) {
            $spreadsheet->setActiveSheetIndex(0)        
                ->setCellValue('A'.$rowNumber, $sno)
                ->setCellValue('B'.$rowNumber, '')
                ->setCellValue('C'.$rowNumber, $value['candidate_name'])
                ->setCellValue('D'.$rowNumber, '')
                ->setCellValue('E'.$rowNumber, $value['phone_home'])
                ->setCellValue('F'.$rowNumber, $value['key_skills'])
                ->setCellValue('G'.$rowNumber, '')
                ->setCellValue('H'.$rowNumber, $value['totalExp'])
                ->setCellValue('I'.$rowNumber, $value['current_employer'])
                ->setCellValue('J'.$rowNumber, '')
                ->setCellValue('K'.$rowNumber, '')
                ->setCellValue('L'.$rowNumber, $value['currentCity'])
                ->setCellValue('M'.$rowNumber, $value['preferredCity']);
            $sno++;
            $rowNumber++;
        }


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_end_clean();
        $writer->save('php://output');
        exit;
    }

    private function L1(){
        $fileName = "L1.xlsx";
        $spreadsheet = new Spreadsheet();

        $headingStyleArray = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '4a86e8'
                ]
            ]
        ];
        
        $spreadsheet->getActiveSheet()->getStyle('A1:M1')->applyFromArray($headingStyleArray)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'S.No')
            ->setCellValue('B1', 'RGS ID')
            ->setCellValue('C1', 'Candidate Name')
            ->setCellValue('D1', 'Shift')
            ->setCellValue('E1', 'Contact Number')
            ->setCellValue('F1', 'Primary Skill')
            ->setCellValue('G1', 'Secondary Skill')
            ->setCellValue('H1', 'Total Exp')
            ->setCellValue('I1', 'Current Organization')
            ->setCellValue('J1', 'Notice Period')
            ->setCellValue('K1', 'Gap')
            ->setCellValue('L1', 'Current Location')
            ->setCellValue('M1', 'Preferred Location');

        $data = new NewReports($this->_siteID);
        $getProfile = $data->getCandidateDetails(525);
        $sno=1;
        $rowNumber = 2;
        foreach ($getProfile as $key => $value) {
            $spreadsheet->setActiveSheetIndex(0)        
                ->setCellValue('A'.$rowNumber, $sno)
                ->setCellValue('B'.$rowNumber, '')
                ->setCellValue('C'.$rowNumber, $value['candidate_name'])
                ->setCellValue('D'.$rowNumber, '')
                ->setCellValue('E'.$rowNumber, $value['phone_home'])
                ->setCellValue('F'.$rowNumber, $value['key_skills'])
                ->setCellValue('G'.$rowNumber, '')
                ->setCellValue('H'.$rowNumber, $value['totalExp'])
                ->setCellValue('I'.$rowNumber, $value['current_employer'])
                ->setCellValue('J'.$rowNumber, '')
                ->setCellValue('K'.$rowNumber, '')
                ->setCellValue('L'.$rowNumber, $value['currentCity'])
                ->setCellValue('M'.$rowNumber, $value['preferredCity']);
            $sno++;
            $rowNumber++;
        }


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_end_clean();
        $writer->save('php://output');
        exit;
    }

    private function L2(){
        $fileName = "L2.xlsx";
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'S.No')
            ->setCellValue('B1', 'RGS ID')
            ->setCellValue('C1', 'Candidate Name')
            ->setCellValue('D1', 'Shift')
            ->setCellValue('E1', 'Contact Number')
            ->setCellValue('F1', 'Primary Skill')
            ->setCellValue('G1', 'Secondary Skill')
            ->setCellValue('H1', 'Total Exp')
            ->setCellValue('I1', 'Current Organization')
            ->setCellValue('J1', 'Notice Period')
            ->setCellValue('K1', 'Gap')
            ->setCellValue('L1', 'Current Location')
            ->setCellValue('M1', 'Preferred Location');

        $data = new NewReports($this->_siteID);
        $getProfile = $data->getCandidateDetails(550);
        $sno=1;
        $rowNumber = 2;
        foreach ($getProfile as $key => $value) {
            $spreadsheet->setActiveSheetIndex(0)        
                ->setCellValue('A'.$rowNumber, $sno)
                ->setCellValue('B'.$rowNumber, '')
                ->setCellValue('C'.$rowNumber, $value['candidate_name'])
                ->setCellValue('D'.$rowNumber, '')
                ->setCellValue('E'.$rowNumber, $value['phone_home'])
                ->setCellValue('F'.$rowNumber, $value['key_skills'])
                ->setCellValue('G'.$rowNumber, '')
                ->setCellValue('H'.$rowNumber, $value['totalExp'])
                ->setCellValue('I'.$rowNumber, $value['current_employer'])
                ->setCellValue('J'.$rowNumber, '')
                ->setCellValue('K'.$rowNumber, '')
                ->setCellValue('L'.$rowNumber, $value['currentCity'])
                ->setCellValue('M'.$rowNumber, $value['preferredCity']);
            $sno++;
            $rowNumber++;
        }


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_end_clean();
        $writer->save('php://output');
        exit;
    }

    private function CE(){
        $fileName = "CE.xlsx";
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'S.No')
            ->setCellValue('B1', 'RGS ID')
            ->setCellValue('C1', 'Candidate Name')
            ->setCellValue('D1', 'Shift')
            ->setCellValue('E1', 'Contact Number')
            ->setCellValue('F1', 'Primary Skill')
            ->setCellValue('G1', 'Secondary Skill')
            ->setCellValue('H1', 'Total Exp')
            ->setCellValue('I1', 'Current Organization')
            ->setCellValue('J1', 'Notice Period')
            ->setCellValue('K1', 'Gap')
            ->setCellValue('L1', 'Current Location')
            ->setCellValue('M1', 'Preferred Location');

        $data = new NewReports($this->_siteID);
        $getProfile = $data->getCandidateDetails(560);
        $sno=1;
        $rowNumber = 2;
        foreach ($getProfile as $key => $value) {
            $spreadsheet->setActiveSheetIndex(0)        
                ->setCellValue('A'.$rowNumber, $sno)
                ->setCellValue('B'.$rowNumber, '')
                ->setCellValue('C'.$rowNumber, $value['candidate_name'])
                ->setCellValue('D'.$rowNumber, '')
                ->setCellValue('E'.$rowNumber, $value['phone_home'])
                ->setCellValue('F'.$rowNumber, $value['key_skills'])
                ->setCellValue('G'.$rowNumber, '')
                ->setCellValue('H'.$rowNumber, $value['totalExp'])
                ->setCellValue('I'.$rowNumber, $value['current_employer'])
                ->setCellValue('J'.$rowNumber, '')
                ->setCellValue('K'.$rowNumber, '')
                ->setCellValue('L'.$rowNumber, $value['currentCity'])
                ->setCellValue('M'.$rowNumber, $value['preferredCity']);
            $sno++;
            $rowNumber++;
        }


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_end_clean();
        $writer->save('php://output');
        exit;
    }

    private function CENotAvailable(){
        $fileName = "CENotAvailable.xlsx";
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'S.No')
            ->setCellValue('B1', 'RGS ID')
            ->setCellValue('C1', 'Candidate Name')
            ->setCellValue('D1', 'Shift')
            ->setCellValue('E1', 'Contact Number')
            ->setCellValue('F1', 'Primary Skill')
            ->setCellValue('G1', 'Secondary Skill')
            ->setCellValue('H1', 'Total Exp')
            ->setCellValue('I1', 'Current Organization')
            ->setCellValue('J1', 'Notice Period')
            ->setCellValue('K1', 'Gap')
            ->setCellValue('L1', 'Current Location')
            ->setCellValue('M1', 'Preferred Location');

        $data = new NewReports($this->_siteID);
        $getProfile = $data->getCandidateDetails(580);
        $sno=1;
        $rowNumber = 2;
        foreach ($getProfile as $key => $value) {
            $spreadsheet->setActiveSheetIndex(0)        
                ->setCellValue('A'.$rowNumber, $sno)
                ->setCellValue('B'.$rowNumber, '')
                ->setCellValue('C'.$rowNumber, $value['candidate_name'])
                ->setCellValue('D'.$rowNumber, '')
                ->setCellValue('E'.$rowNumber, $value['phone_home'])
                ->setCellValue('F'.$rowNumber, $value['key_skills'])
                ->setCellValue('G'.$rowNumber, '')
                ->setCellValue('H'.$rowNumber, $value['totalExp'])
                ->setCellValue('I'.$rowNumber, $value['current_employer'])
                ->setCellValue('J'.$rowNumber, '')
                ->setCellValue('K'.$rowNumber, '')
                ->setCellValue('L'.$rowNumber, $value['currentCity'])
                ->setCellValue('M'.$rowNumber, $value['preferredCity']);
            $sno++;
            $rowNumber++;
        }


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_end_clean();
        $writer->save('php://output');
        exit;
    }

    private function CEPending(){
        $fileName = "CEPending.xlsx";
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'S.No')
            ->setCellValue('B1', 'RGS ID')
            ->setCellValue('C1', 'Candidate Name')
            ->setCellValue('D1', 'Shift')
            ->setCellValue('E1', 'Contact Number')
            ->setCellValue('F1', 'Primary Skill')
            ->setCellValue('G1', 'Secondary Skill')
            ->setCellValue('H1', 'Total Exp')
            ->setCellValue('I1', 'Current Organization')
            ->setCellValue('J1', 'Notice Period')
            ->setCellValue('K1', 'Gap')
            ->setCellValue('L1', 'Current Location')
            ->setCellValue('M1', 'Preferred Location');

        $data = new NewReports($this->_siteID);
        $getProfile = $data->getCandidateDetails(590);
        $sno=1;
        $rowNumber = 2;
        foreach ($getProfile as $key => $value) {
            $spreadsheet->setActiveSheetIndex(0)        
                ->setCellValue('A'.$rowNumber, $sno)
                ->setCellValue('B'.$rowNumber, '')
                ->setCellValue('C'.$rowNumber, $value['candidate_name'])
                ->setCellValue('D'.$rowNumber, '')
                ->setCellValue('E'.$rowNumber, $value['phone_home'])
                ->setCellValue('F'.$rowNumber, $value['key_skills'])
                ->setCellValue('G'.$rowNumber, '')
                ->setCellValue('H'.$rowNumber, $value['totalExp'])
                ->setCellValue('I'.$rowNumber, $value['current_employer'])
                ->setCellValue('J'.$rowNumber, '')
                ->setCellValue('K'.$rowNumber, '')
                ->setCellValue('L'.$rowNumber, $value['currentCity'])
                ->setCellValue('M'.$rowNumber, $value['preferredCity']);
            $sno++;
            $rowNumber++;
        }


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_end_clean();
        $writer->save('php://output');
        exit;
    }


    private function notAvailable(){
        $fileName = "notAvailable.xlsx";
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'S.No')
            ->setCellValue('B1', 'RGS ID')
            ->setCellValue('C1', 'Candidate Name')
            ->setCellValue('D1', 'Shift')
            ->setCellValue('E1', 'Contact Number')
            ->setCellValue('F1', 'Primary Skill')
            ->setCellValue('G1', 'Secondary Skill')
            ->setCellValue('H1', 'Total Exp')
            ->setCellValue('I1', 'Current Organization')
            ->setCellValue('J1', 'Notice Period')
            ->setCellValue('K1', 'Gap')
            ->setCellValue('L1', 'Current Location')
            ->setCellValue('M1', 'Preferred Location');

        $data = new NewReports($this->_siteID);
        $getProfile = $data->getCandidateDetails(975);
        $sno=1;
        $rowNumber = 2;
        foreach ($getProfile as $key => $value) {
            $spreadsheet->setActiveSheetIndex(0)        
                ->setCellValue('A'.$rowNumber, $sno)
                ->setCellValue('B'.$rowNumber, '')
                ->setCellValue('C'.$rowNumber, $value['candidate_name'])
                ->setCellValue('D'.$rowNumber, '')
                ->setCellValue('E'.$rowNumber, $value['phone_home'])
                ->setCellValue('F'.$rowNumber, $value['key_skills'])
                ->setCellValue('G'.$rowNumber, '')
                ->setCellValue('H'.$rowNumber, $value['totalExp'])
                ->setCellValue('I'.$rowNumber, $value['current_employer'])
                ->setCellValue('J'.$rowNumber, '')
                ->setCellValue('K'.$rowNumber, '')
                ->setCellValue('L'.$rowNumber, $value['currentCity'])
                ->setCellValue('M'.$rowNumber, $value['preferredCity']);
            $sno++;
            $rowNumber++;
        }


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_end_clean();
        $writer->save('php://output');
        exit;
    }

    private function pending(){
        $fileName = "pending.xlsx";
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'S.No')
            ->setCellValue('B1', 'RGS ID')
            ->setCellValue('C1', 'Candidate Name')
            ->setCellValue('D1', 'Shift')
            ->setCellValue('E1', 'Contact Number')
            ->setCellValue('F1', 'Primary Skill')
            ->setCellValue('G1', 'Secondary Skill')
            ->setCellValue('H1', 'Total Exp')
            ->setCellValue('I1', 'Current Organization')
            ->setCellValue('J1', 'Notice Period')
            ->setCellValue('K1', 'Gap')
            ->setCellValue('L1', 'Current Location')
            ->setCellValue('M1', 'Preferred Location');

        $data = new NewReports($this->_siteID);
        $getProfile = $data->getCandidateDetails(675);
        $sno=1;
        $rowNumber = 2;
        foreach ($getProfile as $key => $value) {
            $spreadsheet->setActiveSheetIndex(0)        
                ->setCellValue('A'.$rowNumber, $sno)
                ->setCellValue('B'.$rowNumber, '')
                ->setCellValue('C'.$rowNumber, $value['candidate_name'])
                ->setCellValue('D'.$rowNumber, '')
                ->setCellValue('E'.$rowNumber, $value['phone_home'])
                ->setCellValue('F'.$rowNumber, $value['key_skills'])
                ->setCellValue('G'.$rowNumber, '')
                ->setCellValue('H'.$rowNumber, $value['totalExp'])
                ->setCellValue('I'.$rowNumber, $value['current_employer'])
                ->setCellValue('J'.$rowNumber, '')
                ->setCellValue('K'.$rowNumber, '')
                ->setCellValue('L'.$rowNumber, $value['currentCity'])
                ->setCellValue('M'.$rowNumber, $value['preferredCity']);
            $sno++;
            $rowNumber++;
        }


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_end_clean();
        $writer->save('php://output');
        exit;
    }

    private function responded(){
        $fileName = "responded.xlsx";
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'S.No')
            ->setCellValue('B1', 'RGS ID')
            ->setCellValue('C1', 'Candidate Name')
            ->setCellValue('D1', 'Shift')
            ->setCellValue('E1', 'Contact Number')
            ->setCellValue('F1', 'Primary Skill')
            ->setCellValue('G1', 'Secondary Skill')
            ->setCellValue('H1', 'Total Exp')
            ->setCellValue('I1', 'Current Organization')
            ->setCellValue('J1', 'Notice Period')
            ->setCellValue('K1', 'Gap')
            ->setCellValue('L1', 'Current Location')
            ->setCellValue('M1', 'Preferred Location');

        $data = new NewReports($this->_siteID);
        $getProfile = $data->getCandidateDetails(940);
        $sno=1;
        $rowNumber = 2;
        foreach ($getProfile as $key => $value) {
            $spreadsheet->setActiveSheetIndex(0)        
                ->setCellValue('A'.$rowNumber, $sno)
                ->setCellValue('B'.$rowNumber, '')
                ->setCellValue('C'.$rowNumber, $value['candidate_name'])
                ->setCellValue('D'.$rowNumber, '')
                ->setCellValue('E'.$rowNumber, $value['phone_home'])
                ->setCellValue('F'.$rowNumber, $value['key_skills'])
                ->setCellValue('G'.$rowNumber, '')
                ->setCellValue('H'.$rowNumber, $value['totalExp'])
                ->setCellValue('I'.$rowNumber, $value['current_employer'])
                ->setCellValue('J'.$rowNumber, '')
                ->setCellValue('K'.$rowNumber, '')
                ->setCellValue('L'.$rowNumber, $value['currentCity'])
                ->setCellValue('M'.$rowNumber, $value['preferredCity']);
            $sno++;
            $rowNumber++;
        }


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_end_clean();
        $writer->save('php://output');
        exit;
    }

    private function NoShow(){
        $fileName = "NoShow.xlsx";
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'S.No')
            ->setCellValue('B1', 'RGS ID')
            ->setCellValue('C1', 'Candidate Name')
            ->setCellValue('D1', 'Shift')
            ->setCellValue('E1', 'Contact Number')
            ->setCellValue('F1', 'Primary Skill')
            ->setCellValue('G1', 'Secondary Skill')
            ->setCellValue('H1', 'Total Exp')
            ->setCellValue('I1', 'Current Organization')
            ->setCellValue('J1', 'Notice Period')
            ->setCellValue('K1', 'Gap')
            ->setCellValue('L1', 'Current Location')
            ->setCellValue('M1', 'Preferred Location');

        $data = new NewReports($this->_siteID);
        $getProfile = $data->getCandidateDetails(945);
        $sno=1;
        $rowNumber = 2;
        foreach ($getProfile as $key => $value) {
            $spreadsheet->setActiveSheetIndex(0)        
                ->setCellValue('A'.$rowNumber, $sno)
                ->setCellValue('B'.$rowNumber, '')
                ->setCellValue('C'.$rowNumber, $value['candidate_name'])
                ->setCellValue('D'.$rowNumber, '')
                ->setCellValue('E'.$rowNumber, $value['phone_home'])
                ->setCellValue('F'.$rowNumber, $value['key_skills'])
                ->setCellValue('G'.$rowNumber, '')
                ->setCellValue('H'.$rowNumber, $value['totalExp'])
                ->setCellValue('I'.$rowNumber, $value['current_employer'])
                ->setCellValue('J'.$rowNumber, '')
                ->setCellValue('K'.$rowNumber, '')
                ->setCellValue('L'.$rowNumber, $value['currentCity'])
                ->setCellValue('M'.$rowNumber, $value['preferredCity']);
            $sno++;
            $rowNumber++;
        }


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_end_clean();
        $writer->save('php://output');
        exit;
    }

    private function confirm(){
        $fileName = "confirm.xlsx";
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'S.No')
            ->setCellValue('B1', 'RGS ID')
            ->setCellValue('C1', 'Candidate Name')
            ->setCellValue('D1', 'Shift')
            ->setCellValue('E1', 'Contact Number')
            ->setCellValue('F1', 'Primary Skill')
            ->setCellValue('G1', 'Secondary Skill')
            ->setCellValue('H1', 'Total Exp')
            ->setCellValue('I1', 'Current Organization')
            ->setCellValue('J1', 'Notice Period')
            ->setCellValue('K1', 'Gap')
            ->setCellValue('L1', 'Current Location')
            ->setCellValue('M1', 'Preferred Location');

        $data = new NewReports($this->_siteID);
        $getProfile = $data->getCandidateDetails(955);
        $sno=1;
        $rowNumber = 2;
        foreach ($getProfile as $key => $value) {
            $spreadsheet->setActiveSheetIndex(0)        
                ->setCellValue('A'.$rowNumber, $sno)
                ->setCellValue('B'.$rowNumber, '')
                ->setCellValue('C'.$rowNumber, $value['candidate_name'])
                ->setCellValue('D'.$rowNumber, '')
                ->setCellValue('E'.$rowNumber, $value['phone_home'])
                ->setCellValue('F'.$rowNumber, $value['key_skills'])
                ->setCellValue('G'.$rowNumber, '')
                ->setCellValue('H'.$rowNumber, $value['totalExp'])
                ->setCellValue('I'.$rowNumber, $value['current_employer'])
                ->setCellValue('J'.$rowNumber, '')
                ->setCellValue('K'.$rowNumber, '')
                ->setCellValue('L'.$rowNumber, $value['currentCity'])
                ->setCellValue('M'.$rowNumber, $value['preferredCity']);
            $sno++;
            $rowNumber++;
        }


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_end_clean();
        $writer->save('php://output');
        exit;
    }

    private function bgc_sent_customer(){
        $fileName = "bgc_sent_customer.xlsx";
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'S.No')
            ->setCellValue('B1', 'RGS ID')
            ->setCellValue('C1', 'Candidate Name')
            ->setCellValue('D1', 'Shift')
            ->setCellValue('E1', 'Contact Number')
            ->setCellValue('F1', 'Primary Skill')
            ->setCellValue('G1', 'Secondary Skill')
            ->setCellValue('H1', 'Total Exp')
            ->setCellValue('I1', 'Current Organization')
            ->setCellValue('J1', 'Notice Period')
            ->setCellValue('K1', 'Gap')
            ->setCellValue('L1', 'Current Location')
            ->setCellValue('M1', 'Preferred Location');

        $data = new NewReports($this->_siteID);
        $getProfile = $data->getCandidateDetails(910);
        $sno=1;
        $rowNumber = 2;
        foreach ($getProfile as $key => $value) {
            $spreadsheet->setActiveSheetIndex(0)        
                ->setCellValue('A'.$rowNumber, $sno)
                ->setCellValue('B'.$rowNumber, '')
                ->setCellValue('C'.$rowNumber, $value['candidate_name'])
                ->setCellValue('D'.$rowNumber, '')
                ->setCellValue('E'.$rowNumber, $value['phone_home'])
                ->setCellValue('F'.$rowNumber, $value['key_skills'])
                ->setCellValue('G'.$rowNumber, '')
                ->setCellValue('H'.$rowNumber, $value['totalExp'])
                ->setCellValue('I'.$rowNumber, $value['current_employer'])
                ->setCellValue('J'.$rowNumber, '')
                ->setCellValue('K'.$rowNumber, '')
                ->setCellValue('L'.$rowNumber, $value['currentCity'])
                ->setCellValue('M'.$rowNumber, $value['preferredCity']);
            $sno++;
            $rowNumber++;
        }


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_end_clean();
        $writer->save('php://output');
        exit;
    }

    private function bgc_pending(){
        $fileName = "bgc_pending.xlsx";
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'S.No')
            ->setCellValue('B1', 'RGS ID')
            ->setCellValue('C1', 'Candidate Name')
            ->setCellValue('D1', 'Shift')
            ->setCellValue('E1', 'Contact Number')
            ->setCellValue('F1', 'Primary Skill')
            ->setCellValue('G1', 'Secondary Skill')
            ->setCellValue('H1', 'Total Exp')
            ->setCellValue('I1', 'Current Organization')
            ->setCellValue('J1', 'Notice Period')
            ->setCellValue('K1', 'Gap')
            ->setCellValue('L1', 'Current Location')
            ->setCellValue('M1', 'Preferred Location');

        $data = new NewReports($this->_siteID);
        $getProfile = $data->getCandidateDetails(920);
        $sno=1;
        $rowNumber = 2;
        foreach ($getProfile as $key => $value) {
            $spreadsheet->setActiveSheetIndex(0)        
                ->setCellValue('A'.$rowNumber, $sno)
                ->setCellValue('B'.$rowNumber, '')
                ->setCellValue('C'.$rowNumber, $value['candidate_name'])
                ->setCellValue('D'.$rowNumber, '')
                ->setCellValue('E'.$rowNumber, $value['phone_home'])
                ->setCellValue('F'.$rowNumber, $value['key_skills'])
                ->setCellValue('G'.$rowNumber, '')
                ->setCellValue('H'.$rowNumber, $value['totalExp'])
                ->setCellValue('I'.$rowNumber, $value['current_employer'])
                ->setCellValue('J'.$rowNumber, '')
                ->setCellValue('K'.$rowNumber, '')
                ->setCellValue('L'.$rowNumber, $value['currentCity'])
                ->setCellValue('M'.$rowNumber, $value['preferredCity']);
            $sno++;
            $rowNumber++;
        }


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_end_clean();
        $writer->save('php://output');
        exit;
    }

    private function bgc_cleared(){
        $fileName = "bgc_cleared.xlsx";
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'S.No')
            ->setCellValue('B1', 'RGS ID')
            ->setCellValue('C1', 'Candidate Name')
            ->setCellValue('D1', 'Shift')
            ->setCellValue('E1', 'Contact Number')
            ->setCellValue('F1', 'Primary Skill')
            ->setCellValue('G1', 'Secondary Skill')
            ->setCellValue('H1', 'Total Exp')
            ->setCellValue('I1', 'Current Organization')
            ->setCellValue('J1', 'Notice Period')
            ->setCellValue('K1', 'Gap')
            ->setCellValue('L1', 'Current Location')
            ->setCellValue('M1', 'Preferred Location');

        $data = new NewReports($this->_siteID);
        $getProfile = $data->getCandidateDetails(930);
        $sno=1;
        $rowNumber = 2;
        foreach ($getProfile as $key => $value) {
            $spreadsheet->setActiveSheetIndex(0)        
                ->setCellValue('A'.$rowNumber, $sno)
                ->setCellValue('B'.$rowNumber, '')
                ->setCellValue('C'.$rowNumber, $value['candidate_name'])
                ->setCellValue('D'.$rowNumber, '')
                ->setCellValue('E'.$rowNumber, $value['phone_home'])
                ->setCellValue('F'.$rowNumber, $value['key_skills'])
                ->setCellValue('G'.$rowNumber, '')
                ->setCellValue('H'.$rowNumber, $value['totalExp'])
                ->setCellValue('I'.$rowNumber, $value['current_employer'])
                ->setCellValue('J'.$rowNumber, '')
                ->setCellValue('K'.$rowNumber, '')
                ->setCellValue('L'.$rowNumber, $value['currentCity'])
                ->setCellValue('M'.$rowNumber, $value['preferredCity']);
            $sno++;
            $rowNumber++;
        }


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_end_clean();
        $writer->save('php://output');
        exit;
    }

    private function on_board(){
        $fileName = "on_board.xlsx";
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'S.No')
            ->setCellValue('B1', 'RGS ID')
            ->setCellValue('C1', 'Candidate Name')
            ->setCellValue('D1', 'Shift')
            ->setCellValue('E1', 'Contact Number')
            ->setCellValue('F1', 'Primary Skill')
            ->setCellValue('G1', 'Secondary Skill')
            ->setCellValue('H1', 'Total Exp')
            ->setCellValue('I1', 'Current Organization')
            ->setCellValue('J1', 'Notice Period')
            ->setCellValue('K1', 'Gap')
            ->setCellValue('L1', 'Current Location')
            ->setCellValue('M1', 'Preferred Location');

        $data = new NewReports($this->_siteID);
        $getProfile = $data->getCandidateDetails(980);
        $sno=1;
        $rowNumber = 2;
        foreach ($getProfile as $key => $value) {
            $spreadsheet->setActiveSheetIndex(0)        
                ->setCellValue('A'.$rowNumber, $sno)
                ->setCellValue('B'.$rowNumber, '')
                ->setCellValue('C'.$rowNumber, $value['candidate_name'])
                ->setCellValue('D'.$rowNumber, '')
                ->setCellValue('E'.$rowNumber, $value['phone_home'])
                ->setCellValue('F'.$rowNumber, $value['key_skills'])
                ->setCellValue('G'.$rowNumber, '')
                ->setCellValue('H'.$rowNumber, $value['totalExp'])
                ->setCellValue('I'.$rowNumber, $value['current_employer'])
                ->setCellValue('J'.$rowNumber, '')
                ->setCellValue('K'.$rowNumber, '')
                ->setCellValue('L'.$rowNumber, $value['currentCity'])
                ->setCellValue('M'.$rowNumber, $value['preferredCity']);
            $sno++;
            $rowNumber++;
        }


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_end_clean();
        $writer->save('php://output');
        exit;
    }

    private function released(){
        $fileName = "released.xlsx";
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'S.No')
            ->setCellValue('B1', 'RGS ID')
            ->setCellValue('C1', 'Candidate Name')
            ->setCellValue('D1', 'Shift')
            ->setCellValue('E1', 'Contact Number')
            ->setCellValue('F1', 'Primary Skill')
            ->setCellValue('G1', 'Secondary Skill')
            ->setCellValue('H1', 'Total Exp')
            ->setCellValue('I1', 'Current Organization')
            ->setCellValue('J1', 'Notice Period')
            ->setCellValue('K1', 'Gap')
            ->setCellValue('L1', 'Current Location')
            ->setCellValue('M1', 'Preferred Location');

        $data = new NewReports($this->_siteID);
        $getProfile = $data->getCandidateDetails(990);
        $sno=1;
        $rowNumber = 2;
        foreach ($getProfile as $key => $value) {
            $spreadsheet->setActiveSheetIndex(0)        
                ->setCellValue('A'.$rowNumber, $sno)
                ->setCellValue('B'.$rowNumber, '')
                ->setCellValue('C'.$rowNumber, $value['candidate_name'])
                ->setCellValue('D'.$rowNumber, '')
                ->setCellValue('E'.$rowNumber, $value['phone_home'])
                ->setCellValue('F'.$rowNumber, $value['key_skills'])
                ->setCellValue('G'.$rowNumber, '')
                ->setCellValue('H'.$rowNumber, $value['totalExp'])
                ->setCellValue('I'.$rowNumber, $value['current_employer'])
                ->setCellValue('J'.$rowNumber, '')
                ->setCellValue('K'.$rowNumber, '')
                ->setCellValue('L'.$rowNumber, $value['currentCity'])
                ->setCellValue('M'.$rowNumber, $value['preferredCity']);
            $sno++;
            $rowNumber++;
        }


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_end_clean();
        $writer->save('php://output');
        exit;
    }
}

?>
