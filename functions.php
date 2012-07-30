<?php

/***
 * class Translator
 * 
 * This class is responsible for taking an array of either comma separated english written numbers or single english written numbers, 
 * to then translate each english word that makes up each indivisual number to its proper mathematical expression.
 * 
 * Methods in class Translator:
 * 
 * splitString 
 * Responsible for transforming the string into a orginized array containing each individual number in its respective array key, 
 * and each individual english written word, inside its respective number array key as values.
 * 
 * translate
 * Holds the algorithm that sweeps through each array of numbers and translates word per word to its literal mathematical expression. 
 * After the raw mathematical expressions are inserted back in the same order into a parallel array, each key of that array its iterated
 * in order to transform raw numbers into understandable mathematical amounts, given they meet certain conditions
 * like relative position to its adjacent numbers, the values of adjacent words, their expected mathematical expression and how they
 * affect each other in order to add or multiply accordingly.
 * 
 * 
 * @author Rafael pacas
 * 
 */
class Translator  {
	
	protected $dictionary;	
	
	function __construct($inputString){
		
		$this->dictionary["0"] = array("name" => "zero", "value" => 0);
		$this->dictionary["1"] = array("name" => "one", "value" => 01);
		$this->dictionary["2"] = array("name" => "two", "value" => 02);
		$this->dictionary["3"] = array("name" => "three", "value" => 03);
		$this->dictionary["4"] = array("name" => "four", "value" => 04);
		$this->dictionary["5"] = array("name" => "five", "value" => 05);
		$this->dictionary["6"] = array("name" => "six", "value" => 06);
		$this->dictionary["7"] = array("name" => "seven", "value" => 07);
		$this->dictionary["8"] = array("name" => "eight", "value" => 8);
		$this->dictionary["9"] = array("name" => "nine", "value" => 9);
		$this->dictionary["10"] = array("name" => "ten", "value" => 10);
		$this->dictionary["11"] = array("name" => "eleven", "value" => 11);
		$this->dictionary["12"] = array("name" => "twelve", "value" => 12);
		$this->dictionary["13"] = array("name" => "thirteen", "value" => 13);
		$this->dictionary["14"] = array("name" => "fourteen", "value" => 14);
		$this->dictionary["15"] = array("name" => "fifteen", "value" => 15);
		$this->dictionary["16"] = array("name" => "sixteen", "value" => 16);
		$this->dictionary["17"] = array("name" => "seventeen", "value" => 17);
		$this->dictionary["18"] = array("name" => "eighteen", "value" => 18);
		$this->dictionary["19"] = array("name" => "nineteen", "value" => 19);
		$this->dictionary["20"] = array("name" => "twenty", "value" => 20);
		$this->dictionary["30"] = array("name" => "thirty", "value" => 30);
		$this->dictionary["40"] = array("name" => "forty", "value" => 40);
		$this->dictionary["50"] = array("name" => "fifty", "value" => 50);
		$this->dictionary["60"] = array("name" => "sixty", "value" => 60);
		$this->dictionary["70"] = array("name" => "seventy", "value" => 70);
		$this->dictionary["80"] = array("name" => "eighty", "value" => 80);
		$this->dictionary["90"] = array("name" => "ninety", "value" => 90);
		$this->dictionary["100"] = array("name" => "hundred", "value" => 100);
		$this->dictionary["1000"] = array("name" => "thousand", "value" => 1000);
		$this->dictionary["1000000"] = array("name" => "million", "value" => 1000000);
		$this->dictionary["sign"] = array("name" => "negative", "value" => "-");
	}

/**
 * function SplitString
 * 
 * Responsible for transforming the string into a orginized array containing each indivisual number in different keys, 
 * and each individual english written word, inside its respective number array key.
 * 
 * @param string $string User input. 
 * @return array $separatedNums Returns and array with all english written numbers organized by key.
 * @author Rafael Pacas 
 * 
 * */
	public function splitString ($string){

		$commaPattern = "/[\s]*[,][\s]*/";
		$checkedString = strtolower($string);
		$numbers = array();	
		
		if (preg_match($commaPattern, $checkedString)){
			$numbers = preg_split($commaPattern, $checkedString);
		}else{
			$numbers = array($checkedString);
		}
			
		$count = count($numbers);	
		$separatedNums = array();	
		$emptyPattern = "/[\s]* /";	
			
		for ($i=0; $i < $count ; $i++) {
			array_push($separatedNums, preg_split($emptyPattern, $numbers[$i]));

		}
				
		return $separatedNums;
	
	}
	

/**
 * function translate
 * 
 * Holds the algorithm that sweeps through each array of numbers and translates word per word given they meet certain conditions
 * like relative position to its adjacent words, the values of adjacent words, their expected mathematical expression and how they
 * affect each other in order to add or multiply accordingly.
 * 
 * @param string $string User input. 
 * @return array $separatedNums Returns and array with all english written numbers organized by key.
 * @author Rafael Pacas 
 * 
 * */	
	public function translate($numAsWordsArray){
		
		$dictionary = $this->dictionary;
		$numberCount = count($numAsWordsArray);
		$intContainer = array();
		$intFinalContainer = array();
		$sign;
		$operation;
		$number;
		
		/**
		 * This for loop starts the translation process by comparing each word in each key of the splitted and orginized array of english written words, against 
		 * the names inside the dictionary array, when it finds a match it allocates the raw numerical value of that word in the same organization as the original 
		 * array of words.
		 * 
		 * Example:
		 * Array of words 
		 * (
		 * 		[0] => 'one'
		 * 		[1] => 'hundred'
		 * )
		 * 
		 * Translated Array of integers 
		 * (
		 * 		[0] => '1'
		 * 		[1] => '100'
		 * )
		 */
		for ($i=0; $i < $numberCount ; $i++) {
			$number = $numAsWordsArray[$i];
			foreach ($number as $value) {
				foreach ($dictionary as $row) {
					if($value == $row['name']){
						
						$intContainer[]=$row['value'];
					}
				}
			}
			
			// The function only checks if the "-" exists in the array, if it does, it assigns its value to the $sign variable.
			if(in_array("-", $intContainer)){
				$sign = "-";
			}
		
			$wordCount = count($intContainer);
			$hundredOp;
			$thousandOp;
			$millionOp;
			$hundredCounter;
			$thousandCounter;
			$millionCounter;
			$stringPosition;
			
			/**
			 * This for loop iterates through each integer inside the translated number array and tests them for conditions like
 			 * relative position to its adjacent integers, the values of adjacent integers, their expected mathematical expression and how they
 			 * affect each other in order to add or multiply accordingly. 
			 * 
			 * Example: one hundred twenty five == 1 100 20 5 == 125
			 * Array of ints
			 * (
			 * 		[0] => '1'
			 * 		[1] => '100'
			 * 		[2] => '20'
			 * 		[3] => '5'
			 * )
			 * 
			 * Translated Array of integers
			 * (
			 * 		[0] => '125'
			 * 
			 * )
			 * 
			 */
			for ($j= $wordCount-1 ; $j >= 0 ; $j--) {

				if ($intContainer[$j] <> "100"  && $intContainer[$j] <> "1000" && $intContainer[$j] <> "1000000" && $stringPosition <= 1 && $thousandCounter < 1 && $hundredCounter < 1 && $millionCounter < 1) {
					$operation += $intContainer[$j];

				}elseif($intContainer[$j] == "100" && $thousandCounter == 0 && $millionCounter == 0){
					$hundredOp = $intContainer[$j-1]*100;	
					$operation += $hundredOp;
					$hundredCounter++;	
				}elseif($intContainer[$j] == "1000"){
					if($intContainer[$j-1] == "100"){
						$thousandOp = $intContainer[$j-2]*100*1000;	
						$operation += $thousandOp;
						$thousandCounter++;
					}elseif($intContainer[$j-2] == "100"){
						$thousandOp = (($intContainer[$j-3]*100)+$intContainer[$j-1])*1000;	
						$operation += $thousandOp;
						$thousandCounter++;
					}elseif($intContainer[$j-3] == "100" && $intContainer[$j-2] <> "1000000"){
						$thousandOp = (($intContainer[$j-4]*100)+$intContainer[$j-2]+$intContainer[$j-1])*1000;	
						$operation += $thousandOp;
						$thousandCounter++;
					}elseif($intContainer[$j-1] <> "100" && $intContainer[$j-2] <> "100" && $intContainer[$j-3] <> "100" && $intContainer[$j-2] <> "1000000"){
						$thousandOp = ($intContainer[$j-2]+$intContainer[$j-1])*1000;
						$operation += $thousandOp;
						$thousandCounter++;
					}elseif($intContainer[$j-2] == "1000000"){
						$thousandOp = $intContainer[$j-1]*1000;	
						$operation += $thousandOp;
						$thousandCounter++;	
					}
				}elseif($intContainer[$j] == "1000000"){
					if($intContainer[$j-1] == "100"){
						$millionOp = $intContainer[$j-2]*100*1000000;	
						$operation += $millionOp;
						$millionCounter++;
					}elseif($intContainer[$j-2] == "100"){
						$millionOp = (($intContainer[$j-3]*100)+$intContainer[$j-1])*1000000;	
						$operation += $millionOp;
						$millionCounter++;					
					}elseif($intContainer[$j-3] == "100"){
						$millionOp = (($intContainer[$j-4]*100)+$intContainer[$j-2]+$intContainer[$j-1])*1000000;
						$operation += $millionOp;
						$millionCounter++;
					}elseif($intContainer[$j-1] <> "100" && $intContainer[$j-2] <> "100" && $intContainer[$j-3] <> "100"){
						$millionOp = ($intContainer[$j-2]+$intContainer[$j-1])*1000000;
						$operation += $millionOp;
						$millionCounter++;
						
					}
				}
				
			$stringPosition++;
			
			}

			//$sign its attached back to its original number before being pushed into the $intFinalContainer array.
			array_push($intFinalContainer, $sign . $operation);
			unset($operation, $intContainer, $stringPosition, $hundredCounter,$thousandCounter, $millionCounter, $sign);
		}
		return $intFinalContainer;
	}
	
}
