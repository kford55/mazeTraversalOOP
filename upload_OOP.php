<!--
Kenneth Ford Maze Traversal (OOP Style)
-->

<style>
.wall{
	background-color: #000;
	height: 30px;
	width: 31px;
}
.path{
	background-color: #fe7e01;
}
.visit{
	background-color: #00F;
}
</style>
<?php
class Maze{
	function Maze($fileLocation){		
		$row = 0;
		if (($handle = fopen($fileLocation, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$num = count($data);
				//echo "<p> $num fields in line $row: <br /></p>\n";
			
				for ($c=0; $c < $num; $c++) {
					if($row == 0 && trim($data[$c])==""){
						$this->maze[$row][]="s";
						$this->start = array("x"=> $c, "y"=> $row);
					}
					elseif($row > 0 && ($c == 0 || $c == $num-1) && trim($data[$c]) == ""){
						$this->maze[$row][] = "e";
						$this->end = array("x"=> $c, "y" => $row);
					}
					else{
						$this->maze[$row][] = $data[$c] ;
					}
				
				}
				$row++;
			}
			$row--;
			if(!isset($this->end)){	
				for($c = 0; $c<$num; $c++){
					if(trim($this->maze[$row][$c]) == ""){
						$this->maze[$row][$c] = "e";
						$this->end = array("x"=> $c, "y" => $row);
					}
				}
			}
			print_r($this->end);
			fclose($handle);
					
		}
	}
	function display(){
		echo "<table>";
		foreach($this->maze as $row){
			echo "<tr>";
			foreach($row as $cell){
				if($cell=="e")
				{
					$class = "start";
				}
				elseif($cell=="s")
				{
					$class = "end";	
				}
				elseif ($cell == 'P'){
					$class = "path";
				}
				elseif($cell == "v"){
					$class = "visit";
				}
				elseif (trim($cell) != ''){
					$class = "wall";
				}
				
				else{
					$class = "open";
				}
				echo "<td class = \"".$class."\">";
			
				echo $cell;
				echo "</td>";
			}
			echo "</tr>";
		}
		echo "</table>";
	}

	function is_valid($x, $y){
		//global $maze;
		//wall
		if(strtolower($this->maze[$y][$x]) == "x"){
			//echo "wall";
			return false;
		}
		//out of bounds
		elseif($y<0 || $x<0 || $y>=sizeof($this->maze) || $x>=sizeof($this->maze[0])){
			//echo "out of bounds";
			return false;
		}
		//visited
		elseif($this->maze[$y][$x] == "v"){
			//echo "visited";
			return false;
		}
		//echo "good";
		return true;
		 
	}
	function move($x,$y){
		//global $maze, $end;
		echo $x.":".$y."<br>";
		//print_r($this);
		if (!$this->is_valid($x,$y)){
			//echo "false";
			return false;
		}
		else{
			$this->maze[$y][$x] = "v";
			$this->display();
			if ($this->end['y'] == $y && $this->end['x'] == $x){
				$this->maze[$y][$x] = "P";
				return true;
			}
			else if($this->move($x+1,$y)||$this->move($x,$y+1)||$this->move($x-1,$y)||$this->move($x,$y-1)){
				$this->maze[$y][$x] = "P";
				return true;
			}
		}
	}
}
