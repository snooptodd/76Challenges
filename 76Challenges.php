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
	error_reporting(E_ALL|E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	ini_set('display_errors', 'On');

	$aMLocation = file('/home/todd/public_html/MinervaLocation.csv',FILE_IGNORE_NEW_LINES);
	$Challenges = file('/home/todd/public_html/daily.txt',FILE_IGNORE_NEW_LINES);
	$Times= file('/home/todd/public_html/times.txt',FILE_IGNORE_NEW_LINES);
	$Score= file('/home/todd/public_html/score.txt',FILE_IGNORE_NEW_LINES);
	$DailyChallenge=range('a', 'p');
	$WeeklyChallenge=range('a', 'n');
	$Location=array('', 'Arktos Pharma Biome Lab','Watoga High School','Uncanny Caverns','The Burning Mine', 'The Burrows', 'Vault 94', 'Valley Galleria','Watoga Raider Arena','Vault 96','West Tek Research Center');
	$MinervaLocation=array('Away','Foundation','The Crater','Fort Atlas' );
	$EnemyFaction = array('', 'Communists','Blood Eagles','Super Mutants','Robots','Scorched','Mothman Cultists','Mole Miners' );
	$MinervaListPrint = ("\n");
	$EnemyMutations1 = array('Piercing Gaze', 'Savage Strike');
	$EnemyMutations2 = array('', 'Piercing Gaze', 'Volatile', 'Active Camouflage', 'Resilient' ,'Freezing Touch','Toxic Blood','Group Regeneration','Swift-Footed','Blistering Cold: Freezing Touch and Swift-Footed Mutations','Chilling Mend: Freezing Touch and Group Regeneration Mutations','Clouded Toxins: Active Camouflage and Toxic Blood Mutations','Relentless: Resilient and Group Regeneration Mutations','Stinging Frost: Freezing Touch and Toxic Blood Mutations','Swift Stalker: Active Camouflage and Swift-Footed Mutations','Unstable: Volatile and Swift-Footed Mutations','Vaporous: Volatile and Active Camouflage Mutations');
	$MinervaEnventory = file('/home/todd/public_html/MinervaEnventory.txt',FILE_IGNORE_NEW_LINES);
	$MinervaList = 0;
	$now = time();

	sort($Challenges);
	sort($Location);
	sort($EnemyFaction);
	sort($EnemyMutations1);
	sort($EnemyMutations2);
	sort($MinervaEnventory);

	$PageWidth = 0; 
	$LineWrap=false; // if true then wrap line if longer than $PageWidth

	// Discord webhook url created per server. 
	$WebHookURL = "https://discord.com/api/webhooks/912021809472368660/L2FLZ32BUjPkpDo_lxlSaEN5k4ScHJXdBnLP3prbUdnDMfb2UVM_rc57lbTQ1vGDQH9z";

	$DaysSinceEpoch = intdiv(time(),(24*60*60));
	if ($DaysSinceEpoch & 1) {
		// ODD Day 
		$EnemyMutations1 = array('Piercing Gaze','Savage Strike');
	} else {
		// Even Day
		$EnemyMutations1 = array('Savage Strike','Piercing Gaze');
	}

	// takes as input an array that is used to fill in the options for a select box
	function select_option($BoxName,$alist) {
		foreach ($alist as $value) {
			
			if ( isset($_REQUEST[$BoxName]  ) && ( $value == $_REQUEST[$BoxName] ) ) {
			    echo '<option selected value="'.htmlentities($value).'">'.htmlentities($value).'</option>';
			} else {
			    echo '<option value="'.htmlentities($value).'">'.htmlentities($value).'</option>';
			}
			//echo '<option value="'.$value.'">'.$value.'</option>';
		}
	}
	// takes as input the name of the selct box and an array for the options
	function select_box($BoxName, $SelectOptions) {
		//echo isset($_REQUEST[$BoxName]);
		//echo $_REQUEST[$BoxName];
		echo '<td>';
		//echo '<td><label for="'.$BoxName.'">'.$BoxName.'. </label>';
		echo '<select name="'.$BoxName.'" id="'.$BoxName.'">';
		select_option($BoxName,$SelectOptions);
		echo '</select></td>';
	}

	// for the script to work this fucntion needs to make the value passed to $aChallenge availble to the script (global?)
	function read_form($aChallenge,$select_prefix)
	{
		foreach ($aChallenge as $key => $value) {
			// put the results into the *Challenge array
			$aChallenge[$key] = array($_REQUEST[$select_prefix.$value ],$_REQUEST[$select_prefix.$value.'times'],$_REQUEST[$select_prefix.$value.'score']);
		}
	}

	function edit_file($File)
	{
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
	function formatprint($aChallenge,$select_prefix,$PageWidth,$LineWrap,$Weekly) {
		//foreach ($aChallenge as $key => $value) {
			// put the results into the *Challenge array
		//	$aChallenge[$key] = array($_REQUEST[$select_prefix.$value ],$_REQUEST[$select_prefix.$value.'times'],$_REQUEST[$select_prefix.$value.'score']);
			//$ChallengeLength=strlen( utf8_decode(' '.$aChallenge[$key][0].$aChallenge[$key][1].$aChallenge[$key][2]) );
		//}
		$ouput = "";
		if ( !empty($aChallenge[0][0]) ){
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
       	        	$ouput .= $aChallenge[$key][0].' '.$aChallenge[$key][1].' '.$aChallenge[$key][2]."\n";
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
?>
</center>

	<table  style=" right">
		<caption><h1>Weekly Challenges</h1></caption>
		
		<tr><th>Challenge</th><th>Times</th><th>SCORE</th></tr>	
			<?php
			foreach ($WeeklyChallenge as $value) {
				echo '<tr>';
				select_box('w'.$value, $Challenges);
				select_box('w'.$value.'times', $Times);
				select_box('w'.$value.'score', $Score);
				echo '</tr>';
			}
			?>
	</table>


<table  style=" left;">
	<caption><h1>Daily Challenges</h1></caption>
	<tr><th>Challenge</th><th>Times</th><th>SCORE</th></tr>	
		<?php
		foreach ($DailyChallenge as $value) {
			echo '<tr>';
			select_box('d'.$value, $Challenges);
			select_box('d'.$value.'times', $Times);
			select_box('d'.$value.'score', $Score);
			echo '</tr>';
		}
		?>
	<tr><th><input type="submit" name="Submit" value="Edit Challenges"></th>
	<th><input type="submit" name="Submit" value="Edit Times"></th>
	<th><input type="submit" name="Submit" value="Edit Score"></th></tr>
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
	    	<?php select_option('Location',$Location);?>
	    </select>
		<br>
	    <label for="EnemyFaction">Choose a enemy faction. </label>
	    <select name="EnemyFaction" id="EnemyFaction">
	    	<?php select_option('EnemyFaction',$EnemyFaction);?>
	    </select>
		<br>
		<label for="EnemyMutations1">   Choose a primary enemy mutation. </label>
	    <select name="EnemyMutations1" id="EnemyMutations1">
	    	<?php select_option('EnemyMutations1',$EnemyMutations1);?>
	    	</select>
		<br>
		<label for="EnemyMutations2">   Choose a sceondary enemy mutation. </label>
	    <select name="EnemyMutations2" id="EnemyMutations2">
	    	<?php select_option('EnemyMutations2',$EnemyMutations2);?>
	    </select>
		
		 <input type="submit" Name = "Submit" value='Submit'>
		

<?php
	error_reporting(E_ALL|E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	ini_set('display_errors', 'On');

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
			$WeeklyChallenge[$key] = array($_REQUEST['w'.$value],$_REQUEST['w'.$value.'times'],$_REQUEST['w'.$value.'score']);
		}
		foreach ($DailyChallenge as $key => $value) {
			// put the results into the *Challenge array
			$DailyChallenge[$key] = array($_REQUEST['d'.$value],$_REQUEST['d'.$value.'times'],$_REQUEST['d'.$value.'score']);
		}
		if ( $Submit == "Edit Score" ) {
			$File='/home/todd/public_html/score.txt';
			edit_file($File);
		} elseif ($Submit == 'Edit Times') {
			$File='/home/todd/public_html/times.txt';
			edit_file($File);
		} elseif ($Submit =='Edit Challenges') {
			$File='/home/todd/public_html/daily.txt';
			edit_file($File);
		} elseif ( $Submit == 'Submit') {
			
			// display the results
			//echo " \n";
			$textareaValue = "Fallout 76 Daily Update\n";
			$textareaValue .= formatprint($WeeklyChallenge,'w',$PageWidth,$LineWrap,True); 
			$textareaValue .= formatprint($DailyChallenge,'d',$PageWidth,$LineWrap,False);

			// $now = strtotime('03 January 2022 12:00:00');
			if ($MinervaLocationResult!=='Away') {
			 	
				
				// time from Minervas first apperance subtracted from now and the result dvided by #sec in 1 week
				$Mweeks=1+intdiv(($now-strtotime('12 July 2021 12:00:00')),(7*24*60*60));
				
				// the list repetes every 20 weeks, this should make that happen. 
				$MinervaList1=$Mweeks-(20*intdiv($Mweeks,20));
				// there is a extra week added when menerva shuts donwn the big sale intdiv(,5) will remove that week
				$MinervaList=$MinervaList1-intdiv($MinervaList1,5);
			} else {
				foreach($aMLocation as $MLvalue) {
					$MLLine = explode(',',$MLvalue);
					if (strtotime($MLLine[0]) <= $now && $now <= strtotime($MLLine[1]) ) {
						$MinervaLocationResult = $MLLine[2];
						$MinervaList = $MLLine[3];
					}
				}
			}
			if ($MinervaList == '4' ) {
				$MLList = array('1','2','3');
			} elseif ($MinervaList == '8' ){
				$MLList = array('5','6','7');
			}elseif ($MinervaList == '12' ){
				$MLList = array('9','10','11');
			}elseif ($MinervaList == '16' ){
				$MLList = array('13','14','15');
			} else {
				$MLList = array($MinervaList);
			}
			$MinervaListPrint = array('');
			foreach ($MinervaEnventory as $MEkey => $MEvalue) {
				$MEline = explode('|',$MEvalue);
				if ( in_array($MEline[0], $MLList)) {
					//$MinervaListPrint[$MEkey] = $MEline[1]." ".$MEline[2]."\n";
					array_push($MinervaListPrint, $MEline[1]." (".$MEline[2].")" );
				}
			}
			sort($MinervaListPrint);
			if ($MinervaLocationResult=='Away') {
				$textareaValue .=  "**Minerva's Location: $MinervaLocationResult**\n\n";
				//echo "```\n$MinervaLocationResult\n```\n";
			} else {
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
			echo '<input type="submit" Name="Submit" value="Discord"><P>';
			
			if (mb_strlen($textareaValue) > 2000 ) {
				echo "CAUTION:Character count exceeds Discord maximum post length (".mb_strlen($textareaValue).") > 2000 <P>";
			} else {
				echo "Character count (".mb_strlen($textareaValue).")<p>";
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
				DiscordPost ($WebHookURL,$textareaValue);
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
