<h3>site map</h3>
<?php

        $mysesh->err_level = 8;
        $mysesh->err  = "body999999.php State: ";
        $mysesh->err .= (string)$mysesh->AutomatState;
        $mysesh->err_no = 0;
        $mysesh->DbgAddSqlLog();

// get automat state menu data
        $mysesh->AutomatFetchAll();
// new Graph object
	$myGraph = new Graph($mysesh->AutomatDataRows);
// new Symbol table
	$myST = new SymbolTable();

        for ($i = 0; $i < $mysesh->AutomatDataRows; $i++) {
                $row_array = mysqli_fetch_array($mysesh->AutomatData);

		$previous = $row_array['previous'];
		$action = $row_array['action'];
		$state = $row_array['state'];
		$menu = $row_array['menu'];
		$text = $row_array['menutext'];
		$note = $row_array['note'];

/*
 menu:
 0 no menu
 1 menu
 2 menu & site map
*/
		if ($menu == '2') {
			/* echo("<br>$previous $state"); */
			$Index1 = $myST->ST_index($previous);
			$Index2 = $myST->ST_index($state);
			$NodeSiteMap[$Index1] = 1;	// avoid double entry
			$NodeState[$Index1] = $previous;
			$NodeState[$Index2] = $state;
			$NodeNote[$Index2] = $note;
			$myGraph->insertE($Index1, $Index2);
			/* echo ("<br> $i: $Index1,$previous $Index2,$state"); */
		}

	}

/* echo("<br>SymbolTable: N=$myST->ST_N"); */

/*
echo("<br>Graph of indexes" );
for ($v=0; $v < $myST->ST_N; $v++) {
	echo("<br>" . $v . ": " );
	for ($t = $myGraph->adj[$v]; $t != 0; $t = $t->next)
		echo("$t->v ,"); 
	}
echo("<br>Graph of nodes" );
for ($v=0; $v < $myST->ST_N; $v++) {
        echo("<br>" . $NodeState[$v] . ": " );
        for ($t = $myGraph->adj[$v]; $t != 0; $t = $t->next)
                echo($NodeState[$t->v] . ",");
        }
*/

echo("<ul>");
for ($v=0; $v < $myST->ST_N; $v++) {
	if ($NodeSiteMap[$v] == 1) {
		$NodeSiteMap[$v] = -1;
		echo("</ul><hr>");
		echo ("<FORM ACTION='automat.php' METHOD=POST>");
		echo ("<INPUT style='background: #458b74; color: #7fffd4' TYPE=SUBMIT VALUE='$NodeNote[$v]'>");
		echo ("<INPUT TYPE=HIDDEN NAME='action' VALUE='$NodeState[$v]'>");
		echo ("<INPUT TYPE=HIDDEN NAME='seshid' VALUE='$seshid'>");
		echo ("</FORM>");
		echo ("<ul>");
        	for ($t = $myGraph->adj[$v]; $t != 0; $t = $t->next) {
			$index = $t->v;
			if ($NodeSiteMap[$index] != -1) {
				echo ("<li>");
				echo ("<FORM ACTION='automat.php' METHOD=POST>");
				echo ("<INPUT style='background: #458b74; color: #7fffd4' TYPE=SUBMIT VALUE='$NodeNote[$index]'>");
				echo ("<INPUT TYPE=HIDDEN NAME='action' VALUE='$NodeState[$index]'>");
				echo ("<INPUT TYPE=HIDDEN NAME='seshid' VALUE='$seshid'>");
				echo ("</FORM>");
				}
			}
	        }
	}
	echo ("</ul>");
?>

<!--
<hr>
<font class=tiny>
this site map was created with php modules 
<a href="doc/Graph.php">Graph.php (0.5kB)</a> and 
<a href="doc/SymbolTable.php">SymbolTable.php (1.2kB)</a>
</font>
-->