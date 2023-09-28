<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>76 Challenges</title>

</head>
<!-- <style>
table, th, td {border:1px solid black;border-collapse: collapse;}
</style> -->
<body>
	<form Method ="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<?php

	// format of MinervaLocation.csv: Start,end,location,list
	// example
	// 7/12/2021,7/13/2021,Foundation,1
	// 7/19/2021,7/20/2021,The Crater,2
	// 7/26/2021,7/27/2021,Fort Atlas,3
	// 8/5/2021,8/8/2021,Foundation,1|2|3

	// format of times.txt,score.txt and is text file with one entry per line.

	// format of challenges.txt is: challenge (count)|emogi
	// 
	// example
	// 
	// Above Rank 100: Gain XP (x10000)|
	// Buy an item from or Sell an item to another Player (x3)|
	// CALL TO AXE-ION: Complete CALL TO AXE-ION Daily Challenges (x1)|🪓

	// format of MinervaEnventory.txt is list|item|cost 
	// example
	// 1|Cattle Prod|188
	// 1|Chemist's Backpack Mod|263
	// 1|Farmable Dirt Tiles|375


	require_once '/home/todd/src/76Challenges/vendor/autoload.php';
	error_reporting(E_ALL|E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
//	ini_set('display_errors', 'On');
	date_default_timezone_set("America/New_York");

	$MinervaEnventory = file('MinervaEnventory.txt',FILE_IGNORE_NEW_LINES);
	$aMLocation = file('MinervaLocation.txt',FILE_IGNORE_NEW_LINES);
	$DailyChallenges = array('');
	$WeeklyChallenges = array('');
	$Times= file('times.txt',FILE_IGNORE_NEW_LINES);
	$Score= file('score.txt',FILE_IGNORE_NEW_LINES);
	$DailyChallenge=array_map(function($n) { return array_map(function($n) { return null; }, range(1, 4) ); }, range(1, 19) );
	$WeeklyChallenge=array_map(function($n) { return array_map(function($n) { return null; }, range(1, 4) ); }, range(1, 19) );
	$Location=array('', 'Arktos Pharma Biome Lab','Watoga High School','Uncanny Caverns','The Burning Mine', 'The Burrows', 'Vault 94', 'Valley Galleria','Watoga Raider Arena','Vault 96','West Tek Research Center','Charleston Capitol Building','Garrahan Mining Headquarters','Morgantown High School');
	$MinervaLocation=array('Away','Foundation','The Crater','Fort Atlas' );
	$EnemyFaction = array('', 'Communists','Blood Eagles','Super Mutants','Robots','Scorched','Mothman Cultists','Mole Miners','Aliens');
	$MinervaListPrint = ("\n");
	$EnemyMutations1 = array('Piercing Gaze', 'Savage Strike');
	$EnemyMutations2 = array('', 'Reflective Skin','Piercing Gaze', 'Volatile', 'Active Camouflage', 'Resilient' ,'Freezing Touch','Toxic Blood','Group Regeneration','Swift-Footed','Blistering Cold: Freezing Touch and Swift-Footed Mutations','Chilling Mend: Freezing Touch and Group Regeneration Mutations','Clouded Toxins: Active Camouflage and Toxic Blood Mutations','Relentless: Resilient and Group Regeneration Mutations','Stinging Frost: Freezing Touch and Toxic Blood Mutations','Swift Stalker: Active Camouflage and Swift-Footed Mutations','Unstable: Volatile and Swift-Footed Mutations','Vaporous: Volatile and Active Camouflage Mutations','Danger Cloud');
	$FO1st = array('','1ˢᵗ ');
	$icalURL="https://calendar.google.com/calendar/ical/677a43e0ffb5d922130f03876fe8c0bea6cb2fa558a7f50574cbbaa75564c74e%40group.calendar.google.com/public/basic.ics";
	#$icalURL="http://nextcloud.ktntg.com/remote.php/dav/public-calendars/ZPAxXoBAnacByPGk/?export";

	
// read fallout76 calendar for current events
use ICal\ICal;

try {
    $ical = new ICal( array(
        'defaultSpan'                 => 2,     // Default value
        'defaultTimeZone'             => 'UTC',
        'defaultWeekStart'            => 'MO',  // Default value
        'disableCharacterReplacement' => false, // Default value
        'filterDaysAfter'             => null,  // Default value
        'filterDaysBefore'            => null,  // Default value
        'httpUserAgent'               => null,  // Default value
        'skipRecurrence'              => false, // Default value
    ));
	// this is ran after the form is submitted so we dont load the calendar for each page load
    //$ical->initFile('ZPAxXoBAnacByPGk-2023-01-29.ics');

} catch (\Exception $e) {
    die($e);
}	
	
	foreach ( file('daily_challenges.txt',FILE_IGNORE_NEW_LINES) as $key => $value) {
		$DailyChallenges[$key]=explode("|",$value);
	}
	foreach ( file('weekly_challenges.txt',FILE_IGNORE_NEW_LINES) as $key => $value) {
		$WeeklyChallenges[$key]=explode("|",$value);
	}
	
	if ( ! isset( $_POST['Submit'] ) ) {
		// pre fill the Daily and Weekly arrays with common data
		foreach ($WeeklyChallenge as $key => $value) {
			$WeeklyChallenge[$key][2]='1000';
		}
		foreach ($DailyChallenge as $key => $value) {
			$DailyChallenge[$key][2]='250';
		}
		$DailyChallenge[0][3]='1ˢᵗ ';
		$DailyChallenge[1][0]='Gold Star: Complete a Daily Challenge (x6)';
		$DailyChallenge[1][1]='(x6)';
		$DailyChallenge[1][2]='1000';
		// $DailyChallenge[2][0]='Level up!';
		// $DailyChallenge[2][1]='(x1)';
		// $DailyChallenge[2][2]='500';
		// $DailyChallenge[3][0]='Complete a Daily Operation!';
		// $DailyChallenge[3][1]='(x1)';
		// $DailyChallenge[3][2]='250';
		// $DailyChallenge[4][0]='Complete an Event';
		// $DailyChallenge[4][1]='(x1)';
		// $DailyChallenge[4][2]='250';
		// $DailyChallenge[5][0]='Gold Star: Complete a Daily Challenge';
		// $DailyChallenge[5][1]='(x5)';
		// $DailyChallenge[5][2]='500';
		$WeeklyChallenge[1][0]='Repeatable Under Rank 100: Gain XP (x10000)';
		$WeeklyChallenge[1][1]='(x10000)';
		$WeeklyChallenge[1][2]='100';
		$WeeklyChallenge[0][0]='Complete a Gold Star Daily Challenge! (x3)';
		$WeeklyChallenge[0][1]='(x3)';
		$WeeklyChallenge[0][2]='1500';
		//$WeeklyChallenge[2][0]='Complete Daily Operations Daily Challenges!';
		//$WeeklyChallenge[2][1]='(x3)';
		//$WeeklyChallenge[2][2]='1000';
		//$WeeklyChallenge[3][0]='Level up!';
		//$WeeklyChallenge[3][1]='(x3)';
		//$WeeklyChallenge[3][2]='1500.';
		//$WeeklyChallenge[4][0]='Complete an Event';
		//$WeeklyChallenge[4][1]='(x10)';
		//$WeeklyChallenge[4][2]='1000.';
	}

	$MinervaList = 0;
	$now = time();
	//$now = strtotime('20221105T12:01:00');

	sort($DailyChallenges);
	sort($WeeklyChallenges);
	sort($Location);
	sort($EnemyFaction);
	sort($EnemyMutations1);
	sort($EnemyMutations2);
	//sort($MinervaEnventory);

	datalist("DailyChallenges",$DailyChallenges);
	datalist("WeeklyChallenges",$WeeklyChallenges);
	datalist("1st",$FO1st);
	//datalist("times",$Times);
	datalist("score",$Score);

	$PageWidth = 0; 
	$LineWrap=false; // if true then wrap line if longer than $PageWidth

	// Discord webhook url created per server. 
	$WebHookURL = "";
	//  patch 42 had the same mutation1 as the previous day. so this needed changed.
	$DaysSinceEpoch = intdiv(time(),(24*60*60));
	if ($DaysSinceEpoch & 1) {
		// ODD Day 
		$EnemyMutations1 = array('Savage Strike','Piercing Gaze');
	} else {
		// Even Day
		$EnemyMutations1 = array('Piercing Gaze','Savage Strike');
	}

	// takes as input an array that is used to fill in the options for a select box
	function select_option($BoxName,$CurrnetValue, $alist) {
		foreach ($alist as $key => $value) {
			if ( is_array($value) ) {
				if ( isset($CurrnetValue) &&  $CurrnetValue == $value[0] ) {
					echo '<option selected value="'.htmlentities($value[0]).'">'.htmlentities($value[0]).'</option>';
				} else {
					echo '<option value="'.htmlentities($value[0]).'">'.htmlentities($value[0]).'</option>';
				}
				//echo '<option value="'.$value.'">'.$value.'</option>';
			
			} else {
				if ( isset($CurrnetValue) &&  $CurrnetValue == $value ) {
					echo '<option selected value="'.htmlentities($value).'">'.htmlentities($value).'</option>';
				} else {
					echo '<option value="'.htmlentities($value).'">'.htmlentities($value).'</option>';
				}
			}
		}
	}

	// takes as input the name of the select box, its current value and an array for the options
	function select_box($BoxName, $CurrnetValue, $SelectOptions) {
		echo '<td>';
		echo '<td><label for="'.$BoxName.'">'.$BoxName.'. </label>';
		select_option($BoxName, $CurrnetValue, $SelectOptions);
	}

	function text_input($ID,$BoxName, $CurrentValue, $Size) {
		echo '<td>';
		if ( (strcmp($ID,"times")==0) ) {
			echo '<input list="'.$ID.'" name="'.$BoxName.'" id="'.$BoxName.'" value="'.$CurrentValue.'" size="'.$Size.'" type="number" pattern=[\d+]>';
		} else {
			echo '<input list="'.$ID.'" name="'.$BoxName.'" id="'.$BoxName.'" value="'.$CurrentValue.'" size="'.$Size.'" type="text">';
		}
		echo '</input></td>';
	}

	//create data list
	function datalist($ID,$alist){
		echo '<datalist id='.$ID.'>';
		foreach ($alist as $key => $value) {
			if ( is_array($value) ) {
				echo "<option>".$value[0]."</option>";
			} else {
				echo "<option>".$value."</option>";
			}
			
			
		}
		echo "</datalist>";
	}
	
	// Comparison function 
	// sorts FO1st to top , "" to bottom and rest by alpha
	function cmp($a, $b) {
		$ac = ($a[0]);
		$bc = ($b[0]);
		if ($a[3] == "1ˢᵗ ") {return -1;}
		if ($b[3] == "1ˢᵗ ") {return 1;}
		if ($a[3] == "⭐") {return -1;}
		if ($b[3] == "⭐") {return 1;}
		//sort empty to bottom
		if ($ac  == "") {return 1;}
		if ($bc  == "") {return -1;}
		return strcmp($ac,$bc);
	}

	// takes as input Challenge array DailyChallenge,WeeklyChallenge
	// takes as input prefix for select boxes d,w
	// only prints if first item has a value
	function formatprint($Challenges,$aChallenge,$select_prefix) {

		$ouput = "";
		if ( ($aChallenge[3][0]) !== ''){
			if ( $select_prefix == 'w' ) {
				$ouput .= "**Weekly Challenges**\n";
				$ouput .= "```\nChallenge (Count) S.C.O.R.E.\n";
			} else {
				$ouput .= "**Daily Challenges**\n";
				$ouput .= "```\nChallenge (Count) S.C.O.R.E.\n";
			}
			foreach ($aChallenge as $key => $value) {

				if ( $aChallenge[$key][0] ) {
					$ouput .=$aChallenge[$key][3].$aChallenge[$key][0].' '.$aChallenge[$key][2]."\n";
				}
			}
	    	$ouput .= "```\n";
		}
		return $ouput;
	}

	function CurrentEvents($ical) {
		// gets events for for current day
		$output = "**Current Events**\n```\n";
		$events = $ical->eventsFromInterval('1 day');
		foreach ($events as $event) {
			$dtend = $ical->iCalDateToDateTime($event->dtend_array[3]);
			$now = date('d-M-Y');
			$check = $dtend->format('d-M-Y');
			if ( strcmp($now,$check)!=0 ) {
				$output .= $event->summary . ', Ends on (' . $check . ')'."\n";
			}
			// $now = ceil((($ical->iCalDateToUnixTimestamp($event->dtend))-time())/60/60/24);
			// if ($now>1) {
			// 	$output .= $event->summary . ', Ends in ' . $now . ' days.'."\n";
			// } elseif ($now=1) {
			// 	$output .= $event->summary . ', Ends in ' . $now . ' day.'."\n";
			// }
		}
		$output .="```\n";
		return $output;
	}
	
	// read form submission and return array to feed formatprint function
	function ReadForm($Challenges,$aChallenge,$prefix){
		foreach ($aChallenge as $key => $value) {
			// put the results into the *Challenge array
			$tmp=$prefix.$key;
			$aChallenge[$key][0] =  htmlspecialchars($_REQUEST[$tmp]);
			//$aChallenge[$key][1] =  htmlspecialchars($_REQUEST[$tmp.'times']);
			$aChallenge[$key][2] =  htmlspecialchars($_REQUEST[$tmp.'score']);
			if ($_REQUEST[$tmp.'1st'] == '1ˢᵗ ') {
				$aChallenge[$key][3] =  htmlspecialchars($_REQUEST[$tmp.'1st']);
			} else {
				foreach( $Challenges as $key2 => $value2) {
					if ($aChallenge[$key][0] == $value2[0]) {
						$aChallenge[$key][3] = $value2[1];
						//$aChallenge[$key][1] = $value2[2];
					}
				}
			}
		}
		return $aChallenge;
	}

	// minervas fucntion
	function Minerva(){

		$MinervaEnventory = file('MinervaEnventory.txt',FILE_IGNORE_NEW_LINES);
		$aMLocation = file('MinervaLocation.txt',FILE_IGNORE_NEW_LINES);
		$MinervaLocationResult='Away';
		$MinervaNextLocation='';
		$now = time();
		$output = '';

		//$now = strtotime('20230925T12:10:00');

		foreach($aMLocation as $MLvalue) {
			$MLLine = explode(',',$MLvalue);
			if (strtotime($MLLine[0]) <= $now && $now <= strtotime($MLLine[1]) ) {
				$MinervaLocationResult = $MLLine[2];
				$MinervaList = explode('|',$MLLine[3]);
			}
			if ($MinervaNextLocation == '') {
				if (strtotime($MLLine[0]) >= $now  ) {
					$MinervaNextLocation = 'She will next be at '. $MLLine[2] .' on '. date("l jS \of F Y",strtotime($MLLine[0])) ;
				}
			}
		}
		if ($MinervaLocationResult=='Away') {
			$output =  "**Minerva's Location: $MinervaLocationResult**\n```\n$MinervaNextLocation\n```\n";
			//echo "```\n$MinervaLocationResult\n```\n";
		} else {
			$output =  "**Minerva's Location: $MinervaLocationResult**\n";
			$output .=  "```\nName (Gold Price)\n";
			$MinervaListPrint = array('');
			foreach ($MinervaEnventory as $MEkey => $MEvalue) {
				$MEline = explode('|',$MEvalue);
				//if ( in_array($MEline[0], $MLList)) {
				if ( in_array($MEline[0], $MinervaList)) {
					$output .= $MEline[1]." (".$MEline[2].")\n";
					//array_push($MinervaListPrint, $MEline[1]." (".$MEline[2].")" );
				}
			}
			$output .=  "```\n";
		}
		return $output;
	}

	  // Check if the form is submitted
	if ( isset( $_POST['Submit'] ) ) {

		// retrieve the form data by using the element's name attributes value as key
		$Submit = $_POST['Submit'];
		$LocationResult =  htmlspecialchars($_REQUEST['Location']);
		$EnemyFactionResult =  htmlspecialchars($_REQUEST['EnemyFaction']);
		$EnemyMutations1Result =  htmlspecialchars($_REQUEST['EnemyMutations1']);
		$EnemyMutations2Result =  htmlspecialchars($_REQUEST['EnemyMutations2']);
		//$MinervaLocationResult = $_REQUEST['MinervaLocation1'];
		$MinervaLocationResult = "Away";
		$WeeklyChallenge=ReadForm($WeeklyChallenges,$WeeklyChallenge,'w');
		$DailyChallenge=ReadForm($DailyChallenges,$DailyChallenge,'d');
	}
	?>
</center>

	<table  style=" right">
		<caption><h1>Weekly Challenges</h1></caption>
		<tr><th>1st</th><th>Challenge</th><th>SCORE</th></tr>	
			<?php
			foreach ($WeeklyChallenge as $key => $value) {
				echo '<tr>';
				text_input('1st','w'.$key.'1st', $value[3],'5');
				text_input('WeeklyChallenges','w'.$key, $value[0],'70');
				//text_input('times','w'.$key.'times', $value[1],'10');
				text_input('score','w'.$key.'score', $value[2],'10');
				echo '</tr>';
			}
			?>
	</table>


<table  style=" left;">
	<caption><h1>Daily Challenges</h1></caption>
	<tr><th>1st</th><th>Challenge</th><th>SCORE</th></tr>	
		<?php
		foreach ($DailyChallenge as $key => $value) {
			echo '<tr>';
			text_input('1st','d'.$key.'1st', $value[3],5);
			text_input('DailyChallenges','d'.$key, $value[0],70);
			//text_input('times','d'.$key.'times', $value[1],10);
			text_input('score','d'.$key.'score', $value[2],10);
			echo '</tr>';
		}
		?>
</table>	    

	<h1><img src='bos.png' style='vertical-align:middle'>Daily Operation</h1>

	    <label for="Location">Choose a location. </label>
	    <select name="Location" id="Location">
	    	<?php select_option('Location', $LocationResult, $Location);?>
	    </select>
		<br>
	    <label for="EnemyFaction">Choose a enemy faction. </label>
	    <select name="EnemyFaction" id="EnemyFaction">
	    	<?php select_option('EnemyFaction', $EnemyFactionResult, $EnemyFaction);?>
	    </select>
		<br>
		<label for="EnemyMutations1">   Choose a primary enemy mutation. </label>
	    <select name="EnemyMutations1" id="EnemyMutations1">
	    	<?php select_option('EnemyMutations1', $EnemyMutations1Result, $EnemyMutations1);?>
	    	</select>
		<br>
		<label for="EnemyMutations2">   Choose a sceondary enemy mutation. </label>
	    <select name="EnemyMutations2" id="EnemyMutations2">
	    	<?php select_option('EnemyMutations2', $EnemyMutations2Result, $EnemyMutations2);?>
	    </select>
		
		 <input type="submit" Name = "Submit" value='Submit'><P>
		

<?php
	error_reporting(E_ALL|E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	ini_set('display_errors', 'On');

	  // Check if the form is submitted
	if ( isset( $_POST['Submit'] ) ) {
		
		//sort($DailyChallenge);
		usort($DailyChallenge,'cmp');
		//$tmpDailyChallenge=array_reverse($DailyChallenge);
		//$DailyChallenge = $tmpDailyChallenge;
		usort($WeeklyChallenge,'cmp');
		//usort($WeeklyChallenge,'cmp');

		// display the results
		//echo " \n";
		//$ical->initFile('ZPAxXoBAnacByPGk-2023-01-29.ics');
		$ical->initUrl($icalURL, $username = '', $password = '', $userAgent = null);
		$textareaValue = "Fallout 76 Daily Update\n";
		$textareaValue .= CurrentEvents($ical);
		$textareaValue .= formatprint($WeeklyChallenges,$WeeklyChallenge,'w'); 
		$textareaValue .= formatprint($DailyChallenges,$DailyChallenge,'d');
		$textareaValue .= Minerva();

		if (!empty($LocationResult)) {
			$DOPSMode="Uplink";
			if ($EnemyMutations1Result == "Savage Strike") {
				$DOPSMode="Decryption";
			}				
			$textareaValue .=  "**Daily OPS: $DOPSMode**\n";
			$textareaValue .=  "```\n";
			$textareaValue .=  "Location: $LocationResult\n";
			$textareaValue .=  "Enemy Faction: $EnemyFactionResult\n";
			$textareaValue .=  "Enemy Mutations: $EnemyMutations1Result, $EnemyMutations2Result \n";
			$textareaValue .=  "```";
		}
		
		
		if (mb_strlen($textareaValue) > 2000 ) {
			echo "CAUTION:Character count exceeds Discord maximum post length (".mb_strlen($textareaValue).") > 2000 <P>";
		} else {
			echo "Character count: (".mb_strlen($textareaValue).")<br>";
			// echo "Minerva List: ";
			// if (!empty($MinervaList)) {
			// 	//print_r($MinervaList);
			// 	//echo $MinervaList[0].", ".$MinervaList[1].", ".$MinervaList[2];
			// 	foreach($MinervaList as $key => $value) {
			// 		echo $value." " ;
			// 	}
			// }
			echo "<p>";
		}
		echo '<textarea id="content" name="content" cols="120" rows="40" autofocus>';
		echo $textareaValue;
		echo '</textarea>';	
		
	} 
?>
</pre>
</form>
</body>
</html>
