<?php
class Basecontroller extends CI_Model {
    public function dateFormat($date, $format = '') 	{
        if ($format == '') 		{
            $format = "Y-m-d";
        }
        $newFormat = date($format, strtotime($date));
        return $newFormat;
    }
    public function manageColSpan($result, $i, $column) 	{
        //$i--;
        if (isset($_REQUEST['dwltype']) && isset($_REQUEST['print_report']) && $_REQUEST['dwltype'] == "csv") 		{
            return $result;
        }
        $j = $i - 1;
        if ($j >= 0) 		{
            $rowspan = 2;
            for ($j; $j >= 0; $j--) 			{
                //$result[$j]['mdate']['val'];
                if (is_array($result[$j][$column]) && is_array(@$result[$i][$column]) && $result[$j][$column]['val'] == @$result[$i][$column]['val']) 				{
                    $result[$j][$column]['rowspan'] = $rowspan;
                    $result[$j][$column]['val'] = @$result[$i][$column];
                    $rowspan++;
                } 				elseif (is_array($result[$j][$column]) && $result[$j][$column]['val'] == @$result[$i][$column]) 				{
                    $result[$j][$column]['rowspan'] = $rowspan;
                    $result[$j][$column]['val'] = @$result[$i][$column];
                    $rowspan++;
                } 				elseif ($result[$j][$column] === @$result[$i][$column]) 				{
                    $result[$j][$column] = "";
                    $result[$j][$column]['rowspan'] = $rowspan;
                    $result[$j][$column]['val'] = @$result[$i][$column];
                    $rowspan++;
                } 				else				{
                    if ($rowspan > 2) 					{
                        $Tdata = @$result[$i][$column];
                        unset($result[$i][$column]);
                        $result[$i][$column]['val'] = $Tdata;
                        $result[$i][$column]['rowspan'] = '1';
                    }
                    $rowspan = 0;
                    break;
                }
            }
            if ($rowspan > 2) {
                $Tdata = $result[$i][$column];
                unset($result[$i][$column]);
                $result[$i][$column]['val'] = $Tdata;
                $result[$i][$column]['rowspan'] = '1';
                $rowspan = 0;
            }
            if ($j != ($i - 1)) 			{
            }
        }
        return $result;
    }
    public function createdHtmlList($result, $tableClass = '', $border = '0', $scriptName = "document.URL",$tableid='') 	{
        $htmlStr = '';
        $htmlTableHead = "\n<table class=\"$tableClass\" border='$border' id='$tableid'> ";
        $numOfHeader = 0;
        $firstRow = true;
        $tableHeader = "";
        $_paramData = "";
        foreach ($_REQUEST as $key => $value) 		{
            if ($key == "SQLORDERBY" || $key == "SQLORDERBYTYPE" || $key == 'pageName') 			{
            } 			else 			{
                @$_paramData .= "&$key=$value";
            }
        }
        $result[-2]['PARAM'] = $_paramData; //"&fdate=".$formData['fdate']."&tdate=".$formData['tdate'];
        foreach ($result as $key => $row) 		{
            $hederColoumn = count($row);
            if ($key != "-1" && $key != "-2") {
                $htmlStr .="\n\t<tr>";
                foreach ($row as $key1 => $val1) 				{
                    if ($firstRow) 					{
                        $SQLORDERBY = "";
                        $_SortClass = "sort";
                        if (isset($_REQUEST['SQLORDERBY']) && $_REQUEST['SQLORDERBY'] == $key1) 						{
                            if (isset($_REQUEST['SQLORDERBYTYPE']) && $_REQUEST['SQLORDERBYTYPE'] == "desc") 							{
                                $_REQUEST1['SQLORDERBYTYPE'] = "asc";
                                $_SortClass = "desc";
                            } 							else 							{
                                if (@$_REQUEST['SQLORDERBYTYPE'] == "asc") 								{
                                    $_SortClass = "asc";
                                }
                                $_REQUEST1['SQLORDERBYTYPE'] = "desc";
                            }
                            $SQLORDERBY = "&SQLORDERBY=" . $key1 . "&SQLORDERBYTYPE=" . $_REQUEST1['SQLORDERBYTYPE'];
                        } 						else 						{
                            $SQLORDERBY = "&SQLORDERBY=" . $key1 . "&SQLORDERBYTYPE=asc";
                        }
                        if (isset($result[-1][$key1]) && is_array($result[-1][$key1])) 						{
                            if (isset($result[-1][$key1]['rowspan'])) 							{
                                if ((isset($_REQUEST['ORDERYBY']) && $_REQUEST['ORDERYBY'] == $result[-2][$key1]) || isset($result[-2][$key1])) 								{
                                    $tableHeader .="\n\t\t<th rowspan='" . $result[-1][$key1]['rowspan'] . "' onclick=\"javascript:showData(document.URL,'$SQLORDERBY" . $result[-2]['PARAM'] . "','" . $result[-2]['DISPLAYDIV'] . "');return false;\" class=\"$_SortClass\">" . $result[-1][$key1] . "</th>";
                                } 								else 								{
                                    $tableHeader .="\n\t\t<th rowspan='" . $result[-1][$key1]['rowspan'] . "'>" . $result[-1][$key1] . "</th>";
                                }
                            } 							else 							{
                                if (isset($result[-1][$key1]['colspan'])) 								{
                                    $tableHeader .="<th colspan='" . $result[-1][$key1]['colspan'] . "'>" . $result[-1][$key1]['label'];
                                    unset($result[-1][$key1]['colspan']);
                                    unset($result[-1][$key1]['label']);
                                } 								else 								{
                                    if ((isset($_REQUEST['ORDERYBY']) && $_REQUEST['ORDERYBY'] == $result[-2][$key1]) || isset($result[-2][$key1])) 									{
                                        $tableHeader .="\n\t\t<th rowspan ='2' onclick=\"javascript:showData($scriptName,'$SQLORDERBY" . $result[-2]['PARAM'] . "','" . $result[-2]['DISPLAYDIV'] . "');return false;\" class=\"$_SortClass\">" . $result[-1][$key1] . "</th>";
                                    } 									else 									{
                                        $tableHeader .="\n\t\t<th rowspan ='2' >" . $result[-1][$key1] . "</th>";
                                    }
                                }
                            }
                            //$tableHeader .="</th>";
                        } 						else if (isset($result[-1][$key1])) 						{
                            if ((isset($_REQUEST['ORDERYBY']) && $_REQUEST['ORDERYBY'] == $result[-2][$key1]) || isset($result[-2][$key1])) {
                                $tableHeader .="\n\t\t<th rowspan ='2' onclick=\"javascript:showData($scriptName,'$SQLORDERBY" . $result[-2]['PARAM'] . "','" . $result[-2]['DISPLAYDIV'] . "');return false;\" class=\"$_SortClass\">" . $result[-1][$key1] . "</th>";
                            } else {
                                $tableHeader .="\n\t\t<th rowspan ='2' >" . $result[-1][$key1] . "</th>";
                            }
                        } 						else if (!isset($result[-1])) 						{
                            //$tableHeader .="<th>$key1</th>";
                            if ((isset($_REQUEST['ORDERYBY']) && $_REQUEST['ORDERYBY'] == $result[-2][$key1]) || isset($result[-2][$key1])) 							{
                                $tableHeader .="\n\t\t<th onclick=\"javascript:showData($scriptName,'$SQLORDERBY" . $result[-2]['PARAM'] . "','" . $result[-2]['DISPLAYDIV'] . "');return false;\" class=\"$_SortClass\">" . $key1 . "</th>";
                            } 							else 							{
                                $tableHeader .="\n\t\t<th>" . $key1 . "</th>";
                            }
                        }
                        /* if(isset($result[-1])){
                          //$tableHeader .="\n\t\t<th>".$result[-1][$key1]."</th>";
                          if(isset($_REQUEST['ORDERYBY']) || isset($result[-2]))						  {
                          $tableHeader .="\n\t\t<th onclick=\"javascript:showData(document.URL,'$SQLORDERBY".$result[-2]['PARAM']."','".$result[-2]['DISPLAYDIV']."');return false;\" class=\"$_SortClass\">".$result[-1][$key1]."</th>";
                          }						  else						  {  
                          $tableHeader .="\n\t\t<th>".$result[-1][$key1]."</th>";
                          }
                          }/* */
                    }
                    if (is_array($val1)) 					{
                        if (@$skipThis[$key1] < 0) 						{
                            $htmlStr .="\n\t\t<td rowspan=\"" . $val1['rowspan'] . "\">" . $val1['val'] . "</td>";
                            $skipThis[$key1] = --$val1['rowspan'];
                        } 						elseif (@$skipThis[$key1] == 0) 						{
                            $htmlStr .="\n\t\t<td rowspan=\"" . $val1['rowspan'] . "\">" . $val1['val'] . "</td>";
                            $skipThis[$key1] = $val1['rowspan'] - 1;
                        } 						else 						{
                            $skipThis[$key1] --;
                        }
                    } 					else 					{
                        $htmlStr .="\n\t\t<td>" . $val1 . "</td>";
                    }
                }
                $htmlStr .="\n\t</tr>";
            }
            $firstRow = false;
            unset($result[$key]);
        }
        if (isset($result[-1])) 		{
            foreach ($result[-1] as $key1 => $val1) {
                if (is_array($val1)) {
                    $tableHeader .="\n\t<tr>";
                    foreach ($val1 as $key1 => $v) 					{
                        $SQLORDERBY = "";
                        $_SortClass = "sort";
                        if (isset($_REQUEST['SQLORDERBY']) && $_REQUEST['SQLORDERBY'] == $key1) 						{
                            if (isset($_REQUEST['SQLORDERBYTYPE']) && $_REQUEST['SQLORDERBYTYPE'] == "desc") 							{
                                $_REQUEST1['SQLORDERBYTYPE'] = "asc";
                                $_SortClass = "desc";
                                //echo "here $key1 ".$_REQUEST['SQLORDERBYTYPE'];
                            } 							else 							{
                                if (@$_REQUEST['SQLORDERBYTYPE'] == "asc") 								{
                                    $_SortClass = "asc";
                                }
                                $_REQUEST['SQLORDERBYTYPE'] = "desc";
                            }
                            $SQLORDERBY = "&SQLORDERBY=" . $key1 . "&SQLORDERBYTYPE=" . $_REQUEST1['SQLORDERBYTYPE'];
                        }						else 						{
                            $SQLORDERBY = "&SQLORDERBY=" . $key1 . "&SQLORDERBYTYPE=asc";
                        }
                        //if(isset($_REQUEST['ORDERYBY']) || isset($result[-2])){
                        $tableHeader .="\n\t\t<th onclick=\"javascript:showData($scriptName,'$SQLORDERBY" . $result[-2]['PARAM'] . "','" . $result[-2]['DISPLAYDIV'] . "');return false;\" >$v</th>";
                        //}else{
                        //	$tableHeader .="\n\t\t<th>$v</th>";
                        //}
                    }
                    $tableHeader .="\n\t</tr>";
                }
            }
        }
        return $htmlTableHead .="\n\t<thead class='bg-grey'>$tableHeader</thead>\n\t<tbody>$htmlStr</tbody>\n</table>";
    }
    public function dropdown($result = array(), $keyvalue, $value, $name, $fname = '', $selectId = '', $id = '', $dropval = '', $multiplesel = '', $selectAll = false) 	{
        $dropdown = '';
        $dropdown .="<select id='$id' name ='$name'  onchange='$fname(this.value)' class='form-control' $multiplesel>";
        if($dropval == '') 		{
            $dropval = '-Select-';
        }
        if ($selectAll) 		{
            $dropdown .="<option value ='All' >All</option>";
        } 		else 		{
            $dropdown .="<option value ='' >-$dropval-</option>";
        }
        if (is_array($result) && count($result) > 0) 		{
            foreach ($result as $key => $val) 			{
                if ($selectId == $val[$keyvalue] && $val[$keyvalue] != '') 				{
                    $dropdown .="<option value ='" . $val[$value] . "'  selected='selected' >" . $val[$keyvalue] . "</option>";
                } 				else if ($val[$keyvalue] == '') 				{
                } 				else 				{
                    $dropdown .="<option value ='" . $val[$value] . "'>" . $val[$keyvalue] . "</option>";
                }
            }
        }
        $dropdown .="</select>";
        return $dropdown;
    }
    public function checkbox($result = array(), $keyvalue, $name, $fname = '', $selectId = '', $id = '') 	{
        $dropdown = '';
        if (is_array($result) && count($result) > 0) 		{
            foreach ($result as $key => $val) 			{
                if ($selectId == $val[$keyvalue] && $val[$keyvalue] != '') 				{
                    $dropdown .="<input type='checkbox' id='$id' name ='" . $name . "[]'  value='" . $val[$keyvalue] . "'  checked > " . $val[$keyvalue] . "";
                    //$dropdown .="<option value ='".$val[$keyvalue]."'  selected='selected' >".$val[$keyvalue]."</option>";
                } 				else if ($val[$keyvalue] == '') 				{				} 				else 				{
                    $dropdown .="<input type='checkbox' id='$id' name ='" . $name . "[]' value='" . $val[$keyvalue] . "'   > " . $val[$keyvalue] . "";
                }
            }
        }
        return $dropdown;
    }	
    public function validateServer() 	{
        global $_DATA, $dbObj;
        global $validate_data;
        $error = 0;
        $Validate = new Validate();
        foreach ($validate_data as $formkey => $formName) 		{
            //echo $formkey."---------";
            if (isset($formName['UNIQUE']) && $formName['UNIQUE'] == "true") 			{
                if ($dbObj->checkUnique($formkey, trim(getValueGPC($formkey))) > 0) 				{
                    $_DATA["ERROR_" . $formkey] = $formName['ERROR_UNIQUE'];
                    $error++;
                } 				else 				{
                }
            }
            if (getValueGPC($formkey) == "" && isset($formName['ERROR_REQUIRED'])) 			{
                $_DATA["ERROR_" . $formkey] = $formName['ERROR_REQUIRED'];
                $error++;
            }
            if (isset($formName['MAXLENGTH']) && strlen(trim(getValueGPC($formkey))) > $formName['MAXLENGTH']) 			{
                if (isset($formName['ERROR_MAXLENGTH'])) 				{
                    $_DATA["ERROR_" . $formkey] = $formName['ERROR_MAXLENGTH'];
                } 				else 				{
                    $_DATA["ERROR_" . $formkey] = "The maximum length should be <b>'" . $formName['MAXLENGTH'] . "'</b> for <b><u><i>" . $formName['TITLE'] . "</i></u></b>";
                }
                $error++;
            } 			elseif (isset($formName['MINLENGTH']) && strlen(trim(getValueGPC($formkey))) < $formName['MINLENGTH']) 			{
                //echo $formkey."========".strlen(trim(getValueGPC($formkey)))."========".$formName['MINLENGTH'];
                //exit;
                if (isset($formName['ERROR_MINLENGTH'])) 				{
                    $_DATA["ERROR_" . $formkey] = $formName['ERROR_MINLENGTH'];
                } 				else 				{
                    $_DATA["ERROR_" . $formkey] = "The minimum length should be <b>'" . $formName['MINLENGTH'] . "'</b> for <b><u><i>" . $formName['TITLE'] . "</i></u></b>";
                }
                $error++;
            } 			elseif (isset($formName['MINVALUE']) && trim(getValueGPC($formkey)) < $formName['MINVALUE']) 			{
                if (isset($formName['ERROR_MINVALUE'])) 				{
                    $_DATA["ERROR_" . $formkey] = $formName['ERROR_MINLENGTH'];
                } 				else 				{
                    $_DATA["ERROR_" . $formkey] = "The minimum value should be <b>'" . $formName['MINVALUE'] . "'</b> for <b><u><i>" . $formName['TITLE'] . "</i></u></b>";
                }
                $error++;
            } 			elseif (isset($formName['MAXVALUE']) && trim(getValueGPC($formkey)) > $formName['MAXVALUE']) 			{
                if (isset($formName['ERROR_MAXVALUE'])) 				{
                    $_DATA["ERROR_" . $formkey] = $formName['ERROR_MAXVALUE'];
                } 				else 				{
                    $_DATA["ERROR_" . $formkey] = "The maximum value should be <b>'" . $formName['MAXVALUE'] . "'</b> for <b><u><i>" . $formName['TITLE'] . "</i></u></b>";
                }
                $error++;
            }
            if (isset($formName['VALUE_TYPE'])) 			{
                if ($formName['VALUE_TYPE'] == "NUMBER" && !$Validate->isNum(getValueGPC($formkey))) 				{
                    if (isset($formName['ERROR_VALUE_TYPE'])) 					{
                        $_DATA["ERROR_" . $formkey] = $formName['ERROR_VALUE_TYPE'];
                    } 					else 					{
                        $_DATA["ERROR_" . $formkey] = "The value should be <b>'NUMBER'</b> for <b><u><i>" . $formName['TITLE'] . "</i></u></b>.";
                    }
                    $error++;
                } 				elseif ($formName['VALUE_TYPE'] == 'INTEGER' && !$Validate->isInt(getValueGPC($formkey))) 				{
                    if (isset($formName['ERROR_VALUE_TYPE'])) 					{
                        $_DATA["ERROR_" . $formkey] = $formName['ERROR_VALUE_TYPE'];
                    } 					else 					{
                        $_DATA["ERROR_" . $formkey] = "The value should be <b>'INTEGER'</b> for <b><u><i>" . $formName['TITLE'] . "</i></u></b>.";
                    }
                    $error++;
                } 				elseif ($formName['VALUE_TYPE'] == 'STRING' && !$Validate->isString(getValueGPC($formkey))) 				{
                    if (isset($formName['ERROR_VALUE_TYPE'])) 					{
                        $_DATA["ERROR_" . $formkey] = $formName['ERROR_VALUE_TYPE'];
                    } 					else 					{
                        $_DATA["ERROR_" . $formkey] = "The value should comtain <b>'A-Za-z'</b> for <b><u><i>" . $formName['TITLE'] . "</i></u></b>.";
                    }
                    $error++;
                } 				elseif ($formName['VALUE_TYPE'] == 'ALPHANUM' && !$Validate->isAlphanum(getValueGPC($formkey))) 				{
                    if (isset($formName['ERROR_VALUE_TYPE'])) 					{
                        $_DATA["ERROR_" . $formkey] = $formName['ERROR_VALUE_TYPE'];
                    } 					else 					{
                        $_DATA["ERROR_" . $formkey] = "The value should be <b>'A-Za-z0-9'</b> for <b><u><i>" . $formName['TITLE'] . "</i></u></b>.";
                    }
                    $error++;
                } 				elseif ($formName['VALUE_TYPE'] == 'EMAIL' && !$Validate->isEmail(getValueGPC($formkey))) 				{
                    if (isset($formName['ERROR_VALUE_TYPE'])) 					{
                        $_DATA["ERROR_" . $formkey] = $formName['ERROR_VALUE_TYPE'];
                    } 					else 					{
                        $_DATA["ERROR_" . $formkey] = "Please provide a valid email address.";
                    }
                    $error++;
                } 				elseif ($formName['VALUE_TYPE'] == 'USERNAME' && !$Validate->isUsername(getValueGPC($formkey))) 				{
                    if (isset($formName['ERROR_VALUE_TYPE'])) 					{
                        $_DATA["ERROR_" . $formkey] = $formName['ERROR_VALUE_TYPE'];
                    } 					else 					{
                        $_DATA["ERROR_" . $formkey] = "Please provide a valid username.";
                    }
                    $error++;
                } 				elseif ($formName['VALUE_TYPE'] == 'NAME' && !$Validate->isName(getValueGPC($formkey))) 				{
                    if (isset($formName['ERROR_VALUE_TYPE'])) 					{
                        $_DATA["ERROR_" . $formkey] = $formName['ERROR_VALUE_TYPE'];
                    } 					else 					{
                        $_DATA["ERROR_" . $formkey] = "Please provide a valid Name String.";
                    }
                    $error++;
                } 				elseif ($formName['VALUE_TYPE'] == 'COMPANYNAME' && !$Validate->isCompanyName(getValueGPC($formkey))) 				{
                    if (isset($formName['ERROR_VALUE_TYPE'])) 					{
                        $_DATA["ERROR_" . $formkey] = $formName['ERROR_VALUE_TYPE'];
                    } 					else 					{
                        $_DATA["ERROR_" . $formkey] = "Please provide a valid company name.";
                    }
                    $error++;
                } 				elseif ($formName['VALUE_TYPE'] == 'URL' && !$Validate->isURL(getValueGPC($formkey))) 				{
                    if (isset($formName['ERROR_VALUE_TYPE'])) 					{
                        $_DATA["ERROR_" . $formkey] = $formName['ERROR_VALUE_TYPE'];
                    } 					else 					{
                        $_DATA["ERROR_" . $formkey] = "Please provide a valid url.";
                    }
                    $error++;
                }
            }
            if (isset($_DATA["ERROR_" . $formkey]) && !isset($_REQUEST['dwltype']))
                echo "<script> setErrorMessage('ERROR_" . $formkey . "',\"" . addslashes($_DATA["ERROR_" . $formkey]) . "\",0);</script>";
        }/**/
        //echo $error; exit;
        if ($error == 0) 		{
            return true;
        }		else		{
            return false;
        }
    }
    function seconds($time) 	{
        $time = explode(':', $time);
        return (@$time[0] * 3600) + (@$time[1] * 60) + @$time[2];
    }
	public function pagingCode($totalPages,$PARAMS,$_displayDiv='htm2Display',$scriptPageName='document.URL')	{
		//print_r( debug_backtrace());
		global $PageName;
		if(floor($totalPages)<= 1){ return "";}
		global $PageName;
		if(!isset($_GET['checksum '])){ $_REQUEST['checksum']= $_GET['checksum'] = "RY23GHJKDFZHYG45GBHJGCVBCHGGFHJ";}
		if(!isset($_REQUEST['pageNumber'])){$_REQUEST['pageNumber'] = 1;}
		if(!isset($_REQUEST['pageNumber'])){$_REQUEST['pageNumber']=1;}
		$SQLORDERBY = "";
			if( (isset($_GET['SQLORDERBY']) && isset($_GET['SQLORDERBYTYPE' ]) && $_GET['SQLORDERBY'] != "" ))			{
				if($_GET['SQLORDERBYTYPE'] == "desc")				{
					$_GET['SQLORDERBYTYPE'] = "desc";
				}				else				{
					$_GET['SQLORDERBYTYPE'] = "asc";
				}
				$SQLORDERBY = "&SQLORDERBY=".$_GET['SQLORDERBY']."&SQLORDERBYTYPE=".$_GET['SQLORDERBYTYPE'];
			}			else			{
				//$SQLORDERBY = "&SQLORDERBY=".$_GET['SQLORDERBY']."&SQLORDERBYTYPE=asc";		
			}
		$TillPageNumber = (($_REQUEST['pageNumber']+5)> $totalPages) ? $totalPages :($_REQUEST['pageNumber']+5);
		$FromPageNumber = (($_REQUEST['pageNumber']-5)> 0) ?  ($_REQUEST['pageNumber']-5) :1;
		$pagingCode ='';
		$PageName=$PageName.$scriptPageName;
		if($_REQUEST['pageNumber']>1)		{
			$pagingCode.="\n\t\t<li><a href=$PageName?checksum=".$_REQUEST['checksum']."&$PARAMS".@$SQLORDERBY."&pageNumber=".($_REQUEST['pageNumber']-1)." onclick=\"javascript:showData($scriptPageName,'".$_REQUEST['checksum']."&$PARAMS$SQLORDERBY&pageNumber=".($_REQUEST['pageNumber']-1)."','$_displayDiv');return false;\">&laquo;</a></li>";
		}
		for($i=$FromPageNumber;$i<=$TillPageNumber;++$i)		{
			if( $_REQUEST['pageNumber'] == $i ) {
			$pagingCode.="\n\t\t<li  class=\"active\" ><a id='ajaxlink".$i."' href=$PageName?checksum=".$_REQUEST['checksum']."&$PARAMS$SQLORDERBY&pageNumber=$i onclick=\"javascript:showData($scriptPageName,'".$_REQUEST['checksum']."&$PARAMS$SQLORDERBY&pageNumber=$i','$_displayDiv');return false;
		\">";
			$pagingCode.="<b><u>$i</b></u>";
			}			else			{
				//$pagingCode.="\n\t\t<li><a class=\"active\" href=$PageName?checksum=".$_REQUEST['checksum']."&$PARAMS$SQLORDERBY&pageNumber=$i onclick=\"javascript:showData($scriptPageName,'".$_REQUEST['checksum']."&$PARAMS$SQLORDERBY&pageNumber=$i','$_displayDiv');return false;		\">";
				$pagingCode.='<li><a class="active" href="'.$PageName.'?checksum='.$_REQUEST['checksum'].'&'.$PARAMS.$SQLORDERBY.'&pageNumber='.$i.'">';
				"\n\t\t<li><a class=\"active\" href=$PageName?checksum=".$_REQUEST['checksum']."&$PARAMS$SQLORDERBY&pageNumber=$i onclick=\"javascript:showData($scriptPageName,'".$_REQUEST['checksum']."&$PARAMS$SQLORDERBY&pageNumber=$i','$_displayDiv');return false;		\">";
			$pagingCode.="$i";
			 }
		$pagingCode.="</a></li>";
		}
		if(($_REQUEST['pageNumber']+1)<=$totalPages)		{
			$pagingCode.="\n\t\t<li><a href=$PageName?checksum=".$_REQUEST['checksum']."&$PARAMS$SQLORDERBY&pageNumber=".($_REQUEST['pageNumber']+1)." onclick=\"javascript:showData($scriptPageName,'".$_REQUEST['checksum']."&$PARAMS$SQLORDERBY&pageNumber=".($_REQUEST['pageNumber']+1)."','$_displayDiv');return false;\">&raquo;</a></li>";
		}
		//$pagingCode .= "<li> <a href=\"$PageName?checksum=".$_REQUEST['checksum']."&print_report=true&$PARAMS$SQLORDERBY\" target=\"_ReportPrint\">Print Report</a> </li>";
		//$pagingCode .= "<li> <a href=\"$PageName?checksum=".$_REQUEST['checksum']."&print_page=true&$PARAMS$SQLORDERBY&pageNumber=".($_REQUEST['pageNumber'])."\" target=\"_ReportPrint\">Print Page</a></li>";
		//$pagingCode .= "<li> <a href=\"$PageName?checksum=".$_REQUEST['checksum']."&excel_export=true&$PARAMS$SQLORDERBY\" target=\"_ReportPrint\">Export Report</a></li>  ";
		return $pagingCode;
	}
}
function getValueGPC($string) {
    if (isset($_REQUEST[$string]) && trim($_REQUEST[$string]) != "") 	{
        return $_REQUEST[$string];
    } 	else 	{
        return "";
    }
}

?>