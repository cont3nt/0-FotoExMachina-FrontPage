<?php
function cjsupport_backend_report_breadcrumbs(){
	$sep = ' &nbsp;/&nbsp; ';
	$report_url = cjsupport_string(cjsupport_callback_url());
	if(!isset($_GET['report'])){
		$today_year = date('Y');
		$today_month = date('m');
		$today_day = date('d');
		$url = $report_url.'report=daily&ry='.$today_year.'&rm='.$today_month.'&rd='.$today_day;
		wp_redirect($url);
		exit;
	}
	if(isset($_GET['report']) && $_GET['report'] == 'alltime'){
		$output[] = __('All Time', 'cjsupport');
	}
	if(isset($_GET['report']) && $_GET['report'] == 'yearly'){
		$output[] = '<a href="'.$report_url.'report=alltime">'.__('All Time', 'cjsupport').'</a>';
		$output[] = $sep;
		$output[] = $_GET['ry'];
	}elseif(isset($_GET['report']) && $_GET['report'] == 'monthly'){
		$month_name = date('F', mktime(0, 0, 0, $_GET['rm'], 10));
		$output[] = '<a href="'.$report_url.'report=alltime">'.__('All Time', 'cjsupport').'</a>';
		$output[] = $sep;
		$output[] = '<a href="'.$report_url.'report=yearly&ry='.$_GET['ry'].'">'.$_GET['ry'].'</a>';
		$output[] = $sep;
		$output[] = $month_name;
	}elseif(isset($_GET['report']) && $_GET['report'] == 'daily'){
		$month_name = date('F', mktime(0, 0, 0, $_GET['rm'], 10));
		$day_name = date('l, M d', mktime(0, 0, 0, $_GET['rm'], $_GET['rd']));
		$output[] = '<a href="'.$report_url.'report=alltime">'.__('All Time', 'cjsupport').'</a>';
		$output[] = $sep;
		$output[] = '<a href="'.$report_url.'report=yearly&ry='.$_GET['ry'].'">'.$_GET['ry'].'</a>';
		$output[] = $sep;
		$output[] = '<a href="'.$report_url.'report=monthly&ry='.$_GET['ry'].'&rm='.$_GET['rm'].'">'.$month_name.'</a>';
		$output[] = $sep;
		$output[] = $day_name;
	}
	return implode('', $output);
}