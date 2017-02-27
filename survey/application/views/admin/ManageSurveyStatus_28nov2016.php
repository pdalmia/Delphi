<div class="page-content-wrapper">
    <div class="page-content">
        <?php
        $mess = $this->session->flashdata('message');
        if ($mess!='') { ?>
            <div class="alert-success2 alert">
                <?php echo $mess['message']; ?>
            </div>
        <?php }?>
        <!-- BEGIN PAGE CONTENT-->
        <div class="row">
            <div class="col-md-12">
                <?php include("RoundTabStatus.php");?>
                <div class="portlet box blue-hoki" id="form_wizard_1">
                    <div class="portlet-title">
                        <div class="caption"> <i class="fa fa-gift"></i> Survey Status &nbsp;::&nbsp; <?php echo $survey_name;?></div>
                        <div class="tools hidden-xs"> <a href="javascript:;" class="collapse"> </a> <a href="#portlet-config" data-toggle="modal" class="config"> </a> <a href="javascript:;" class="reload"> </a> <a href="javascript:;" class="remove"> </a> </div>
                    </div>
                    <div class="portlet-body form">
                        <form action="" class="form-horizontal" id="submit_form" method="POST">
                            <div class="form-wizard">
                                <div class="form-body">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab1">
                                            <div class="form-group">
                                                <table class="table table-striped table-bordered dataTable no-footer" id="search-sno" role="grid">
                                                    <thead class="bg-grey">
                                                        <tr role="row">
                                                            <th style="width: 30px;"></th>
                                                            <th>Name</th>
                                                            <th>Email ID</th>
															<th>Survey Status</th>
															<th>Link Emailed</th>
															<th>Url</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    $i = 0;
                                                    foreach ($data as $ulist) {
														$i =$i + 1;
													?>
                                                    <tr role="row">
														<td><?php echo $i;?></td>
                                                        <td><?php echo $ulist['UserName'];?></td>
                                                        <td><?php echo $ulist['Email'];?></td>
														<td><?php 
																$SurveyStatus = "";
																if ($ulist['SurveyStatus'] == "0")
																	$SurveyStatus = "Not Started";
																else if ($ulist['SurveyStatus'] == "1" || $ulist['SurveyStatus'] == "2" || $ulist['SurveyStatus'] == "3")
																	$SurveyStatus = "Started";
																else if ($ulist['SurveyStatus'] == "4")
																	$SurveyStatus = "Completed";
																echo $SurveyStatus;
															?>
														</td>
														<td><?php 
																$SurveyStatus = "";
																if ($ulist['SendEmail'] == "0")
																	$SurveyStatus = "No";
																else if ($ulist['SendEmail'] == "1")
																	$SurveyStatus = "Yes";
																echo $SurveyStatus;
															?>
														<td><?php echo BASE_URL_SURVEY.@$ulist['SurveyURL'];?></td>
                                                    </tr>
                                                    <?php }?>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label"></label>
                                                <div class="col-md-6">
                                                    <input type="hidden" name="Survey_Id" id="Survey_Id" value="<?php echo $survey_id;?>">
                                                    <input type="hidden" name="roundid" id="roundid" value="<?php echo $roundid;?>">
                                                    <a href="#portlet-config" data-toggle="modal" class="config"> </a>
													<button type="submit" name="DownloadData" value="DownloadData" class="btn green button-submit"> Download Data <i class="m-icon-swapright m-icon-white"></i> </button>
                                                    <a href="<?php echo base_url(); ?>Admin/ManageSurvey" class="btn green button-submit">Back</a>
                                                </div>
                                            </div>
											<?php
											include("phpexcel/Classes/PHPExcel.php");
											$i=7;
											$objPHPExcel = new PHPExcel();
											// Set document properties
											$objPHPExcel->getProperties()->setCreator("Ondai Team")
											->setLastModifiedBy("Ondai Team")
											->setTitle("Office 2007 XLSX Data Document")
											->setSubject("Office 2007 XLSX Data Document")
											->setCategory("Download data file");
											$styleArray = array(
																	'font' => array(
																	'bold' => true
																					));

											$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);

											$objPHPExcel->setActiveSheetIndex(0)
														->setCellValue('A1', 'UserName')
														->setCellValue('B1', 'SurveyURL')
														->setCellValue('C1', 'SurveyQuestion_Id')
														->setCellValue('D1', 'SurveyProposition_Id')
														->setCellValue('E1', 'Rating')
														->setCellValue('F1', 'Reason')
														->setCellValue('G1', 'CreatedDate')
														->setCellValue('H1', 'UpdatedDate');
											$objPHPExcel->getActiveSheet()->getStyle("A1:I1")->getFont()->setBold(true);
											
											for ($col = 'A'; $col !== 'H'; $col++)
											{
												$objPHPExcel->getActiveSheet()
														->getColumnDimension($col)
														->setAutoSize(true);
											}
											$rowCount=2;
											while ($rowr = mysql_fetch_array($getSurveyStatusData)) {
												$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $rowr[0]);
												$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $rowr[1]);
												$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $rowr[2]);
												$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $rowr[3]);
												$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $rowr[4]);
												$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $rowr[5]);
												$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $rowr[6]);
												$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $rowr[7]);
												$rowCount++;	
											}
											$xlsname = "Survey.xls";
											header('Content-Type: application/vnd.ms-excel');
											header('Content-Disposition: attachment;filename="' . $xlsname . '"');
											header('Cache-Control: max-age=0');
											// If you're serving to IE 9, then the following may be needed
											header('Cache-Control: max-age=1');

											// If you're serving to IE over SSL, then the following may be needed
											header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
											header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
											header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
											header('Pragma: public'); // HTTP/1.0

											$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
											$objWriter->save('php://output');
											exit;
											?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT-->
    </div>
</div>