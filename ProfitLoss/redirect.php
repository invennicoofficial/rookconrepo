<?php
include('../tcpdf/tcpdf.php');
include('../include.php');
include('compensation_pdf.php');
include('expenses_pdf.php');
include('inventory_pdf.php');
include('revenue_pdf.php');
include('receivables_pdf.php');
include('summary_pdf.php');
if($_GET['tab'] == 'revenue') {
	profit_loss_revenue_pdf($dbc);
}
if($_GET['tab'] == 'compensation') {
	profit_loss_pdf($dbc);
}
if($_GET['tab'] == 'expenses') {
	profit_loss_expense_pdf($dbc);
}
if($_GET['tab'] == 'inventory') {
	profit_loss_inventory_pdf($dbc);
}
if($_GET['tab'] == 'receivables') {
	profit_loss_receivable_pdf($dbc);
}
if($_GET['tab'] == 'summary') {
	profit_loss_summary_pdf($dbc);
}