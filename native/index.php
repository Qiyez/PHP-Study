<?php 
class Book{
	protected $name = 'computer';

	public function setName($name){
		$this->name = $name;
	}
	public function getName(){
		return $this->name;
	}
}

class LBook extends Book{
	public function getTName(){
		return $this->name;
	}
}


$lbook = new LBook();
$lbook->setName('德玛西亚');
echo $lbook->getName().PHP_EOL;
echo $lbook->getTName();


 ?>