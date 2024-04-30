<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>76 Challenges</title>
	<style>
	.warning {
	background-color: yellow;
	text-align: center;
	}
	table {
		border-collapse: collapse;
	}
	th {
		border: none;
		/* background_color: lightgrey; */
	}
	td {
		border: 1px solid black;
	}
	textarea {
		width: 50rem;
		height: 50rem;
		/* padding: 12px 20px; */
		box-sizing: border-box;
		border: 1px solid black;
		border-radius: 4px;
		/* background-color: #f8f8f8; */
		/*resize: none;*/
	}
	select {
		width: fit-content;
		/*padding: 16px 20px;*/
		border: 1px solid black;
		border-radius: 4px;
		/* background-color: #f1f1f1; */
	}
	input.text {
		width: fit-content;
		padding: 0px;
		border: none;
		/* border-radius: 4px; */
		/* background-color: #f1f1f1; */
	}
	div {
		max-height: 40000px;
		max-width: 100%;
	}
</style>
</head>

<body>
	<form Method ="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<?php

	// format of times.txt,score.txt and is text file with one entry per line.

	// format of challenges.txt is: challenge (count)|emogi|reward
	// 
	// example
	// 
	// Above Rank 100: Gain XP (x10000)||
	// Buy an item from or Sell an item to another Player (x3)||
	// CALL TO AXE-ION: Complete CALL TO AXE-ION Daily Challenges (x1)|🪓|Lunchbox


	//require_once '/home/todd/src/76Challenges/vendor/autoload.php';
	require_once 'libraies.phar';
	error_reporting(E_ALL|E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	//ini_set('display_errors', 'On');
	date_default_timezone_set("America/New_York");

	$Challenges = array('');
	$EventChallenge=array_map(function($n) { return array_map(function($n) { return null; }, range(1, 4) ); }, range(1, 10) );
	$DailyChallenge=array_map(function($n) { return array_map(function($n) { return null; }, range(1, 4) ); }, range(1, 19) );
	$WeeklyChallenge=array_map(function($n) { return array_map(function($n) { return null; }, range(1, 4) ); }, range(1, 19) );
	$Location=array('', 'Arktos Pharma Biome Lab','Watoga High School','Uncanny Caverns','The Burning Mine', 'The Burrows', 'Vault 94', 'Valley Galleria','Watoga Raider Arena','Vault 96','West Tek Research Center','Charleston Capitol Building','Garrahan Mining Headquarters','Morgantown High School');
	$EnemyFaction = array('', 'Communists','Blood Eagles','Super Mutants','Robots','Scorched','Mothman Cultists','Mole Miners','Aliens');
	$EnemyMutations1 = array('Piercing Gaze', 'Savage Strike');
	$EnemyMutations2 = array('', 'Reflective Skin','Piercing Gaze', 'Volatile', 'Active Camouflage', 'Resilient' ,'Freezing Touch','Toxic Blood','Group Regeneration','Swift-Footed','Blistering Cold: Freezing Touch and Swift-Footed Mutations','Chilling Mend: Freezing Touch and Group Regeneration Mutations','Clouded Toxins: Active Camouflage and Toxic Blood Mutations','Relentless: Resilient and Group Regeneration Mutations','Stinging Frost: Freezing Touch and Toxic Blood Mutations','Swift Stalker: Active Camouflage and Swift-Footed Mutations','Unstable: Volatile and Swift-Footed Mutations','Vaporous: Volatile and Active Camouflage Mutations','Danger Cloud');
	$FO1st = array('','1ˢᵗ ');
	//$ChallengeType = $arrayName = array('Event','Weekly','Daily');

	use ICal\ICal;

	foreach ( file('challenges.txt',FILE_IGNORE_NEW_LINES) as $key => $value) {
		$tmpChallenges[$key]=$value;
	}
	$Challenges = array_unique($tmpChallenges,SORT_STRING);

	if ( ! isset( $_POST['Submit'] ) ) {
		
		$DailyChallenge[0][3]='1ˢᵗ ';
		$DailyChallenge[1][0]='Gold Star: Complete a Daily Challenge (x6)|Daily|⭐|1000';
		$WeeklyChallenge[1][0]='Repeatable Under Rank 100: Gain XP (x10000)|Weekly|🔁 |100';
		$WeeklyChallenge[0][0]='Complete a Gold Star Daily Challenge! (x3)|Weekly|⭐|1500';
	}

	$now = time();
	//$now = strtotime('20221105T12:01:00');

	sort($Challenges);
	sort($Location);
	sort($EnemyFaction);
	sort($EnemyMutations1);
	sort($EnemyMutations2);

	datalist($Challenges);
			
	echo '<datalist id=1st><option>1ˢᵗ</option></datalist>';
	
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
	
	// text input box 
	//used for challenges
	function text_input($ID,$BoxName, $CurrentValue, $Size) {
		echo '<td>';
		if ( (strcmp($ID,"times")==0) ) {
			echo '<input class="text" list="'.$ID.'" name="'.$BoxName.'" id="'.$BoxName.'" value="'.$CurrentValue.'" size="'.$Size.'" type="number" pattern=[\d+]>';
		} else {
			echo '<input class="text" list="'.$ID.'" name="'.$BoxName.'" id="'.$BoxName.'" value="'.$CurrentValue.'" size="'.$Size.'"autocomplete="off" type="text">';
		}
		echo '</input></td>';
	}

	//create data list for html input tag 
	// if event and $value[1] is event then echo
	// if Weekly and $value[1] is Weekly then echo
	// if daily and $value[1] is Daily then echom
	function datalist($alist){
		$tmpevent = '<datalist id=EventChallenges>';
		$tmpweekly = '<datalist id=WeeklyChallenges>';
		$tmpdaily = '<datalist id=DailyChallenges>';
		foreach ($alist as $key => $value) {
			$tmpvalue = explode('|',$value)[1];
			if ( $tmpvalue=='Event' ) {
				$tmpevent .="<option>".$value."</option>";
			} elseif ( $tmpvalue=='Weekly' ) {
				$tmpweekly .= "<option>".$value."</option>";
			} elseif ($tmpvalue=='Daily' ) {
				$tmpdaily .= "<option>".$value."</option>";
			} 
		}
		$tmpevent .= "</datalist>";
		$tmpweekly .= "</datalist>";
		$tmpdaily .= "</datalist>";
		echo $tmpevent;
		echo $tmpweekly;
		echo $tmpdaily;
	}
	
	// Comparison function 
	// sorts FO1st to top , "" to bottom and rest by alpha
	function cmp($a, $b) {
		$ac = ($a[0]);
		$bc = ($b[0]);
		if ($a[3] == "1ˢᵗ ") {return -1;}
		if ($b[3] == "1ˢᵗ ") {return 1;}
		if (str_contains($ac,"⭐")) {return -1;}
		if (str_contains($bc,"⭐")) {return 1;}
		//sort empty to bottom
		if ($ac  == "") {return 1;}
		if ($bc  == "") {return -1;}
		return strcmp($ac,$bc);
	}


	function formatprint($ScoreMult,$aChallenge,$select_prefix) {
	// takes as input Challenge array (DailyChallenge,WeeklyChallenge)
	// takes as input prefix for select boxes (d,w)
	// only prints if there are more than 3 entries and item[*][0] has a value
		//$ScoreMult = 1;
		$output = "";
		if ( ($aChallenge[3][0]) !== ''){
			if ( $select_prefix == 'w' ) {
				$output .= "**Weekly Challenges**\n\n";
				$output .= "Challenge (Count) S.C.O.R.E.\n";
			} elseif ( $select_prefix == 'd' ) {
				$output .= "**Daily Challenges**\n\n";
				$output .= "Challenge (Count) S.C.O.R.E.\n";
			} elseif ($select_prefix == 'e') {
				$output .= "**Event Challenges**\n\nChallenge (Count) Reward\n";
			}
			foreach ($aChallenge as $key => $value) {
				if ( $value[0] ) {
					$tmp=explode("|",$value[0]);
					$value[0] = $tmp[0]; // full name and required count
					$value[1] = $tmp[2]; // flair
					// $value[2] is reward
					// $value[3] is "1st"
					if ( strlen($tmp[3])==0 && $tmp[1]=='Weekly' ) {
						$value[2] = '1000';
					} elseif ( strlen($tmp[3])==0 && $tmp[1]=='Daily') {
						$value[2] = '250';
					} else {
						$value[2] = $tmp[3];
					}
					
					if (is_numeric($value[2]) ) {
						$output .="* ".$value[3].$value[1].$value[0].' '.$ScoreMult * $value[2]."\n";
					} else {
						$output .="* ".$value[3].$value[1].$value[0].' '.$value[2]."\n";
					}
				}
			}
	    	$output .= "\n";
		}
		return $output;
	}

	function CurrentEvents() {
		// read fallout76 calendar for current events

		// gets events for for current day
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
		} catch (\Exception $e) {
			die($e);
		}
		
		//$ical->initFile('ZPAxXoBAnacByPGk-2023-01-29.ics');
		$icalURL="https://calendar.google.com/calendar/ical/677a43e0ffb5d922130f03876fe8c0bea6cb2fa558a7f50574cbbaa75564c74e%40group.calendar.google.com/public/basic.ics";
		//$icalURL="http://nextcloud.ktntg.com/remote.php/dav/public-calendars/ZPAxXoBAnacByPGk/?export";
		$ical->initUrl($icalURL, $username = '', $password = '', $userAgent = null);
		$output = "**Current Events**\n";
		$events = $ical->eventsFromInterval('1 day');
		foreach ($events as $event) {
			$dtend = $ical->iCalDateToDateTime($event->dtend_array[3]);
			$now = date('d-M-Y');
			$check = $dtend->format('d-M-Y');
			if ( strcmp($now,$check)!=0 ) {
				$output .= "* ".$event->summary . ', Ends on (' . $check . ')'."\n";
				if (is_string($event->description)) {
					$output .= "* ".strip_tags($event->description) . "\n";
				}
			}
			// $now = ceil((($ical->iCalDateToUnixTimestamp($event->dtend))-time())/60/60/24);
			// if ($now>1) {
			// 	$output .= $event->summary . ', Ends in ' . $now . ' days.'."\n";
			// } elseif ($now=1) {
			// 	$output .= $event->summary . ', Ends in ' . $now . ' day.'."\n";
			// }
		}
		$output .="\n";
		return $output;
	}
	
	function ReadForm($aChallenge,$prefix){
	// read form submission and return array to feed formatprint and text_input function
	// we have to allow editing of the input fields
	// 
	// $aChallenge is the list of challenges from the last time the form was submitted
	// $prefix is weekly or daily (w,d) challenges and used to read correct item from $S_REQUEST
	
		foreach ($aChallenge as $key => $value) {
			// put the results into the *Challenge array
			$tmp=$prefix.$key;
			$aChallenge[$key][0] =  htmlspecialchars($_REQUEST[$tmp]);
			$aChallenge[$key][3] =  htmlspecialchars($_REQUEST[$tmp.'1st']);
		}
		return $aChallenge;
	}



	function Minerva(){
		// minervas fucntion

		// format of MinervaLocation.txt: Start,end,location,list
		// example
		// 7/12/2021,7/13/2021,Foundation,1
		// 7/19/2021,7/20/2021,The Crater,2
		// 7/26/2021,7/27/2021,Fort Atlas,3
		// 8/5/2021,8/8/2021,Foundation,1|2|3
		
		// format of MinervaEnventory.txt is list|item|cost 
		// example
		// 1|Cattle Prod|188
		// 1|Chemist's Backpack Mod|263
		// 1|Farmable Dirt Tiles|375

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
			$output =  "**Minerva's Location: $MinervaLocationResult**\n\n$MinervaNextLocation\n";
		} else {
			$output =  "**Minerva's Location: $MinervaLocationResult**\n\n";
			$output .=  "Name (Gold Price)\n";
			foreach ($MinervaEnventory as $MEkey => $MEvalue) {
				$MEline = explode('|',$MEvalue);
				if ( in_array($MEline[0], $MinervaList)) {
					$gold = round($MEline[2]*0.75);
					$output .= "* ".$MEline[1]." (".$gold.")\n";
				}
			}
			
		}
		$output .=  "\n";
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
		$WeeklyChallenge=ReadForm($WeeklyChallenge,'w');
		$DailyChallenge=ReadForm($DailyChallenge,'d');
		$EventChallenge=ReadForm($EventChallenge,'e');
	}
	?>

	<table>
		<caption><h1>Weekly Challenges</h1></caption>
		<tr><th>1st</th><th>Challenge</th></tr>	
			<?php
			foreach ($WeeklyChallenge as $key => $value) {
				echo '<tr>';
				text_input('1st','w'.$key.'1st', $value[3],'5');
				text_input('WeeklyChallenges','w'.$key, $value[0],'106');
				echo '</tr>';
			}
			?>
	</table>

	<table>
		<caption><h1>Daily Challenges</h1></caption>
		<tr><th>1st</th><th>Challenge</th></tr>	
			<?php
			foreach ($DailyChallenge as $key => $value) {
				echo '<tr>';
				text_input('1st','d'.$key.'1st', $value[3],5);
				text_input('DailyChallenges','d'.$key, $value[0],106);
				echo '</tr>';
			}
			?>
	</table>

	<table>
		<caption><h1>Event Challenges</h1></caption>
		<tr><th>Challenge</th></tr>	
			<?php
			foreach ($EventChallenge as $key => $value) {
				echo '<tr>';
				text_input('EventChallenges','e'.$key, $value[0],'113');
				echo '</tr>';
			}
			?>
	</table>

	<h1><img src='bos.png'> Daily Operation</h1>

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

	  // Check if the form is submitted
	if ( isset( $_POST['Submit'] ) ) {
		
		usort($DailyChallenge,'cmp');
		usort($WeeklyChallenge,'cmp');
		
		$textareaValue = "Fallout 76 Daily Update\n";
		$textareaValue .= CurrentEvents();
		$ScoreMult=1;
		if (str_contains($textareaValue,'Double SCORE') || str_contains($textareaValue,'Double Score')) {
			$ScoreMult = 2;
		}
		$textareaValue .= formatprint(1,$EventChallenge,'e'); 
		$textareaValue .= formatprint(1,$WeeklyChallenge,'w'); 
		$textareaValue .= formatprint($ScoreMult,$DailyChallenge,'d');
		$textareaValue .= Minerva();

		if (!empty($LocationResult)) {
			$DOPSMode="Uplink";
			if ($EnemyMutations1Result == "Savage Strike") {
				$DOPSMode="Decryption";
			}				
			$textareaValue .=  "**Daily OPS: $DOPSMode**\n";
			//$textareaValue .=  "\n";
			$textareaValue .=  "* Location: $LocationResult\n";
			$textareaValue .=  "* Enemy Faction: $EnemyFactionResult\n";
			$textareaValue .=  "* Enemy Mutations: $EnemyMutations1Result, $EnemyMutations2Result \n";
			//$textareaValue .=  "";
		}
		
		if (mb_strlen($textareaValue) > 2000 ) {
			echo '<p> <span class="warning">CAUTION:Character count exceeds Discord maximum post length (' .mb_strlen($textareaValue).") > 2000.</span></p>";
		} else {
			echo "<p>Character count: (".mb_strlen($textareaValue).")</p>";
		}
		echo '<div><textarea id="content" name="content" autofocus>';
		echo $textareaValue;
		echo '</textarea></div>';	
		
	} 
?>
</pre>
</form>
</body>
</html>
