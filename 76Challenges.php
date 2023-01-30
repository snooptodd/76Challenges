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
	<form Method ="POST" action="76Challenges.php">
	<?php

	// format of MinervaLocation.csv: Start,end,location,list
	// example
	// 7/12/2021,7/13/2021,Foundation,1
	// 7/19/2021,7/20/2021,The Crater,2
	// 7/26/2021,7/27/2021,Fort Atlas,3
	// 8/5/2021,8/8/2021,Foundation,1|2|3

	// format of times.txt,score.txt and is text file with one entry per line.

	// format of challenges.txt is challenge|emogi 
	// frist line is "|"
	// example
	// |
	// Above Rank 100: Gain XP|
	// Buy an item from or Sell an item to another Player|
	// CALL TO AXE-ION: Complete CALL TO AXE-ION Daily Challenges|🪓

	// format of MinervaEnventory.txt is list|item|cost 
	// example
	// 1|Cattle Prod|188
	// 1|Chemist's Backpack Mod|263
	// 1|Farmable Dirt Tiles|375


	require_once '/home/todd/src/76Challenges/vendor/autoload.php';
	error_reporting(E_ALL|E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	ini_set('display_errors', 'On');

	$rootPath='/home/todd/src/76Challenges/';
	$MinervaEnventory = file($rootPath.'MinervaEnventory.txt',FILE_IGNORE_NEW_LINES);
	$aMLocation = file($rootPath.'MinervaLocation.txt',FILE_IGNORE_NEW_LINES);
	$Challenges = array('');
	$Times= file($rootPath.'times.txt',FILE_IGNORE_NEW_LINES);
	$Score= file($rootPath.'score.txt',FILE_IGNORE_NEW_LINES);
	$DailyChallenge=array_map(function($n) { return array_map(function($n) { return null; }, range(1, 4) ); }, range(1, 19) );
	$WeeklyChallenge=array_map(function($n) { return array_map(function($n) { return null; }, range(1, 4) ); }, range(1, 19) );
	$Location=array('', 'Arktos Pharma Biome Lab','Watoga High School','Uncanny Caverns','The Burning Mine', 'The Burrows', 'Vault 94', 'Valley Galleria','Watoga Raider Arena','Vault 96','West Tek Research Center','Capitol Building','Garrahan Mining Headquarters','Morgantown High School');
	$MinervaLocation=array('Away','Foundation','The Crater','Fort Atlas' );
	$EnemyFaction = array('', 'Communists','Blood Eagles','Super Mutants','Robots','Scorched','Mothman Cultists','Mole Miners','Aliens');
	$MinervaListPrint = ("\n");
	$EnemyMutations1 = array('Piercing Gaze', 'Savage Strike');
	$EnemyMutations2 = array('', 'Reflective Skin','Piercing Gaze', 'Volatile', 'Active Camouflage', 'Resilient' ,'Freezing Touch','Toxic Blood','Group Regeneration','Swift-Footed','Blistering Cold: Freezing Touch and Swift-Footed Mutations','Chilling Mend: Freezing Touch and Group Regeneration Mutations','Clouded Toxins: Active Camouflage and Toxic Blood Mutations','Relentless: Resilient and Group Regeneration Mutations','Stinging Frost: Freezing Touch and Toxic Blood Mutations','Swift Stalker: Active Camouflage and Swift-Footed Mutations','Unstable: Volatile and Swift-Footed Mutations','Vaporous: Volatile and Active Camouflage Mutations');
	$FO1st = array('','1ˢᵗ ');

	
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
    //$ical->initFile($rootPath.'ZPAxXoBAnacByPGk-2023-01-29.ics');
    // $ical->initUrl('https://nextcloud.ktntg.com/remote.php/dav/calendars/todd/minerva/?export', $username = 'todd', $password = 'N@bozqT6HVC6', $userAgent = null);
} catch (\Exception $e) {
    die($e);
}	
	
	foreach ( file($rootPath.'challenges.txt',FILE_IGNORE_NEW_LINES) as $key => $value) {
		$Challenges[$key]=explode("|",$value);
	}
	
	// pre fill the Daily and Weekly arrays with common data
	foreach ($WeeklyChallenge as $key => $value) {
		$WeeklyChallenge[$key][2]='1000';
	}
	if ( ! isset( $_POST['Submit'] ) ) {
		$DailyChallenge[0][3]='1ˢᵗ ';
		$DailyChallenge[1][0]='Above Rank 100: Gain XP';
		$DailyChallenge[1][1]='(x2500)';
		$DailyChallenge[1][2]='50';
		$DailyChallenge[2][0]='Level up!';
		$DailyChallenge[2][1]='(x1)';
		$DailyChallenge[2][2]='500';
		$DailyChallenge[3][0]='Complete a Daily Operation!';
		$DailyChallenge[3][1]='(x1)';
		$DailyChallenge[3][2]='250';
		$DailyChallenge[4][0]='Complete an Event';
		$DailyChallenge[4][1]='(x1)';
		$DailyChallenge[4][2]='250';
		$DailyChallenge[5][0]='Gold Star: Complete a Daily Challenge';
		$DailyChallenge[5][1]='(x5)';
		$DailyChallenge[5][2]='500';
		$WeeklyChallenge[0][0]='Repeatable Under Rank 100: Gain XP';
		$WeeklyChallenge[0][1]='(x10000)';
		$WeeklyChallenge[0][2]='100';
		$WeeklyChallenge[1][0]='Complete a Gold Star Daily Challenge!';
		$WeeklyChallenge[1][1]='(x1)';
		$WeeklyChallenge[1][2]='1500';
		$WeeklyChallenge[2][0]='Complete Daily Operations Daily Challenges!';
		$WeeklyChallenge[2][1]='(x3)';
		$WeeklyChallenge[2][2]='1000';
		$WeeklyChallenge[3][0]='Level up!';
		$WeeklyChallenge[3][1]='(x3)';
		$WeeklyChallenge[3][2]='1500.';
		$WeeklyChallenge[4][0]='Complete an Event';
		$WeeklyChallenge[4][1]='(x10)';
		$WeeklyChallenge[4][2]='1000.';
	}

	$MinervaList = 0;
	$now = time();
	//$now = strtotime('20221105T12:01:00');

	sort($Challenges);
	sort($Location);
	sort($EnemyFaction);
	sort($EnemyMutations1);
	sort($EnemyMutations2);
	//sort($MinervaEnventory);

	$PageWidth = 0; 
	$LineWrap=false; // if true then wrap line if longer than $PageWidth

	// Discord webhook url created per server. 
	$WebHookURL = "";

	$DaysSinceEpoch = intdiv(time(),(24*60*60));
	if ($DaysSinceEpoch & 1) {
		// ODD Day 
		$EnemyMutations1 = array('Piercing Gaze','Savage Strike');
	} else {
		// Even Day
		$EnemyMutations1 = array('Savage Strike','Piercing Gaze');
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
		//echo '<td><label for="'.$BoxName.'">'.$BoxName.'. </label>';
		echo '<select name="'.$BoxName.'" id="'.$BoxName.'">';
		select_option($BoxName, $CurrnetValue, $SelectOptions);
		echo '</select></td>';
	}
	
	// Comparison function 
	// sorts FO1st to top , "" to bottom and rest by alpha
	function cmp($a, $b) {
		$ac = ($a[0]);
		$bc = ($b[0]);
		if ($a[3] == "1ˢᵗ ") {return -1;}
		if ($b[3] == "1ˢᵗ ") {return 1;}
		//sort empty to bottom
		if ($ac  == "") {return 1;}
		if ($bc  == "") {return -1;}
		return strcmp($ac,$bc);
	}

	function edit_file($File)	{
		echo '<p><textarea name="content" id="content" cols="120" rows="40" autofocus>&#10;';
		include($File);
		echo '</textarea><br>';
		echo '<input type="submit" Name="Submit" value="Save"> ';
		echo '<label hidden="true" for="fname">File name:</label><input type="text" hidden="true" id="fname" name="fname" value='.$File.' ><br>';
	}

	// takes as input Challenge array DailyChallenge,WeeklyChallenge
	// takes as input prefix for select boxes d,w
	// PageWidth is the width of the page
	// Weekly is true if its called for Weekly challenge
	// only prints if first item has a value
	function formatprint($Challenges,$aChallenge,$select_prefix,$PageWidth,$LineWrap,$Weekly) {
		//foreach ($aChallenge as $key => $value) {
			// put the results into the *Challenge array
		//	$aChallenge[$key] = array($_REQUEST[$select_prefix.$value ],$_REQUEST[$select_prefix.$value.'times'],$_REQUEST[$select_prefix.$value.'score']);
			//$ChallengeLength=strlen( utf8_decode(' '.$aChallenge[$key][0].$aChallenge[$key][1].$aChallenge[$key][2]) );
		//}
		$ouput = "";
		if ( ($aChallenge[6][0]) !== ''){
			if ( $Weekly ) {
				$ouput .= "**Weekly Challenges**\n";
				$ouput .= "```\n".str_pad('Challenge (Count)',$PageWidth-10)." S.C.O.R.E.\n";
			} else {
				$ouput .= "**Daily Challenges**\n";
				$ouput .= "```\n".str_pad('Challenge (Count)',$PageWidth-10)." S.C.O.R.E.\n";
			}
			foreach ($aChallenge as $key => $value) {
				$ChallengeLength=strlen( utf8_decode(' '.$aChallenge[$key][0].$aChallenge[$key][1].$aChallenge[$key][2]) );
				// if Linewrap is set and longer than $PageWidth split after challenge and pad next line to $PageWidth.
       			if ( $LineWrap && ($ChallengeLength > $PageWidth) ) {
               		while (strlen(utf8_decode(' '.$aChallenge[$key][1].$aChallenge[$key][2])) < $PageWidth) {
               			$aChallenge[$key][1]=' '.$aChallenge[$key][1];
                	}
       	        	// print results
               		$ouput .= $aChallenge[$key][0]."\n".$aChallenge[$key][1].' '.$aChallenge[$key][2]."\n";
       				// if less than $PageWidth and longer than 10 char then pad the length to $PageWidth
            	} elseif ( $ChallengeLength > 10 ) {
       	        	while (strlen(utf8_decode('  '.$aChallenge[$key][0].$aChallenge[$key][1].$aChallenge[$key][2])) < $PageWidth) {
               	    		$aChallenge[$key][2]=' '.$aChallenge[$key][2];
               		}
                	// print results
					// [TODO] check if $aChallenge[$key][0] in $Challenges is an array if so then add its 2nd element. 
					// it might be better to make all $Challenges be an array and just add whatever value to the string.
					// the below will probably work but $Challenges needs passed to the function.
					foreach( $Challenges as $key2 => $value2) {
						if ($aChallenge[$key][0] == $value2[0]) {
							$ouput .=$aChallenge[$key][3].$value2[1].$aChallenge[$key][0].' '.$aChallenge[$key][1].' '.$aChallenge[$key][2]."\n";
						}
					}
       	        	//$ouput .= $aChallenge[$key][3].' '.$aChallenge[$key][0].' '.$aChallenge[$key][1].' '.$aChallenge[$key][2]."\n";
				}
			}
	    	$ouput .= "```\n";
		}
		return $ouput;
	}

	function DiscordPost($WebHookURL,$Content)
	{
		//=======================================================================================================
		// Create new webhook in your Discord channel settings and copy&paste URL
		//=======================================================================================================

		$webhookurl = $WebHookURL;

		//=======================================================================================================
		// Compose message. You can use Markdown
		// Message Formatting -- https://discordapp.com/developers/docs/reference#message-formatting
		//========================================================================================================

		$timestamp = date("c", strtotime("now"));

		$json_data = json_encode([
		    // Message
		    "content" => "$Content",
		    
		    // Username
		    //"username" => "",

		    // Avatar URL.
		    // Uncoment to replace image set in webhook
		    //"avatar_url" => "https://ru.gravatar.com/userimage/28503754/1168e2bddca84fec2a63addb348c571d.jpg?size=512",

		    // Text-to-speech
		    //"tts" => false,

		    // File upload
		    // "file" => "",

		    // Embeds Array
		/*    "embeds" => [
		        [
		            // Embed Title
		            "title" => "PHP - Send message to Discord (embeds) via Webhook",

		            // Embed Type
		            "type" => "rich",

		            // Embed Description
		            "description" => "Description will be here, someday, you can mention users here also by calling userID <@12341234123412341>",

		            // URL of title link
		            "url" => "https://gist.github.com/Mo45/cb0813cb8a6ebcd6524f6a36d4f8862c",

		            // Timestamp of embed must be formatted as ISO8601
		            "timestamp" => $timestamp,

		            // Embed left border color in HEX
		            "color" => hexdec( "3366ff" ),

		            // Footer
		            "footer" => [
		                "text" => "GitHub.com/Mo45",
		                "icon_url" => "https://ru.gravatar.com/userimage/28503754/1168e2bddca84fec2a63addb348c571d.jpg?size=375"
		            ],

		            // Image to send
		            "image" => [
		                "url" => "https://ru.gravatar.com/userimage/28503754/1168e2bddca84fec2a63addb348c571d.jpg?size=600"
		            ],

		            // Thumbnail
		            //"thumbnail" => [
		            //    "url" => "https://ru.gravatar.com/userimage/28503754/1168e2bddca84fec2a63addb348c571d.jpg?size=400"
		            //],

		            // Author
		            "author" => [
		                "name" => "krasin.space",
		                "url" => "https://krasin.space/"
		            ],

		            // Additional Fields array
		            "fields" => [
		                // Field 1
		                [
		                    "name" => "Field #1 Name",
		                    "value" => "Field #1 Value",
		                    "inline" => false
		                ],
		                // Field 2
		                [
		                    "name" => "Field #2 Name",
		                    "value" => "Field #2 Value",
		                    "inline" => true
		                ]
		                // Etc..
		            ]
		        ]
		    ]
		*/
		], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );


		$ch = curl_init( $webhookurl );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
		curl_setopt( $ch, CURLOPT_POST, 1);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_data);
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt( $ch, CURLOPT_HEADER, 0);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

		$response = curl_exec( $ch );
		// If you need to debug, or find out why you can't send message uncomment line below, and execute script.
		// echo $response;
		curl_close( $ch );

	}

	function CurrentEvents($ical) {
		// gets events for for current day
		$output = "**Current Events**\n```\n";
		$events = $ical->eventsFromInterval('1 day');
		foreach ($events as $event) {
			$dtend = $ical->iCalDateToDateTime($event->dtend_array[3]);
			$output .= $event->summary . ', Ends on (' . $dtend->format('d-M-Y') . ')'."\n";
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
	
	  // Check if the form is submitted
	if ( isset( $_POST['Submit'] ) ) {

		// retrieve the form data by using the element's name attributes value as key
		$Submit = $_POST['Submit'];
		if (isset($_REQUEST['fname'])) {
			$File=$_REQUEST['fname'];
		}
		$LocationResult = $_REQUEST['Location'];
		$EnemyFactionResult = $_REQUEST['EnemyFaction'];
		$EnemyMutations1Result = $_REQUEST['EnemyMutations1'];
		$EnemyMutations2Result = $_REQUEST['EnemyMutations2'];
		//$MinervaLocationResult = $_REQUEST['MinervaLocation1'];
		$MinervaLocationResult = "Away";
		//read_form($WeeklyChallenge,'w');
		//read_form($DailyChallenge,'d');
		foreach ($WeeklyChallenge as $key => $value) {
			// put the results into the *Challenge array
			$WeeklyChallenge[$key][0] = $_REQUEST['w'.$key];
			$WeeklyChallenge[$key][1] = $_REQUEST['w'.$key.'times'];
			$WeeklyChallenge[$key][2] = $_REQUEST['w'.$key.'score'];
			$WeeklyChallenge[$key][3] = $_REQUEST['w'.$key.'1st'];
		}
		foreach ($DailyChallenge as $key => $value) {
			// put the results into the *Challenge array
			$DailyChallenge[$key][0] = $_REQUEST['d'.$key];
			$DailyChallenge[$key][1] = $_REQUEST['d'.$key.'times'];
			$DailyChallenge[$key][2] = $_REQUEST['d'.$key.'score'];
			$DailyChallenge[$key][3] = $_REQUEST['d'.$key.'1st'];
		}
		//sort($DailyChallenge);
		//usort($DailyChallenge,'cmp');
		// sort($WeeklyChallenge);
		//usort($WeeklyChallenge,'cmp');
	}
	?>
</center>

	<table  style=" right">
		<caption><h1>Weekly Challenges</h1></caption>
		<tr><th>1st</th><th>Challenge</th><th>Times</th><th>SCORE</th></tr>	
			<?php
			foreach ($WeeklyChallenge as $key => $value) {
				echo '<tr>';
				select_box('w'.$key.'1st', $value[3], $FO1st);
				select_box('w'.$key, $value[0], $Challenges);
				select_box('w'.$key.'times', $value[1], $Times);
				select_box('w'.$key.'score', $value[2], $Score);
				echo '</tr>';
			}
			?>
	</table>


<table  style=" left;">
	<caption><h1>Daily Challenges</h1></caption>
	<tr><th>1st</th><th>Challenge</th><th>Times</th><th>SCORE</th></tr>	
		<?php
		foreach ($DailyChallenge as $key => $value) {
			echo '<tr>';
			select_box('d'.$key.'1st', $value[3], $FO1st);
			select_box('d'.$key, $value[0], $Challenges);
			select_box('d'.$key.'times', $value[1], $Times);
			select_box('d'.$key.'score', $value[2], $Score);
			echo '</tr>';
		}
		?>
	<!-- <tr><th> </th><th><input type="submit" name="Submit" value="Edit Challenges"></th>
	<th><input type="submit" name="Submit" value="Edit Times"></th>
	<th><input type="submit" name="Submit" value="Edit Score"></th></tr> -->
</table>	    
<!-- 	<h1>Minerva's Location</h1>
            <label for="MinervaLocation1"> Minerva's Location. </label>
            <select name="MinervaLocation1" id="MinervaLocation1">
                <?php //select_option('MinervaLocation1',$MinervaLocation);?>
            </select>
 -->

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
		
		if ( $Submit == "Edit Score" ) {
			$File=$rootPath.'score.txt';
			edit_file($File);
		} elseif ($Submit == 'Edit Times') {
			$File=$rootPath.'times.txt';
			edit_file($File);
		} elseif ($Submit =='Edit Challenges') {
			$File=$rootPath.'daily.txt';
			edit_file($File);
		} elseif ( $Submit == 'Submit') {
			
			// display the results
			//echo " \n";
			//$ical->initFile($rootPath.'ZPAxXoBAnacByPGk-2023-01-29.ics');
			$ical->initUrl('https://nextcloud.ktntg.com/remote.php/dav/calendars/todd/minerva/?export', $username = 'todd', $password = 'N@bozqT6HVC6', $userAgent = null);
			$textareaValue = "Fallout 76 Daily Update\n";
			$textareaValue .= CurrentEvents($ical);
			$textareaValue .= formatprint($Challenges,$WeeklyChallenge,'w',$PageWidth,$LineWrap,True); 
			$textareaValue .= formatprint($Challenges,$DailyChallenge,'d',$PageWidth,$LineWrap,False);

			foreach($aMLocation as $MLvalue) {
				$MLLine = explode(',',$MLvalue);
				if (strtotime($MLLine[0]) <= $now && $now <= strtotime($MLLine[1]) ) {
					$MinervaLocationResult = $MLLine[2];
					$MinervaList = explode('|',$MLLine[3]);
				}
			}
			if ($MinervaLocationResult=='Away') {
				$textareaValue .=  "**Minerva's Location: $MinervaLocationResult**\n\n";
				//echo "```\n$MinervaLocationResult\n```\n";
			} else {
				$MinervaListPrint = array('');
				foreach ($MinervaEnventory as $MEkey => $MEvalue) {
					$MEline = explode('|',$MEvalue);
					//if ( in_array($MEline[0], $MLList)) {
					if ( in_array($MEline[0], $MinervaList)) {
						//$MinervaListPrint[$MEkey] = $MEline[1]." ".$MEline[2]."\n";
						array_push($MinervaListPrint, $MEline[1]." (".$MEline[2].")" );
					}
				}
				//sort($MinervaListPrint);
			
				$textareaValue .=  "**Minerva's Location: $MinervaLocationResult**\n";
				$textareaValue .=  "```\nName (Gold Price)";
				foreach ($MinervaListPrint as $key => $value) {
					$textareaValue .=  "$value\n";
				}
				$textareaValue .=  "```\n";
			}

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
			//echo '<input type="submit" Name="Submit" value="Discord"><P>';
			
			if (mb_strlen($textareaValue) > 2000 ) {
				echo "CAUTION:Character count exceeds Discord maximum post length (".mb_strlen($textareaValue).") > 2000 <P>";
			} else {
				echo "Character count: (".mb_strlen($textareaValue).")<br>";
				echo "Minerva List: ";
				if (!empty($MinervaList)) {
					//print_r($MinervaList);
					//echo $MinervaList[0].", ".$MinervaList[1].", ".$MinervaList[2];
					foreach($MinervaList as $key => $value) {
						echo $value." " ;
					}
				}
				echo "<p>";
			}
			echo '<textarea id="content" name="content" cols="120" rows="40" autofocus>';
			echo $textareaValue;
			echo '</textarea>';	
			
		} elseif ( $Submit == 'Save' ) {
			$textareaValue = ($_REQUEST['content']);
			//if (preg_match("/^[0-9a-zA-Z-' $#;:.,!]*$/",$textareaValue)) {
				file_put_contents($File,$textareaValue);
			//} else {
			//	$nameErr = "Only letters, numbers and white space allowed";
			//}
		} elseif ( $Submit == 'Discord' ) {
			$textareaValue = ($_REQUEST['content']);
			if (mb_strlen($textareaValue) < 2000 ) {
				//DiscordPost ($WebHookURL,$textareaValue);
				echo "Function Disabled";
			} else {
				echo "CAUTION:Character count exceeds Discord maximum post length (".mb_strlen($textareaValue).") > 2000 ";
			}
		}
	}
?>
</pre>
</form>
</body>
</html>
