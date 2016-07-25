<?php
//
// **** DROP TABLES
// ----------------
	echo ("<br><hr>Droping tables ...");
//
// 1.ACTSESSIONS
	$table = "actsessions";
	{ include ("fnc/tables/DropTable.php"); }
// 2.DEBUGLOG
	$table = "debuglog";
	{ include ("fnc/tables/DropTable.php"); }
// 3.USER
	$table = "user";
	{ include ("fnc/tables/DropTable.php"); }
// 4.GROUPS
	$table = "groups";
	{ include ("fnc/tables/DropTable.php"); }
// 5.SERVICES
	$table = "services";
	{ include ("fnc/tables/DropTable.php"); }
// 6.CUSTOMERS
	$table = "customers";
	{ include ("fnc/tables/DropTable.php"); }
// 7.USRAUTOMAT
	$table = "usrautomat";
	{ include ("fnc/tables/DropTable.php"); }
// 8.ORDERS
	$table = "orders";
	{ include ("fnc/tables/DropTable.php"); }
//
// **** CREATE TABLES
// ------------------
	echo ("<br><hr>Creating tables ...");
//
// 1.ACTSESSIONS
	{ include ("fnc/tables/CreateActsessions.php"); }
// 2.DEBUGLOG
	{ include ("fnc/tables/CreateDebuglog.php"); }
// 3.USER
	{ include ("fnc/tables/CreateUser.php"); }
// 4.GROUPS
	{ include ("fnc/tables/CreateGroups.php"); }
// 5.SERVICES
	{ include ("fnc/tables/CreateServices.php"); }
// 6.CUSTOMERS
	{ include ("fnc/tables/CreateCustomers.php"); }
// 7.USRAUTOMAT
	{ include ("fnc/tables/CreateUsrautomat.php"); }
// 8.ORDERS
	{ include ("fnc/tables/CreateOrders.php"); }
//
// **** LOAD TABLES
// ----------------
	echo ("<br><hr>Loading tables ...");
//
// USRAUTOMAT
	$table = "usrautomat";
	$file = $mysesh->CONFPATH . "/usrautomat.tab";
	{ include ("fnc/tables/LoadTable.php"); }
// USER
	$table = "user";
	$file = $mysesh->CONFPATH . "/user.tab";
	{ include ("fnc/tables/LoadTable.php"); }
// GROUPS
	$table = "groups";
	$file = $mysesh->CONFPATH . "/groups.tab";
	{ include ("fnc/tables/LoadTable.php"); }
// SERVICES
	$table = "services";
	$file = $mysesh->CONFPATH . "/services.tab";
	{ include ("fnc/tables/LoadTable.php"); }
?>








